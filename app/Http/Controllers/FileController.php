<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $files = File::where('user_id', auth()->id())->get();
        return view('files.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $this->fileService->storeFile($request->file('file'), auth()->id());
            return back()->with('success', 'File uploaded successfully.');
        }

        return back()->withErrors('Failed to upload file.');
    }

    public function download(File $file)
    {
        Gate::authorize('download', $file);

        if (Storage::exists($file->path)) {
            return Storage::download($file->path, $file->name);
        }

        abort(404, 'File not found on disk.');
    }

    public function share(File $file)
    {
        if (auth()->id() !== $file->user_id) {
            abort(403, 'Unauthorized.');
        }

        $token = $this->fileService->createShareToken($file);
        $shareUrl = route('files.shared.download', $token);

        return back()->with([
            'share_link' => $shareUrl,
            'shared_file_id' => $file->id
        ]);
    }

    public function downloadShared(string $token)
    {
        $file = File::where('share_token', $token)
            ->where('share_token_expires_at', '>', now())
            ->firstOrFail();

        if (Storage::exists($file->path)) {
            return Storage::download($file->path, $file->name);
        }

        abort(404, 'File not found on disk.');
    }
}
