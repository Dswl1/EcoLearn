<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\UserProgress;
use App\Notifications\ContentDeleted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Content::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                if (DB::connection()->getDriverName() === 'mysql' && strlen($search) >= 3) {
                    $q->whereFulltext(['title', 'description', 'tags'], $search.'*', ['mode' => 'boolean']);
                } else {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('tags', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('sdg_category')) {
            $query->where('sdg_category', $request->sdg_category);
        }

        $sort = $request->input('sort', 'created_at-desc');
        $parts = explode('-', $sort, 2);
        $sortField = in_array($parts[0], ['created_at', 'title', 'status', 'difficulty']) ? $parts[0] : 'created_at';
        $sortDirection = ($parts[1] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $contents = $query->orderBy($sortField, $sortDirection)->paginate(15);

        return view('admin.content.index', compact('contents'));
    }

    public function create(): View
    {
        return view('admin.content.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'sdg_category' => ['nullable', 'string', 'max:50'],
            'tags' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'difficulty' => ['nullable', 'in:basic,core,expert'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpeg,png,webp', 'max:2048'],
            'ai_summary' => ['boolean'],
            'public_access' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(6);
        $validated['user_id'] = $request->user()->id;
        $validated['ai_summary'] = $request->boolean('ai_summary');
        $validated['public_access'] = $request->boolean('public_access');

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

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

        return to_route('admin.content.index')
            ->with('success', 'Content created successfully.');
    }

    public function show(Content $content): View
    {
        $content->load(['flashcards', 'quizzes', 'user']);

        $progress = UserProgress::where('content_id', $content->id)->get();

        $totalViewers = $progress->unique('user_id')->count();
        $flashcardLearners = $progress->where('type', 'flashcard')->unique('user_id')->count();
        $quizTakers = $progress->where('type', 'quiz')->unique('user_id')->count();

        $quizScores = $progress->where('type', 'quiz')->whereNotNull('score');
        $avgQuizScore = $quizScores->avg('score');
        $totalQuizAttempts = $quizScores->sum('attempts');

        $flashcardProgress = $progress->where('type', 'flashcard');
        $totalFlashcard = $content->flashcards->count();
        $masteredFlashcards = $flashcardProgress->where('status', 'mastered')->count();
        $masteryRate = $totalFlashcard > 0 ? round(($masteredFlashcards / ($totalFlashcard * max($flashcardLearners, 1))) * 100, 1) : 0;

        $engagementData = [
            'labels' => ['Material Viewers', 'Flashcard Learners', 'Quiz Takers'],
            'data' => [$totalViewers, $flashcardLearners, $quizTakers],
        ];

        return view('admin.content.show', compact(
            'content', 'totalViewers', 'flashcardLearners', 'quizTakers',
            'avgQuizScore', 'totalQuizAttempts', 'masteryRate', 'engagementData'
        ));
    }

    public function edit(Content $content): View
    {
        return view('admin.content.edit', compact('content'));
    }

    public function update(Request $request, Content $content): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'sdg_category' => ['nullable', 'string', 'max:50'],
            'tags' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'difficulty' => ['nullable', 'in:basic,core,expert'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpeg,png,webp', 'max:2048'],
            'ai_summary' => ['boolean'],
            'public_access' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($content->thumbnail) {
                Storage::disk('public')->delete($content->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['ai_summary'] = $request->boolean('ai_summary');
        $validated['public_access'] = $request->boolean('public_access');

        if ($validated['status'] === 'published' && empty($content->published_at)) {
            $validated['published_at'] = now();
        }

        if (! empty($validated['body'])) {
            $validated['body'] = Content::sanitizeBody($validated['body']);
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

        return to_route('admin.content.index')
            ->with('success', 'Content updated successfully.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        if ($content->thumbnail) {
            Storage::disk('public')->delete($content->thumbnail);
        }

        if ($content->status === 'published' && $content->user_id !== auth()->id()) {
            $content->user->notify(new ContentDeleted($content, $content->title));
        }

        $content->delete();

        if (Content::count() === 0) {
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE contents AUTO_INCREMENT = 1');
            } elseif (DB::connection()->getDriverName() === 'sqlite') {
                DB::statement('DELETE FROM sqlite_sequence WHERE name = "contents"');
            }
        }

        return to_route('admin.content.index')
            ->with('success', 'Content deleted successfully.');
    }

    public function approve(Content $content): RedirectResponse
    {
        $content->update([
            'status' => 'published',
            'published_at' => $content->published_at ?? now(),
        ]);

        return to_route('admin.content.index')
            ->with('success', 'Content approved and published.');
    }

    public function reject(Request $request, Content $content): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $content->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return to_route('admin.content.index')
            ->with('success', 'Content rejected.');
    }
}
