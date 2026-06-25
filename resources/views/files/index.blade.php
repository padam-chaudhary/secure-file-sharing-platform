<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Files
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Upload Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Upload New File</h3>
                    @include('files.upload')
                </div>
            </div>

            {{-- Files List --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Uploaded Files</h3>

                    @if($files->isEmpty())
                        <p class="text-gray-500 text-sm">No files uploaded yet. Use the form above to upload your first
                            file.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($files as $file)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between flex-wrap gap-3">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $file->name }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Stored as: {{ basename($file->path) }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @can('download', $file)
                                                <a href="{{ route('files.download', $file->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 transition">
                                                    ⬇ Download
                                                </a>
                                            @endcan

                                            <form action="{{ route('files.share', $file->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50 transition">
                                                    🔗 Get Shareable Link
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if (session('share_link') && session('shared_file_id') == $file->id)
                                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-md p-3">
                                            <p class="text-xs text-blue-700 font-medium mb-1">🔗 Shareable Link (expires in 60
                                                minutes):</p>
                                            <input type="text" readonly value="{{ session('share_link') }}" onclick="this.select();"
                                                class="w-full text-xs bg-white border border-blue-200 rounded px-2 py-1.5 text-blue-800 cursor-pointer">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>