<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Content;
use App\Models\UserProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $userId = $user->id;

        $materialsStudied = UserProgress::where('user_id', $userId)
            ->distinct('content_id')
            ->count('content_id');

        $flashcardsMastered = UserProgress::where('user_id', $userId)
            ->where('type', 'flashcard')
            ->where('status', 'mastered')
            ->count();

        $quizPointsEarned = UserProgress::where('user_id', $userId)
            ->where('type', 'quiz')
            ->sum('score');

        $totalQuizAttempts = UserProgress::where('user_id', $userId)
            ->where('type', 'quiz')
            ->count();

        $totalPossiblePoints = $totalQuizAttempts * 10;
        $avgQuizScore = $totalPossiblePoints > 0 ? round(($quizPointsEarned / $totalPossiblePoints) * 100) : 0;

        $recentProgress = UserProgress::where('user_id', $userId)
            ->with('content')
            ->latest()
            ->take(10)
            ->get();

        $userContents = Content::where('user_id', $userId)->count();

        return view('profile.index', compact(
            'user', 'materialsStudied', 'flashcardsMastered',
            'avgQuizScore', 'quizPointsEarned',
            'recentProgress', 'userContents'
        ));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
