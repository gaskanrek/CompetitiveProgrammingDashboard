@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Mahasiswa</h1>
    @if($codeforcesHandle)
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

@if(!$codeforcesHandle)
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading">Handle Codeforces belum diatur!</h4>
        <p>Untuk menggunakan fitur dashboard secara penuh, silakan tambahkan handle Codeforces Anda.</p>
        <hr>
        <a href="{{ route('mahasiswa.profile') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Atur Handle Codeforces
        </a>
    </div>
@else
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Rating Saat Ini</h6>
                            <h2 class="mb-0">{{ number_format($codeforcesHandle->current_rating) }}</h2>
                            <small>Max: {{ number_format($codeforcesHandle->max_rating) }}</small>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-trophy"></i>
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
                            <h6 class="card-title mb-0">Soal Terpecahkan</h6>
                            <h2 class="mb-0">{{ number_format($solvedProblemsCount) }}</h2>
                            <small>Total submissions</small>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-check-circle"></i>
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
                            <h6 class="card-title mb-0">Rank</h6>
                            <h3 class="mb-0">{{ $codeforcesHandle->rank ?? 'Unrated' }}</h3>
                            <small>Max: {{ $codeforcesHandle->max_rank ?? 'N/A' }}</small>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-award"></i>
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
                            <h6 class="card-title mb-0">Kontribusi</h6>
                            <h2 class="mb-0">{{ number_format($codeforcesHandle->contribution) }}</h2>
                            <small>Community points</small>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="bi bi-heart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8">
        @if($codeforcesHandle && $recentSubmissions->count() > 0)
            <!-- Recent Submissions -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Submission Terbaru</h5>
                    <a href="{{ route('mahasiswa.activity', ['tab' => 'submissions']) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Problem</th>
                                    <th>Bahasa</th>
                                    <th>Verdict</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubmissions->take(8) as $submission)
                                    <tr>
                                        <td>
                                            <a href="{{ $submission->problem->url }}" target="_blank" class="text-decoration-none">
                                                {{ $submission->problem->contest->contest_id }}{{ $submission->problem->index }} - {{ $submission->problem->name }}
                                            </a>
                                        </td>
                                        <td>{{ $submission->programming_language }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $submission->verdict_color }}; color: white;">
                                                {{ $submission->verdict }}
                                            </span>
                                        </td>
                                        <td>{{ $submission->creation_time->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($upcomingContests->count() > 0)
            <!-- Upcoming Contests -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kontes Mendatang</h5>
                    <a href="{{ route('mahasiswa.contests') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @foreach($upcomingContests as $contest)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <h6 class="mb-1">
                                    <a href="https://codeforces.com/contest/{{ $contest->contest_id }}" target="_blank" class="text-decoration-none">
                                        {{ $contest->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>{{ $contest->start_time->format('d M Y, H:i') }}
                                    <i class="bi bi-clock ms-2 me-1"></i>{{ $contest->getDurationInHours() }} jam
                                </small>
                            </div>
                            <span class="badge bg-{{ $contest->phase === 'BEFORE' ? 'primary' : 'secondary' }}">
                                {{ $contest->phase }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        @if($codeforcesHandle && $ratingHistory->count() > 0)
            <!-- Rating History -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Rating</h5>
                    <a href="{{ route('mahasiswa.activity', ['tab' => 'rating']) }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @foreach($ratingHistory->take(5) as $history)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $history->contest_name }}</h6>
                                <small class="text-muted">Rank #{{ number_format($history->rank) }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold" style="color: {{ $history->rating_change_color }}">
                                    {{ $history->rating_change > 0 ? '+' : '' }}{{ $history->rating_change }}
                                </div>
                                <small class="text-muted">{{ $history->new_rating }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(!$codeforcesHandle)
                        <a href="{{ route('mahasiswa.profile') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>Atur Handle Codeforces
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.recommendations') }}" class="btn btn-success">
                            <i class="bi bi-lightbulb me-2"></i>Lihat Rekomendasi Soal
                        </a>
                        <a href="{{ route('mahasiswa.activity') }}" class="btn btn-info">
                            <i class="bi bi-activity me-2"></i>Lihat Aktivitas Lengkap
                        </a>
                    @endif
                    <a href="{{ route('mahasiswa.contests') }}" class="btn btn-warning">
                        <i class="bi bi-calendar-event me-2"></i>Kontes Mendatang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
