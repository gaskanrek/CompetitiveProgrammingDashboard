@extends('layouts.auth')

@section('title', 'Login - CP Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="logo-section">
            <h1>🏆 CP Dashboard</h1>
            <p class="text-white-50 mb-0">Platform Monitoring Competitive Programming</p>
        </div>
        
        <div class="card auth-card">
            <div class="card-header text-center py-4">
                <h3 class="mb-0 fw-bold">Selamat Datang Kembali!</h3>
                <p class="mb-0 opacity-75">Masuk ke akun Anda</p>
            </div>
            <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar disini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
