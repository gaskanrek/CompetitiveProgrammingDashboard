@extends('layouts.app')

@section('title', 'Pengaturan - Pendamping Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">⚙️ Pengaturan</h2>
            <p class="text-muted mb-0">Kelola profil dan preferensi Anda</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    <form id="profileForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ Auth::user()->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ Auth::user()->email }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" 
                                   value="{{ Auth::user()->nim }}" readonly>
                            <div class="form-text">NIM tidak dapat diubah</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" name="role" 
                                   value="{{ ucfirst(Auth::user()->role) }}" readonly>
                            <div class="form-text">Role tidak dapat diubah</div>
                        </div>
                        
                        @if(Auth::user()->userProfile)
                        <div class="mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <input type="text" class="form-control" id="angkatan" name="angkatan" 
                                   value="{{ Auth::user()->userProfile->angkatan }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" 
                                   value="{{ Auth::user()->userProfile->jurusan }}">
                        </div>
                        @endif
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Ganti Password
                    </h5>
                </div>
                <div class="card-body">
                    <form id="passwordForm">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" required minlength="8">
                            <div class="form-text">Password harus minimal 8 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-2"></i>
                            Ganti Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Dashboard Preferences -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-sliders me-2"></i>
                        Preferensi Dashboard
                    </h5>
                </div>
                <div class="card-body">
                    <form id="preferencesForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tema Dashboard</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="theme" id="light" value="light" checked>
                                <label class="form-check-label" for="light">
                                    <i class="bi bi-sun me-2"></i>Terang
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="theme" id="dark" value="dark" disabled>
                                <label class="form-check-label" for="dark">
                                    <i class="bi bi-moon me-2"></i>Gelap (Segera Hadir)
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="items_per_page" class="form-label">Item per Halaman</label>
                            <select class="form-select" id="items_per_page" name="items_per_page">
                                <option value="10" selected>10 item</option>
                                <option value="25">25 item</option>
                                <option value="50">50 item</option>
                                <option value="100">100 item</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_refresh" 
                                       name="auto_refresh" checked>
                                <label class="form-check-label" for="auto_refresh">
                                    Auto-refresh data setiap 5 menit
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_animations" 
                                       name="show_animations" checked>
                                <label class="form-check-label" for="show_animations">
                                    Tampilkan animasi dan efek
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-check-circle me-2"></i>
                            Simpan Preferensi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>
                        Statistik Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <h4 class="text-primary mb-1">{{ $mahasiswaCount ?? 0 }}</h4>
                                    <p class="small text-muted mb-0">Mahasiswa Dibimbing</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <h4 class="text-success mb-1">{{ $activeMahasiswaCount ?? 0 }}</h4>
                                    <p class="small text-muted mb-0">Mahasiswa Aktif</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <h4 class="text-warning mb-1">{{ $totalContests ?? 0 }}</h4>
                                    <p class="small text-muted mb-0">Kontes Diikuti</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body text-center p-3">
                                    <h4 class="text-info mb-1">{{ Auth::user()->created_at->diffInDays() }}</h4>
                                    <p class="small text-muted mb-0">Hari Bergabung</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between">
                            <span>Akun dibuat:</span>
                            <span>{{ Auth::user()->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Terakhir login:</span>
                            <span>{{ Auth::user()->last_login_at ? \Carbon\Carbon::parse(Auth::user()->last_login_at)->diffForHumans() : 'Tidak tersedia' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Status akun:</span>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="row">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Zona Berbahaya
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Hapus Akun</h6>
                            <p class="text-muted mb-0">Menghapus akun akan menghilangkan semua data Anda secara permanen.</p>
                        </div>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash me-2"></i>
                            Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                <p>Apakah Anda yakin ingin menghapus akun? Semua data berikut akan dihapus secara permanen:</p>
                <ul>
                    <li>Profil dan informasi pribadi</li>
                    <li>Histori login dan aktivitas</li>
                    <li>Data mahasiswa yang dibimbing</li>
                    <li>Preferensi dan pengaturan</li>
                </ul>
                <p class="fw-bold">Ketik "HAPUS" untuk mengonfirmasi:</p>
                <input type="text" class="form-control" id="deleteConfirmation" placeholder="Ketik HAPUS">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled onclick="deleteAccount()">
                    <i class="bi bi-trash me-2"></i>
                    Hapus Akun Permanen
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Profile Form
document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm me-2"></i>Menyimpan...';
        button.disabled = true;
        
        const response = await fetch('{{ route("pendamping.settings.profile") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Profil berhasil diperbarui!');
        } else {
            showAlert('error', result.message || 'Gagal memperbarui profil');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memperbarui profil');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
});

// Password Form
document.getElementById('passwordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        showAlert('error', 'Konfirmasi password tidak cocok');
        return;
    }
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm me-2"></i>Mengubah...';
        button.disabled = true;
        
        const response = await fetch('{{ route("pendamping.settings.password") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Password berhasil diubah!');
            this.reset();
        } else {
            showAlert('error', result.message || 'Gagal mengubah password');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat mengubah password');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
});

// Preferences Form
document.getElementById('preferencesForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm me-2"></i>Menyimpan...';
        button.disabled = true;
        
        const response = await fetch('{{ route("pendamping.settings.preferences") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Preferensi berhasil disimpan!');
        } else {
            showAlert('error', result.message || 'Gagal menyimpan preferensi');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menyimpan preferensi');
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
    }
});

// Delete confirmation
document.getElementById('deleteConfirmation').addEventListener('input', function() {
    const button = document.getElementById('confirmDeleteBtn');
    button.disabled = this.value !== 'HAPUS';
});

async function deleteAccount() {
    const button = document.getElementById('confirmDeleteBtn');
    const originalText = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm me-2"></i>Menghapus...';
        button.disabled = true;
        
        const response = await fetch('{{ route("pendamping.settings.delete") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Akun berhasil dihapus. Mengalihkan...');
            setTimeout(() => window.location.href = '{{ route("login") }}', 2000);
        } else {
            showAlert('error', result.message || 'Gagal menghapus akun');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menghapus akun');
    } finally {
        button.innerHTML = originalText;
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
</script>
@endsection
