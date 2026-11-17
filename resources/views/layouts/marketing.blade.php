<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AI Dental Receptionist / Admin Agent') — ImranDevBD</title>
    <meta name="description" content="@yield('meta_description', 'AI dental receptionist and admin agent for appointment booking, patient intake, insurance checks, and front-desk automation that saves clinics hours daily.')">
    <meta name="author" content="Imran Ahmed">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: {{ $accent ?? '#0284C7' }};
            --ink: #0c4a6e;
            --muted: #475569;
            --bg: #f0f9ff;
            --card: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "DM Sans", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 500px at 10% -10%, rgba(2, 132, 199, 0.18), transparent 60%),
                radial-gradient(900px 400px at 100% 0%, rgba(14, 165, 233, 0.12), transparent 55%),
                linear-gradient(180deg, #f8fbff 0%, #eef6fb 100%);
            min-height: 100vh;
        }
        a { color: var(--accent); text-decoration: none; }
        .wrap { width: min(1120px, calc(100% - 2rem)); margin: 0 auto; }
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 0; gap: 1rem; flex-wrap: wrap;
        }
        .brand {
            font-family: Fraunces, Georgia, serif;
            font-size: 1.35rem; font-weight: 700; color: var(--ink);
        }
        nav { display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; }
        nav a { color: var(--muted); font-weight: 500; }
        nav a:hover { color: var(--accent); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--accent); color: #fff !important; border-radius: 999px;
            padding: 0.7rem 1.2rem; font-weight: 700; border: 0;
        }
        .btn-outline {
            background: transparent; color: var(--accent) !important;
            border: 1.5px solid var(--accent);
        }
        .hero {
            padding: 3.5rem 0 2.5rem;
            display: grid; gap: 1.25rem;
        }
        .hero h1 {
            font-family: Fraunces, Georgia, serif;
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            line-height: 1.1; margin: 0; max-width: 16ch;
        }
        .hero p { max-width: 58ch; color: var(--muted); font-size: 1.1rem; line-height: 1.6; margin: 0; }
        .cta-row { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 0.5rem; }
        .panel {
            background: var(--card);
            border: 1px solid rgba(2, 132, 199, 0.12);
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 18px 40px rgba(12, 74, 110, 0.06);
        }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .section { padding: 2rem 0 3rem; }
        .section h2 {
            font-family: Fraunces, Georgia, serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            margin: 0 0 0.75rem;
        }
        .muted { color: var(--muted); line-height: 1.6; }
        .pill {
            display: inline-block; padding: 0.2rem 0.7rem; border-radius: 999px;
            background: rgba(2, 132, 199, 0.12); color: var(--accent); font-size: 0.85rem; font-weight: 700;
        }
        footer {
            border-top: 1px solid rgba(2, 132, 199, 0.12);
            padding: 2rem 0 3rem; color: var(--muted); font-size: 0.95rem;
        }
        .dash-shot {
            width: 100%; border-radius: 1rem; border: 1px solid rgba(2, 132, 199, 0.15);
            box-shadow: 0 24px 50px rgba(12, 74, 110, 0.12);
        }
        @media (max-width: 720px) {
            .hero { padding-top: 2rem; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <a class="brand" href="{{ route('home') }}">Dental AI Desk</a>
            <nav>
                <a href="{{ route('features') }}">Features</a>
                <a href="{{ route('modules') }}">Modules</a>
                <a href="{{ route('pricing') }}">Pricing</a>
                <a href="{{ route('demo') }}">Demo</a>
                <a class="btn" href="/admin">Admin Login</a>
            </nav>
        </header>

        @yield('content')

        <footer>
            <p><strong>AI Dental Receptionist / Admin Agent</strong> by <a href="https://imrandev.bd">Imran Ahmed</a> — built for modern dental clinics.</p>
            <p>Contact: <a href="mailto:me@imrandev.bd">me@imrandev.bd</a> · <a href="https://imrandev.bd/contact">imrandev.bd/contact</a></p>
        </footer>
    </div>
</body>
</html>
