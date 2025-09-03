@extends('layouts.app')

@section('title', 'Detail Mahasiswa - ' . $mahasiswa->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('pendamping.mahasiswa') }}">Daftar Mahasiswa</a></li>
                    <li class="breadcrumb-item active">{{ $mahasiswa->name }}</li>
                </ol>
            </nav>
            <h2 class="mb-1">👤 Detail Mahasiswa</h2>
            <p class="text-muted mb-0">Informasi lengkap dan aktivitas competitive programming</p>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi Mahasiswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%" class="fw-semibold">Nama:</td>
                                    <td>{{ $mahasiswa->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Email:</td>
                                    <td>{{ $mahasiswa->email }}</td>
                                </tr>
                                @if($mahasiswa->profile)
                                <tr>
                                    <td class="fw-semibold">Nama Lengkap:</td>
                                    <td>{{ $mahasiswa->profile->full_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">NIM:</td>
                                    <td>{{ $mahasiswa->profile->nim ?? '-' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($mahasiswa->profile)
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%" class="fw-semibold">Universitas:</td>
                                    <td>{{ $mahasiswa->profile->university ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Jurusan:</td>
                                    <td>{{ $mahasiswa->profile->major ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Angkatan:</td>
                                    <td>{{ $mahasiswa->profile->graduation_year ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Bergabung:</td>
                                    <td>{{ $mahasiswa->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($handle)
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Rating Saat Ini</h6>
                            <h3 class="mb-0">{{ number_format($handle->current_rating ?? 0) }}</h3>
                            @if($handle->max_rating)
                            <small>Max: {{ number_format($handle->max_rating) }}</small>
                            @endif
                        </div>
                        <i class="bi bi-trophy display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Problems Solved</h6>
                            <h3 class="mb-0">{{ number_format($solvedProblemsCount) }}</h3>
                            <small>Total soal diselesaikan</small>
                        </div>
                        <i class="bi bi-check-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Rank</h6>
                            <h4 class="mb-0">{{ ucfirst($handle->rank ?? 'unrated') }}</h4>
                            @if($handle->max_rank)
                            <small>Max: {{ ucfirst($handle->max_rank) }}</small>
                            @endif
                        </div>
                        <i class="bi bi-award display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Handle Codeforces</h6>
                            <h5 class="mb-0">
                                <a href="https://codeforces.com/profile/{{ $handle->handle }}" 
                                   target="_blank" class="text-dark text-decoration-none">
                                    {{ $handle->handle }}
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </h5>
                            <small>{{ $handle->is_active ? 'Aktif' : 'Tidak Aktif' }}</small>
                        </div>
                        <i class="bi bi-link-45deg display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <!-- Recent Submissions -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-code-slash me-2"></i>
                        Submission Terbaru
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($submissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Problem</th>
                                        <th>Verdict</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submissions->take(5) as $submission)
                                    <tr>
                                        <td>
                                            @if($submission->problem)
                                                <a href="https://codeforces.com/problemset/problem/{{ $submission->problem->contest_id }}/{{ $submission->problem->index }}" 
                                                   target="_blank" class="text-decoration-none">
                                                    {{ $submission->problem->contest_id }}{{ $submission->problem->index }} - {{ $submission->problem->name }}
                                                </a>
                                            @else
                                                Problem {{ $submission->problem_id }}
                                            @endif
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
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-code-slash display-4 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada submission</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rating History -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Riwayat Rating
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($ratingHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Contest</th>
                                        <th>Rating</th>
                                        <th>Perubahan</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ratingHistory->take(5) as $rating)
                                    <tr>
                                        <td>
                                            @if($rating->contest)
                                                <a href="https://codeforces.com/contest/{{ $rating->contest->id }}" 
                                                   target="_blank" class="text-decoration-none">
                                                    {{ $rating->contest->name }}
                                                </a>
                                            @else
                                                Contest {{ $rating->contest_id }}
                                            @endif
                                        </td>
                                        <td class="fw-semibold">{{ $rating->new_rating }}</td>
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
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-graph-up display-4 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada riwayat rating</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- No Handle Message -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                <h5 class="alert-heading">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Handle Codeforces Belum Ditambahkan
                </h5>
                <p class="mb-0">Mahasiswa ini belum menambahkan handle Codeforces. Data aktivitas competitive programming tidak tersedia.</p>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
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
</script>
@endsection
