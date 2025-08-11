@extends('layouts.app')

@section('title', 'Aktivitas Mahasiswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Aktivitas Saya</h1>
    @if($handle)
        <div class="btn-toolbar mb-2 mb-md-0">
            <form action="{{ route('mahasiswa.refresh') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                </button>
            </form>
        </div>
    @endif
</div>

@if(!$handle)
    <div class="alert alert-warning">
        <h4 class="alert-heading">Handle Codeforces belum diatur!</h4>
        <p>Untuk melihat aktivitas, silakan tambahkan handle Codeforces Anda terlebih dahulu.</p>
        <a href="{{ route('mahasiswa.profile') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Atur Handle Codeforces
        </a>
    </div>
@else
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="activityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="submissions-tab" data-bs-toggle="tab" data-bs-target="#submissions" type="button" role="tab">
                <i class="bi bi-code-slash me-2"></i>Submissions
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating" type="button" role="tab">
                <i class="bi bi-graph-up me-2"></i>Rating History
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contests-tab" data-bs-toggle="tab" data-bs-target="#contests" type="button" role="tab">
                <i class="bi bi-trophy me-2"></i>Contest Participations
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="activityTabsContent">
        <!-- Submissions Tab -->
        <div class="tab-pane fade show active" id="submissions" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Submissions</h5>
                </div>
                <div class="card-body">
                    @if($submissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Problem</th>
                                        <th>Bahasa</th>
                                        <th>Verdict</th>
                                        <th>Waktu</th>
                                        <th>Memory</th>
                                        <th>Submitted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions as $submission)
                                        <tr>
                                            <td>
                                                @if($submission->problem && $submission->problem->contest)
                                                    <a href="{{ $submission->problem->url }}" target="_blank" class="text-decoration-none">
                                                        {{ $submission->problem->contest->contest_id }}{{ $submission->problem->index }} - {{ $submission->problem->name }}
                                                    </a>
                                                    @if($submission->problem->rating)
                                                        <br><small class="text-muted">Rating: {{ $submission->problem->rating }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Problem not found</span>
                                                @endif
                                            </td>
                                            <td>{{ $submission->programming_language }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $submission->verdict_color }}; color: white;">
                                                    {{ $submission->verdict }}
                                                </span>
                                            </td>
                                            <td>{{ $submission->time_consumed_millis }} ms</td>
                                            <td>{{ number_format($submission->memory_consumed_mb, 2) }} MB</td>
                                            <td>{{ $submission->creation_time->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $submissions->appends(request()->except('submissions_page'))->appends(['tab' => 'submissions'])->links('pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-code-slash display-4 text-muted"></i>
                            <h5 class="mt-3">Belum Ada Submissions</h5>
                            <p class="text-muted">Submissions akan muncul setelah Anda mengirim solusi di Codeforces</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rating History Tab -->
        <div class="tab-pane fade" id="rating" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Rating</h5>
                </div>
                <div class="card-body">
                    @if($ratingHistory->count() > 0)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ number_format($handle->current_rating) }}</h4>
                                        <small>Current Rating</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ number_format($handle->max_rating) }}</h4>
                                        <small>Max Rating</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Contest</th>
                                        <th>Rank</th>
                                        <th>Old Rating</th>
                                        <th>New Rating</th>
                                        <th>Change</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ratingHistory as $history)
                                        <tr>
                                            <td>
                                                @if($history->contest)
                                                    <a href="https://codeforces.com/contest/{{ $history->contest->contest_id }}" target="_blank" class="text-decoration-none">
                                                        {{ $history->contest_name }}
                                                    </a>
                                                @else
                                                    {{ $history->contest_name }}
                                                @endif
                                            </td>
                                            <td>#{{ number_format($history->rank) }}</td>
                                            <td>{{ number_format($history->old_rating) }}</td>
                                            <td>{{ number_format($history->new_rating) }}</td>
                                            <td>
                                                <span class="fw-bold" style="color: {{ $history->rating_change_color }}">
                                                    {{ $history->rating_change > 0 ? '+' : '' }}{{ $history->rating_change }}
                                                </span>
                                            </td>
                                            <td>{{ $history->rating_update_time->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $ratingHistory->appends(request()->except('rating_page'))->appends(['tab' => 'rating'])->links('pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-up display-4 text-muted"></i>
                            <h5 class="mt-3">Belum Ada Riwayat Rating</h5>
                            <p class="text-muted">Riwayat rating akan muncul setelah Anda mengikuti kontes di Codeforces</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contest Participations Tab -->
        <div class="tab-pane fade" id="contests" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Partisipasi Kontes</h5>
                </div>
                <div class="card-body">
                    @if($contestParticipations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Contest</th>
                                        <th>Rank</th>
                                        <th>Points</th>
                                        <th>Penalty</th>
                                        <th>Hacks</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contestParticipations as $participation)
                                        <tr>
                                            <td>
                                                @if($participation->contest)
                                                    <a href="https://codeforces.com/contest/{{ $participation->contest->contest_id }}" target="_blank" class="text-decoration-none">
                                                        {{ $participation->contest->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Contest not found</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $participation->rank <= 100 ? 'success' : ($participation->rank <= 500 ? 'warning' : 'secondary') }}">
                                                    #{{ number_format($participation->rank) }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($participation->points) }}</td>
                                            <td>{{ number_format($participation->penalty) }}</td>
                                            <td>
                                                <span class="text-success">+{{ $participation->successful_hack_count }}</span> / 
                                                <span class="text-danger">-{{ $participation->unsuccessful_hack_count }}</span>
                                            </td>
                                            <td>{{ $participation->participation_time->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $contestParticipations->appends(request()->except('contests_page'))->appends(['tab' => 'contests'])->links('pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-trophy display-4 text-muted"></i>
                            <h5 class="mt-3">Belum Ada Partisipasi Kontes</h5>
                            <p class="text-muted">Partisipasi kontes akan muncul setelah Anda mengikuti kontes di Codeforces</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<script>
// Handle tab switching based on URL parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab');
    
    if (activeTab) {
        // Remove active class from all tabs and tab panes (only in tab container)
        document.querySelectorAll('#activityTabs .nav-link').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('#activityTabsContent .tab-pane').forEach(pane => {
            pane.classList.remove('show', 'active');
        });
        
        // Activate the correct tab
        const targetTab = document.getElementById(activeTab + '-tab');
        const targetPane = document.getElementById(activeTab);
        
        if (targetTab && targetPane) {
            targetTab.classList.add('active');
            targetPane.classList.add('show', 'active');
        }
    }
    
    // Update URL when tab is clicked
    document.querySelectorAll('#activityTabs [data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target').substring(1);
            const url = new URL(window.location);
            url.searchParams.set('tab', target);
            window.history.replaceState({}, '', url);
        });
    });
});
</script>

<style>
.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
    line-height: 1.25;
    border: 1px solid #dee2e6;
    color: #6c757d;
    background-color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
}

.page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #adb5bd;
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}
</style>
@endsection
