<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            background: #fafafa;
            color: #1a1a1a;
        }

        /* Navigation */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
        }

        .nav.scrolled {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
        }

        .nav-title {
            font-weight: 600;
            font-size: 1rem;
            color: #1a1a1a;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            color: #666;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: #1a1a1a;
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-link.primary {
            background: #1a1a1a;
            color: #fff;
        }

        .nav-link.primary:hover {
            background: #333;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f7 50%, #fafafa 100%);
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 30% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 70% 60%, rgba(59, 130, 246, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 90%, rgba(139, 92, 246, 0.06) 0%, transparent 40%);
            pointer-events: none;
        }

        .hero-content {
            text-align: center;
            max-width: 900px;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6366f1;
            margin-bottom: 1.5rem;
            animation: fade-up 0.6s ease-out;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            background: linear-gradient(135deg, #1a1a1a 0%, #444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            animation: fade-up 0.6s ease-out 0.1s both;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #666;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto 2.5rem;
            animation: fade-up 0.6s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            animation: fade-up 0.6s ease-out 0.3s both;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: #1a1a1a;
            color: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background: #333;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: #fff;
            color: #1a1a1a;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
        }

        /* Schools Section */
        .schools {
            padding: 6rem 2rem;
            background: #fff;
        }

        .schools-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .section-label {
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6366f1;
            margin-bottom: 0.75rem;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 3rem;
        }

        .schools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .school-card {
            background: #fafafa;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s ease;
        }

        .school-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            border-color: transparent;
        }

        .school-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .school-icon.mi {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .school-icon.smp {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .school-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.25rem;
        }

        .school-info p {
            font-size: 0.875rem;
            color: #666;
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: linear-gradient(180deg, #fafafa 0%, #fff 100%);
        }

        .features-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.25rem;
            background: linear-gradient(135deg, #f0f0f5 0%, #e8e8ed 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .feature-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats {
            padding: 4rem 2rem;
            background: #1a1a1a;
            color: #fff;
        }

        .stats-container {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
        }

        .stat-item h4 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-item p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* CTA Section */
        .cta {
            padding: 6rem 2rem;
            background: #fff;
            text-align: center;
        }

        .cta-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
        }

        /* Footer */
        .footer {
            padding: 2rem;
            text-align: center;
            font-size: 0.875rem;
            color: #999;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .footer a {
            color: #666;
            text-decoration: none;
        }

        .footer a:hover {
            color: #1a1a1a;
        }

        /* Animations */
        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                padding: 1rem;
            }

            .nav-title {
                display: none;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 2rem;
            }

            .schools-grid {
                grid-template-columns: 1fr;
            }

            .stat-item h4 {
                font-size: 2.25rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="nav" id="nav">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="nav-logo">
                <span class="nav-title">{{ config('app.name') }}</span>
            </div>
            <div class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-link primary">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <div class="hero-badge">
                📚 Perpustakaan Digital Terintegrasi
            </div>
            <h1 class="hero-title">
                Baca Kapan Saja,<br>Di Mana Saja
            </h1>
            <p class="hero-subtitle">
                Akses ribuan koleksi buku digital dari Yayasan Pendidikan Daarul Hikmah Al Madani.
                Sistem peminjaman modern dengan pengalaman membaca yang nyaman.
            </p>
            <div class="hero-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z" />
                            </svg>
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z" />
                                <path fill-rule="evenodd"
                                    d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                            </svg>
                            Masuk Sekarang
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    <path fill-rule="evenodd"
                                        d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />
                                </svg>
                                Daftar Akun Baru
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </section>

    <!-- Schools Section -->
    <section class="schools">
        <div class="schools-container">
            <p class="section-label">Institusi</p>
            <h2 class="section-title">Sekolah dalam Naungan Yayasan</h2>
            <div class="schools-grid">
                <div class="school-card">
                    <div class="school-icon mi">🏫</div>
                    <div class="school-info">
                        <h3>MI Daarul Hikmah</h3>
                        <p>Madrasah Ibtidaiyah</p>
                    </div>
                </div>
                <div class="school-card">
                    <div class="school-icon smp">🎓</div>
                    <div class="school-info">
                        <h3>SMP Garuda</h3>
                        <p>Sekolah Menengah Pertama</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <p class="section-label">Fitur</p>
            <h2 class="section-title">Mengapa Memilih E-Library Kami?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Koleksi Lengkap</h3>
                    <p>Ribuan buku digital dari berbagai kategori tersedia untuk dibaca kapan saja</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Akses Mobile</h3>
                    <p>Baca buku favorit Anda langsung dari smartphone atau tablet dengan mudah</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Aman & Mudah</h3>
                    <p>Sistem peminjaman online yang aman dengan antarmuka yang user-friendly</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Cepat & Responsive</h3>
                    <p>Pengalaman membaca yang mulus dengan loading cepat di semua perangkat</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Pencarian Pintar</h3>
                    <p>Temukan buku yang Anda cari dengan fitur pencarian canggih</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Tracking Peminjaman</h3>
                    <p>Pantau riwayat dan status peminjaman buku Anda dengan mudah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="stat-item">
                <h4>1000+</h4>
                <p>Koleksi Buku</p>
            </div>
            <div class="stat-item">
                <h4>500+</h4>
                <p>Anggota Aktif</p>
            </div>
            <div class="stat-item">
                <h4>2</h4>
                <p>Sekolah Terintegrasi</p>
            </div>
            <div class="stat-item">
                <h4>24/7</h4>
                <p>Akses Online</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2 class="cta-title">Siap Mulai Membaca?</h2>
            <p class="cta-subtitle">Daftar sekarang dan jelajahi ribuan buku digital gratis dari perpustakaan kami</p>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Masuk ke Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar Gratis Sekarang</a>
                @endauth
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>

    <script>
        // Navbar scroll effect
        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>
