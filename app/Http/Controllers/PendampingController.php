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

    public function activities(Request $request)
    {
        $recentSubmissions = \App\Models\Submission::with(['codeforcesHandle.user.profile', 'problem.contest'])
            ->orderBy('creation_time', 'desc')
            ->paginate(30, ['*'], 'submissions_page');

        $recentRatingChanges = \App\Models\RatingHistory::with(['codeforcesHandle.user.profile', 'contest'])
            ->orderBy('rating_update_time', 'desc')
            ->paginate(20, ['*'], 'rating_page');

        return view('pendamping.activities', compact('recentSubmissions', 'recentRatingChanges'));
    }

    public function contests()
    {
        $upcomingContests = Contest::where('phase', 'BEFORE')
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();

        return view('pendamping.contests', compact('upcomingContests'));
    }

    public function settings()
    {
        $mahasiswaRole = \App\Models\Role::where('name', 'mahasiswa')->first();
        $mahasiswaCount = User::where('role_id', $mahasiswaRole->id)->count();
        $activeMahasiswaCount = User::where('role_id', $mahasiswaRole->id)
            ->whereHas('primaryCodeforcesHandle', function($query) {
                $query->where('is_active', true);
            })->count();
        
        $totalContests = \App\Models\ContestParticipation::whereHas('codeforcesHandle.user', function($query) use ($mahasiswaRole) {
            $query->where('role_id', $mahasiswaRole->id);
        })->distinct('contest_id')->count();

        return view('pendamping.settings', compact('mahasiswaCount', 'activeMahasiswaCount', 'totalContests'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'angkatan' => 'nullable|string|max:10',
            'jurusan' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->userProfile && ($request->angkatan || $request->jurusan)) {
            $user->userProfile->update([
                'angkatan' => $request->angkatan,
                'jurusan' => $request->jurusan,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!\Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json(['success' => false, 'message' => 'Password saat ini tidak benar']);
        }

        Auth::user()->update([
            'password' => \Hash::make($request->new_password),
        ]);

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah']);
    }

    public function updatePreferences(Request $request)
    {
        // Store preferences in session for now
        session([
            'dashboard_theme' => $request->theme,
            'items_per_page' => $request->items_per_page,
            'auto_refresh' => $request->has('auto_refresh'),
            'show_animations' => $request->has('show_animations'),
        ]);

        return response()->json(['success' => true, 'message' => 'Preferensi berhasil disimpan']);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        // Logout user first
        Auth::logout();
        
        // Delete user account
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Akun berhasil dihapus']);
    }

    public function toggleContestImportant(Request $request, $contestId)
    {
        $contest = Contest::findOrFail($contestId);
        $contest->update(['is_important' => $request->is_important]);

        return response()->json([
            'success' => true, 
            'message' => $request->is_important ? 'Kontes ditandai sebagai penting' : 'Kontes dihapus dari daftar penting'
        ]);
    }

    public function refreshAllData()
    {
        // Set maximum execution time for this process
        set_time_limit(600); // 10 minutes untuk safety
        
        try {
            // Get count of active handles first
            $activeHandlesCount = CodeforcesHandle::where('is_active', true)->count();
            
            if ($activeHandlesCount == 0) {
                // Still refresh contests even if no active handles
                \Artisan::call('codeforces:crawl-contests');
                
                if (request()->expectsJson()) {
                    return response()->json(['success' => true, 'message' => 'Contest data refreshed']);
                }
                return redirect()->back()->with('success', 'Contest data refreshed');
            }
            
            // Selalu refresh synchronous (foreground) seperti dashboard mahasiswa
            \Artisan::call('codeforces:crawl-all');
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'All data refreshed successfully']);
            }
            return redirect()->back()->with('success', 'All data refreshed successfully');
            
        } catch (\Exception $e) {
            \Log::error('Refresh error: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Terjadi error saat refresh data: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi error saat refresh data: ' . $e->getMessage());
        }
    }

    public function refreshBatchData(Request $request)
    {
        $batchSize = $request->get('batch_size', 3);
        $offset = $request->get('offset', 0);
        
        $activeHandles = CodeforcesHandle::where('is_active', true)
            ->skip($offset)
            ->take($batchSize)
            ->get();
            
        if ($activeHandles->isEmpty()) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Semua data telah direfresh!'
            ]);
        }
        
        $processed = 0;
        $errors = [];
        
        foreach ($activeHandles as $handle) {
            try {
                \Artisan::call('codeforces:crawl-user', ['handle' => $handle->handle]);
                $processed++;
                sleep(1); // Delay to avoid rate limiting
            } catch (\Exception $e) {
                $errors[] = "Error refreshing {$handle->handle}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'completed' => false,
            'processed' => $processed,
            'errors' => $errors,
            'next_offset' => $offset + $batchSize
        ]);
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

    public function deleteUser($userId)
    {
        try {
            $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
            $user = User::where('id', $userId)
                ->where('role_id', $mahasiswaRole->id)
                ->firstOrFail();

            $userName = $user->name;
            
            // Delete semua data terkait user
            // 1. Delete submissions melalui codeforces handles
            if ($user->codeforcesHandles) {
                foreach ($user->codeforcesHandles as $handle) {
                    // Delete submissions
                    $handle->submissions()->delete();
                    // Delete rating histories
                    $handle->ratingHistories()->delete();
                    // Delete contest participations
                    $handle->contestParticipations()->delete();
                }
                
                // Delete codeforces handles
                $user->codeforcesHandles()->delete();
            }
            
            // 2. Delete user profile
            if ($user->profile) {
                $user->profile->delete();
            }
            
            // 3. Delete user
            $user->delete();

            return response()->json([
                'success' => true, 
                'message' => "Akun {$userName} berhasil dihapus beserta semua data terkait!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }
}
