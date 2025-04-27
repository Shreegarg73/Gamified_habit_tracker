
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Habit Tracker - Create New Habit</title>

    {{-- Preconnect for fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Embedded Vanilla CSS - Aesthetic Dark Text Theme for Forms --}}
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

        /* --- Main Content Styling (Form Page) --- */
        .main-content { flex-grow: 1; padding: 2.5rem 20px; max-width: 700px; /* Narrower */ margin: 2rem auto 4rem auto; width: 100%; }
        .form-container {
            background: rgba(255, 255, 255, 0.85); /* More opaque card */
            backdrop-filter: blur(5px) saturate(100%);
            -webkit-backdrop-filter: blur(5px) saturate(100%);
            border-radius: 15px; padding: 3rem 3.5rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 35px 0 rgba(0, 0, 0, 0.1);
            animation: popIn 0.6s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
            opacity: 0;
        }
        .form-header { color: #1a202c; /* Dark Header */ margin-bottom: 2.5rem; font-size: 1.8rem; font-weight: 600; text-align: center; }

        /* --- Form Element Styling --- */
        .form-group { margin-bottom: 1.8rem; }
        .form-label { display: block; margin-bottom: 0.6rem; font-weight: 500; color: #4a5568; /* Darker grey */ font-size: 0.95rem; }
        .form-label .req { color: #dc2626; /* Red asterisk */}
        .form-control {
            display: block; width: 100%; padding: 12px 18px; font-size: 1rem;
            font-family: inherit; color: #1a202c; /* Dark text */
            background-color: rgba(255, 255, 255, 0.7); /* Lighter background */
            border: 1px solid rgba(0, 0, 0, 0.15); border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            box-shadow: inset 0 1px 3px rgba(0,0,0, 0.08);
        }
        .form-control::placeholder { color: #94a3b8; /* Lighter placeholder */ opacity: 1; }
        .form-control:focus { outline: none; border-color: #6366f1; box-shadow: inset 0 1px 3px rgba(0,0,0, 0.08), 0 0 0 3px rgba(99, 102, 241, 0.2); background-color: #fff; }
        select.form-control { appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill="%234a5568" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'); background-repeat: no-repeat; background-position: right 15px center; padding-right: 45px; cursor: pointer; }
        select.form-control option { background-color: #fff; color: #2d3748; } /* Standard options */
        select.form-control option[disabled] { color: #94a3b8; }
        textarea.form-control { line-height: 1.6; min-height: 110px; resize: vertical; }

        /* --- Validation Error Styling --- */
        .alert-validation { padding: 15px 25px; margin-bottom: 1.8rem; border-radius: 10px; color: #991b1b; background-color: #fee2e2; border: 1px solid #fecaca; font-size: 0.95rem; animation: slideDownFadeIn 0.5s ease forwards; opacity: 0; }
        .alert-validation strong { font-weight: 600; display: block; margin-bottom: 0.5rem; color: #b91c1c; }
        .alert-validation ul { list-style-position: inside; padding-left: 5px; }
        .alert-validation li { margin-bottom: 0.3rem; font-size: 0.9rem; }
        .input-error-message { color: #dc2626; /* Red error */ font-size: 0.85rem; margin-top: 0.5rem; display: block; }

        /* --- Form Button Styling --- */
        .form-actions { margin-top: 2.5rem; display: flex; justify-content: flex-end; align-items: center; gap: 1rem; }
        .btn-cancel { color: #4a5568; background: none; padding: 10px 25px; }
        .btn-cancel:hover { color: #1a202c; text-decoration: underline; }
        .btn-submit { /* Primary button style */
            background: linear-gradient(135deg, #6366f1, #4f46e5); color: #ffffff;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); padding: 12px 30px;
            font-weight: 500; font-size: 1rem; border-radius: 8px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(79, 70, 229, 0.4); }
        .btn-submit:active { transform: translateY(0); box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }

        /* --- Animations --- */
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDownFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0px); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .menu-toggle { display: block; }
            .main-content { max-width: 100%; margin-top: 1.5rem; padding: 1.5rem 15px; }
            .form-container { padding: 2rem 1.5rem; border-radius: 12px;}
            .form-header { font-size: 1.6rem; margin-bottom: 2rem; }
            .form-actions { justify-content: space-between; }
        }
        @media (max-width: 480px) {
            .form-container { padding: 1.5rem 1rem; }
            .form-header { font-size: 1.4rem; }
            .form-label { font-size: 0.9rem; }
            .form-control { padding: 12px 15px; }
            .btn-submit { padding: 10px 25px; }
            .btn-cancel { padding: 10px 20px; }
        }
    </style>
</head>
<body>
    <div class="page-container">

        {{-- Navigation Bar (Consistent) --}}
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

        {{-- Main Page Content - Form --}}
        <main class="main-content">
            <div class="form-container">
                <h1 class="form-header">Create a New Habit</h1>

                {{-- Display Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-validation" role="alert">
                        <strong>Please correct the errors below:</strong>
                        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                    </div>
                @endif

                {{-- Create Habit Form --}}
                <form method="POST" action="{{ route('habits.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="title" class="form-label">Habit Title <span class="req">*</span></label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required autofocus autocomplete="off" placeholder="e.g., Drink 2L Water Daily">
                        @error('title') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" class="form-control" placeholder="Add notes like 'Use 1L bottle, refill once'">{{ old('description') }}</textarea>
                        @error('description') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category <span class="req">*</span></label>
                        <select name="category" id="category" class="form-control" required>
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>-- Select a Category --</option>
                            @foreach ($categories as $category) <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option> @endforeach
                        </select>
                         @error('category') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="frequency" class="form-label">Frequency <span class="req">*</span></label>
                        <select name="frequency" id="frequency" class="form-control" required>
                            <option value="" disabled {{ old('frequency') ? '' : 'selected' }}>-- Select How Often --</option>
                            @foreach ($frequencies as $frequency) <option value="{{ $frequency }}" {{ old('frequency') == $frequency ? 'selected' : '' }}>{{ ucfirst($frequency) }}</option> @endforeach
                        </select>
                         @error('frequency') <span class="input-error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-actions">
                         <a href="{{ route('dashboard') }}" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Create Habit</button>
                    </div>
                </form>
            </div>
        </main>

    </div>
</body>
</html>
