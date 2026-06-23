<?php

use App\Models\Content;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->admin = User::factory()->create(['is_admin' => true]);
});

it('shows published materials to authenticated users', function () {
    $published = Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
        'title' => 'Published Material',
    ]);
    Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'draft',
        'title' => 'Draft Material',
    ]);

    $this->actingAs($this->user)
        ->get(route('materials.index'))
        ->assertOk()
        ->assertSee('Published Material')
        ->assertDontSee('Draft Material');
});

it('shows material detail page to authenticated users', function () {
    $content = Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
    ]);

    $this->actingAs($this->user)
        ->get(route('materials.show', $content))
        ->assertOk()
        ->assertSee($content->title);
});

it('returns 404 for draft material on show page', function () {
    $content = Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'draft',
    ]);

    $this->actingAs($this->user)
        ->get(route('materials.show', $content))
        ->assertNotFound();
});

it('filters materials by sdg category', function () {
    Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
        'sdg_category' => 'SDG 4',
        'title' => 'SDG4 Material',
    ]);
    Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
        'sdg_category' => 'SDG 13',
        'title' => 'SDG13 Material',
    ]);

    $this->actingAs($this->user)
        ->get(route('materials.index', ['sdg_category' => 'SDG 4']))
        ->assertOk()
        ->assertSee('SDG4 Material')
        ->assertDontSee('SDG13 Material');
});

it('allows guests to browse materials listing', function () {
    Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
        'title' => 'Guest Visible',
    ]);

    $this->get(route('materials.index'))
        ->assertOk()
        ->assertSee('Guest Visible');
});

it('shows locked preview to guests on detail page', function () {
    $content = Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
        'body' => 'Full body content.',
    ]);

    $this->get(route('materials.show', $content))
        ->assertOk()
        ->assertSee(__('app.guest_login_to_read'))
        ->assertSee('Full body content.');
});

it('shows flashcards and quizzes locked for guests', function () {
    $content = Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
    ]);

    $content->flashcards()->create([
        'question' => 'Flashcard Question',
        'answer' => 'Flashcard Answer',
        'order' => 1,
    ]);

    $content->quizzes()->create([
        'question' => 'Quiz Question',
        'options' => ['A', 'B', 'C', 'D'],
        'correct_answer' => '0',
        'order' => 1,
    ]);

    $this->get(route('materials.show', $content))
        ->assertOk()
        ->assertSee(__('app.guest_flashcards_locked'))
        ->assertSee(__('app.guest_quiz_locked'))
        ->assertDontSee('Flashcard Question')
        ->assertDontSee('Quiz Question');
});

it('shows flashcards and quizzes on material detail page', function () {
    $content = Content::factory()->create([
        'user_id' => $this->admin->id,
        'status' => 'published',
    ]);

    $content->flashcards()->create([
        'question' => 'Flashcard Question',
        'answer' => 'Flashcard Answer',
        'order' => 1,
    ]);

    $content->quizzes()->create([
        'question' => 'Quiz Question',
        'options' => ['A', 'B', 'C', 'D'],
        'correct_answer' => '0',
        'order' => 1,
    ]);

    $this->actingAs($this->user)
        ->get(route('materials.show', $content))
        ->assertOk()
        ->assertSee('Flashcard Question')
        ->assertSee('Quiz Question');
});
