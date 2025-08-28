@extends('layouts.app')

@section('title', 'Aktivitas Terkini - Pendamping Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📊 Aktivitas Terkini</h2>
            <p class="text-muted mb-0">Monitor aktivitas real-time mahasiswa dalam competitive programming</p>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="activityTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="submissions-tab" data-bs-toggle="tab" data-bs-target="#submissions" type="button" role="tab">
                <i class="bi bi-code-slash me-2"></i>Submission Terbaru
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rating-tab" data-bs-toggle="tab" data-bs-target="#rating" type="button" role="tab">
                <i class="bi bi-graph-up me-2"></i>Perubahan Rating
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="activityTabsContent">
        <!-- Submissions Tab -->
        <div class="tab-pane fade show active" id="submissions" role="tabpanel">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-code-slash me-2"></i>
                        Submission Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentSubmissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="25%">Mahasiswa</th>
                                        <th width="30%">Problem</th>
                                        <th width="15%">Bahasa</th>
                                        <th width="15%">Verdict</th>
                                        <th width="15%">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubmissions as $submission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($submission->codeforcesHandle->user->name ?? 'N', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small">{{ $submission->codeforcesHandle->user->name ?? 'Unknown' }}</div>
                                                    <div class="text-muted small">{{ $submission->codeforcesHandle->handle }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($submission->problem)
                                                <a href="https://codeforces.com/problemset/problem/{{ $submission->problem->contest_id }}/{{ $submission->problem->index }}" 
                                                   target="_blank" class="text-decoration-none">
                                                    <div class="fw-semibold">{{ $submission->problem->contest_id }}{{ $submission->problem->index }}</div>
                                                    <div class="text-muted small">{{ Str::limit($submission->problem->name, 30) }}</div>
                                                </a>
                                            @else
                                                <span class="text-muted">Problem {{ $submission->problem_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $submission->programming_language }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ getVerdictBadgeClass($submission->verdict) }}">
                                                {{ $submission->verdict }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $submission->creation_time->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $recentSubmissions->appends(['tab' => 'submissions'])->links('pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-code-slash display-4 text-muted"></i>
                            <h5 class="mt-3">Belum Ada Submission</h5>
                            <p class="text-muted">Belum ada submission dari mahasiswa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rating Changes Tab -->
        <div class="tab-pane fade" id="rating" role="tabpanel">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Perubahan Rating
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentRatingChanges->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Mahasiswa</th>
                                        <th width="20%">Contest</th>
                                        <th width="15%">Old Rating</th>
                                        <th width="15%">New Rating</th>
                                        <th width="15%">Perubahan</th>
                                        <th width="15%">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRatingChanges as $rating)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($rating->codeforcesHandle->user->name ?? 'N', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small">{{ $rating->codeforcesHandle->user->name ?? 'Unknown' }}</div>
                                                    <div class="text-muted small">{{ $rating->codeforcesHandle->handle }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($rating->contest)
                                                <a href="https://codeforces.com/contest/{{ $rating->contest->contest_id }}" target="_blank" class="text-decoration-none">
                                                    <div class="fw-semibold small">{{ Str::limit($rating->contest->name, 20) }}</div>
                                                </a>
                                            @else
                                                <span class="text-muted small">Contest {{ $rating->contest_id }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ number_format($rating->old_rating) }}</td>
                                        <td class="fw-semibold">{{ number_format($rating->new_rating) }}</td>
                                        <td>
                                            @php
                                                $change = $rating->new_rating - $rating->old_rating;
                                            @endphp
                                            <span class="badge {{ $change >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $change >= 0 ? '+' : '' }}{{ $change }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $rating->rating_update_time->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $recentRatingChanges->appends(['tab' => 'rating'])->links('pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-graph-up display-4 text-muted"></i>
                            <h5 class="mt-3">Belum Ada Perubahan Rating</h5>
                            <p class="text-muted">Belum ada perubahan rating dari mahasiswa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Ringkasan Aktivitas Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $recentSubmissions->where('creation_time', '>', now()->startOfDay())->count() }}</h4>
                                <p class="text-muted mb-0">Submission Hari Ini</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-success">{{ $recentSubmissions->where('verdict', 'OK')->where('creation_time', '>', now()->startOfDay())->count() }}</h4>
                                <p class="text-muted mb-0">Accepted Hari Ini</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="text-info">{{ $recentRatingChanges->where('rating_update_time', '>', now()->startOfDay())->count() }}</h4>
                                <p class="text-muted mb-0">Rating Update Hari Ini</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $recentSubmissions->pluck('codeforcesHandle.user.id')->unique()->count() }}</h4>
                            <p class="text-muted mb-0">Mahasiswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.border-end {
    border-right: 1px solid #dee2e6;
}

.border-end:last-child {
    border-right: none;
}
</style>

@php
function getVerdictBadgeClass($verdict) {
    switch($verdict) {
        case 'OK': return 'bg-success';
        case 'WRONG_ANSWER': return 'bg-danger';
        case 'TIME_LIMIT_EXCEEDED': return 'bg-warning text-dark';
        case 'COMPILATION_ERROR': return 'bg-dark';
        case 'RUNTIME_ERROR': return 'bg-danger';
        case 'MEMORY_LIMIT_EXCEEDED': return 'bg-warning text-dark';
        case 'PRESENTATION_ERROR': return 'bg-info';
        case 'IDLENESS_LIMIT_EXCEEDED': return 'bg-secondary';
        default: return 'bg-light text-dark';
    }
}
@endphp

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
@endsection
