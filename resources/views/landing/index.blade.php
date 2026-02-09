@extends('layouts.landing')

@section('title', 'Beranda')

@push('styles')
<style>
    /* Hero Section */
    .hero {
        padding: 120px 0 160px 0;
        background: linear-gradient(135deg, #213448 0%, #2d4a65 100%);
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
    }

    .hero h1 {
        font-size: 3.5rem;
        font-weight: 800;
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.95);
        max-width: 650px;
        margin: 0 auto 2.5rem auto;
        line-height: 1.8;
    }

    /* Stats Section */
    .section-stats {
        background-color: #f8f9fa;
        padding-bottom: 80px;
        position: relative;
    }

    .stats-container {
        margin-top: -100px;
        position: relative;
        z-index: 100;
    }

    .card-stat {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 3rem 2rem;
    }

    .card-stat:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 70px rgba(0, 0, 0, 0.2);
    }

    .stat-icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f0f4f8 0%, #e1e8ed 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        color: #213448;
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 700;
        color: #213448;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 0.85rem;
    }

    /* CTA Section */
    .cta-section {
        padding: 5rem 0;
        background-color: #ffffff;
    }

    .cta-card {
        background: linear-gradient(135deg, #213448 0%, #2d4a65 100%);
        border: none;
        border-radius: 40px;
        padding: 4rem 3rem;
        box-shadow: 0 20px 60px rgba(33, 52, 72, 0.2);
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 1.5rem;
        font-family: 'Poppins', sans-serif;
    }

    .cta-description {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto 2.5rem auto;
        line-height: 1.8;
    }

    /* Buttons & Badges */
    .btn-dashboard, .btn-register {
        background-color: #EAE0CF;
        color: #213448;
        font-weight: 700;
        padding: 15px 45px;
        border-radius: 50px;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-block;
    }

    .btn-dashboard:hover, .btn-register:hover {
        background-color: #d8cdb8;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        color: #213448;
    }

    .welcome-badge {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 1rem 2rem;
        border-radius: 50px;
        display: inline-block;
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Responsive */
    @media (max-width: 767px) {
        .hero { padding: 80px 0 120px 0; }
        .hero h1 { font-size: 2.2rem; }
        .stats-container { margin-top: -80px; }
        .cta-title { font-size: 1.8rem; }
    }
</style>
@endpush

@section('content')
<section class="hero text-center text-white">
    <div class="container">
        <h1>Selamat Datang di Sistem Alumni</h1>
        <p class="hero-subtitle">
            Wadah resmi komunikasi dan informasi alumni SD Muhammadiyah Birrul Walidain Kudus.
        </p>

        @auth
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('alumni.dashboard') }}"
               class="btn btn-dashboard shadow">
                <i class="bi bi-speedometer2 me-2"></i> Ke Dashboard Saya
            </a>
        @endauth
    </div>
</section>

<section class="section-stats">
    <div class="container stats-container">
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card-stat text-center">
                    <div class="stat-icon-circle">
                        <i class="bi bi-people-fill" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="stat-number">{{ $stats['total_alumni'] ?? '0' }}</h2>
                    <p class="stat-label mb-0">Alumni Terverifikasi</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <div class="card-stat text-center">
                    <div class="stat-icon-circle">
                        <i class="bi bi-mortarboard-fill" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="stat-number">{{ $stats['total_angkatan'] ?? '0' }}</h2>
                    <p class="stat-label mb-0">Total Angkatan</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-card text-center">
            <h2 class="cta-title">Mari Jalin Silaturahmi!</h2>
            <p class="cta-description">
                Daftarkan diri Anda untuk bergabung ke dalam jaringan alumni dan dapatkan informasi terbaru seputar sekolah.
            </p>

            @guest
                <a href="{{ route('register') }}" class="btn btn-register shadow-lg">
                    Daftar Sebagai Alumni Sekarang
                </a>
            @else
                <div class="welcome-badge">
                    <span>
                        <i class="bi bi-person-circle me-2"></i>
                        Selamat datang kembali, <strong>{{ Auth::user()->username }}</strong>
                    </span>
                </div>
            @endguest
        </div>
    </div>
</section>
@endsection
