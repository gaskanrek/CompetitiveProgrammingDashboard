@extends('layouts.app')

@section('title', 'Rekomendasi Soal')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Rekomendasi Soal</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-funnel me-2"></i>Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?rating=800-1200">Easy (800-1200)</a></li>
                <li><a class="dropdown-item" href="?rating=1200-1600">Medium (1200-1600)</a></li>
                <li><a class="dropdown-item" href="?rating=1600-2000">Hard (1600-2000)</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('mahasiswa.recommendations') }}">All Levels</a></li>
            </ul>
        </div>
    </div>
</div>

@if(!$handle)
    <div class="alert alert-warning">
        <h4 class="alert-heading">Handle Codeforces belum diatur!</h4>
        <p>Untuk mendapatkan rekomendasi soal, silakan tambahkan handle Codeforces Anda terlebih dahulu.</p>
        <a href="{{ route('mahasiswa.profile') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Atur Handle Codeforces
        </a>
    </div>
@else
    <!-- User Stats & Target -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ number_format($currentRating) }}</h4>
                    <small>Current Rating</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ number_format($targetRating) }}</h4>
                    <small>Target Rating</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $handle->solved_problems_count }}</h4>
                    <small>Problems Solved</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations Info -->
    <div class="alert alert-info">
        <h6 class="alert-heading">
            <i class="bi bi-lightbulb me-2"></i>Rekomendasi berdasarkan rating Anda
        </h6>
        <p class="mb-0">
            Berikut adalah soal-soal yang direkomendasikan dengan rating antara 
            <strong>{{ number_format($currentRating - 100) }}</strong> hingga 
            <strong>{{ number_format($targetRating) }}</strong> 
            yang belum pernah Anda selesaikan.
        </p>
    </div>

    <!-- Recommended Problems -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Soal Rekomendasi</h5>
        </div>
        <div class="card-body">
            @if($recommendedProblems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Problem</th>
                                <th>Rating</th>
                                <th>Difficulty</th>
                                <th>Tags</th>
                                <th>Solved</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recommendedProblems as $problem)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>
                                                @if($problem->contest)
                                                    {{ $problem->contest->contest_id }}{{ $problem->index }}
                                                @endif
                                            </strong>
                                            - {{ $problem->name }}
                                        </div>
                                        @if($problem->contest)
                                            <small class="text-muted">{{ $problem->contest->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($problem->rating)
                                            <span class="badge bg-{{ $problem->rating >= $currentRating ? 'warning' : 'success' }}">
                                                {{ $problem->rating }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $problem->difficulty_level }}</span>
                                    </td>
                                    <td>
                                        @if($problem->tags && is_array($problem->tags))
                                            @foreach(array_slice($problem->tags, 0, 3) as $tag)
                                                <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                                            @endforeach
                                            @if(count($problem->tags) > 3)
                                                <span class="badge bg-light text-dark">+{{ count($problem->tags) - 3 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No tags</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ number_format($problem->solved_count) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ $problem->url }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Solve
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $recommendedProblems->links('pagination.custom') }}
            @else
                <div class="text-center py-4">
                    <i class="bi bi-check-circle display-4 text-success"></i>
                    <h5 class="mt-3">Hebat! Anda sudah menyelesaikan semua soal di level ini</h5>
                    <p class="text-muted">
                        Coba tingkatkan target rating atau lihat soal dengan level yang lebih tinggi
                    </p>
                    <div class="mt-3">
                        <a href="{{ route('mahasiswa.contests') }}" class="btn btn-primary">
                            Lihat Kontes Mendatang
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Tips Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Tips Latihan
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Strategi Latihan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success me-2"></i>Mulai dengan soal rating 100-200 poin di bawah current rating</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Secara bertahap tingkatkan ke soal yang lebih sulit</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Fokus pada topic/tags yang sering muncul</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i>Jangan langsung lihat editorial, coba dulu minimal 30 menit</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Target Mingguan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-target text-primary me-2"></i>Selesaikan minimal 10-15 soal per minggu</li>
                        <li><i class="bi bi-target text-primary me-2"></i>Ikuti minimal 1 kontes per minggu</li>
                        <li><i class="bi bi-target text-primary me-2"></i>Review dan pahami editorial soal yang tidak bisa diselesaikan</li>
                        <li><i class="bi bi-target text-primary me-2"></i>Latih implementasi dan debugging</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
