@extends('layouts.admin')

@section('title', 'Dashboard Kepala Sekolah')
@section('page-title', 'Dashboard Kepala Sekolah')

@push('styles')
<style>
    /* Styling dari admin */
    :root {
        --primary:       #1B3A52;
        --primary-light: #2a5378;
        --primary-dark:  #112534;
        --accent:        #EAE0CF;
        --accent-soft:   rgba(232,200,122,0.12);
        --success:       #16a34a;
        --warning:       #d97706;
        --danger:        #e53e3e;
        --info:          #0891b2;
        --radius:        14px;
        --transition:    all 0.24s cubic-bezier(0.4,0,0.2,1);
        --shadow-card:   0 2px 0 rgba(255,255,255,0.8) inset, 0 6px 24px rgba(27,58,82,0.08);
    }

    .stat-card {
        border-radius: var(--radius);
        padding: 1.4rem 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        text-decoration: none;
        display: block;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        margin-bottom: 1rem;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(0,0,0,0.22); color: white; }
    .stat-card-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); }
    .stat-card-success { background: linear-gradient(135deg, #15803d 0%, #22c55e 100%); }
    .stat-card-info    { background: linear-gradient(135deg, #0e7490 0%, #22d3ee 100%); }
    
    .stat-icon { font-size: 2rem; opacity: 0.2; float: right; margin-top: -4px; transition: var(--transition); }
    .stat-number { font-weight: 800; font-size: 2rem; margin: 0.5rem 0 0.2rem; line-height: 1; display: block; }
    .stat-label { font-size: 0.82rem; opacity: 0.88; font-weight: 600; margin: 0; }

    .card-section { background: white; border-radius: var(--radius); border: 1px solid rgba(226,232,240,0.8); box-shadow: var(--shadow-card); overflow: hidden; height: 100%; }
    .card-section-header { background: var(--primary); padding: 0.95rem 1.5rem; display: flex; align-items: center; justify-content: space-between; position: relative; }
    .card-section-header::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: var(--accent); }
    .card-section-title { display: flex; align-items: center; gap: 8px; color: white; font-weight: 700; font-size: 0.83rem; letter-spacing: 0.3px; }
    .card-section-body { padding: 1.5rem; }

    .alumni-item { display: flex; align-items: center; gap: 11px; padding: 0.85rem 0; border-bottom: 1px solid #f8fafc; }
    .alumni-item:last-child { border-bottom: none; }
    .alumni-avatar-sm { width: 40px; height: 40px; border-radius: 10px; background: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
    .alumni-avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
    .alumni-item-name { font-weight: 700; font-size: 0.875rem; color: var(--primary); }
    .alumni-item-meta { font-size: 0.75rem; color: #94a3b8; }
</style>
@endpush

@section('content')

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-card-primary">
            <i class="bi bi-people-fill stat-icon"></i>
            <div class="stat-card-inner">
                <span class="stat-label">Total Alumni</span>
                <span class="stat-number">{{ number_format($stats['total_alumni']) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-card-success">
            <i class="bi bi-patch-check-fill stat-icon"></i>
            <div class="stat-card-inner">
                <span class="stat-label">Alumni Terverifikasi</span>
                <span class="stat-number">{{ number_format($stats['total_terverifikasi']) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-card-info">
            <i class="bi bi-calendar3 stat-icon"></i>
            <div class="stat-card-inner">
                <span class="stat-label">Total Angkatan</span>
                <span class="stat-number">{{ number_format($stats['total_angkatan']) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ── QUICK LINKS ── --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card-section">
            <div class="card-section-header">
                <div class="card-section-title"><i class="bi bi-link-45deg"></i> Menu Navigasi Cepat</div>
            </div>
            <div class="card-section-body d-flex gap-3 flex-wrap">
                <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline-primary fw-bold">
                    <i class="bi bi-people-fill me-1"></i> Data Alumni
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-success fw-bold">
                    <i class="bi bi-file-earmark-bar-graph-fill me-1"></i> Laporan & Tracer Study
                </a>
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-secondary fw-bold">
                    <i class="bi bi-clock-history me-1"></i> Audit Log
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── CHARTS ── --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card-section">
            <div class="card-section-header">
                <div class="card-section-title"><i class="bi bi-pie-chart-fill"></i> Distribusi Pendidikan Saat Ini (Tracer Study)</div>
            </div>
            <div class="card-section-body text-center">
                @if(empty($stats['tracer_study']))
                    <p class="text-muted py-4 mb-0">Belum ada data tracer study yang tersedia.</p>
                @else
                    <div style="height: 300px; position: relative; margin: auto; width: 100%; max-width: 500px;">
                        <canvas id="tracerStudyChart"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── TABLES ── --}}
<div class="row g-3">
    {{-- Statistik Per Angkatan --}}
    <div class="col-lg-7">
        <div class="card-section">
            <div class="card-section-header">
                <div class="card-section-title">
                    <i class="bi bi-bar-chart-fill"></i> Statistik Per Angkatan
                </div>
            </div>
            <div class="card-section-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4 text-secondary">Angkatan</th>
                                <th class="py-3 px-4 text-secondary">Tahun Ajaran</th>
                                <th class="py-3 px-4 text-secondary text-center">Jumlah Alumni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($angkatanStats as $angkatan)
                                <tr>
                                    <td class="py-3 px-4 fw-bold text-primary">{{ $angkatan->nama_angkatan }}</td>
                                    <td class="py-3 px-4 text-muted">{{ $angkatan->tahun_ajaran }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <strong>{{ number_format($angkatan->alumni_count) }}</strong> <small class="text-muted">Orang</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada data angkatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pendaftar Terbaru --}}
    <div class="col-lg-5">
        <div class="card-section">
            <div class="card-section-header">
                <div class="card-section-title">
                    <i class="bi bi-person-plus-fill"></i> 10 Alumni Terbaru
                </div>
            </div>
            <div class="card-section-body">
                @forelse($recentAlumni as $alumni)
                    @php $foto = $alumni->fotos->where('is_main', true)->first(); @endphp
                    <div class="alumni-item">
                        <div class="alumni-avatar-sm">
                            @if($foto)
                                <img src="{{ asset('storage/' . $foto->path_file) }}" alt="{{ $alumni->nama_lengkap }}">
                            @else
                                <i class="bi bi-person-fill text-white opacity-50"></i>
                            @endif
                        </div>
                        <div>
                            <div class="alumni-item-name">{{ $alumni->nama_lengkap }}</div>
                            <div class="alumni-item-meta">{{ $alumni->angkatan->nama_angkatan ?? 'Tanpa Angkatan' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">Belum ada pendaftar baru.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tracerData = @json($stats['tracer_study'] ?? []);
    if(Object.keys(tracerData).length > 0) {
        const ctx = document.getElementById('tracerStudyChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(tracerData),
                datasets: [{
                    data: Object.values(tracerData),
                    backgroundColor: [
                        '#1B3A52', '#16a34a', '#0891b2', '#e53e3e', '#d97706', '#8b5cf6', '#ec4899', '#14b8a6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif" }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
