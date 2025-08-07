@extends('layouts.app')

@section('title', 'Profil & Handle Codeforces')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil & Handle Codeforces</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Codeforces Handle Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Handle Codeforces</h5>
            </div>
            <div class="card-body">
                @if($codeforcesHandle)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                @if($codeforcesHandle->avatar_url)
                                    <img src="{{ $codeforcesHandle->avatar_url }}" alt="Avatar" class="rounded-circle me-3" width="64" height="64">
                                @endif
                                <div>
                                    <h4 class="mb-1">
                                        <a href="https://codeforces.com/profile/{{ $codeforcesHandle->handle }}" target="_blank" class="text-decoration-none">
                                            {{ $codeforcesHandle->handle }}
                                        </a>
                                    </h4>
                                    <span class="badge" style="background-color: {{ $codeforcesHandle->rank_color }}; color: white;">
                                        {{ $codeforcesHandle->rank ?? 'Unrated' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="mb-0" style="color: {{ $codeforcesHandle->rank_color }}">
                                            {{ number_format($codeforcesHandle->current_rating) }}
                                        </h5>
                                        <small class="text-muted">Current Rating</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0 text-success">{{ number_format($codeforcesHandle->max_rating) }}</h5>
                                    <small class="text-muted">Max Rating</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td><strong>Max Rank:</strong></td>
                                        <td>{{ $codeforcesHandle->max_rank ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kontribusi:</strong></td>
                                        <td>{{ number_format($codeforcesHandle->contribution) }}</td>
                                    </tr>
                                    @if($codeforcesHandle->organization)
                                        <tr>
                                            <td><strong>Organisasi:</strong></td>
                                            <td>{{ $codeforcesHandle->organization }}</td>
                                        </tr>
                                    @endif
                                    @if($codeforcesHandle->country)
                                        <tr>
                                            <td><strong>Negara:</strong></td>
                                            <td>{{ $codeforcesHandle->country }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Terakhir Update:</strong></td>
                                        <td>{{ $codeforcesHandle->last_crawled_at ? $codeforcesHandle->last_crawled_at->diffForHumans() : 'Belum pernah' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="d-flex gap-2">
                        <form action="{{ route('mahasiswa.refresh') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                            </button>
                        </form>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changeHandleModal">
                            <i class="bi bi-pencil me-2"></i>Ubah Handle
                        </button>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-plus display-4 text-muted"></i>
                        <h5 class="mt-3">Belum Ada Handle Codeforces</h5>
                        <p class="text-muted">Tambahkan handle Codeforces Anda untuk menggunakan fitur dashboard</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHandleModal">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Handle
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Profile Information -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informasi Profil</h5>
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Profil
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                @if($user->profile)
                                    <tr>
                                        <td><strong>Nama Lengkap:</strong></td>
                                        <td>{{ $user->profile->full_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NIM:</strong></td>
                                        <td>{{ $user->profile->nim ?? '-' }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                @if($user->profile)
                                    <tr>
                                        <td><strong>Universitas:</strong></td>
                                        <td>{{ $user->profile->university ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jurusan:</strong></td>
                                        <td>{{ $user->profile->major ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tahun Lulus:</strong></td>
                                        <td>{{ $user->profile->graduation_year ?? '-' }}</td>
                                    </tr>
                                    @if($user->profile->phone)
                                        <tr>
                                            <td><strong>Telepon:</strong></td>
                                            <td>{{ $user->profile->phone }}</td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($user->profile && $user->profile->bio)
                    <hr>
                    <div>
                        <strong>Bio:</strong>
                        <p class="mt-2">{{ $user->profile->bio }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($codeforcesHandle)
            <!-- Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h3 class="mb-0">{{ $codeforcesHandle->solved_problems_count }}</h3>
                                <small class="text-muted">Soal Terpecahkan</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mb-0">{{ $codeforcesHandle->submissions()->count() }}</h5>
                                <small class="text-muted">Total Submissions</small>
                            </div>
                            <div class="col-6">
                                <h5 class="mb-0">{{ $codeforcesHandle->contestParticipations()->count() }}</h5>
                                <small class="text-muted">Kontes Diikuti</small>
                            </div>
                        </div>
                    </div>
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
                    @if($codeforcesHandle)
                        <a href="{{ route('mahasiswa.activity') }}" class="btn btn-primary">
                            <i class="bi bi-activity me-2"></i>Lihat Aktivitas
                        </a>
                        <a href="{{ route('mahasiswa.recommendations') }}" class="btn btn-success">
                            <i class="bi bi-lightbulb me-2"></i>Rekomendasi Soal
                        </a>
                    @endif
                    <a href="{{ route('mahasiswa.contests') }}" class="btn btn-info">
                        <i class="bi bi-calendar-event me-2"></i>Kontes Mendatang
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-gear me-2"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding Handle -->
<div class="modal fade" id="addHandleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Handle Codeforces</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mahasiswa.codeforces.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="handle" class="form-label">Handle Codeforces</label>
                        <input type="text" class="form-control" id="handle" name="handle" 
                               placeholder="Masukkan handle Codeforces Anda" required>
                        <div class="form-text">
                            Pastikan handle yang dimasukkan benar dan dapat diakses di 
                            <a href="https://codeforces.com" target="_blank">codeforces.com</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Handle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Changing Handle -->
<div class="modal fade" id="changeHandleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Handle Codeforces</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mahasiswa.codeforces.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="handle_edit" class="form-label">Handle Codeforces</label>
                        <input type="text" class="form-control" id="handle_edit" name="handle" 
                               value="{{ $codeforcesHandle ? $codeforcesHandle->handle : '' }}" required>
                        <div class="form-text">
                            Mengubah handle akan mengganti handle aktif Anda
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Handle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
