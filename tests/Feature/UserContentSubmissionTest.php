<?php

use App\Models\Content;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->admin = User::factory()->create(['is_admin' => true]);
});

it('shows user their submissions list', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->user)
        ->get(route('user.content.index'))
        ->assertOk()
        ->assertSee($content->title);
});

it('allows user to create content', function () {
    $this->actingAs($this->user)
        ->get(route('user.content.create'))
        ->assertOk();
});

it('stores user content as draft', function () {
    $this->actingAs($this->user)
        ->post(route('user.content.store'), [
            'title' => 'My Submission',
            'description' => 'Test description',
            'body' => 'Test body',
            'sdg_category' => 'SDG 4',
            'difficulty' => 'core',
        ])
        ->assertRedirect(route('user.content.index'));

    $this->assertDatabaseHas('contents', [
        'title' => 'My Submission',
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);
});

it('stores user content with flashcards and quizzes', function () {
    $this->actingAs($this->user)
        ->post(route('user.content.store'), [
            'title' => 'Content with FC and QZ',
            'body' => 'Test body',
            'flashcards' => [
                ['question' => 'Q1', 'answer' => 'A1'],
            ],
            'quizzes' => [
                [
                    'question' => 'Quiz Q1',
                    'options' => ['A', 'B', 'C', 'D'],
                    'correct_answer' => '0',
                ],
            ],
        ])
        ->assertRedirect(route('user.content.index'));

    $content = Content::where('title', 'Content with FC and QZ')->first();
    expect($content)->not->toBeNull();
    expect($content->flashcards)->toHaveCount(1);
    expect($content->quizzes)->toHaveCount(1);
});

it('submits content for review', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->user)
        ->post(route('user.content.submit', $content))
        ->assertRedirect(route('user.content.index'));

    $content->refresh();
    expect($content->status)->toBe('pending_review');
    expect($content->submitted_at)->not->toBeNull();
});

it('does not allow submitting already published content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'published',
    ]);

    $this->actingAs($this->user)
        ->post(route('user.content.submit', $content))
        ->assertRedirect();
});

it('allows editing own draft content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->user)
        ->get(route('user.content.edit', $content))
        ->assertOk()
        ->assertSee($content->title);
});

it('allows updating own draft content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->user)
        ->put(route('user.content.update', $content), [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'body' => 'Updated body',
        ])
        ->assertRedirect(route('user.content.index'));

    $content->refresh();
    expect($content->title)->toBe('Updated Title');
});

it('allows editing rejected content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'rejected',
        'rejection_reason' => 'Needs improvement',
    ]);

    $this->actingAs($this->user)
        ->get(route('user.content.edit', $content))
        ->assertOk();
});

it('resubmits rejected content after editing', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'rejected',
        'rejection_reason' => 'Needs better content',
    ]);

    $this->actingAs($this->user)
        ->put(route('user.content.update', $content), [
            'title' => $content->title,
            'description' => 'Fixed description',
            'body' => 'Fixed body content',
        ])
        ->assertRedirect(route('user.content.index'));

    $content->refresh();
    expect($content->status)->toBe('draft');
    expect($content->rejection_reason)->toBeNull();

    $this->actingAs($this->user)
        ->post(route('user.content.submit', $content))
        ->assertRedirect(route('user.content.index'));

    $content->refresh();
    expect($content->status)->toBe('pending_review');
});

it('blocks editing of published content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'published',
    ]);

    $this->actingAs($this->user)
        ->get(route('user.content.edit', $content))
        ->assertForbidden();
});

it('blocks users from editing other users content', function () {
    $otherUser = User::factory()->create();
    $content = Content::factory()->create([
        'user_id' => $otherUser->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->user)
        ->get(route('user.content.edit', $content))
        ->assertForbidden();
});

it('allows admin to approve pending content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending_review',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.content.approve', $content))
        ->assertRedirect(route('admin.content.index'));

    $content->refresh();
    expect($content->status)->toBe('published');
    expect($content->published_at)->not->toBeNull();
});

it('allows admin to reject pending content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending_review',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.content.reject', $content), [
            'rejection_reason' => 'Insufficient quality',
        ])
        ->assertRedirect(route('admin.content.index'));

    $content->refresh();
    expect($content->status)->toBe('rejected');
    expect($content->rejection_reason)->toBe('Insufficient quality');
});

it('blocks non-admin from approving content', function () {
    $content = Content::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'pending_review',
    ]);

    $this->actingAs($this->user)
        ->post(route('admin.content.approve', $content))
        ->assertForbidden();
});
