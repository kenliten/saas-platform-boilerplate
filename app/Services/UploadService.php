<?php

namespace App\Services;

class UploadService
{
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf'
    ];

    protected $maxSize = 2 * 1024 * 1024; // 2MB

    public function upload($file, $destinationDir)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('File upload error code: ' . $file['error']);
        }

        if ($file['size'] > $this->maxSize) {
            throw new \Exception('File size exceeds limit of 2MB');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new \Exception('Invalid file type: ' . $mimeType);
        }

        // Sanitize filename or generate random one
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        
        // Ensure destination exists
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $targetPath = $destinationDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception('Failed to move uploaded file');
        }

        return $filename;
    }
}
