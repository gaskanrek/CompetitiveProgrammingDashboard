@extends('layouts.app')

@section('title', 'Manajemen Kontes - Pendamping Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📅 Kontes Mendatang & Berlangsung</h2>
            <p class="text-muted mb-0">Platform Monitoring Competitive Programming</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $currentContests->count() }}</h4>
                    <small>Kontes Berlangsung</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $upcomingContests->count() }}</h4>
                    <small>Total Kontes Mendatang</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $upcomingContests->where('start_time', '>=', now())->where('start_time', '<=', now()->addDays(7))->count() }}</h4>
                    <small>Kontes Terdekat</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Telegram Channel Updates -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-telegram me-2"></i>
                Update dari Channel Telegram
            </h6>
            <div class="d-flex align-items-center">
                <small class="me-3">{{ count($telegramMessages) }} pesan terbaru</small>
                <button type="button" class="btn btn-sm btn-light" onclick="refreshTelegramMessages()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(!empty($telegramMessages))
                <div class="row">
                    @foreach($telegramMessages as $message)
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-chat-left-text"></i>
                                            {{ $message['chat_title'] }}
                                        </small>
                                        <div class="text-end">
                                            <small class="text-muted d-block">
                                                {{ $message['date']->diffForHumans() }}
                                            </small>
                                            @if(isset($message['expires_at']) && $message['expires_at'])
                                                <small class="badge bg-{{ $message['is_near_expiry'] ? 'warning' : 'secondary' }} opacity-75">
                                                    <i class="bi bi-clock"></i>
                                                    Expire {{ $message['expires_at']->diffForHumans() }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="mb-2 small">{{ Str::limit($message['text'], 150) }}</p>
                                    @if($message['forward_from'])
                                        <small class="text-info">
                                            <i class="bi bi-arrow-right"></i>
                                            Diteruskan dari {{ $message['forward_from'] }}
                                        </small>
                                    @endif
                                    @php
                                        $telegramService = new \App\Services\TelegramService();
                                        $urls = $telegramService->extractUrls($message);
                                    @endphp
                                    @if(!empty($urls))
                                        <div class="mt-2">
                                            @foreach($urls as $url)
                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-link-45deg"></i> Link
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-chat-dots display-4 text-muted"></i>
                    <h6 class="mt-3 text-muted">Belum ada update dari media sosial</h6>
                    <p class="text-muted small mb-3">
                        Update kontes dan informasi terbaru akan muncul di sini setelah tersedia dari channel Telegram
                    </p>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="refreshTelegramMessages()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Coba Refresh Sekarang
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Contests -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kontes Berlangsung & Mendatang</h5>
            <div>
                <a href="https://codeforces.com/contests" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Lihat di Codeforces
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($allContests->count() > 0)
                <div class="row">
                    @foreach($allContests as $contest)
                        <div class="col-md-6 mb-4">
                            <div class="card border-start border-{{ $contest->phase === 'CODING' ? 'danger' : ($contest->isUpcoming() ? 'primary' : 'secondary') }} border-4 h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title mb-0">
                                            <a href="https://codeforces.com/contest/{{ $contest->contest_id }}" target="_blank" class="text-decoration-none">
                                                {{ $contest->name }}
                                            </a>
                                        </h6>
                                        <span class="badge bg-{{ $contest->phase === 'CODING' ? 'danger' : ($contest->phase === 'BEFORE' ? 'primary' : 'secondary') }}">
                                            {{ $contest->phase === 'CODING' ? 'BERLANGSUNG' : $contest->phase }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row text-center small">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <div class="fw-bold text-primary">
                                                        <i class="bi bi-calendar3"></i>
                                                    </div>
                                                    <div>{{ $contest->start_time->format('d M') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <div class="fw-bold text-success">
                                                        <i class="bi bi-clock"></i>
                                                    </div>
                                                    <div>{{ $contest->start_time->format('H:i') }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold text-info">
                                                    <i class="bi bi-hourglass"></i>
                                                </div>
                                                <div>{{ $contest->getDurationFormatted() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        @if($contest->phase === 'CODING')
                                            @php
                                                $endTime = $contest->start_time->addSeconds($contest->duration_seconds);
                                                $timeRemaining = $endTime->diffForHumans();
                                            @endphp
                                            <div class="alert alert-danger py-2 mb-2">
                                                <small>
                                                    <i class="bi bi-play-circle-fill me-1"></i>
                                                    <strong>SEDANG BERLANGSUNG!</strong><br>
                                                    Berakhir {{ $timeRemaining }}
                                                </small>
                                            </div>
                                        @elseif($contest->start_time > now())
                                            @php
                                                $timeUntilStart = $contest->start_time->diffForHumans();
                                                $isToday = $contest->start_time->isToday();
                                                $isTomorrow = $contest->start_time->isTomorrow();
                                            @endphp
                                            <div class="alert alert-{{ $isToday ? 'warning' : ($isTomorrow ? 'info' : 'light') }} py-2 mb-2">
                                                <small>
                                                    <i class="bi bi-clock-history me-1"></i>
                                                    Dimulai {{ $timeUntilStart }}
                                                    @if($isToday)
                                                        <strong>(Hari ini!)</strong>
                                                    @elseif($isTomorrow)
                                                        <strong>(Besok)</strong>
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                    </div>

                                    @if($contest->description)
                                        <p class="card-text small text-muted">{{ Str::limit($contest->description, 100) }}</p>
                                    @endif

                                    <div class="d-flex justify-content-end align-items-center">
                                        @if($contest->difficulty)
                                            <small class="text-muted">
                                                Difficulty: {{ $contest->difficulty }}/5
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Additional Info -->
                                    @if($contest->kind || $contest->icpc_region)
                                        <div class="mt-2">
                                            @if($contest->kind)
                                                <span class="badge bg-light text-dark me-1">{{ $contest->kind }}</span>
                                            @endif
                                            @if($contest->icpc_region)
                                                <span class="badge bg-light text-dark">{{ $contest->icpc_region }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-4 text-muted"></i>
                    <h5 class="mt-3">Tidak ada kontes berlangsung atau mendatang</h5>
                    <p class="text-muted">Kontes baru akan muncul setelah data di-refresh atau dijadwalkan oleh Codeforces</p>
                    <div class="mt-3">
                        <a href="https://codeforces.com/contests" target="_blank" class="btn btn-primary me-2">
                            <i class="bi bi-box-arrow-up-right me-2"></i>Cek di Codeforces
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
async function refreshTelegramMessages() {
    const button = event.target.closest('button');
    const originalHtml = button.innerHTML;
    
    try {
        button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Refresh';
        button.disabled = true;
        
        const response = await fetch('{{ route("pendamping.telegram.refresh") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('success', `${result.message} (${result.count} pesan)`);
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', result.message || 'Gagal memperbarui pesan');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memperbarui pesan');
    } finally {
        button.innerHTML = originalHtml;
        button.disabled = false;
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
@endsection
