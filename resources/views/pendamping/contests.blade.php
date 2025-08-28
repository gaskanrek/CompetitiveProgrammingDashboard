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

    <div class="row">
        <!-- Upcoming Contests -->
        <div class="col-lg-8 mb-4">
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
                                        <th width="40%">Nama Kontes</th>
                                        <th width="15%">Tipe</th>
                                        <th width="20%">Waktu Mulai</th>
                                        <th width="10%">Durasi</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingContests->take(10) as $contest)
                                    <tr class="{{ $contest->is_important ? 'table-warning' : '' }}">
                                        <td>
                                            <div>
                                                <a href="https://codeforces.com/contest/{{ $contest->id }}" 
                                                   target="_blank" class="text-decoration-none fw-semibold">
                                                    {{ $contest->name }}
                                                    <i class="bi bi-box-arrow-up-right small"></i>
                                                </a>
                                                @if($contest->is_important)
                                                    <span class="badge bg-warning text-dark ms-2">
                                                        <i class="bi bi-star-fill"></i> Penting
                                                    </span>
                                                @endif
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
                                        <td>
                                            <button type="button" class="btn btn-sm {{ $contest->is_important ? 'btn-warning' : 'btn-outline-warning' }}" 
                                                    onclick="toggleImportant({{ $contest->id }}, {{ $contest->is_important ? 'false' : 'true' }})"
                                                    title="{{ $contest->is_important ? 'Hapus dari kontes penting' : 'Tandai sebagai kontes penting' }}">
                                                <i class="bi bi-star{{ $contest->is_important ? '-fill' : '' }}"></i>
                                            </button>
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

        <!-- Important Contests -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill me-2"></i>
                        Kontes Penting
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($importantContests->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($importantContests->take(5) as $contest)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold small">{{ Str::limit($contest->name, 25) }}</div>
                                        <div class="text-muted small">
                                            {{ $contest->start_time->format('d M, H:i') }}
                                        </div>
                                        <div class="text-info small">
                                            {{ $contest->start_time->diffForHumans() }}
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            onclick="toggleImportant({{ $contest->id }}, false)"
                                            title="Hapus dari kontes penting">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-star display-6 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada kontes penting</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Contest Participations -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>
                        Partisipasi Kontes Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentParticipations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="25%">Mahasiswa</th>
                                        <th width="35%">Kontes</th>
                                        <th width="10%">Rank</th>
                                        <th width="15%">Rating Change</th>
                                        <th width="15%">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentParticipations->take(10) as $participation)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($participation->codeforcesHandle->user->name ?? 'N', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small">{{ $participation->codeforcesHandle->user->name ?? 'Unknown' }}</div>
                                                    <div class="text-muted small">{{ $participation->codeforcesHandle->handle }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($participation->contest)
                                                <a href="https://codeforces.com/contest/{{ $participation->contest->id }}" 
                                                   target="_blank" class="text-decoration-none">
                                                    <div class="fw-semibold small">{{ Str::limit($participation->contest->name, 40) }}</div>
                                                    <div class="text-muted small">Contest {{ $participation->contest->id }}</div>
                                                </a>
                                            @else
                                                <span class="text-muted">Contest {{ $participation->contest_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($participation->rank)
                                                <span class="badge {{ $participation->rank <= 100 ? 'bg-success' : ($participation->rank <= 500 ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                    #{{ $participation->rank }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($participation->rating_change)
                                                <span class="badge {{ $participation->rating_change >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $participation->rating_change >= 0 ? '+' : '' }}{{ $participation->rating_change }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ $participation->participation_time->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people display-4 text-muted"></i>
                            <h5 class="mt-3">Belum Ada Partisipasi</h5>
                            <p class="text-muted">Belum ada mahasiswa yang berpartisipasi dalam kontes.</p>
                        </div>
                    @endif
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

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>

<script>
async function toggleImportant(contestId, isImportant) {
    const button = event.target.closest('button');
    const originalHtml = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i>';
        button.disabled = true;
        
        const response = await fetch(`{{ url('pendamping/contests') }}/${contestId}/important`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ is_important: isImportant })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', isImportant ? 'Kontes ditandai sebagai penting!' : 'Kontes dihapus dari daftar penting!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', result.message || 'Gagal mengubah status kontes');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat mengubah status kontes');
    } finally {
        button.innerHTML = originalHtml;
        button.disabled = false;
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

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
