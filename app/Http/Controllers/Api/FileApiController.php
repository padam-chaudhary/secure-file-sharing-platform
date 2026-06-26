<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class FileApiController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * List all files uploaded by the authenticated user.
     */
    public function index(Request $request)
    {
        $files = File::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->name,
                    'path' => $file->path,
                    'share_token' => $file->share_token,
                    'share_token_expires_at' => $file->share_token_expires_at,
                    'created_at' => $file->created_at,
                    'updated_at' => $file->updated_at,
                ];
            });

        return response()->json([
            'message' => 'Files retrieved successfully.',
            'files' => $files,
        ]);
    }

    /**
     * Upload and save a new file securely.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // Max 10MB
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $this->fileService->storeFile($request->file('file'), $request->user()->id);

            return response()->json([
                'message' => 'File uploaded successfully.',
                'file' => [
                    'id' => $file->id,
                    'name' => $file->name,
                    'created_at' => $file->created_at,
                ],
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to upload file.',
        ], 400);
    }

    /**
     * Download a file securely, verifying owner access control.
     */
    public function download(Request $request, File $file)
    {
        Gate::authorize('download', $file);

        if (Storage::exists($file->path)) {
            return Storage::download($file->path, $file->name);
        }

        return response()->json([
            'message' => 'File not found on disk.',
        ], 404);
    }

    /**
     * Share a file by generating a secure link.
     */
    public function share(Request $request, File $file)
    {
        if ($request->user()->id !== $file->user_id) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $token = $this->fileService->createShareToken($file);
        $shareUrl = route('api.files.shared.download', $token);

        return response()->json([
            'message' => 'File shared successfully.',
            'share_token' => $token,
            'share_url' => $shareUrl,
            'expires_at' => $file->share_token_expires_at,
        ]);
    }

    /**
     * Download a shared file publicly using a valid share token.
     */
    public function downloadShared(string $token)
    {
        $file = File::where('share_token', $token)
            ->where('share_token_expires_at', '>', now())
            ->first();

        if (!$file) {
            return response()->json([
                'message' => 'Invalid or expired share link.',
            ], 404);
        }

        if (Storage::exists($file->path)) {
            return Storage::download($file->path, $file->name);
        }

        return response()->json([
            'message' => 'File not found on disk.',
        ], 404);
    }
}
