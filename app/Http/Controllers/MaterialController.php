<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\UserProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $query = Content::with('user')
            ->where('status', 'published');

        if ($search = $request->input('search')) {
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

        if ($sdg = $request->input('sdg_category')) {
            $query->where('sdg_category', $sdg);
        }

        if ($difficulty = $request->input('difficulty')) {
            $query->where('difficulty', $difficulty);
        }

        $materials = $query->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('materials.index', compact('materials'));
    }

    public function show(Content $content): View
    {
        if ($content->status !== 'published') {
            abort(404);
        }

        $content->load(['flashcards', 'quizzes', 'user']);

        $progress = collect();
        if (auth()->check()) {
            $progress = UserProgress::where('user_id', auth()->id())
                ->where('content_id', $content->id)
                ->get()
                ->keyBy(fn ($p) => $p->type.'_'.$p->item_id)
                ->map(fn ($p) => ['status' => $p->status, 'score' => $p->score]);
        }

        $previewBody = null;
        if (auth()->guest()) {
            $previewBody = Str::limit(strip_tags($content->body), 600);
        }

        return view('materials.show', compact('content', 'progress', 'previewBody'));
    }

    public function saveProgress(Request $request): JsonResponse
    {
        $data = $request->validate([
            'content_id' => 'required|exists:contents,id',
            'type' => 'required|in:flashcard,quiz',
            'item_id' => 'required|integer',
            'status' => 'required|string',
            'score' => 'nullable|integer',
        ]);

        $existing = UserProgress::where([
            'user_id' => auth()->id(),
            'content_id' => $data['content_id'],
            'type' => $data['type'],
            'item_id' => $data['item_id'],
        ])->first();

        if ($existing) {
            $existing->update([
                'status' => $data['status'],
                'score' => $data['score'] ?? $existing->score,
                'attempts' => $data['type'] === 'quiz' ? $existing->attempts + 1 : $existing->attempts,
            ]);
            $progress = $existing;
        } else {
            $progress = UserProgress::create([
                'user_id' => auth()->id(),
                'content_id' => $data['content_id'],
                'type' => $data['type'],
                'item_id' => $data['item_id'],
                'status' => $data['status'],
                'score' => $data['score'] ?? null,
                'attempts' => 1,
            ]);
        }

        return response()->json(['ok' => true, 'progress' => $progress]);
    }

    public function saveQuizBatch(Request $request): JsonResponse
    {
        $data = $request->validate([
            'content_id' => 'required|exists:contents,id',
            'answers' => 'required|array|min:1',
            'answers.*.item_id' => 'required|integer|exists:quizzes,id',
            'answers.*.selected' => 'required|integer|min:0|max:3',
            'answers.*.correct' => 'required|integer|min:0|max:3',
        ]);

        $results = [];
        foreach ($data['answers'] as $answer) {
            $correct = (int) $answer['selected'] === (int) $answer['correct'];
            $status = $correct ? 'passed' : 'failed';
            $score = $correct ? 10 : 0;

            $progress = UserProgress::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'content_id' => $data['content_id'],
                    'type' => 'quiz',
                    'item_id' => $answer['item_id'],
                ],
                [
                    'status' => $status,
                    'score' => $score,
                    'attempts' => DB::raw('COALESCE(attempts, 0) + 1'),
                ]
            );

            $results[] = [
                'item_id' => $answer['item_id'],
                'correct' => $correct,
                'status' => $status,
                'score' => $score,
            ];
        }

        return response()->json(['ok' => true, 'results' => $results]);
    }
}
