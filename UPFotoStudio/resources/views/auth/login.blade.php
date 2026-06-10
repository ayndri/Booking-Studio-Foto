<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login Dashboard' }} - UPFotoStudio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-main: #edf2f7;
            --bg-soft: #dbeafe;
            --card: #ffffff;
            --text-main: #10243d;
            --text-muted: #5b6879;
            --line: #d8e0ea;
            --focus: #0ea5e9;
            --btn: #0f6ae8;
            --btn-hover: #0a56c2;
            --danger-bg: #fff3f3;
            --danger-line: #f4b7b7;
            --danger-text: #8f1d1d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at 15% 20%, var(--bg-soft), transparent 32%),
                radial-gradient(circle at 85% 80%, #e2e8f0, transparent 35%),
                var(--bg-main);
            display: grid;
            place-items: center;
            padding: 1.5rem;
        }

        .login-wrap {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: var(--card);
            border: 1px solid rgba(15, 106, 232, 0.12);
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(13, 36, 67, 0.1);
            padding: 2rem;
        }

        .brand-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #0b4da8;
            background: #e9f2ff;
            border: 1px solid #cfe1ff;
            margin-bottom: 1rem;
        }

        .login-title {
            margin: 0;
            font-size: clamp(1.5rem, 2.5vw, 1.8rem);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            margin: 0.5rem 0 1.5rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .login-label {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .login-input {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0.72rem 0.9rem;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .login-input:focus {
            border-color: var(--focus);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.14);
        }

        .login-btn {
            margin-top: 0.3rem;
            border: 0;
            border-radius: 12px;
            padding: 0.78rem 1rem;
            font-weight: 700;
            background: linear-gradient(120deg, #0f6ae8, #0a7ede);
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .login-btn:hover {
            background: linear-gradient(120deg, var(--btn-hover), #0a6cbf);
            transform: translateY(-1px);
            box-shadow: 0 14px 25px rgba(15, 106, 232, 0.28);
        }

        .error-box {
            background: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid var(--danger-line);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.92rem;
        }

        .error-box ul {
            margin: 0;
            padding-left: 1rem;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<main class="login-wrap">
    <section class="login-card">
        <div class="brand-chip">UPFotoStudio Dashboard</div>
        <h1 class="login-title">{{ $title ?? 'Login Dashboard' }}</h1>
        <p class="login-subtitle">{{ $subtitle ?? 'Masuk dengan akun Admin atau Owner' }}</p>

        @if($errors->any())
            <div class="error-box">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ $submitRoute ?? route('login.attempt') }}" class="vstack gap-3">
            @csrf
            <div>
                <label class="login-label" for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control login-input"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>
            <div>
                <label class="login-label" for="password">Password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control login-input"
                    required
                >
            </div>
            <button class="btn btn-primary login-btn w-100" type="submit">Masuk Dashboard</button>
        </form>
    </section>
</main>
</body>
</html>
