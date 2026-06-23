<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Flashcard;
use App\Models\Quiz;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (Auth::user()->is_admin) {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    private function adminDashboard(): View
    {
        $totalUsers = User::count();
        $totalContents = Content::count();
        $totalFlashcards = Flashcard::count();
        $totalQuizzes = Quiz::count();

        $activeUsers = User::whereMonth('created_at', now()->month)->count();
        $publishedThisMonth = Content::where('status', 'published')
            ->whereMonth('published_at', now()->month)
            ->count();

        $userGrowth = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $sdgDistribution = Content::selectRaw('sdg_category, COUNT(*) as count')
            ->whereNotNull('sdg_category')
            ->groupBy('sdg_category')
            ->pluck('count', 'sdg_category');

        $recentUsers = User::latest()->take(5)->get();
        $recentContents = Content::with('user')->latest()->take(5)->get();

        $userGrowthLabels = json_encode($userGrowth->keys());
        $userGrowthData = json_encode($userGrowth->values());
        $sdgLabels = json_encode($sdgDistribution->keys());
        $sdgData = json_encode($sdgDistribution->values());

        return view('dashboard.index', compact(
            'totalUsers', 'totalContents', 'totalFlashcards', 'totalQuizzes',
            'activeUsers', 'publishedThisMonth',
            'sdgDistribution',
            'userGrowthLabels', 'userGrowthData',
            'sdgLabels', 'sdgData',
            'recentUsers', 'recentContents'
        ));
    }

    private function userDashboard(): View
    {
        $userId = Auth::id();

        $materialsStudied = UserProgress::where('user_id', $userId)
            ->distinct('content_id')
            ->count('content_id');

        $flashcardsMastered = UserProgress::where('user_id', $userId)
            ->where('type', 'flashcard')
            ->where('status', 'mastered')
            ->count();

        $totalFlashcardAttempts = UserProgress::where('user_id', $userId)
            ->where('type', 'flashcard')
            ->count();

        $quizPointsEarned = UserProgress::where('user_id', $userId)
            ->where('type', 'quiz')
            ->sum('score');

        $totalQuizAttempts = UserProgress::where('user_id', $userId)
            ->where('type', 'quiz')
            ->count();

        $totalPossiblePoints = $totalQuizAttempts * 10;

        $avgQuizScore = $totalPossiblePoints > 0 ? round(($quizPointsEarned / $totalPossiblePoints) * 100) : 0;

        $flashcardMaterialsCount = UserProgress::where('user_id', $userId)
            ->where('type', 'flashcard')
            ->distinct('content_id')
            ->count('content_id');

        $progressByDay = UserProgress::where('user_id', $userId)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as day, COUNT(*) as count")
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('count', 'day');

        $progressLabels = json_encode($progressByDay->keys());
        $progressData = json_encode($progressByDay->values());

        $materialsProgress = Content::whereHas('userProgress', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->withCount(['flashcards', 'quizzes'])
            ->with(['userProgress' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->get()
            ->map(function ($material) {
                $fcMastered = $material->userProgress
                    ->where('type', 'flashcard')
                    ->where('status', 'mastered')
                    ->count();
                $fcTotal = $material->userProgress
                    ->where('type', 'flashcard')
                    ->count();
                $qzScore = $material->userProgress
                    ->where('type', 'quiz')
                    ->sum('score');
                $qzCount = $material->userProgress
                    ->where('type', 'quiz')
                    ->count();

                return (object) [
                    'id' => $material->id,
                    'title' => $material->title,
                    'slug' => $material->slug,
                    'flashcards_mastered' => $fcMastered,
                    'flashcards_total' => $fcTotal,
                    'quiz_score' => $qzScore,
                    'quiz_possible' => $qzCount * 10,
                    'total_flashcards' => $material->flashcards_count,
                    'total_quizzes' => $material->quizzes_count,
                ];
            });

        $recentProgress = UserProgress::where('user_id', $userId)
            ->with('content')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.user', compact(
            'materialsStudied', 'flashcardsMastered', 'totalFlashcardAttempts',
            'quizPointsEarned', 'totalQuizAttempts', 'totalPossiblePoints',
            'avgQuizScore', 'flashcardMaterialsCount',
            'progressLabels', 'progressData',
            'materialsProgress', 'recentProgress'
        ));
    }
}
