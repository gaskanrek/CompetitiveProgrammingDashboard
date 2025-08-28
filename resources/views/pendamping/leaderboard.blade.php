@extends('layouts.app')

@section('title', 'Leaderboard - Pendamping Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🏆 Leaderboard</h2>
            <p class="text-muted mb-0">Ranking mahasiswa berdasarkan rating Codeforces</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy-fill me-2"></i>
                        Top Competitive Programmers
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($leaderboard->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="8%" class="text-center">Rank</th>
                                        <th width="25%">Mahasiswa</th>
                                        <th width="15%">Handle</th>
                                        <th width="12%">Current Rating</th>
                                        <th width="12%">Max Rating</th>
                                        <th width="15%">Rank Codeforces</th>
                                        <th width="13%">Problems Solved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboard as $index => $handle)
                                    <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                        <td class="text-center">
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-trophy-fill"></i> 1st
                                                </span>
                                            @elseif($index == 1)
                                                <span class="badge bg-light text-dark fs-6">
                                                    <i class="bi bi-award-fill"></i> 2nd
                                                </span>
                                            @elseif($index == 2)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-award"></i> 3rd
                                                </span>
                                            @else
                                                <span class="fs-5 fw-bold">{{ $leaderboard->firstItem() + $index }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ strtoupper(substr($handle->user->name ?? 'N', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $handle->user->name ?? 'Tidak ada nama' }}</div>
                                                    <div class="text-muted small">{{ $handle->user->email }}</div>
                                                    @if($handle->user->profile && $handle->user->profile->full_name)
                                                        <div class="text-muted small">{{ $handle->user->profile->full_name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="https://codeforces.com/profile/{{ $handle->handle }}" 
                                               target="_blank" class="text-decoration-none">
                                                <span class="badge bg-info">
                                                    <i class="bi bi-link-45deg"></i>
                                                    {{ $handle->handle }}
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-bold fs-5 rating-{{ str_replace(' ', '-', strtolower($handle->current_rank ?? 'unrated')) }}">
                                                {{ number_format($handle->current_rating ?? 0) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-muted">
                                                {{ number_format($handle->max_rating ?? 0) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ getRankBadgeClass($handle->current_rank) }}">
                                                {{ ucwords(str_replace('_', ' ', $handle->current_rank ?? 'unrated')) }}
                                            </span>
                                            @if($handle->max_rank && $handle->max_rank !== $handle->current_rank)
                                                <div class="text-muted small">
                                                    Best: {{ ucwords(str_replace('_', ' ', $handle->max_rank)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-success">
                                                {{ number_format($handle->solved_problems_count ?? 0) }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $leaderboard->links('pagination.custom') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-trophy display-1 text-muted"></i>
                            <h4 class="mt-3">Belum Ada Data Leaderboard</h4>
                            <p class="text-muted">Belum ada mahasiswa dengan handle Codeforces aktif.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($leaderboard->count() > 0)
    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Mahasiswa</h6>
                            <h3 class="mb-0">{{ $leaderboard->total() }}</h3>
                            <small>Terdaftar aktif</small>
                        </div>
                        <i class="bi bi-people display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Rata-rata Rating</h6>
                            <h3 class="mb-0">
                                {{ number_format($leaderboard->avg('current_rating') ?? 0) }}
                            </h3>
                            <small>Semua mahasiswa</small>
                        </div>
                        <i class="bi bi-graph-up display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Rating Tertinggi</h6>
                            <h3 class="mb-0">
                                {{ number_format($leaderboard->max('current_rating') ?? 0) }}
                            </h3>
                            <small>Best performer</small>
                        </div>
                        <i class="bi bi-award display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Problems</h6>
                            <h3 class="mb-0">
                                {{ number_format($leaderboard->sum('solved_problems_count') ?? 0) }}
                            </h3>
                            <small>Problems solved</small>
                        </div>
                        <i class="bi bi-check-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.875rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>

@php
function getRankBadgeClass($rank) {
    switch(strtolower($rank)) {
        case 'newbie': return 'bg-secondary';
        case 'pupil': return 'bg-success';
        case 'specialist': return 'bg-info';
        case 'expert': return 'bg-primary';
        case 'candidate master': return 'bg-warning text-dark';
        case 'master': return 'bg-warning text-dark';
        case 'international master': return 'bg-danger';
        case 'grandmaster': return 'bg-danger';
        case 'international grandmaster': return 'bg-danger';
        case 'legendary grandmaster': return 'bg-dark';
        default: return 'bg-light text-dark';
    }
}
@endphp
@endsection
