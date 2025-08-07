<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\CodeforcesHandle;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendampingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isPendamping()) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    public function mahasiswa()
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $mahasiswaUsers = User::where('role_id', $mahasiswaRole->id)
            ->with(['profile', 'primaryCodeforcesHandle'])
            ->paginate(20);

        return view('pendamping.mahasiswa', compact('mahasiswaUsers'));
    }

    public function mahasiswaDetail($id)
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $mahasiswa = User::where('id', $id)
            ->where('role_id', $mahasiswaRole->id)
            ->with(['profile', 'primaryCodeforcesHandle'])
            ->firstOrFail();

        $handle = $mahasiswa->primaryCodeforcesHandle;
        
        $data = [
            'mahasiswa' => $mahasiswa,
            'handle' => $handle,
            'submissions' => collect(),
            'ratingHistory' => collect(),
            'contestParticipations' => collect(),
            'solvedProblemsCount' => 0,
        ];

        if ($handle) {
            $data['submissions'] = $handle->submissions()
                ->with('problem.contest')
                ->orderBy('creation_time', 'desc')
                ->limit(10)
                ->get();

            $data['ratingHistory'] = $handle->ratingHistories()
                ->with('contest')
                ->orderBy('rating_update_time', 'desc')
                ->limit(10)
                ->get();

            $data['contestParticipations'] = $handle->contestParticipations()
                ->with('contest')
                ->orderBy('participation_time', 'desc')
                ->limit(10)
                ->get();

            $data['solvedProblemsCount'] = $handle->solved_problems_count;
        }

        return view('pendamping.mahasiswa-detail', $data);
    }

    public function leaderboard()
    {
        $leaderboard = CodeforcesHandle::with(['user.profile'])
            ->where('is_active', true)
            ->orderBy('current_rating', 'desc')
            ->paginate(50);

        return view('pendamping.leaderboard', compact('leaderboard'));
    }

    public function activities()
    {
        $recentSubmissions = \App\Models\Submission::with(['codeforcesHandle.user.profile', 'problem.contest'])
            ->orderBy('creation_time', 'desc')
            ->paginate(30);

        $recentRatingChanges = \App\Models\RatingHistory::with(['codeforcesHandle.user.profile', 'contest'])
            ->orderBy('rating_update_time', 'desc')
            ->paginate(20);

        return view('pendamping.activities', compact('recentSubmissions', 'recentRatingChanges'));
    }

    public function contests()
    {
        $upcomingContests = Contest::where('phase', 'BEFORE')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->paginate(15);

        $recentContests = Contest::where('phase', 'FINISHED')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('pendamping.contests', compact('upcomingContests', 'recentContests'));
    }

    public function refreshAllData()
    {
        // Trigger crawling untuk semua user
        \Artisan::call('codeforces:crawl-all');

        return redirect()->back()->with('success', 'Proses refresh data untuk semua mahasiswa telah dimulai!');
    }

    public function refreshUserData($userId)
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $user = User::where('id', $userId)
            ->where('role_id', $mahasiswaRole->id)
            ->firstOrFail();

        $handle = $user->primaryCodeforcesHandle;
        
        if (!$handle) {
            return redirect()->back()->with('error', 'Mahasiswa belum memiliki handle Codeforces!');
        }

        // Trigger crawling untuk user specific
        \Artisan::call('codeforces:crawl-user', ['handle' => $handle->handle]);

        return redirect()->back()->with('success', "Data {$user->name} berhasil diperbarui!");
    }

    public function markContestImportant(Request $request, $contestId)
    {
        $contest = Contest::findOrFail($contestId);
        $contest->update(['is_important' => $request->is_important]);

        return redirect()->back()->with('success', 'Status kontes berhasil diperbarui!');
    }
}
