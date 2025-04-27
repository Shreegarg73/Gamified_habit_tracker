<x-app-layout> {{-- Use the main application layout (resources/views/layouts/app.blade.php) --}}

    {{-- Define the header section that will be injected into the $header slot in app.blade.php --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Habits Dashboard') }}
        </h2>
    </x-slot>

    {{-- Add this line if you encounter issues with Str::plural later --}}
    {{-- @php use Illuminate\Support\Str; @endphp --}}

    {{-- Main content area --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Display Success Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    {{-- Use {!! !!} to render potential HTML entities like emojis from the message --}}
                    <span class="block sm:inline">{!! session('success') !!}</span>
                </div>
            @endif

            {{-- Display Info Messages (e.g., already completed) --}}
            @if (session('info'))
                <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Info:</strong>
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            {{-- Top Section: Filter + Create Button --}}
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                {{-- Filter Form --}}
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <label for="category" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Category:</label>
                    <select name="category" id="category" onchange="this.form.submit()" {{-- Submit form on change --}}
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Removed explicit filter button - now triggers on select change --}}
                    {{-- Link to clear filter --}}
                     @if($selectedCategory)
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 ml-2">Clear Filter</a>
                     @endif
                </form>

                {{-- Create New Habit Button --}}
                <a href="{{ route('habits.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                    {{ __('Create New Habit') }}
                </a>
            </div>

            {{-- Habit List --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($habits->isEmpty())
                        <p class="text-center text-gray-500 dark:text-gray-400">You haven't created any habits yet. <a href="{{ route('habits.create') }}" class="text-indigo-600 hover:underline">Get started!</a></p>
                    @else
                        <div class="space-y-6">
                            {{-- Loop through each habit --}}
                            @foreach ($habits as $habit)
                                {{-- Determine if the habit is completed for the current period --}}
                                @php
                                    $isCompleted = ($habit->frequency === 'daily') ? $habit->isCompletedToday() : $habit->isCompletedThisWeek();
                                @endphp
                                <div class="border dark:border-gray-700 p-4 rounded-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4 {{ $isCompleted ? 'opacity-60 bg-gray-50 dark:bg-gray-900' : '' }}"> {{-- Style completed habits --}}
                                    {{-- Habit Details --}}
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold flex items-center gap-2">
                                            {{ $habit->title }}
                                            @if($isCompleted)
                                                <span class="text-green-500" title="Completed for this period">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </h3>
                                        @if($habit->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $habit->description }}</p>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 space-x-4">
                                           <span>Category: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $habit->category }}</span></span>
                                           <span>Frequency: <span class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($habit->frequency) }}</span></span>
                                        </div>

                                        {{-- Progress & Streak Display --}}
                                        <div class="mt-3">
                                            {{-- Progress Bar Placeholder - Still requires calculation logic --}}
                                            {{-- <p class="text-sm font-medium">Progress:</p>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-1">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                                            </div> --}}
                                            <p class="text-sm mt-2">Current Streak: <span class="font-bold">{{ $habit->current_streak }}</span> {{ Str::plural('day', $habit->current_streak) }} | Longest Streak: <span class="font-bold">{{ $habit->longest_streak }}</span> {{ Str::plural('day', $habit->longest_streak) }}</p>

                                            {{-- Milestone Badges --}}
                                             @if($habit->current_streak >= 7 && $habit->current_streak < 30) {{-- Show between 7 and 29 --}}
                                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    🔥 7+ Day Streak!
                                                </span>
                                             @elseif ($habit->current_streak >= 30) {{-- Show 30+ --}}
                                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    🏆 30+ Day Streak!
                                                </span>
                                             @endif
                                             {{-- Add more milestones here --}}
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex-shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mt-4 md:mt-0">
                                         {{-- Mark Complete Button (Form) --}}
                                         {{-- Only show if not already completed for the period --}}
                                         @unless ($isCompleted)
                                            <form action="{{ route('habits.complete', $habit) }}" method="POST" class="inline-block w-full sm:w-auto">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm text-center">
                                                    Mark Done
                                                </button>
                                            </form>
                                         @else
                                            {{-- Show a disabled or confirmation state if completed --}}
                                            <span class="px-3 py-1.5 bg-gray-400 text-white rounded-md text-sm text-center cursor-not-allowed w-full sm:w-auto">
                                                Completed!
                                            </span>
                                         @endunless

                                        {{-- Edit Button --}}
                                        <a href="{{ route('habits.edit', $habit) }}" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md text-sm text-center w-full sm:w-auto">
                                            Edit
                                        </a>

                                        {{-- Delete Button (inside a form) --}}
                                        <form action="{{ route('habits.destroy', $habit) }}" method="POST" class="inline-block w-full sm:w-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm text-center"
                                                    onclick="return confirm('Are you sure you want to delete this habit? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
