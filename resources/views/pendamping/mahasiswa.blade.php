@extends('layouts.app')

@section('title', 'Daftar Mahasiswa - Pendamping Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📚 Daftar Mahasiswa</h2>
            <p class="text-muted mb-0">Monitor aktivitas dan progress mahasiswa dalam competitive programming</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="refreshAllData()">
                <i class="bi bi-arrow-clockwise"></i> Refresh Semua Data
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>
                        Mahasiswa Terdaftar ({{ $mahasiswaUsers->total() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($mahasiswaUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">Mahasiswa</th>
                                        <th width="15%">NIM</th>
                                        <th width="15%">Handle Codeforces</th>
                                        <th width="10%">Rating</th>
                                        <th width="10%">Rank</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaUsers as $index => $mahasiswa)
                                    <tr>
                                        <td>{{ $mahasiswaUsers->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ strtoupper(substr($mahasiswa->name ?? 'N', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $mahasiswa->name ?? 'Tidak ada nama' }}</div>
                                                    <div class="text-muted small">{{ $mahasiswa->email }}</div>
                                                    @if($mahasiswa->profile)
                                                        <div class="text-muted small">{{ $mahasiswa->profile->full_name }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($mahasiswa->profile && $mahasiswa->profile->nim)
                                                <span class="badge bg-secondary">{{ $mahasiswa->profile->nim }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mahasiswa->primaryCodeforcesHandle)
                                                <a href="https://codeforces.com/profile/{{ $mahasiswa->primaryCodeforcesHandle->handle }}" 
                                                   target="_blank" class="text-decoration-none">
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-link-45deg"></i>
                                                        {{ $mahasiswa->primaryCodeforcesHandle->handle }}
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-muted">Belum ada handle</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mahasiswa->primaryCodeforcesHandle && $mahasiswa->primaryCodeforcesHandle->current_rating)
                                                <span class="fw-bold text-primary">
                                                    {{ number_format($mahasiswa->primaryCodeforcesHandle->current_rating) }}
                                                </span>
                                                @if($mahasiswa->primaryCodeforcesHandle->max_rating)
                                                    <div class="text-muted small">
                                                        Max: {{ number_format($mahasiswa->primaryCodeforcesHandle->max_rating) }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mahasiswa->primaryCodeforcesHandle && $mahasiswa->primaryCodeforcesHandle->current_rank)
                                                <span class="badge {{ getRankBadgeClass($mahasiswa->primaryCodeforcesHandle->current_rank) }}">
                                                    {{ ucfirst($mahasiswa->primaryCodeforcesHandle->current_rank) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mahasiswa->primaryCodeforcesHandle)
                                                @if($mahasiswa->primaryCodeforcesHandle->is_active)
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-pause-circle"></i> Tidak Aktif
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-question-circle"></i> Belum Setup
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('pendamping.mahasiswa.detail', $mahasiswa->id) }}" 
                                                   class="btn btn-outline-primary" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-info" 
                                                        onclick="refreshUserData({{ $mahasiswa->id }})" title="Refresh Data">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $mahasiswaUsers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people display-1 text-muted"></i>
                            <h4 class="mt-3">Belum Ada Mahasiswa</h4>
                            <p class="text-muted">Belum ada mahasiswa yang terdaftar dalam sistem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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

.badge {
    font-size: 0.75rem;
}
</style>

<script>
async function refreshUserData(userId) {
    const button = event.target.closest('button');
    const originalHtml = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i>';
        button.disabled = true;
        
        const response = await fetch(`{{ url('pendamping/mahasiswa') }}/${userId}/refresh`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Data berhasil direfresh!');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', result.message || 'Gagal refresh data');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat refresh data');
    } finally {
        button.innerHTML = originalHtml;
        button.disabled = false;
    }
}

async function refreshAllData() {
    const button = event.target;
    const originalHtml = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Refreshing...';
        button.disabled = true;
        
        const response = await fetch('{{ route("pendamping.refresh.all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Semua data berhasil direfresh!');
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('error', result.message || 'Gagal refresh data');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat refresh data');
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
</script>
@endsection
