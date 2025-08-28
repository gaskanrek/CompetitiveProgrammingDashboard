@extends('layouts.app')

@section('title', 'Manajemen Kontes - Pendamping Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📅 Manajemen Kontes</h2>
            <p class="text-muted mb-0">Kelola kontes penting dan monitor partisipasi mahasiswa</p>
        </div>
    </div>

    <!-- Upcoming Contests -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Kontes Mendatang
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($upcomingContests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="45%">Nama Kontes</th>
                                        <th width="15%">Tipe</th>
                                        <th width="25%">Waktu Mulai</th>
                                        <th width="15%">Durasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingContests->take(15) as $contest)
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="https://codeforces.com/contest/{{ $contest->id }}" 
                                                   target="_blank" class="text-decoration-none fw-semibold">
                                                    {{ $contest->name }}
                                                    <i class="bi bi-box-arrow-up-right small"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ getContestTypeBadgeClass($contest->type) }}">
                                                {{ $contest->type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div class="fw-semibold">
                                                    {{ $contest->start_time->format('d M Y') }}
                                                </div>
                                                <div class="text-muted">
                                                    {{ $contest->start_time->format('H:i') }} WIB
                                                </div>
                                                <div class="text-info">
                                                    {{ $contest->start_time->diffForHumans() }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ gmdate('H:i', $contest->duration_seconds) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x display-4 text-muted"></i>
                            <h5 class="mt-3">Tidak Ada Kontes Mendatang</h5>
                            <p class="text-muted">Tidak ada kontes yang dijadwalkan dalam waktu dekat.</p>
                        </div>
                    @endif
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

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>

<script>
@php
function getContestTypeBadgeClass($type) {
    switch(strtoupper($type)) {
        case 'CF': return 'bg-primary';
        case 'IOI': return 'bg-success';
        case 'ICPC': return 'bg-warning text-dark';
        default: return 'bg-secondary';
    }
}
@endphp
</script>
@endsection
