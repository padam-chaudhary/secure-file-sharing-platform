<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Secure File Sharing System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 flex flex-col min-h-screen">
    <!-- Navbar -->
    <header class="w-full py-5 px-6 sm:px-12 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200/50 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo / Brand -->
            <a href="/" class="flex items-center gap-2.5 group">
                <span class="text-2xl">🛡️</span>
                <span class="font-bold text-xl tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400 bg-clip-text text-transparent group-hover:opacity-90 transition">
                    {{ config('app.name', 'Secure File Sharing') }}
                </span>
            </a>

            <!-- Navigation -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-lg transition shadow-md shadow-indigo-600/10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                            Go to Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 transition px-3 py-2">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-lg transition shadow-md shadow-indigo-600/10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center items-center px-6 sm:px-12 py-16 sm:py-24 relative overflow-hidden">
        <!-- Background Decorative Gradients -->
        <div class="absolute inset-0 -z-10 flex justify-center items-center opacity-30 dark:opacity-20 pointer-events-none">
            <div class="w-[500px] h-[500px] bg-indigo-500/30 blur-[100px] rounded-full translate-x-[-10%] translate-y-[-20%]"></div>
            <div class="w-[500px] h-[500px] bg-violet-500/30 blur-[100px] rounded-full translate-x-[20%] translate-y-[10%]"></div>
        </div>

        <!-- Hero Section -->
        <div class="max-w-4xl text-center flex flex-col items-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50/80 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-sm font-medium mb-6">
                <span>🔒</span> Safe, Private & Expiring Sharing
            </div>
            
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight sm:leading-none mb-6 font-sans">
                Share files securely<br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-violet-600 dark:from-indigo-400 dark:via-purple-400 dark:to-violet-400 bg-clip-text text-transparent">
                    with absolute privacy.
                </span>
            </h1>
            
            <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 max-w-2xl mb-10 leading-relaxed">
                Upload your critical files, generate secure temporary links, and share them with confidence. Links automatically expire, keeping your data completely safe.
            </p>

            <!-- Hero Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center w-full sm:w-auto mb-16">
                @auth
                    <a href="{{ url('/dashboard') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-xl transition shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                        Access Your Files
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 rounded-xl transition shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                        Start Sharing
                    </a>
                    <a href="#features" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 dark:bg-slate-900 dark:hover:bg-slate-800/80 dark:text-slate-300 dark:hover:text-white border border-slate-200 dark:border-slate-800 rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">
                        How it Works
                    </a>
                @endauth
            </div>
        </div>

        <!-- Features Section -->
        <section id="features" class="w-full max-w-6xl mt-8 pt-16 border-t border-slate-200/60 dark:border-slate-800/60 scroll-mt-24">
            <h2 class="text-3xl font-bold text-center text-slate-900 dark:text-white mb-12">
                Engineered for Security
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="flex flex-col p-8 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-2xl flex items-center justify-center mb-6 shadow-inner">
                        🛡️
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Owner-Only Control</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm">
                        All uploaded files are private by default. Our strict authorization policies guarantee that only you, the file owner, can access and download them.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-col p-8 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-2xl flex items-center justify-center mb-6 shadow-inner">
                        🔗
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Expiring Share Links</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm">
                        Need to share? Generate a unique 40-character secure token. Share the link with anyone to allow direct download without requiring an account.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="flex flex-col p-8 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/50 dark:border-slate-800/50 shadow-sm hover:shadow-md hover:-translate-y-1 transition duration-300">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-2xl flex items-center justify-center mb-6 shadow-inner">
                        ⏱️
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Automatic Expiration</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm">
                        Your shared links are valid for exactly 60 minutes. Once expired, links immediately return a 404 error, ensuring no permanent public access.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="w-full py-8 px-6 sm:px-12 border-t border-slate-200/50 dark:border-slate-800/50 bg-white/30 dark:bg-slate-950/30 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500 dark:text-slate-400">
            <div>
                © {{ date('Y') }} {{ config('app.name', 'Secure File Sharing') }}. All rights reserved.
            </div>
            <div class="flex items-center gap-1">
                <span>Designed for secure file exchanges.</span>
            </div>
        </div>
    </footer>
</body>

</html>