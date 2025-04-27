<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Habit Tracker - Dashboard</title>

    {{-- Preconnect for fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Embedded Vanilla CSS - Aesthetic Dark Text Theme --}}
    <style>
        /* === Basic Reset & Base Styles === */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            color: #2d3748; /* PRIMARY CHANGE: Dark text color */
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 16px;
            /* Subtle Animated Gradient Background */
            background: linear-gradient(160deg, #29323c, #485563, #2b5876, #4e4376, #360033); /* Deep, muted tones */
            background-size: 400% 400%;
            animation: gradientShift 40s ease infinite; /* Slower animation */
            overflow-x: hidden;
        }
        a { color: #6366f1; /* Muted Indigo */ text-decoration: none; transition: color 0.3s ease; }
        a:hover { color: #4338ca; /* Darker Indigo */ }
        img, svg { display: block; max-width: 100%; }
        button { font-family: inherit; cursor: pointer; border: none; border-radius: 8px; padding: 10px 20px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; }

        /* === Main Layout Container === */
        .page-container { display: flex; flex-direction: column; min-height: 100vh; }

        /* --- Navigation Styling --- */
        .main-nav {
            background: rgba(255, 255, 255, 0.25); /* Lighter glass, more opaque for text */
            backdrop-filter: blur(10px) saturate(150%);
            -webkit-backdrop-filter: blur(10px) saturate(150%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.7rem 0;
            position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.08);
        }
        .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-logo a { font-size: 1.4rem; font-weight: 700; color: #1a202c; /* Darker logo text */ text-decoration: none; }
        .nav-logo svg { width: 34px; height: 34px; fill: #1a202c; } /* Darker logo icon */
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a {
            color: #4a5568; /* Dark grey links */
            font-weight: 500; padding: 6px 12px; border-radius: 6px;
            transition: color 0.3s ease, background-color 0.3s ease;
            position: relative;
        }
        .nav-links a::after { content: ''; position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%) scaleX(0); width: 0; height: 2px; background: #6366f1; /* Muted indigo underline */ border-radius: 1px; transition: width 0.3s ease; transform-origin: center; }
        .nav-links a:hover { color: #1a202c; background-color: rgba(255, 255, 255, 0.3); }
        .nav-links a.active { color: #1a202c; font-weight: 600; }
        .nav-links a.active::after { width: 70%; transform: translateX(-50%) scaleX(1); }
        .user-menu { position: relative; display: inline-block; }
        .user-menu-trigger { background: none; border: none; color: #4a5568; display: flex; align-items: center; cursor: pointer; padding: 6px; border-radius: 6px; transition: all 0.3s ease; }
        .user-menu-trigger:hover { background-color: rgba(255, 255, 255, 0.3); color: #1a202c; }
        .user-menu-trigger span { margin-right: 8px; font-weight: 500; }
        .user-menu-trigger svg { width: 16px; height: 16px; fill: currentColor; }
        .user-dropdown-content { /* Dropdown needs light background for dark text */
            display: none; position: absolute; right: 0; top: calc(100% + 10px);
            background: rgba(255, 255, 255, 0.9); /* Lighter, less transparent background */
            backdrop-filter: blur(10px) saturate(150%);
            -webkit-backdrop-filter: blur(10px) saturate(150%);
            min-width: 180px; box-shadow: 0 8px 30px rgba(0,0,0,0.15); border-radius: 10px; z-index: 1001;
            border: 1px solid rgba(0, 0, 0, 0.08); overflow: hidden;
            animation: fadeIn 0.25s ease forwards;
        }
        .user-menu:focus-within .user-dropdown-content, .user-menu:hover .user-dropdown-content { display: block; }
        .user-dropdown-content a, .user-dropdown-content button {
            color: #2d3748; /* Dark text in dropdown */
            padding: 12px 18px; text-decoration: none; display: block; background: none; border: none; width: 100%; text-align: left; font-size: 0.95rem; transition: background-color 0.3s ease, color 0.3s ease;
        }
        .user-dropdown-content a:hover, .user-dropdown-content button:hover { background-color: rgba(0, 0, 0, 0.05); color: #1a202c; }
        .user-dropdown-content form { margin: 0; }
        .user-dropdown-content form button { border-radius: 0; }
        .menu-toggle { display: none; background: none; border: none; color: #1a202c; }
        .menu-toggle svg { width: 28px; height: 28px; fill: #1a202c; }

        /* --- Main Content Styling --- */
        .main-content { flex-grow: 1; padding: 2.5rem 20px; max-width: 1200px; margin: 0 auto; width: 100%; }
        .dashboard-header {
            color: #ffffff; /* Keep header white for contrast on dark bg */
            margin-bottom: 2rem; font-size: 1.9rem; font-weight: 600;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 0.6s ease-out forwards; opacity: 0;
        }

        /* --- Dashboard Summary Section --- */
        .dashboard-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1.8rem; margin-bottom: 3rem; }
        .summary-card {
            background: rgba(255, 255, 255, 0.6); /* More opaque light glass */
            backdrop-filter: blur(8px) saturate(120%);
            -webkit-backdrop-filter: blur(8px) saturate(120%);
            border-radius: 12px;
            padding: 1.6rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 6px 25px 0 rgba(0, 0, 0, 0.08);
            display: flex; flex-direction: column; align-items: flex-start; gap: 0.8rem;
            animation: fadeInUp 0.5s ease-out forwards; opacity: 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .summary-card:hover { transform: translateY(-6px); box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.12); }
        .summary-card:nth-child(1) { animation-delay: 0.1s; }
        .summary-card:nth-child(2) { animation-delay: 0.18s; }
        .summary-card:nth-child(3) { animation-delay: 0.26s; }
        .summary-card:nth-child(4) { animation-delay: 0.34s; }

        .summary-icon {
            background: linear-gradient(145deg, rgba(0, 0, 0, 0.03), rgba(0, 0, 0, 0.06));
            border-radius: 10px; padding: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .summary-icon svg { width: 28px; height: 28px; }

        /* --- UPDATED ICON COLORS --- */
        .summary-card:nth-child(1) .summary-icon svg { fill: #4338ca; } /* Dark Indigo */
        .summary-card:nth-child(2) .summary-icon svg { fill: #059669; } /* Dark Emerald */
        .summary-card:nth-child(3) .summary-icon svg { fill: #0284c7; } /* Dark Sky Blue */
        .summary-card:nth-child(4) .summary-icon svg { fill: #d97706; } /* Dark Amber/Orange */
        /* --- END UPDATED ICON COLORS --- */

        .summary-content .label { font-size: 0.9rem; color: #4a5568; /* Darker grey label */ margin-bottom: 0.1rem; font-weight: 500; }
        .summary-content .value { font-size: 1.9rem; font-weight: 700; color: #1a202c; /* Near black value */ line-height: 1.1; }
        .summary-content .habit-title-inline { font-weight: 400; color: #4a5568; display: block; font-size: 0.8rem; margin-top: 3px; opacity: 0.9; }

        /* Alerts / Messages */
        .alert { padding: 15px 25px; margin-bottom: 1.8rem; border-radius: 10px; border: 1px solid; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07); animation: slideDownFadeIn 0.5s ease forwards; opacity: 0; display: flex; align-items: center; gap: 1rem; background-color: #fff; /* Solid background for readability */ }
        .alert strong { font-weight: 600; }
        .alert svg { width: 22px; height: 22px; flex-shrink: 0; }
        .alert-success { color: #1f2937; border-color: #a7f3d0; background-color: #ecfdf5; } /* Light green bg */
        .alert-success strong { color: #065f46; }
        .alert-success svg { fill: #34d399; }
        .alert-info { color: #1f2937; border-color: #bae6fd; background-color: #eff6ff; } /* Light blue bg */
        .alert-info strong { color: #1e40af; }
        .alert-info svg { fill: #60a5fa; }

        /* Filters and Actions Bar */
        .actions-bar { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem; padding: 1rem 1.5rem; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(8px) saturate(120%); -webkit-backdrop-filter: blur(8px) saturate(120%); border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07); border: 1px solid rgba(0, 0, 0, 0.05); animation: fadeInUp 0.6s ease-out forwards 0.2s; opacity: 0; }
        .filter-form { display: flex; align-items: center; gap: 1rem; }
        .filter-form label { font-weight: 500; color: #4a5568; font-size: 0.9rem; }
        .filter-form select { padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(0, 0, 0, 0.1); background-color: rgba(255, 255, 255, 0.7); color: #2d3748; font-family: inherit; font-size: 0.9rem; cursor: pointer; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill="%234a5568" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'); background-repeat: no-repeat; background-position: right 10px center; padding-right: 35px; transition: all 0.3s ease; }
        .filter-form select:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); background-color: #fff; }
        .filter-form select option { background-color: #fff; color: #2d3748; } /* Standard dropdown */
        .filter-form .clear-filter-link { font-size: 0.85rem; color: #6366f1; text-decoration: underline; margin-left: 0.5rem; }
        .filter-form .clear-filter-link:hover { color: #4338ca; }
        .create-habit-btn { /* Primary button style */
            background: linear-gradient(135deg, #6366f1, #4f46e5); /* Indigo gradient */
            color: #ffffff; /* White text for contrast */
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            padding: 10px 24px;
            font-weight: 500; border-radius: 8px;
            transition: all 0.3s ease;
        }
        .create-habit-btn:hover { transform: translateY(-2px); box-shadow: 0 7px 20px rgba(79, 70, 229, 0.4); }
        .create-habit-btn:active { transform: translateY(0); box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); }

        /* Habit List Styling */
        .habit-list-header { color: #f1f5f9; /* Lighter grey header */ font-size: 1.35rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 0.6rem; border-bottom: 1px solid rgba(255, 255, 255, 0.15); text-shadow: 0 1px 2px rgba(0,0,0,0.1); animation: fadeInUp 0.6s ease-out forwards 0.4s; opacity: 0; }
        .habit-list { display: grid; gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(310px, 1fr)); }
        .habit-card {
            background: rgba(255, 255, 255, 0.75); /* More opaque light card */
            backdrop-filter: blur(6px) saturate(100%);
            -webkit-backdrop-filter: blur(6px) saturate(100%);
            border-radius: 15px; padding: 1.8rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 8px 30px 0 rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.4s ease;
            display: flex; flex-direction: column; justify-content: space-between;
            animation: fadeInUp 0.5s ease-out forwards; opacity: 0;
        }
        .habit-list .habit-card { animation-delay: calc(var(--card-index) * 0.07s + 0.5s); } /* Use JS-set variable */
        .habit-card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px 0 rgba(0, 0, 0, 0.15); }
        .habit-card.completed { opacity: 0.7; background: rgba(248, 250, 252, 0.7); /* Slightly greyed out */ box-shadow: 0 6px 25px 0 rgba(0, 0, 0, 0.08); }
        .habit-card.completed:hover { transform: translateY(-2px); box-shadow: 0 8px 30px 0 rgba(0, 0, 0, 0.1); }
        .habit-details { margin-bottom: 1.5rem; flex-grow: 1; }
        .habit-title { font-size: 1.3rem; font-weight: 600; color: #1e293b; /* Dark title */ margin-bottom: 0.6rem; display: flex; align-items: center; gap: 0.6rem; }
        .habit-title .completion-icon svg { width: 20px; height: 20px; fill: #10b981; /* Emerald color check */ }
        .habit-description { font-size: 0.95rem; color: #475569; /* Slate grey description */ margin-bottom: 1.2rem; line-height: 1.6; }
        .habit-meta { display: flex; flex-wrap: wrap; gap: 0.5rem 1.5rem; font-size: 0.8rem; color: #64748b; /* Lighter slate grey */ margin-bottom: 1.2rem; }
        .habit-meta span span { font-weight: 500; color: #334155; background-color: rgba(0, 0, 0, 0.05); padding: 3px 8px; border-radius: 6px; }
        .habit-streaks { font-size: 0.95rem; color: #475569; margin-bottom: 1.2rem; }
        .habit-streaks .streak-value { font-weight: 700; color: #f59e0b; /* Amber */ }
        .milestone-badge { display: inline-flex; align-items: center; gap: 0.3rem; margin-top: 0.7rem; padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; border: 1px solid; }
        .milestone-badge.streak-7 { background-color: #fef3c7; color: #b45309; border-color: #fde68a; } /* Amber theme */
        .milestone-badge.streak-30 { background-color: #ffedd5; color: #9a3412; border-color: #fed7aa; } /* Orange theme */
        .habit-actions { display: flex; flex-wrap: wrap; gap: 0.8rem; border-top: 1px solid rgba(0, 0, 0, 0.1); padding-top: 1.5rem; margin-top: auto; }
        .habit-actions form { display: inline-block; flex-grow: 1; min-width: 85px; }
        .habit-actions button, .habit-actions a { padding: 8px 15px; font-size: 0.85rem; font-weight: 500; border-radius: 8px; text-align: center; width: 100%; display: block; transition: all 0.3s ease; border: 1px solid transparent; }

        /* Button Variants - Subtle Gradients/Colors */
        .btn-complete { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; border-color: #6ee7b7; }
        .btn-complete:hover { background: linear-gradient(135deg, #a7f3d0, #6ee7b7); box-shadow: 0 3px 10px rgba(16, 185, 129, 0.2); transform: translateY(-1px); }
        .btn-completed { background: #f8fafc; color: #94a3b8; cursor: not-allowed; border-color: #e2e8f0; }
        .btn-completed:hover { transform: none; box-shadow: none; }
        .btn-edit { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4338ca; border-color: #a5b4fc; }
        .btn-edit:hover { background: linear-gradient(135deg, #c7d2fe, #a5b4fc); box-shadow: 0 3px 10px rgba(99, 102, 241, 0.2); transform: translateY(-1px); }
        .btn-delete { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #b91c1c; border-color: #fca5a5; }
        .btn-delete:hover { background: linear-gradient(135deg, #fecaca, #fca5a5); box-shadow: 0 3px 10px rgba(239, 68, 68, 0.2); transform: translateY(-1px); }


        /* Empty State */
        .empty-state { text-align: center; padding: 3rem 1rem; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(6px); border-radius: 15px; border: 1px solid rgba(0, 0, 0, 0.05); color: #475569; animation: fadeInUp 0.6s ease forwards; opacity: 0;}
        .empty-state p { margin-bottom: 1rem; font-size: 1.1rem; }
        .empty-state a { font-weight: 600; color: #6366f1; text-decoration: underline; }

        /* Animations */
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDownFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0px); } }

        /* Responsive Design */
        @media (max-width: 768px) {
            body { font-size: 15px; }
            .nav-links { display: none; }
            .menu-toggle { display: block; }
            .main-content { padding: 1.8rem 15px; }
            .dashboard-header { font-size: 1.6rem; }
            .dashboard-summary { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.2rem;}
            .summary-card { padding: 1.2rem; border-radius: 12px;}
            .summary-icon { padding: 10px; }
            .summary-icon svg { width: 24px; height: 24px; }
            .summary-content .value { font-size: 1.6rem; }
            .summary-content .label { font-size: 0.85rem; }
            .actions-bar { flex-direction: column; align-items: stretch; gap: 1rem; padding: 1rem 1.2rem; border-radius: 12px;}
            .filter-form { justify-content: center; }
            .create-habit-btn { width: 100%; text-align: center; font-size: 0.95rem; padding: 10px 20px; }
            .habit-list-header { font-size: 1.2rem; }
            .habit-list { grid-template-columns: 1fr; gap: 1.5rem; }
            .habit-card { padding: 1.5rem; border-radius: 12px;}
            .habit-title { font-size: 1.2rem; }
            .habit-actions { flex-direction: column; }
            .habit-actions form, .habit-actions a { width: 100%; }
            .alert { padding: 12px 20px; font-size: 0.9rem; }
        }
         @media (max-width: 480px) {
              .dashboard-summary { grid-template-columns: 1fr 1fr; gap: 1rem;}
              .summary-card { padding: 1rem; }
              .summary-content .value { font-size: 1.5rem; }
              .habit-card { padding: 1.2rem; }
              .habit-title { font-size: 1.15rem; }
              .alert { flex-direction: column; text-align: center; gap: 0.5rem;}
         }

    </style>
    {{-- JS for setting animation delays --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const habitCards = document.querySelectorAll('.habit-list .habit-card');
            habitCards.forEach((card, index) => {
                card.style.setProperty('--card-index', index);
            });
        });
    </script>
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

        {{-- Main Page Content --}}
        <main class="main-content">
            <h1 class="dashboard-header">Overview</h1>

             {{-- Dashboard Summary Section --}}
             <section class="dashboard-summary">
                 <div class="summary-card">
                     <div class="summary-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 5.636a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 1 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061ZM1.5 12a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H2.25A.75.75 0 0 1 1.5 12Zm4.136 5.636a.75.75 0 1 0-1.06 1.06l1.591 1.591a.75.75 0 1 0 1.06-1.061l-1.59-1.591ZM12 19.5a.75.75 0 0 1-.75.75v2.25a.75.75 0 0 1 1.5 0V20.25A.75.75 0 0 1 12 19.5ZM18.364 18.364a.75.75 0 0 1 0-1.06l1.591-1.591a.75.75 0 1 1 1.06 1.06l-1.59 1.59a.75.75 0 0 1-1.06 0ZM22.5 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5h2.25a.75.75 0 0 1 .75.75ZM18.364 5.636a.75.75 0 1 0 1.06-1.06l-1.59-1.591a.75.75 0 1 0-1.061 1.06l1.591 1.59ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 1.5a4.5 4.5 0 1 1 0-9 4.5 4.5 0 0 1 0 9Z"/></svg></div>
                     <div class="summary-content"><div class="label">Active Habits</div><div class="value">{{ $totalHabits ?? 0 }}</div></div>
                 </div>
                 <div class="summary-card">
                      <div class="summary-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" /></svg></div>
                     <div class="summary-content"><div class="label">Completed Today</div><div class="value">{{ $completedTodayCount ?? 0 }}</div></div>
                 </div>
                 <div class="summary-card">
                       <div class="summary-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" /></svg></div>
                     <div class="summary-content"><div class="label">Completed This Week</div><div class="value">{{ $completedThisWeekCount ?? 0 }}</div></div>
                 </div>
                 <div class="summary-card">
                       <div class="summary-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.705A.75.75 0 0 0 6 6.878a7.5 7.5 0 1 0 10.994 1.932 9.716 9.716 0 0 0-3.468-3.988A.75.75 0 0 0 12.963 2.286ZM6.75 12.75a5.25 5.25 0 0 0 10.5 0V8.25a5.25 5.25 0 0 0-10.5 0v4.5Z" clip-rule="evenodd" /></svg></div>
                     <div class="summary-content">
                         <div class="label">Best Current Streak</div>
                         @if ($longestStreakHabit && $longestStreakHabit->current_streak > 0)
                             <div class="value">{{ $longestStreakHabit->current_streak }} <span style="font-size: 1rem; font-weight: 500; color:#475569;">days</span></div>
                             <span class="habit-title-inline">{{ $longestStreakHabit->title }}</span>
                         @else
                             <div class="value">0 <span style="font-size: 1rem; font-weight: 500; color:#475569;">days</span></div>
                              <span class="habit-title-inline">-</span>
                         @endif
                     </div>
                 </div>
             </section>

            {{-- Display Success/Info Messages --}}
            @if (session('success')) <div class="alert alert-success" role="alert"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg><div><strong>Success!</strong> {!! session('success') !!}</div></div> @endif
            @if (session('info')) <div class="alert alert-info" role="alert"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /></svg><div><strong>Info:</strong> {{ session('info') }}</div></div> @endif

            {{-- Filters and Actions Bar --}}
            <section class="actions-bar">
                <form method="GET" action="{{ route('dashboard') }}" class="filter-form">
                    <label for="category">Filter:</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @isset($categories) @foreach ($categories as $categoryOption) <option value="{{ $categoryOption }}" {{ (isset($selectedCategory) && $selectedCategory == $categoryOption) ? 'selected' : '' }}> {{ $categoryOption }} </option> @endforeach @endisset
                    </select>
                     @if(isset($selectedCategory) && $selectedCategory) <a href="{{ route('dashboard') }}" class="clear-filter-link">Clear</a> @endif
                </form>
                <a href="{{ route('habits.create') }}" class="create-habit-btn"> Create New Habit </a>
            </section>

            {{-- Habit List Section --}}
            <section class="habit-list-section">
                 <h2 class="habit-list-header">Your Habits</h2>
                @if($habits->isEmpty())
                    <div class="empty-state">
                        <p>Let's build some positive momentum!</p>
                        <a href="{{ route('habits.create') }}">Create your first habit</a>
                    </div>
                @else
                    <div class="habit-list">
                        @foreach ($habits as $index => $habit)
                            @php $isCompleted = ($habit->frequency === 'daily') ? $habit->isCompletedToday() : $habit->isCompletedThisWeek(); @endphp
                             <div class="habit-card {{ $isCompleted ? 'completed' : '' }}" style="--card-index: {{ $index }}"> {{-- Use index for stagger --}}
                                <div class="habit-details">
                                    <h3 class="habit-title">
                                        {{ $habit->title }}
                                        @if($isCompleted) <span class="completion-icon" title="Completed"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg></span> @endif
                                    </h3>
                                    @if($habit->description) <p class="habit-description">{{ $habit->description }}</p> @endif
                                    <div class="habit-meta"><span>Category: <span>{{ $habit->category }}</span></span> <span>Frequency: <span>{{ ucfirst($habit->frequency) }}</span></span></div>
                                    <div class="habit-streaks"> Current: <span class="streak-value">{{ $habit->current_streak }}</span> {{ Str::plural('day', $habit->current_streak) }} | Longest: <span class="streak-value">{{ $habit->longest_streak }}</span> {{ Str::plural('day', $habit->longest_streak) }} </div>
                                     @if($habit->current_streak >= 7 && $habit->current_streak < 30) <span class="milestone-badge streak-7">✨ 7+ Day!</span> @elseif ($habit->current_streak >= 30) <span class="milestone-badge streak-30">🌟 30+ Day!</span> @endif
                                </div>
                                <div class="habit-actions">
                                     @unless ($isCompleted)
                                        <form action="{{ route('habits.complete', $habit) }}" method="POST"><button type="submit" class="btn-complete">Mark Done</button>@csrf</form>
                                     @else
                                        <button class="btn-completed" disabled>Completed!</button>
                                     @endunless
                                    <a href="{{ route('habits.edit', $habit) }}" class="btn-edit">Edit</a>
                                    <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('Delete this habit permanently?')"><button type="submit" class="btn-delete">Delete</button>@csrf @method('DELETE')</form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </main>

    </div> {{-- End page-container --}}

</body>
</html>
