@extends('layouts.auth')

@section('title', 'Register - CP Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="logo-section">
            <h1>🏆 CP Dashboard</h1>
            <p class="text-white-50 mb-0">Platform Monitoring Competitive Programming</p>
        </div>
        
        <div class="card auth-card">
            <div class="card-header text-center py-4">
                <h3 class="mb-0 fw-bold">Bergabung dengan Kami!</h3>
                <p class="mb-0 opacity-75">Buat akun baru untuk memulai</p>
            </div>
            <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Pengguna *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role *</label>
                                <select class="form-select @error('role_id') is-invalid @enderror" 
                                        id="role_id" name="role_id" required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <hr class="my-4">
                            <h6 class="text-muted mb-3">Informasi Profil (Opsional)</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name') }}">
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="nim" class="form-label">NIM</label>
                                    <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                           id="nim" name="nim" value="{{ old('nim') }}">
                                    @error('nim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="university" class="form-label">Universitas</label>
                                    <input type="text" class="form-control @error('university') is-invalid @enderror" 
                                           id="university" name="university" value="{{ old('university') }}">
                                    @error('university')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="major" class="form-label">Jurusan</label>
                                    <input type="text" class="form-control @error('major') is-invalid @enderror" 
                                           id="major" name="major" value="{{ old('major') }}">
                                    @error('major')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="graduation_year" class="form-label">Tahun Lulus</label>
                                <select class="form-select @error('graduation_year') is-invalid @enderror" 
                                        id="graduation_year" name="graduation_year">
                                    <option value="">Pilih Tahun</option>
                                    @for($year = 2020; $year <= 2030; $year++)
                                        <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('graduation_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Daftar
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk disini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
