<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} - UPFotoStudio</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background: #f4f3f0;
            color: #111;
        }

        /* ── Card ─────────────────────── */
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            border-top: 3px solid #2f5443;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,.08);
            padding: 36px 36px 32px;
        }

        /* ── Brand ────────────────────── */
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .brand-mark {
            width: 34px; height: 34px;
            background: #2f5443;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -.01em;
            color: #111;
        }
        .brand-name span { color: #2f5443; }

        /* ── Heading ──────────────────── */
        .login-h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2rem);
            font-weight: 700;
            color: #111;
            letter-spacing: -.02em;
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: .88rem;
            color: #888;
            margin-bottom: 26px;
            line-height: 1.5;
        }

        /* ── Error ────────────────────── */
        .err-box {
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .84rem;
            margin-bottom: 18px;
        }
        .err-box ul { margin: 0; padding-left: 16px; }

        /* ── Form ─────────────────────── */
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px;
        }
        .field input {
            width: 100%;
            border: 1.5px solid rgba(0,0,0,.1);
            border-radius: 10px;
            padding: 12px 14px;
            font-family: 'Poppins', sans-serif;
            font-size: .92rem;
            color: #111;
            background: #fafaf8;
            outline: none;
            transition: border-color 200ms ease, box-shadow 200ms ease;
        }
        .field input:focus {
            border-color: rgba(47,84,67,.45);
            box-shadow: 0 0 0 3px rgba(47,84,67,.1);
            background: #fff;
        }
        .field input::placeholder { color: #bbb; }

        /* ── Button ───────────────────── */
        .btn-login {
            display: block;
            width: 100%;
            background: #2f5443;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 13px;
            font-family: 'Poppins', sans-serif;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
            transition: background 180ms ease, transform 180ms ease, box-shadow 180ms ease;
        }
        .btn-login:hover {
            background: #1f3d30;
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(47,84,67,.28);
        }

        /* ── Back link ────────────────── */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: .8rem;
            color: #888;
            text-decoration: none;
            transition: color 150ms ease;
        }
        .back-link:hover { color: #2f5443; }

        @media (max-width: 480px) {
            .card { padding: 28px 22px 24px; }
        }
    </style>
</head>
<body>

<div class="card">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-mark">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                <rect x="1" y="5" width="16" height="12" rx="2.5" fill="white" fill-opacity=".9"/>
                <circle cx="9" cy="11" r="3.5" fill="#2f5443"/>
                <rect x="5.5" y="1" width="7" height="4" rx="1.2" fill="white" fill-opacity=".75"/>
                <circle cx="9" cy="11" r="1.6" fill="#eef7f2"/>
            </svg>
        </div>
        <span class="brand-name"><span>UP</span>FotoStudio</span>
    </div>

    <h1 class="login-h1">{{ $title ?? 'Masuk Dashboard' }}</h1>
    <p class="login-sub">{{ $subtitle ?? 'Masuk dengan akun Admin atau Owner' }}</p>

    {{-- Errors --}}
    @if($errors->any())
        <div class="err-box">
            <ul>
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="post" action="{{ $submitRoute ?? route('login.attempt') }}">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="admin@upfoto.test"
                   required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   required>
        </div>
        <button type="submit" class="btn-login">Masuk Dashboard &rarr;</button>
    </form>

    <a href="{{ route('frontend.home') }}" class="back-link">← Kembali ke website</a>
</div>

</body>
</html>
