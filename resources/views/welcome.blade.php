<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Library Yayasan Pendidikan Daarul Hikmah Al Madani</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            overflow-x: hidden;
        }

        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .floating-books {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-books::before,
        .floating-books::after {
            content: '📚';
            position: absolute;
            font-size: 3rem;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }

        .floating-books::before {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-books::after {
            bottom: 20%;
            right: 10%;
            animation-delay: 10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(10deg);
            }
        }

        .container {
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .hero {
            text-align: center;
            max-width: 800px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #3b82f6 100%);
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
            }

            50% {
                box-shadow: 0 25px 50px rgba(99, 102, 241, 0.5);
            }
        }

        .title {
            font-size: 1rem;
            font-weight: 600;
            color: #a5b4fc;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-bottom: 0.5rem;
        }

        .main-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #c7d2fe 50%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .subtitle {
            font-size: 1.125rem;
            color: #94a3b8;
            margin-bottom: 3rem;
        }

        .schools {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .school-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .school-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .school-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .school-icon.mi {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .school-icon.smp {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .school-name {
            font-weight: 600;
            color: #fff;
            font-size: 1rem;
        }

        .school-type {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .cta-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #fff;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 4rem;
            max-width: 900px;
        }

        .feature {
            text-align: center;
            padding: 1.5rem;
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }

        .feature-title {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }

        .feature-desc {
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .footer {
            width: 100%;
            max-width: 900px;
            margin: 3rem auto 0;
            padding: 1.5rem 1rem;
            text-align: center;
            color: #64748b;
            font-size: 0.75rem;
            border-top: 1px solid rgba(100, 116, 139, 0.2);
        }

        @media (max-width: 640px) {
            .schools {
                flex-direction: column;
            }

            .school-card {
                width: 100%;
                justify-content: center;
            }

            .cta-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>
    <div class="floating-books"></div>

    <div class="container">
        <div class="hero">
            <div class="logo-icon">📖</div>

            <p class="title">E-Library</p>
            <h1 class="main-title">Yayasan Pendidikan<br>Daarul Hikmah Al Madani</h1>
            <p class="subtitle">Akses ribuan buku digital kapan saja, dimana saja</p>

            <div class="schools">
                <div class="school-card">
                    <div class="school-icon mi">🏫</div>
                    <div>
                        <p class="school-name">MI Daarul Hikmah</p>
                        <p class="school-type">Madrasah Ibtidaiyah</p>
                    </div>
                </div>
                <div class="school-card">
                    <div class="school-icon smp">🎓</div>
                    <div>
                        <p class="school-name">SMP Garuda</p>
                        <p class="school-type">Sekolah Menengah Pertama</p>
                    </div>
                </div>
            </div>

            <div class="cta-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                            📚 Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            🔑 Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary">
                                ✨ Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">📚</div>
                    <p class="feature-title">Koleksi Lengkap</p>
                    <p class="feature-desc">Ribuan buku digital tersedia</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">📱</div>
                    <p class="feature-title">Akses Mobile</p>
                    <p class="feature-desc">Baca di mana saja</p>
                </div>
                <div class="feature">
                    <div class="feature-icon">🔒</div>
                    <p class="feature-title">Aman & Mudah</p>
                    <p class="feature-desc">Sistem peminjaman online</p>
                </div>
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} E-Library Yayasan Pendidikan Daarul Hikmah Al Madani
        </div>
    </div>
</body>

</html>
