<?php
namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Generate a sanitized, unique filename.
     */
    public function generateSafeFilename(string $originalFilename): string
    {
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $filename = pathinfo($originalFilename, PATHINFO_FILENAME);
        
        $safeName = Str::slug($filename);
        
        return $extension 
            ? $safeName . '-' . uniqid() . '.' . $extension 
            : $safeName . '-' . uniqid();
    }

    /**
     * Generate a secure share token for a file.
     */
    public function createShareToken(File $file, int $expiryInMinutes = 60): string
    {
        $token = Str::random(40);
        $file->update([
            'share_token' => $token,
            'share_token_expires_at' => now()->addMinutes($expiryInMinutes),
        ]);
        return $token;
    }

    /**
     * Handle the upload and saving of a file.
     */
    public function storeFile(UploadedFile $uploadedFile, int $userId): File
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $safeName = $this->generateSafeFilename($originalName);
        
        // Store the file in a private directory inside storage/app/files
        $path = $uploadedFile->storeAs('files', $safeName);
        
        return File::create([
            'name' => $originalName,
            'path' => $path,
            'user_id' => $userId,
        ]);
    }
}
