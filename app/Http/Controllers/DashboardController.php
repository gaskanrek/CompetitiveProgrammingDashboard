<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\CodeforcesHandle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard();
        } elseif ($user->isPendamping()) {
            return $this->pendampingDashboard();
        }
        
        return redirect()->route('login');
    }

    private function mahasiswaDashboard()
    {
        $user = Auth::user();
        $codeforcesHandle = $user->primaryCodeforcesHandle;
        
        // Upcoming contests
        $upcomingContests = Contest::where('phase', 'BEFORE')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        // Get leaderboard data (top 5)
        $leaderboard = CodeforcesHandle::with('user.profile')
            ->where('is_active', true)
            ->orderBy('current_rating', 'desc')
            ->limit(5)
            ->get();

        // Recent submissions if user has handle
        $recentSubmissions = collect();
        $ratingHistory = collect();
        $solvedProblemsCount = 0;
        
        if ($codeforcesHandle) {
            $recentSubmissions = $codeforcesHandle->submissions()
                ->with('problem')
                ->orderBy('creation_time', 'desc')
                ->limit(10)
                ->get();
                
            $ratingHistory = $codeforcesHandle->ratingHistories()
                ->orderBy('rating_update_time', 'desc')
                ->limit(5)
                ->get();
                
            $solvedProblemsCount = $codeforcesHandle->solved_problems_count;
        }

        return view('dashboard.mahasiswa', compact(
            'user',
            'codeforcesHandle',
            'upcomingContests',
            'recentSubmissions',
            'ratingHistory',
            'solvedProblemsCount',
            'leaderboard'
        ));
    }

    private function pendampingDashboard()
    {
        // Get all mahasiswa
        $mahasiswaRole = \App\Models\Role::where('name', 'mahasiswa')->first();
        $mahasiswaUsers = User::where('role_id', $mahasiswaRole->id)
            ->with(['primaryCodeforcesHandle', 'profile'])
            ->get();

        // Get leaderboard data
        $leaderboard = CodeforcesHandle::with('user.profile')
            ->where('is_active', true)
            ->orderBy('current_rating', 'desc')
            ->limit(10)
            ->get();

        // Recent activities
        $recentSubmissions = \App\Models\Submission::with(['codeforcesHandle.user', 'problem'])
            ->orderBy('creation_time', 'desc')
            ->limit(15)
            ->get();

        // Upcoming contests
        $upcomingContests = Contest::where('phase', 'BEFORE')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        // Statistics
        $totalMahasiswa = $mahasiswaUsers->count();
        $activeMahasiswa = $mahasiswaUsers->filter(function($user) {
            return $user->primaryCodeforcesHandle !== null;
        })->count();

        return view('dashboard.pendamping', compact(
            'mahasiswaUsers',
            'leaderboard',
            'recentSubmissions',
            'upcomingContests',
            'totalMahasiswa',
            'activeMahasiswa'
        ));
    }
}
