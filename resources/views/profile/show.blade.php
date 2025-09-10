@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">👤 Profil Saya</h2>
            <p class="text-muted mb-0">Informasi profil dan pengaturan akun Anda</p>
        </div>
        <div>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Profil
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Nama</label>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Email</label>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">
                                {{ $user->role && $user->role->name === 'pendamping' ? 'NIP' : 'NIM' }}
                            </label>
                            <p class="mb-0">{{ $user->userProfile->nim ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Role</label>
                            <p class="mb-0">
                                <span class="badge {{ $user->role && $user->role->name === 'mahasiswa' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->role ? ucfirst($user->role->name) : 'Unknown' }}
                                </span>
                            </p>
                        </div>
                        
                        @if($user->userProfile)
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Nama Lengkap</label>
                            <p class="mb-0">{{ $user->userProfile->full_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">No. Telepon</label>
                            <p class="mb-0">{{ $user->userProfile->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Universitas</label>
                            <p class="mb-0">{{ $user->userProfile->university ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Jurusan</label>
                            <p class="mb-0">{{ $user->userProfile->major ?? $user->userProfile->jurusan ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted small">Angkatan</label>
                            <p class="mb-0">{{ $user->userProfile->angkatan ?? $user->userProfile->graduation_year ?? '-' }}</p>
                        </div>
                        @if($user->userProfile->bio)
                        <div class="col-12 mb-3">
                            <label class="fw-bold text-muted small">Bio</label>
                            <p class="mb-0">{{ $user->userProfile->bio }}</p>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Statistics & Info -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Statistik Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($user->role && $user->role->name === 'mahasiswa')
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <h4 class="text-primary mb-1">
                                        {{ $user->primaryCodeforcesHandle ? $user->primaryCodeforcesHandle->current_rating : 'N/A' }}
                                    </h4>
                                    <p class="small text-muted mb-0">Rating Codeforces</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <h4 class="text-success mb-1">
                                        {{ $user->primaryCodeforcesHandle ? $user->primaryCodeforcesHandle->solved_problems_count : 0 }}
                                    </h4>
                                    <p class="small text-muted mb-0">Problem Solved</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Bergabung:</span>
                            <span>{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Terakhir update:</span>
                            <span>{{ $user->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->role && $user->role->name === 'mahasiswa' && $user->primaryCodeforcesHandle)
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="bi bi-code-square me-2"></i>
                        Codeforces Handle
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">{{ $user->primaryCodeforcesHandle->handle }}</div>
                            <div class="small text-muted">
                                Max Rating: {{ $user->primaryCodeforcesHandle->max_rating ?? 'N/A' }}
                            </div>
                        </div>
                        <a href="https://codeforces.com/profile/{{ $user->primaryCodeforcesHandle->handle }}" 
                           target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
