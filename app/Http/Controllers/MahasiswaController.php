<?php

namespace App\Http\Controllers;

use App\Models\CodeforcesHandle;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isMahasiswa()) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    public function profile()
    {
        $user = Auth::user();
        $codeforcesHandle = $user->primaryCodeforcesHandle;
        
        return view('mahasiswa.profile', compact('user', 'codeforcesHandle'));
    }

    public function updateCodeforcesHandle(Request $request)
    {
        $request->validate([
            'handle' => 'required|string|max:50|unique:codeforces_handles,handle,' . 
                        (Auth::user()->primaryCodeforcesHandle->id ?? 'null')
        ]);

        $user = Auth::user();
        
        // Deactivate existing handles
        $user->codeforcesHandles()->update(['is_active' => false]);
        
        // Create or update handle
        $handle = $user->codeforcesHandles()->where('handle', $request->handle)->first();
        
        if ($handle) {
            $handle->update(['is_active' => true]);
        } else {
            CodeforcesHandle::create([
                'user_id' => $user->id,
                'handle' => $request->handle,
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Handle Codeforces berhasil diperbarui!');
    }

    public function refreshData()
    {
        $user = Auth::user();
        $handle = $user->primaryCodeforcesHandle;
        
        if (!$handle) {
            return redirect()->back()->with('error', 'Anda belum memiliki handle Codeforces!');
        }

        // Trigger crawling untuk user ini
        \Artisan::call('codeforces:crawl-user', ['handle' => $handle->handle]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function activity(Request $request)
    {
        $user = Auth::user();
        $handle = $user->primaryCodeforcesHandle;
        
        if (!$handle) {
            return redirect()->route('mahasiswa.profile')
                ->with('error', 'Anda belum memiliki handle Codeforces!');
        }

        // Paginate with different page parameters for each tab
        $submissions = $handle->submissions()
            ->with('problem.contest')
            ->orderBy('creation_time', 'desc')
            ->paginate(20, ['*'], 'submissions_page');

        $ratingHistory = $handle->ratingHistories()
            ->with('contest')
            ->orderBy('rating_update_time', 'desc')
            ->paginate(10, ['*'], 'rating_page');

        $contestParticipations = $handle->contestParticipations()
            ->with('contest')
            ->orderBy('participation_time', 'desc')
            ->paginate(10, ['*'], 'contests_page');

        return view('mahasiswa.activity', compact(
            'handle',
            'submissions',
            'ratingHistory',
            'contestParticipations'
        ));
    }

    public function recommendations()
    {
        $user = Auth::user();
        $handle = $user->primaryCodeforcesHandle;
        
        if (!$handle) {
            return redirect()->route('mahasiswa.profile')
                ->with('error', 'Anda belum memiliki handle Codeforces!');
        }

        $currentRating = $handle->current_rating;
        $targetRating = $currentRating + 200; // Target rating sedikit di atas current

        // Get unsolved problems around target rating
        $solvedProblemIds = $handle->submissions()
            ->where('verdict', 'OK')
            ->pluck('problem_id')
            ->toArray();

        $recommendedProblems = Problem::whereNotIn('id', $solvedProblemIds)
            ->whereBetween('rating', [$currentRating - 100, $targetRating])
            ->with('contest')
            ->orderBy('rating')
            ->paginate(20);

        return view('mahasiswa.recommendations', compact(
            'handle',
            'recommendedProblems',
            'currentRating',
            'targetRating'
        ));
    }

    public function contests()
    {
        $upcomingContests = \App\Models\Contest::where('phase', 'BEFORE')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->paginate(10);

        return view('mahasiswa.contests', compact('upcomingContests'));
    }
}
