<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class ImageService
 *
 * Handles storing uploaded user images with unique filenames
 * and returns accessible URLs to the stored files.
 */
class ImageService
{
    /**
     * Path where user images are stored (relative to storage/app).
     *
     * @var string
     */
    protected string $storagePath;

    /**
     * ImageService constructor.
     *
     * Initializes the storage path using config `paths.user_images`.
     * Defaults to 'images/users' if not configured.
     */
    public function __construct()
    {
        $this->storagePath = config('paths.user_images', 'images/users');
    }

    /**
     * Generate a unique filename for the uploaded file.
     *
     * Combines a unique ID and timestamp with the original file extension.
     *
     * @param UploadedFile $file The uploaded file.
     * @return string A unique filename (e.g., 64f8a2e9a1a3d_1695223430.jpg).
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        return uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Store the uploaded file in the configured storage path.
     *
     * @param UploadedFile $file The uploaded image file.
     * @param string|null $path Optional custom storage path. If null, defaults to the internal $this->storagePath.
     * @return string The publicly accessible URL of the stored image.
     */
    protected function store(UploadedFile $file, ?string $path = null): string
    {
        $storagePath = $path ?? $this->storagePath;
        $filename = $this->generateUniqueFilename($file);
        $file->storeAs($storagePath, $filename);
        return Storage::url("{$storagePath}/{$filename}");
    }

    /**
     * Upload and store portfolio image (if exists).
     *
     * @param UploadedFile|null $file
     * @param string $path
     * @return string|null The stored image path or null if not uploaded.
     */
    public function handleImageUpload(?UploadedFile $file, string $path, ?string $previous = null): ?string
    {
        if ($file) {
            return $this->store($file, $path);
        }
        return $previous;
    }
}
