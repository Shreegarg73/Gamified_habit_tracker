
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Habit Tracker - Profile Settings</title>

    {{-- Preconnect for fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Embedded Vanilla CSS - Aesthetic Dark Text Theme for Profile --}}
    <style>
        /* === Basic Reset & Base Styles === */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; color: #2d3748; line-height: 1.7; min-height: 100vh; display: flex; flex-direction: column; font-size: 16px; background: linear-gradient(160deg, #29323c, #485563, #2b5876, #4e4376, #360033); background-size: 400% 400%; animation: gradientShift 40s ease infinite; overflow-x: hidden; }
        a { color: #6366f1; text-decoration: none; transition: color 0.3s ease; }
        a:hover { color: #4338ca; }
        img, svg { display: block; max-width: 100%; }
        button { font-family: inherit; cursor: pointer; border: none; border-radius: 8px; padding: 10px 20px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; }

        /* === Main Layout Container === */
        .page-container { display: flex; flex-direction: column; min-height: 100vh; }

        /* --- Navigation Styling --- */
        .main-nav { background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(10px) saturate(150%); -webkit-backdrop-filter: blur(10px) saturate(150%); border-bottom: 1px solid rgba(255, 255, 255, 0.2); padding: 0.7rem 0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 3px 20px rgba(0, 0, 0, 0.08); }
        .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-logo a { font-size: 1.4rem; font-weight: 700; color: #1a202c; text-decoration: none; }
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a { color: #4a5568; font-weight: 500; padding: 6px 12px; border-radius: 6px; transition: color 0.3s ease, background-color 0.3s ease; position: relative; }
        .nav-links a::after { content: ''; position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%) scaleX(0); width: 0; height: 2px; background: #6366f1; border-radius: 1px; transition: width 0.3s ease; transform-origin: center; }
        .nav-links a:hover { color: #1a202c; background-color: rgba(255, 255, 255, 0.3); }
        .nav-links a.active { color: #1a202c; font-weight: 600; }
        .nav-links a.active::after { width: 70%; transform: translateX(-50%) scaleX(1); }
        .user-menu { position: relative; display: inline-block; }
        .user-menu-trigger { background: none; border: none; color: #4a5568; display: flex; align-items: center; cursor: pointer; padding: 6px; border-radius: 6px; transition: all 0.3s ease; }
        .user-menu-trigger:hover { background-color: rgba(255, 255, 255, 0.3); color: #1a202c; }
        .user-menu-trigger span { margin-right: 8px; font-weight: 500; }
        .user-menu-trigger svg { width: 16px; height: 16px; fill: currentColor; }
        .user-dropdown-content { display: none; position: absolute; right: 0; top: calc(100% + 10px); background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px) saturate(150%); -webkit-backdrop-filter: blur(10px) saturate(150%); min-width: 180px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); border-radius: 10px; z-index: 1001; border: 1px solid rgba(0, 0, 0, 0.08); overflow: hidden; animation: fadeIn 0.25s ease forwards; }
        .user-menu:focus-within .user-dropdown-content, .user-menu:hover .user-dropdown-content { display: block; }
        .user-dropdown-content a, .user-dropdown-content button { color: #2d3748; padding: 12px 18px; text-decoration: none; display: block; background: none; border: none; width: 100%; text-align: left; font-size: 0.95rem; transition: background-color 0.3s ease, color 0.3s ease; }
        .user-dropdown-content a:hover, .user-dropdown-content button:hover { background-color: rgba(0, 0, 0, 0.05); color: #1a202c; }
        .user-dropdown-content form { margin: 0; }
        .user-dropdown-content form button { border-radius: 0; }
        .menu-toggle { display: none; background: none; border: none; color: #1a202c; }
        .menu-toggle svg { width: 28px; height: 28px; fill: #1a202c; }

        /* --- Main Content Styling (Profile Page) --- */
        .main-content { flex-grow: 1; padding: 2.5rem 20px; max-width: 850px; margin: 2rem auto 4rem auto; width: 100%; display: grid; grid-template-columns: 1fr; gap: 2rem; }
        .profile-section {
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(5px) saturate(100%); -webkit-backdrop-filter: blur(5px) saturate(100%);
            border-radius: 15px; padding: 2.5rem 3rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 35px 0 rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease-out forwards; opacity: 0;
        }
        .profile-section:nth-child(1) { animation-delay: 0.1s; }
        .profile-section:nth-child(2) { animation-delay: 0.2s; }
        .profile-section:nth-child(3) { animation-delay: 0.3s; }

        .section-header { color: #1a202c; margin-bottom: 0.8rem; font-size: 1.4rem; font-weight: 600; border-bottom: 1px solid rgba(0, 0, 0, 0.1); padding-bottom: 0.8rem; }
        .section-description { color: #4a5568; font-size: 0.95rem; margin-bottom: 1.8rem; line-height: 1.6; }

        /* --- Form Element Styling (Profile Forms) --- */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4a5568; font-size: 0.9rem; }
        .form-control { display: block; width: 100%; padding: 10px 15px; font-size: 1rem; font-family: inherit; color: #1a202c; background-color: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.15); border-radius: 8px; transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease; box-shadow: inset 0 1px 3px rgba(0,0,0, 0.08); }
        .form-control::placeholder { color: #94a3b8; opacity: 1; }
        .form-control:focus { outline: none; border-color: #6366f1; box-shadow: inset 0 1px 3px rgba(0,0,0, 0.08), 0 0 0 3px rgba(99, 102, 241, 0.2); background-color: #fff; }

        /* --- Validation & Status Message Styling --- */
        .input-error-message { color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block; }
        .status-message { color: #059669; /* Emerald dark */ font-size: 0.9rem; font-weight: 500; margin-left: 1rem; opacity: 0; transition: opacity 0.5s ease-in-out; display: inline-block; }
        .status-message.visible { opacity: 1; }
        .email-verify-notice p { color: #b45309; /* Amber dark */ } /* For unverified email */
        .email-verify-notice button { background:none; border:none; color:#6366f1; text-decoration:underline; cursor:pointer; padding:0; font-size: inherit; font-weight: 500; }
        .email-verify-notice .link-sent { color: #059669; } /* Status message */

        /* --- Form Button Styling --- */
        .form-actions { margin-top: 1.5rem; display: flex; align-items: center; gap: 1rem; }
        .btn-submit { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #ffffff; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); padding: 10px 25px; font-weight: 500; border-radius: 8px; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(79, 70, 229, 0.4); }
        .btn-submit:active { transform: translateY(0); box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }
        .btn-danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: #ffffff; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); padding: 10px 25px; font-weight: 500; border-radius: 8px; }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(220, 38, 38, 0.4); }
        .btn-danger:active { transform: translateY(0); box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); }

        /* --- Delete Account Modal Styles --- */
        .modal-overlay { position: fixed; inset: 0; background-color: rgba(30, 41, 59, 0.7); /* Darker overlay */ backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 2000; opacity: 0; visibility: hidden; transition: opacity 0.3s ease, visibility 0s linear 0.3s; }
        .modal-overlay.visible { opacity: 1; visibility: visible; transition: opacity 0.3s ease; }
        .modal-content { background: #ffffff; /* Solid white modal */ color: #1a202c; padding: 2.5rem; border-radius: 15px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2); max-width: 500px; width: 90%; text-align: center; transform: scale(0.95); transition: transform 0.3s ease; }
        .modal-overlay.visible .modal-content { transform: scale(1); }
        .modal-header { font-size: 1.5rem; font-weight: 600; color: #1a202c; margin-bottom: 1rem; }
        .modal-text { color: #4a5568; margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.6; }
        .modal-form-group { margin-bottom: 1.5rem; }
        .modal-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #4a5568; font-size: 0.9rem; text-align: left;}
        .modal-input { display: block; width: 100%; padding: 10px 15px; font-size: 1rem; font-family: inherit; color: #1a202c; background-color: #f8fafc; /* Light grey input bg */ border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: inset 0 1px 2px rgba(0,0,0, 0.05); }
        .modal-input:focus { outline: none; border-color: #ef4444; /* Red focus for delete confirm */ box-shadow: inset 0 1px 2px rgba(0,0,0, 0.05), 0 0 0 3px rgba(239, 68, 68, 0.2); }
        .modal-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; }
        .btn-secondary { background: #e2e8f0; /* Light grey */ color: #475569; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); padding: 10px 25px; border-radius: 8px;}
        .btn-secondary:hover { background: #cbd5e1; color: #1e293b; }

        /* --- Animations --- */
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDownFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0px); } }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .menu-toggle { display: block; }
            .main-content { max-width: 100%; margin-top: 1.5rem; padding: 1.5rem 15px; gap: 1.8rem; }
            .profile-section { padding: 2rem 1.5rem; border-radius: 12px; }
            .section-header { font-size: 1.3rem; }
            .modal-content { padding: 2rem 1.5rem; }
            .modal-header { font-size: 1.4rem; }
        }
         @media (max-width: 480px) {
             .profile-section { padding: 1.5rem 1rem; }
             .section-header { font-size: 1.2rem; }
             .form-label { font-size: 0.9rem; }
             .form-control { padding: 12px 15px; }
             .btn-submit, .btn-danger, .btn-secondary { padding: 10px 20px; font-size: 0.9rem; }
             .modal-content { padding: 1.5rem 1rem; }
             .modal-header { font-size: 1.2rem; }
         }
    </style>
    {{-- Alpine JS Required for Status Messages and Modal --}}
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body>
    <div class="page-container">

        {{-- Navigation Bar --}}
        <nav class="main-nav">
             <div class="nav-container">
                 <div class="nav-logo"> <a href="{{ route('dashboard') }}" title="Dashboard">{{ config('app.name', 'Habits') }}</a> </div>
                 <button class="menu-toggle" aria-label="Toggle Menu"><svg viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" /></svg></button>
                 <div class="nav-links">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('habits.create') }}" class="{{ request()->routeIs('habits.create') ? 'active' : '' }}">New Habit</a>
                    @auth
                        <div class="user-menu">
                            <button class="user-menu-trigger" aria-haspopup="true"><span>{{ Auth::user()->name }}</span><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                            <div class="user-dropdown-content">
                                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log Out</button></form>
                            </div>
                        </div>
                    @endauth
                 </div>
             </div>
         </nav>

        {{-- Main Page Content - Profile Sections --}}
        <main class="main-content">

            <section class="profile-section">
                <header>
                    <h2 class="section-header">Profile Information</h2>
                    <p class="section-description">Update your account details.</p>
                </header>
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf @method('patch')
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name', 'updateProfileInformation') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                         @error('email', 'updateProfileInformation') <span class="input-error-message">{{ $message }}</span> @enderror
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="email-verify-notice" style="margin-top: 1rem; font-size: 0.9rem;">
                                <p> Email address unverified.
                                    <button form="send-verification" type="submit"> Resend verification email. </button>
                                </p>
                                @if (session('status') === 'verification-link-sent') <p class="link-sent" style="margin-top: 0.5rem;"> Verification link sent. </p> @endif
                            </div>
                        @endif
                    </div>
                    <div class="form-actions" x-data="{ shown: false, timeout: null }" x-init="@if (session('status') === 'profile-updated') clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 3000); @endif">
                        <button type="submit" class="btn-submit">Save Changes</button>
                        <span class="status-message" x-show="shown" x-transition.opacity.duration.500ms>Saved.</span>
                    </div>
                </form>
                <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;"> @csrf </form>
            </section>

            <section class="profile-section">
                <header>
                    <h2 class="section-header">Update Password</h2>
                    <p class="section-description">Ensure your account uses a long, random password.</p>
                </header>
                <form method="post" action="{{ route('password.update') }}">
                    @csrf @method('put')
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password">
                        @error('current_password', 'updatePassword') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                         @error('password', 'updatePassword') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>
                     <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                         @error('password_confirmation', 'updatePassword') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-actions" x-data="{ shown: false, timeout: null }" x-init="@if (session('status') === 'password-updated') clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 3000); @endif">
                        <button type="submit" class="btn-submit">Update Password</button>
                        <span class="status-message" x-show="shown" x-transition.opacity.duration.500ms>Saved.</span>
                    </div>
                </form>
            </section>

            <section class="profile-section" x-data="{ confirmingUserDeletion: false }">
                <header>
                    <h2 class="section-header">Delete Account</h2>
                    <p class="section-description">Permanently delete your account and all associated data.</p>
                </header>
                <button @click="confirmingUserDeletion = true" class="btn-danger">Delete Account</button>

                {{-- Delete Account Confirmation Modal --}}
                <div class="modal-overlay" x-show="confirmingUserDeletion" x-transition.opacity x-cloak @keydown.escape.window="confirmingUserDeletion = false" @click.self="confirmingUserDeletion = false">
                    <div class="modal-content" @click.stop>
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf @method('delete')
                            <h2 class="modal-header">Confirm Account Deletion</h2>
                            <p class="modal-text">This cannot be undone. Enter your password to confirm permanent deletion.</p>
                            <div class="modal-form-group">
                                <label for="password_delete" class="modal-label">Password</label>
                                <input type="password" id="password_delete" name="password" class="modal-input" placeholder="Your Password" required autocomplete="current-password">
                                 @error('password', 'userDeletion') <span class="input-error-message" style="text-align:left;">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="btn-secondary" @click="confirmingUserDeletion = false">Cancel</button>
                                <button type="submit" class="btn-danger">Delete My Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>
</html>
