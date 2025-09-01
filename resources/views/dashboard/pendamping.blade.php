@extends('layouts.app')

@section('title', 'Dashboard Pendamping')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Pendamping</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" onclick="refreshAllData()">
            <i class="bi bi-arrow-clockwise me-2"></i>Refresh Semua Data
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Total Mahasiswa</h6>
                        <h2 class="mb-0">{{ number_format($totalMahasiswa) }}</h2>
                        <small>Terdaftar</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Mahasiswa Aktif</h6>
                        <h2 class="mb-0">{{ number_format($activeMahasiswa) }}</h2>
                        <small>Dengan handle CF</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Kontes Mendatang</h6>
                        <h2 class="mb-0">{{ number_format($upcomingContests->count()) }}</h2>
                        <small>Dalam 30 hari</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Aktivitas Hari Ini</h6>
                        <h2 class="mb-0">{{ number_format($recentSubmissions->where('creation_time', '>=', today())->count()) }}</h2>
                        <small>Submissions</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-activity"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8">
        <!-- Leaderboard -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Leaderboard Top 10</h5>
                <a href="{{ route('pendamping.leaderboard') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($leaderboard->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Handle</th>
                                    <th>Rating</th>
                                    <th>Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaderboard as $index => $handle)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('pendamping.mahasiswa.detail', $handle->user->id) }}" class="text-decoration-none">
                                                {{ $handle->user->name }}
                                            </a>
                                            @if($handle->user->profile && $handle->user->profile->nim)
                                                <br><small class="text-muted">{{ $handle->user->profile->nim }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="https://codeforces.com/profile/{{ $handle->handle }}" target="_blank" class="text-decoration-none">
                                                {{ $handle->handle }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="fw-bold" style="color: {{ $handle->rank_color }}">
                                                {{ number_format($handle->current_rating) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $handle->rank ?? 'Unrated' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2">Belum ada data leaderboard</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Aktivitas Terbaru</h5>
                <a href="{{ route('pendamping.activities') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentSubmissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Problem</th>
                                    <th>Verdict</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubmissions->take(10) as $submission)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pendamping.mahasiswa.detail', $submission->codeforcesHandle->user->id) }}" class="text-decoration-none">
                                                {{ $submission->codeforcesHandle->user->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $submission->problem->url }}" target="_blank" class="text-decoration-none">
                                                {{ $submission->problem->contest->contest_id }}{{ $submission->problem->index }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $submission->verdict_color }}; color: white;">
                                                {{ $submission->verdict }}
                                            </span>
                                        </td>
                                        <td>{{ $submission->creation_time->format('d M Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2">Belum ada aktivitas submission</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <!-- Upcoming Contests -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kontes Mendatang</h5>
                <a href="{{ route('pendamping.contests') }}" class="btn btn-sm btn-outline-primary">
                    Kelola
                </a>
            </div>
            <div class="card-body">
                @if($upcomingContests->count() > 0)
                    @foreach($upcomingContests->take(5) as $contest)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <h6 class="mb-1">
                                    <a href="https://codeforces.com/contest/{{ $contest->contest_id }}" target="_blank" class="text-decoration-none">
                                        {{ Str::limit($contest->name, 30) }}
                                    </a>
                                    @if($contest->is_important)
                                        <i class="bi bi-star-fill text-warning ms-1" title="Kontes Penting"></i>
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>{{ $contest->start_time->format('d M, H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-primary">{{ $contest->getDurationFormatted() }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <p class="text-muted mt-2">Tidak ada kontes mendatang</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pendamping.mahasiswa') }}" class="btn btn-primary">
                        <i class="bi bi-people me-2"></i>Kelola Mahasiswa
                    </a>
                    <a href="{{ route('pendamping.leaderboard') }}" class="btn btn-success">
                        <i class="bi bi-award me-2"></i>Lihat Leaderboard
                    </a>
                    <a href="{{ route('pendamping.activities') }}" class="btn btn-info">
                        <i class="bi bi-activity me-2"></i>Monitor Aktivitas
                    </a>
                    <a href="{{ route('pendamping.contests') }}" class="btn btn-warning">
                        <i class="bi bi-calendar-event me-2"></i>Kelola Kontes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
async function refreshAllData() {
    console.log('refreshAllData function called');
    
    // Cari tombol berdasarkan onclick attribute jika event.target tidak tersedia
    const button = event?.target || document.querySelector('button[onclick="refreshAllData()"]');
    if (!button) {
        console.error('Button not found');
        showAlert('error', 'Tombol tidak ditemukan');
        return;
    }
    
    console.log('Button found:', button);
    
    const originalHtml = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Refreshing...';
        button.disabled = true;
        
        console.log('Starting foreground refresh process...');
        
        // Refresh foreground - tunggu sampai selesai
        showAlert('info', 'Memulai refresh semua data (kontes, rating, submissions)...');
        
        const response = await fetch('{{ route("pendamping.refresh.all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const result = await response.json();
        console.log('Response:', result);
        
        if (result.success) {
            console.log('Refresh completed successfully, reloading page...');
            showAlert('success', 'Semua data berhasil direfresh! Memuat ulang halaman...');
            // Auto refresh halaman setelah 3 detik
            setTimeout(() => location.reload(), 3000);
        }
        
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat refresh data: ' + error.message);
    } finally {
        button.innerHTML = originalHtml;
        button.disabled = false;
        console.log('Button restored');
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'info' ? 'info' : 'danger'} alert-dismissible fade show position-fixed`;
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
</script>
