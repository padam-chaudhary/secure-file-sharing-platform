<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome Banner --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h3>
                    <p class="text-gray-600 text-sm">
                        You are logged in to the <strong>Secure File Sharing System</strong>.
                        Upload files, generate expiring share links, and manage access — all securely.
                    </p>
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php
                    $fileCount = \App\Models\File::where('user_id', auth()->id())->count();
                    $sharedCount = \App\Models\File::where('user_id', auth()->id())->whereNotNull('share_token')->count();
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                    <div class="text-3xl">📁</div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $fileCount }}</div>
                        <div class="text-sm text-gray-500">Total Files Uploaded</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                    <div class="text-3xl">🔗</div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $sharedCount }}</div>
                        <div class="text-sm text-gray-500">Files with Share Links</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                    <div class="text-3xl">🛡️</div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">Active</div>
                        <div class="text-sm text-gray-500">Authorization Policy</div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Quick Actions</h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('files.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700 transition">
                            📂 Manage My Files
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 transition">
                            👤 Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            {{-- How it works --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-800 mb-4">How This System Works</h4>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                        <li><strong>Upload a file</strong> — filenames are automatically sanitized and stored securely.</li>
                        <li><strong>Download your files</strong> — only you (the owner) can download your files via the policy check.</li>
                        <li><strong>Generate a share link</strong> — creates a unique 40-character token valid for 60 minutes.</li>
                        <li><strong>Anyone with the link</strong> can download the file until the token expires.</li>
                        <li><strong>Expired links return 404</strong> — no permanent public access is granted.</li>
                    </ol>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

