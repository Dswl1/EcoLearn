<?php

use App\Models\Content;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->user = User::factory()->create(['is_admin' => false]);
    Storage::fake('public');
});

it('redirects non-admin users from admin pages', function () {
    $this->actingAs($this->user)
        ->get(route('admin.content.index'))
        ->assertForbidden();
});

it('allows admin to view index', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.content.index'))
        ->assertOk();
});

it('allows admin to view create page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.content.create'))
        ->assertOk();
});

it('allows admin to store content', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.content.store'), [
            'title' => 'Test Content',
            'description' => 'Test description',
            'body' => 'Test body content',
            'sdg_category' => 'SDG 4',
            'tags' => 'test, sdg',
            'status' => 'published',
            'difficulty' => 'core',
        ])
        ->assertRedirect(route('admin.content.index'));

    $this->assertDatabaseHas('contents', ['title' => 'Test Content']);
});

it('allows admin to view content', function () {
    $content = Content::factory()->create(['user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.content.show', $content))
        ->assertOk();
});

it('allows admin to view edit page', function () {
    $content = Content::factory()->create(['user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.content.edit', $content))
        ->assertOk();
});

it('allows admin to update content', function () {
    $content = Content::factory()->create(['user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.content.update', $content), [
            'title' => 'Updated Title',
            'status' => 'draft',
        ])
        ->assertRedirect(route('admin.content.index'));

    $this->assertDatabaseHas('contents', ['title' => 'Updated Title']);
});

it('allows admin to delete content', function () {
    $content = Content::factory()->create(['user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->delete(route('admin.content.destroy', $content))
        ->assertRedirect(route('admin.content.index'));

    $this->assertDatabaseMissing('contents', ['id' => $content->id]);
});

it('validates title when storing', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.content.store'), [
            'title' => '',
            'status' => 'draft',
        ])
        ->assertSessionHasErrors('title');
});

it('can upload thumbnail when storing content', function () {
    $file = UploadedFile::fake()->create('thumbnail.jpg', 100, 'image/jpeg');

    $this->actingAs($this->admin)
        ->post(route('admin.content.store'), [
            'title' => 'With Thumbnail',
            'status' => 'draft',
            'thumbnail' => $file,
        ])
        ->assertRedirect(route('admin.content.index'));

    $content = Content::where('title', 'With Thumbnail')->first();
    expect($content)->not->toBeNull();
    expect($content->thumbnail)->not->toBeNull();

    Storage::disk('public')->assertExists($content->thumbnail);
});

it('can store content with flashcards and quizzes', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.content.store'), [
            'title' => 'With Flashcards',
            'status' => 'draft',
            'flashcards' => [
                ['question' => 'Q1', 'answer' => 'A1'],
                ['question' => 'Q2', 'answer' => 'A2'],
            ],
            'quizzes' => [
                [
                    'question' => 'Quiz Q',
                    'options' => ['Opt A', 'Opt B', 'Opt C', 'Opt D'],
                    'correct_answer' => '2',
                ],
            ],
        ])
        ->assertRedirect(route('admin.content.index'));

    $content = Content::where('title', 'With Flashcards')->first();
    expect($content)->not->toBeNull();
    expect($content->flashcards)->toHaveCount(2);
    expect($content->flashcards->first()->question)->toBe('Q1');
    expect($content->flashcards->first()->answer)->toBe('A1');
    expect($content->quizzes)->toHaveCount(1);
    expect($content->quizzes->first()->question)->toBe('Quiz Q');
    expect($content->quizzes->first()->options)->toBe(['Opt A', 'Opt B', 'Opt C', 'Opt D']);
    expect($content->quizzes->first()->correct_answer)->toBe('2');
});

it('can update content with flashcards and quizzes', function () {
    $content = Content::factory()->create(['user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->put(route('admin.content.update', $content), [
            'title' => 'Updated FC',
            'status' => 'published',
            'flashcards' => [
                ['question' => 'New Q', 'answer' => 'New A'],
            ],
            'quizzes' => [
                [
                    'question' => 'New Quiz',
                    'options' => ['X', 'Y', 'Z', 'W'],
                    'correct_answer' => '1',
                ],
            ],
        ])
        ->assertRedirect(route('admin.content.index'));

    $content->refresh();
    expect($content->flashcards)->toHaveCount(1);
    expect($content->flashcards->first()->question)->toBe('New Q');
    expect($content->quizzes)->toHaveCount(1);
    expect($content->quizzes->first()->correct_answer)->toBe('1');
});
