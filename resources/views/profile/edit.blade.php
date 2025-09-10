@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">✏️ Edit Profil</h2>
            <p class="text-muted mb-0">Perbarui informasi profil dan pengaturan akun Anda</p>
        </div>
        <div>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="{{ old('full_name', $user->userProfile->full_name ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nim" class="form-label">
                                    {{ $user->role && $user->role->name === 'pendamping' ? 'NIP' : 'NIM' }}
                                </label>
                                <input type="text" class="form-control" id="nim" name="nim" 
                                       value="{{ old('nim', $user->userProfile->nim ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', $user->userProfile->phone ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="university" class="form-label">Universitas</label>
                                <input type="text" class="form-control" id="university" name="university" 
                                       value="{{ old('university', $user->userProfile->university ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="major" class="form-label">Jurusan</label>
                                <input type="text" class="form-control" id="major" name="major" 
                                       value="{{ old('major', $user->userProfile->major ?? $user->userProfile->jurusan ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="graduation_year" class="form-label">Tahun Lulus / Angkatan</label>
                                <select class="form-select" id="graduation_year" name="graduation_year">
                                    <option value="">Pilih Tahun</option>
                                    @for($year = 2020; $year <= 2030; $year++)
                                        <option value="{{ $year }}" 
                                                {{ old('graduation_year', $user->userProfile->graduation_year ?? $user->userProfile->angkatan) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" 
                                          placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->userProfile->bio ?? '') }}</textarea>
                                <div class="form-text">Maksimal 500 karakter</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Field yang bertanda * wajib diisi
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="col-lg-4 mb-4">
                <!-- Current Info -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-shield-check me-2"></i>
                            Informasi Akun
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Role:</span>
                                <span class="badge {{ $user->role && $user->role->name === 'mahasiswa' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->role ? ucfirst($user->role->name) : 'Unknown' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bergabung:</span>
                                <span>{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-success">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="bi bi-key me-2"></i>
                            Ganti Password
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            Untuk keamanan akun, ganti password secara berkala.
                        </p>
                        <button type="button" class="btn btn-warning btn-sm w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-shield-lock me-2"></i>
                            Ganti Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Ganti Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        <div class="form-text">Minimal 8 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Character counter for bio
document.getElementById('bio').addEventListener('input', function() {
    const maxLength = 500;
    const currentLength = this.value.length;
    const formText = this.nextElementSibling;
    
    if (currentLength > maxLength) {
        this.value = this.value.substring(0, maxLength);
        currentLength = maxLength;
    }
    
    formText.textContent = `${currentLength}/${maxLength} karakter`;
    
    if (currentLength > maxLength * 0.9) {
        formText.classList.add('text-warning');
    } else {
        formText.classList.remove('text-warning');
    }
});
</script>
@endsection
