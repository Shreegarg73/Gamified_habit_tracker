
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Habit Tracker - Login</title>

    {{-- Preconnect for fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Embedded Vanilla CSS - Aesthetic Dark Text Theme for Auth --}}
    <style>
        /* === Basic Reset & Base Styles === */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; color: #2d3748; line-height: 1.7; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; background: linear-gradient(160deg, #29323c, #485563, #2b5876, #4e4376, #360033); background-size: 400% 400%; animation: gradientShift 40s ease infinite; overflow-x: hidden; }
        a { color: #6366f1; text-decoration: none; transition: color 0.3s ease; }
        a:hover { color: #4338ca; }
        img, svg { display: block; max-width: 100%; }
        button { font-family: inherit; cursor: pointer; border: none; border-radius: 8px; padding: 10px 20px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; }

        /* === Auth Page Container === */
        .auth-container { width: 100%; max-width: 430px; /* Slightly narrower */ }

        /* === Auth Form Styling === */
        .auth-form-wrapper { background: rgba(255, 255, 255, 0.9); /* More opaque */ backdrop-filter: blur(4px) saturate(100%); -webkit-backdrop-filter: blur(4px) saturate(100%); border-radius: 15px; padding: 2.5rem 3rem; border: 1px solid rgba(0, 0, 0, 0.08); box-shadow: 0 10px 35px 0 rgba(0, 0, 0, 0.12); animation: popIn 0.6s cubic-bezier(0.25, 0.8, 0.25, 1) forwards; opacity: 0; }
        .auth-logo { display: flex; justify-content: center; margin-bottom: 1.5rem; }
        .auth-logo a { color: #1a202c; font-size: 1.8rem; font-weight: 700; text-decoration: none; }
        .auth-header { color: #1a202c; margin-bottom: 2rem; font-size: 1.6rem; font-weight: 600; text-align: center; }

        /* === Form Element Styling === */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4a5568; font-size: 0.9rem; }
        .form-control { display: block; width: 100%; padding: 12px 18px; font-size: 1rem; font-family: inherit; color: #1a202c; background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent */ border: 1px solid rgba(0, 0, 0, 0.15); border-radius: 8px; transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease; box-shadow: inset 0 1px 3px rgba(0,0,0, 0.08); }
        .form-control::placeholder { color: #94a3b8; opacity: 1; }
        .form-control:focus { outline: none; border-color: #6366f1; box-shadow: inset 0 1px 3px rgba(0,0,0, 0.08), 0 0 0 3px rgba(99, 102, 241, 0.2); background-color: #fff; }

        /* === Validation Error & Status Message Styling === */
        .input-error-message { color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block; }
        .session-status { padding: 12px 20px; margin-bottom: 1.5rem; border-radius: 10px; color: #065f46; background-color: #d1fae5; border: 1px solid #a7f3d0; font-size: 0.95rem; animation: slideDownFadeIn 0.5s ease forwards; opacity: 0; text-align: center; }

        /* Remember Me Checkbox */
        .remember-me { display: flex; align-items: center; margin-bottom: 1.5rem; }
        .remember-me input[type="checkbox"] { width: 16px; height: 16px; margin-right: 0.6rem; appearance: none; -webkit-appearance: none; background-color: #fff; border: 1px solid #cbd5e1; border-radius: 4px; cursor: pointer; position: relative; transition: background-color 0.3s ease, border-color 0.3s ease; flex-shrink: 0;}
        .remember-me input[type="checkbox"]:checked { background-color: #6366f1; border-color: #4f46e5; }
        .remember-me input[type="checkbox"]:checked::after { content: ''; position: absolute; top: 2px; left: 5px; width: 4px; height: 8px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .remember-me label { font-size: 0.95rem; color: #4a5568; cursor: pointer; }

        /* === Form Actions & Links === */
        .form-actions { margin-top: 2rem; text-align: center; }
        .btn-submit { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #ffffff; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); padding: 12px 30px; font-weight: 500; font-size: 1rem; width: 100%; border-radius: 8px; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(79, 70, 229, 0.4); }
        .btn-submit:active { transform: translateY(0); box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }
        .auth-links { margin-top: 2rem; text-align: center; font-size: 0.9rem; }
        .auth-links a { color: #4f46e5; margin: 0 0.5rem; transition: color 0.3s ease; }
        .auth-links a:hover { color: #4338ca; text-decoration: underline; }
        .auth-links .separator { color: #94a3b8; }

        /* === Animations === */
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDownFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0px); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }

        /* === Responsive Design === */
        @media (max-width: 768px) {
             body { padding: 15px; align-items: flex-start; padding-top: 3rem;}
            .auth-form-wrapper { padding: 2.5rem 1.8rem; border-radius: 12px; }
            .auth-header { font-size: 1.5rem; }
            .form-control { padding: 12px 15px; }
            .btn-submit { padding: 12px 25px; font-size: 0.95rem; }
            .auth-links { font-size: 0.85rem; }
        }
         @media (max-width: 480px) {
             .auth-form-wrapper { padding: 2rem 1.2rem; }
              .auth-header { font-size: 1.4rem; }
              .form-group { margin-bottom: 1.3rem; }
         }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-form-wrapper">

            <div class="auth-logo"> <a href="/" title="Home">{{ config('app.name', 'Habits') }}</a> </div>
            <h1 class="auth-header">Welcome Back</h1>

            @if (session('status')) <div class="session-status" role="alert"> {{ session('status') }} </div> @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="your@email.com">
                    @error('email') <span class="input-error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter password">
                     @error('password') <span class="input-error-message">{{ $message }}</span> @enderror
                </div>

                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>

                <div class="form-actions"> <button type="submit" class="btn-submit">Log In</button> </div>

                <div class="auth-links">
                     @if (Route::has('password.request')) <a href="{{ route('password.request') }}"> Forgot password? </a> <span class="separator">|</span> @endif
                     @if (Route::has('register')) <a href="{{ route('register') }}"> Create account </a> @endif
                </div>
            </form>
        </div>
    </div>
</body>
</html>
