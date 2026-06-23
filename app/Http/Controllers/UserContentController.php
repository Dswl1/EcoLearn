<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\User;
use App\Notifications\ContentSubmittedForReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserContentController extends Controller
{
    public function index(): View
    {
        $contents = Content::where('user_id', auth()->id())
            ->withCount(['flashcards', 'quizzes'])
            ->latest()
            ->paginate(15);

        return view('user-content.index', compact('contents'));
    }

    public function create(): View
    {
        return view('user-content.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'sdg_category' => ['nullable', 'string', 'max:50'],
            'tags' => ['nullable', 'string', 'max:255'],
            'difficulty' => ['nullable', 'in:basic,core,expert'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(6);
        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'draft';

        if (! empty($validated['body'])) {
            $validated['body'] = Content::sanitizeBody($validated['body']);
        }

        $content = Content::create($validated);

        if ($request->has('flashcards')) {
            foreach ($request->flashcards as $i => $fc) {
                if (! empty($fc['question'])) {
                    $content->flashcards()->create([
                        'question' => $fc['question'],
                        'answer' => $fc['answer'] ?? '',
                        'order' => $i,
                    ]);
                }
            }
        }

        if ($request->has('quizzes')) {
            foreach ($request->quizzes as $qz) {
                if (! empty($qz['question'])) {
                    $content->quizzes()->create([
                        'question' => $qz['question'],
                        'options' => $qz['options'] ?? [],
                        'correct_answer' => $qz['correct_answer'] ?? 0,
                        'order' => $qz['order'] ?? 0,
                    ]);
                }
            }
        }

        return to_route('user.content.index')
            ->with('success', __('app.content_created'));
    }

    public function edit(Content $content): View
    {
        if ($content->user_id !== auth()->id()) {
            abort(403);
        }

        if ($content->status === 'published') {
            abort(403, __('app.cannot_edit_published'));
        }

        $content->load(['flashcards', 'quizzes']);

        return view('user-content.edit', compact('content'));
    }

    public function update(Request $request, Content $content): RedirectResponse
    {
        if ($content->user_id !== auth()->id()) {
            abort(403);
        }

        if ($content->status === 'published') {
            abort(403, __('app.cannot_edit_published'));
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'sdg_category' => ['nullable', 'string', 'max:50'],
            'tags' => ['nullable', 'string', 'max:255'],
            'difficulty' => ['nullable', 'in:basic,core,expert'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($content->thumbnail) {
                Storage::disk('public')->delete($content->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if (! empty($validated['body'])) {
            $validated['body'] = Content::sanitizeBody($validated['body']);
        }

        if ($content->status === 'rejected') {
            $validated['status'] = 'draft';
            $validated['rejection_reason'] = null;
        }

        $content->update($validated);

        if ($request->has('flashcards')) {
            $content->flashcards()->delete();
            foreach ($request->flashcards as $i => $fc) {
                if (! empty($fc['question'])) {
                    $content->flashcards()->create([
                        'question' => $fc['question'],
                        'answer' => $fc['answer'] ?? '',
                        'order' => $i,
                    ]);
                }
            }
        }

        if ($request->has('quizzes')) {
            $content->quizzes()->delete();
            foreach ($request->quizzes as $qz) {
                if (! empty($qz['question'])) {
                    $content->quizzes()->create([
                        'question' => $qz['question'],
                        'options' => $qz['options'] ?? [],
                        'correct_answer' => $qz['correct_answer'] ?? 0,
                        'order' => $qz['order'] ?? 0,
                    ]);
                }
            }
        }

        return to_route('user.content.index')
            ->with('success', __('app.content_updated'));
    }

    public function submit(Content $content): RedirectResponse
    {
        if ($content->user_id !== auth()->id()) {
            abort(403);
        }

        if ($content->status === 'published') {
            return back()->with('error', __('app.already_published'));
        }

        $content->update([
            'status' => 'pending_review',
            'submitted_at' => now(),
            'rejection_reason' => null,
        ]);

        User::where('is_admin', true)->each(fn ($admin) => $admin->notify(new ContentSubmittedForReview($content))
        );

        return to_route('user.content.index')
            ->with('success', __('app.submitted_for_review'));
    }
}
