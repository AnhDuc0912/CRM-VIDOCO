<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class FileHelper
{
    /**
     * Allowed file types
     */
    const ALLOWED_IMAGES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const ALLOWED_DOCUMENTS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
    const ALLOWED_ARCHIVES = ['zip', 'rar', '7z'];

    /**
     * Max file sizes (in KB)
     */
    const MAX_IMAGE_SIZE = 5120; // 5MB
    const MAX_DOCUMENT_SIZE = 10240; // 10MB
    const MAX_ARCHIVE_SIZE = 51200; // 50MB

    /**
     * Upload file to storage
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $disk
     * @param array $options
     * @return array
     */
    public static function uploadFile(UploadedFile $file, string $path = '', string $disk = 'public', array $options = [])
    {
        try {
            // Validate file
            self::validateFile($file, $options);

            // Generate filename
            $filename = self::generateFilename($file, $options);

            // Store file
            $storedPath = $file->storeAs($path, $filename, $disk);

            return [
                'success' => true,
                'path' => $storedPath,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'url' => self::getFileUrl($storedPath, $disk),
                'extension' => $file->getClientOriginalExtension(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload multiple files
     *
     * @param array $files
     * @param string $path
     * @param string $disk
     * @param array $options
     * @return array
     */
    public static function uploadMultipleFiles(array $files, string $path = '', string $disk = 'public', array $options = [])
    {
        $results = [];

        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $results[$index] = self::uploadFile($file, $path, $disk, $options);
            }
        }

        return $results;
    }

    /**
     * Delete file
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public static function deleteFile(string $path, string $disk = 'public')
    {
        try {
            return Storage::disk($disk)->delete($path);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get file URL
     *
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    public static function getFileUrl(string $path, string $disk = 'public')
    {
        try {
            if ($disk === 'public') {
                return asset('storage/' . $path);
            }

            // For private files, you might want to create a route to serve them
            return route('files.download', ['path' => base64_encode($path)]);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get file content
     *
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    public static function getFileContent(string $path, string $disk = 'local')
    {
        try {
            return Storage::disk($disk)->get($path);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Check if file exists
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public static function fileExists(string $path, string $disk = 'public')
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Get file info
     *
     * @param string $path
     * @param string $disk
     * @return array|null
     */
    public static function getFileInfo(string $path, string $disk = 'public')
    {
        try {
            if (!self::fileExists($path, $disk)) {
                return null;
            }

            return [
                'size' => Storage::disk($disk)->size($path),
                'last_modified' => Storage::disk($disk)->lastModified($path),
                'mime_type' => self::getMimeType($path, $disk),
                'url' => self::getFileUrl($path, $disk)
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Create zip file from array of file paths and return for download
     *
     * @param array $filePaths Array of file paths relative to storage
     * @param string $zipName Name of the zip file (without extension)
     * @param string $disk Storage disk name
     * @return array Response with success status and file path or error message
     */
    public static function createZipFromFiles(array $filePaths, string $zipName = 'download', string $disk = 'public')
    {
        // Try ZipArchive first, fallback to command line zip
        $result = self::createZipWithZipArchive($filePaths, $zipName, $disk);

        if (!$result['success'] && strpos($result['error'], 'ZipArchive') !== false) {
            // Fallback to command line zip
            return self::createZipWithCommandLine($filePaths, $zipName, $disk);
        }

        return $result;
    }

    /**
     * Create zip using ZipArchive
     */
    private static function createZipWithZipArchive(array $filePaths, string $zipName, string $disk)
    {
        try {
            // Check if zip extension is available
            if (!extension_loaded('zip')) {
                throw new Exception('Zip extension is not available');
            }

            // Create temporary directory for zip file
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                if (!mkdir($tempDir, 0755, true)) {
                    throw new Exception('Cannot create temporary directory: ' . $tempDir);
                }
            }

            // Check if directory is writable
            if (!is_writable($tempDir)) {
                throw new Exception('Temporary directory is not writable: ' . $tempDir);
            }

            // Generate unique zip filename
            $zipFilename = $zipName . '_' . time() . '.zip';
            $zipPath = $tempDir . '/' . $zipFilename;

            // Create new ZipArchive with system temp directory
            $zip = new ZipArchive();

            // Use system temp directory instead of our custom one
            $systemTempDir = sys_get_temp_dir();
            $tempZipPath = $systemTempDir . '/' . $zipFilename;

            $zipResult = $zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            if ($zipResult !== TRUE) {
                $errorMessages = [
                    ZipArchive::ER_EXISTS => 'File already exists',
                    ZipArchive::ER_INCONS => 'Zip archive inconsistent',
                    ZipArchive::ER_INVAL => 'Invalid argument',
                    ZipArchive::ER_MEMORY => 'Malloc failure',
                    ZipArchive::ER_NOENT => 'No such file',
                    ZipArchive::ER_NOZIP => 'Not a zip archive',
                    ZipArchive::ER_OPEN => 'Can\'t open file',
                    ZipArchive::ER_READ => 'Read error',
                    ZipArchive::ER_SEEK => 'Seek error'
                ];
                $errorMsg = $errorMessages[$zipResult] ?? 'Unknown error (code: ' . $zipResult . ')';
                throw new Exception('Cannot create zip file: ' . $errorMsg . ' at path: ' . $tempZipPath);
            }

            $addedFiles = 0;
            $errors = [];

            // Add each file to zip
            foreach ($filePaths as $filePath) {
                try {
                    // Check if file exists
                    if (!self::fileExists($filePath, $disk)) {
                        $errors[] = "File not found: {$filePath}";
                        continue;
                    }

                    // Get file content
                    $fileContent = Storage::disk($disk)->get($filePath);
                    if ($fileContent === null) {
                        $errors[] = "Cannot read file: {$filePath}";
                        continue;
                    }

                    // Get filename from path
                    $filename = basename($filePath);

                    // Add file to zip
                    $zip->addFromString($filename, $fileContent);
                    $addedFiles++;
                } catch (Exception $e) {
                    $errors[] = "Error processing file {$filePath}: " . $e->getMessage();
                }
            }

            $zip->close();

            // Check if any files were added
            if ($addedFiles === 0) {
                // Clean up empty zip file
                if (file_exists($tempZipPath)) {
                    unlink($tempZipPath);
                }
                throw new Exception('No valid files found to add to zip');
            }

            // Move zip file to our temp directory
            if (!copy($tempZipPath, $zipPath)) {
                throw new Exception('Failed to move zip file to final location');
            }

            // Clean up temporary file
            unlink($tempZipPath);

            return [
                'success' => true,
                'file_path' => $zipPath,
                'file_name' => $zipFilename,
                'file_size' => filesize($zipPath),
                'files_added' => $addedFiles,
                'errors' => $errors
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'errors' => $errors ?? []
            ];
        }
    }

    /**
     * Create zip using command line zip
     */
    public static function createZipWithCommandLine(array $filePaths, string $zipName = 'download', string $disk = 'public')
    {
        try {
            $storage = Storage::disk($disk);

            $tempDir = 'temp';
            if (!$storage->exists($tempDir)) {
                $storage->makeDirectory($tempDir);
            }

            $zipFileName = $zipName . '_' . time() . '.zip';
            $zipPath = $tempDir . '/' . $zipFileName;
            $fullZipPath = $storage->path($zipPath);

            $zipDir = dirname($fullZipPath);
            if (!is_dir($zipDir)) {
                mkdir($zipDir, 0755, true);
            }

            if (file_exists($fullZipPath)) {
                unlink($fullZipPath);
            }

            $filesAdded = 0;
            $errors = [];
            $validFiles = [];

            foreach ($filePaths as $filePath) {
                $fullFilePath = $storage->path($filePath);

                if (!file_exists($fullFilePath)) {
                    $errors[] = "File không tồn tại: " . $filePath;
                    continue;
                }

                if (!is_readable($fullFilePath)) {
                    $errors[] = "File không đọc được: " . $filePath;
                    continue;
                }

                $validFiles[] = $fullFilePath;
            }

            if (empty($validFiles)) {
                return [
                    'success' => false,
                    'error' => 'Không có file hợp lệ để tạo zip',
                    'errors' => $errors
                ];
            }

            $fileList = implode(' ', array_map('escapeshellarg', $validFiles));
            $command = sprintf(
                'zip -j %s %s 2>&1',
                escapeshellarg($fullZipPath),
                $fileList
            );

            $output = shell_exec($command);

            if (file_exists($fullZipPath) && filesize($fullZipPath) > 0) {
                $filesAdded = substr_count($output, 'adding:');

                return [
                    'success' => true,
                    'file_path' => $fullZipPath,
                    'file_name' => $zipFileName,
                    'files_added' => $filesAdded,
                    'file_size' => filesize($fullZipPath),
                    'errors' => $errors
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Command line zip failed: ' . $output,
                    'errors' => $errors
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Command line zip exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Download zip file and clean up
     *
     * @param string $zipPath Full path to zip file
     * @param string $zipName Name for download
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|null
     */
    public static function downloadZip(string $zipPath, string $zipName = null)
    {
        try {
            if (!file_exists($zipPath)) {
                Log::error("Zip file not found: " . $zipPath);
                return null;
            }

            if (!is_readable($zipPath)) {
                Log::error("Zip file not readable: " . $zipPath);
                return null;
            }

            $zipName = $zipName ?: basename($zipPath);

            if (!str_ends_with($zipName, '.zip')) {
                $zipName .= '.zip';
            }

            return response()->download($zipPath, $zipName, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $zipName . '"'
            ])->deleteFileAfterSend(true);
        } catch (Exception $e) {
            Log::error("Error in downloadZip: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get MIME type of file
     *
     * @param string $path
     * @param string $disk
     * @return string|null
     */
    private static function getMimeType(string $path, string $disk = 'public')
    {
        try {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'txt' => 'text/plain',
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                '7z' => 'application/x-7z-compressed'
            ];

            return $mimeTypes[$extension] ?? 'application/octet-stream';
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Validate file
     *
     * @param UploadedFile $file
     * @param array $options
     * @throws Exception
     */
    private static function validateFile(UploadedFile $file, array $options = [])
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new Exception('File upload failed');
        }

        // Check file extension
        $allowedTypes = $options['allowed_types'] ?? array_merge(
            self::ALLOWED_IMAGES,
            self::ALLOWED_DOCUMENTS,
            self::ALLOWED_ARCHIVES
        );

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            throw new Exception('File type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }

        // Check file size
        $maxSize = $options['max_size'] ?? self::getMaxSizeByType($extension);
        if ($file->getSize() > $maxSize * 1024) {
            throw new Exception('File size exceeds maximum allowed size: ' . $maxSize . 'KB');
        }

        // Check MIME type
        // $mimeType = $file->getMimeType();
        // if (!self::isValidMimeType($mimeType, $extension)) {
        //     throw new Exception('Invalid file type');
        // }
    }

    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @param array $options
     * @return string
     */
    private static function generateFilename(UploadedFile $file, array $options = [])
    {
        $extension = $file->getClientOriginalExtension();
        $prefix = $options['prefix'] ?? '';

        if (isset($options['custom_name'])) {
            return $options['custom_name'] . '.' . $extension;
        }

        $uniqueId = uniqid();
        $timestamp = time();

        if ($prefix) {
            return $prefix . '_' . $timestamp . '_' . $uniqueId . '.' . $extension;
        }

        return $timestamp . '_' . $uniqueId . '.' . $extension;
    }

    /**
     * Get max size by file type
     *
     * @param string $extension
     * @return int
     */
    private static function getMaxSizeByType(string $extension)
    {
        if (in_array($extension, self::ALLOWED_IMAGES)) {
            return self::MAX_IMAGE_SIZE;
        }

        if (in_array($extension, self::ALLOWED_DOCUMENTS)) {
            return self::MAX_DOCUMENT_SIZE;
        }

        if (in_array($extension, self::ALLOWED_ARCHIVES)) {
            return self::MAX_ARCHIVE_SIZE;
        }

        return self::MAX_DOCUMENT_SIZE;
    }

    /**
     * Check if MIME type is valid
     *
     * @param string $mimeType
     * @param string $extension
     * @return bool
     */
    private static function isValidMimeType(string $mimeType, string $extension)
    {
        $validMimeTypes = [
            'jpg' => ['image/jpeg', 'image/jpg'],
            'jpeg' => ['image/jpeg', 'image/jpg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'txt' => ['text/plain'],
            'zip' => ['application/zip'],
            'rar' => ['application/x-rar-compressed'],
            '7z' => ['application/x-7z-compressed']
        ];

        return isset($validMimeTypes[$extension]) &&
            in_array($mimeType, $validMimeTypes[$extension]);
    }

    /**
     * Format file size
     *
     * @param int $size
     * @return string
     */
    public static function formatFileSize(int $size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Clean filename
     *
     * @param string $filename
     * @return string
     */
    public static function cleanFilename(string $filename)
    {
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);

        // Remove leading/trailing underscores
        return trim($filename, '_');
    }

    /**
     * Copy files from one directory to another
     *
     * @param string $sourcePath Source file path
     * @param string $destinationPath Destination file path
     * @param string $disk Storage disk name
     * @return array
     */
    public static function copyFile(string $sourcePath, string $destinationPath, string $disk = 'public')
    {
        try {
            // Check if source file exists
            if (!self::fileExists($sourcePath, $disk)) {
                return [
                    'success' => false,
                    'error' => 'Source file not found: ' . $sourcePath
                ];
            }

            // Get file content
            $fileContent = Storage::disk($disk)->get($sourcePath);
            if ($fileContent === null) {
                return [
                    'success' => false,
                    'error' => 'Cannot read source file: ' . $sourcePath
                ];
            }

            // Create destination directory if it doesn't exist
            $destinationDir = dirname($destinationPath);
            if (!Storage::disk($disk)->exists($destinationDir)) {
                Storage::disk($disk)->makeDirectory($destinationDir);
            }

            // Store file at destination
            $stored = Storage::disk($disk)->put($destinationPath, $fileContent);
            if (!$stored) {
                return [
                    'success' => false,
                    'error' => 'Failed to copy file to destination: ' . $destinationPath
                ];
            }

            return [
                'success' => true,
                'source_path' => $sourcePath,
                'destination_path' => $destinationPath,
                'url' => self::getFileUrl($destinationPath, $disk)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Copy file error: ' . $e->getMessage()
            ];
        }
    }
}
