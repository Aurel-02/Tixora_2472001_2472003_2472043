<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tixora - Konser</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --jacarta: #3A345B;
            --queen-pink: #F3C8DD;
            --middle-purple: #D183A9;
            --old-lavender: #71557A;
            --brown-chocolate: #4B1535;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--jacarta), var(--brown-chocolate));
            color: var(--queen-pink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            /* Glassmorphism Effect */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(243, 200, 221, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar .logo {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--queen-pink);
            text-shadow: 0 0 10px rgba(243, 200, 221, 0.5);
            text-decoration: none;
            text-transform: uppercase;
        }

        .navbar .btn-login {
            background: rgba(209, 131, 169, 0.2);
            border: 1px solid var(--middle-purple);
            color: var(--queen-pink);
            padding: 8px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .navbar .btn-login:hover {
            background: var(--middle-purple);
            color: #fff;
            box-shadow: 0 0 15px var(--middle-purple);
            transform: scale(1.05);
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .navbar .btn-signup {
            color: var(--queen-pink);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            padding: 8px 20px;
            border-radius: 50px;
            border: 1px solid rgba(243, 200, 221, 0.2);
        }

        .navbar .btn-signup:hover {
            background: rgba(243, 200, 221, 0.1);
            border-color: var(--queen-pink);
            color: #fff;
        }

        .footer {
            text-align: center;
            padding: 40px 30px;
            background: rgba(58, 52, 91, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid rgba(113, 85, 122, 0.4);
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .hero {
            text-align: center;
            padding: 50px 20px 20px;
        }

        .hero h1 {
            font-size: 3rem;
            text-shadow: 0 0 15px var(--middle-purple);
            font-weight: 700;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            color: var(--queen-pink);
            font-weight: 300;
            margin-top: 5px;
        }

        .carousel-container {
            flex: 1; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            padding: 20px 0;
            cursor: grab;
        }

        .carousel-container:active {
            cursor: grabbing;
        }

        .carousel-track {
            display: flex;
            gap: 30px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 20px 40px;
            width: 100%;
            -ms-overflow-style: none; 
            scrollbar-width: none; 
            scroll-behavior: smooth;
        }
        
        .carousel-track::-webkit-scrollbar {
            display: none;
        }

        .carousel-card {
            flex: 0 0 calc(33.333% - 20px);
            min-width: 280px;
            height: 400px;
            scroll-snap-align: center;
            background: rgba(113, 85, 122, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(243, 200, 221, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
            position: relative;
            overflow: hidden;
            text-align: center;
            padding: 0; /* Changed to 0 for full bleed posters, internal elements will have padding if needed */
        }

        .carousel-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .carousel-card:hover img {
            transform: scale(1.1);
        }

        .poster-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            text-align: left;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .carousel-card:hover .poster-overlay {
            opacity: 1;
        }

        .carousel-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(209, 131, 169, 0.4);
            border-color: rgba(243, 200, 221, 0.6);
        }

        .carousel-card .placeholder-text {
            color: rgba(243, 200, 221, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
            padding: 30px 20px;
            border: 1px dashed rgba(243, 200, 221, 0.4);
            border-radius: 10px;
            width: 100%;
        }

        .carousel-card .placeholder-text small {
            display: block;
            margin-top: 10px;
            font-size: 0.85rem;
            font-weight: 300;
            opacity: 0.7;
        }

        .footer {
            text-align: center;
            padding: 30px;
            background: rgba(58, 52, 91, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid rgba(113, 85, 122, 0.4);
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer .footer-title {
            color: var(--queen-pink);
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-shadow: 0 0 5px var(--middle-purple);
        }

        .footer .footer-links {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer .footer-links a, .footer .footer-links span {
            color: rgba(243, 200, 221, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 400;
            transition: color 0.3s ease;
        }

        .footer .footer-links a:hover {
            color: var(--queen-pink);
            text-shadow: 0 0 5px var(--queen-pink);
        }

        .footer .divider {
            color: rgba(243, 200, 221, 0.3);
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            .hero h1 {
                font-size: 2.5rem;
            }
            .carousel-card {
                flex: 0 0 calc(50% - 15px);
                height: 350px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 12px 15px;
            }
            .navbar .logo {
                font-size: 22px;
            }
            .navbar .btn-login {
                padding: 6px 18px;
                font-size: 0.9rem;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .carousel-card {
                flex: 0 0 85%;
            }
            .footer .footer-links {
                flex-direction: column;
                gap: 8px;
            }
            .footer .divider {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- navbar -->
    <nav class="navbar">
        <a href="/" class="logo">Tixora</a>
        <div class="nav-links">
            <a href="/signup" class="btn-signup">Sign Up</a>
            <a href="/login" class="btn-login">Login</a>
        </div>
    </nav>

    <!-- Header Text -->
    <header class="hero">
        <h1>TIXORA</h1>
        <p>Your Concert Experience</p>
    </header>

    <!-- carousel -->
    <main class="carousel-container">
        <div class="carousel-track" id="carousel">
            @forelse($events as $event)
                <a href="{{ route('event.detail', $event->id_event) }}" class="carousel-card">
                    @if($event->poster)
                        <img src="{{ asset($event->poster) }}" alt="{{ $event->nama_event }}">
                        <div class="poster-overlay">
                            <h3 style="font-size: 1.2rem; margin-bottom: 5px;">{{ $event->nama_event }}</h3>
                            <p style="font-size: 0.9rem; opacity: 0.8;">{{ \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->format('d M Y') }}</p>
                        </div>
                    @else
                        <div class="placeholder-text" style="padding: 20px;">
                            {{ $event->nama_event }}
                            <small>Poster belum tersedia</small>
                        </div>
                    @endif
                </a>
            @empty
                <div class="carousel-card">
                    <div class="placeholder-text" style="padding: 20px;">
                        Belum ada event yang tersedia
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="footer">
        
        <div class="footer-title" style="margin-top: 20px;">TIXORA</div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <span class="divider">|</span>
            <a href="#">Terms of Service</a>
            <span class="divider">|</span>
            <a href="mailto:info@tixora.com">info@tixora.com</a>
        </div>
        <div class="footer-links" style="margin-top: 5px;">
            <span style="font-size: 0.8rem; opacity: 0.6;">&copy; 2026 Tixora. All rights reserved.</span>
        </div>
    </footer>

    <script>
        const slider = document.getElementById('carousel');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; 
            slider.scrollLeft = scrollLeft - walk;
        });
    </script>
</body>
</html>
