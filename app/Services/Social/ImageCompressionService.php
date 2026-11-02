<?php

namespace App\Services\Social;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageCompressionService
{
    /**
     * Every stored image must be <= 5 MB.
     */
    public const MAX_BYTES = 5 * 1024 * 1024;

    /**
     * Maximum working dimension.
     */
    protected int $maxDimension = 4000;

    /**
     * Convert EVERY uploaded image to JPEG and store it <= 5 MB.
     */
    public function store(
        UploadedFile $file,
        string $directory = 'social-posts'
    ): array {
        if (! $file->isValid()) {
            throw new RuntimeException(
                'The uploaded image is invalid.'
            );
        }

        if (! extension_loaded('gd')) {
            throw new RuntimeException(
                'PHP GD extension is required to compress images.'
            );
        }

        $sourcePath = $file->getRealPath();

        if (! $sourcePath || ! is_file($sourcePath)) {
            throw new RuntimeException(
                'Unable to read uploaded image.'
            );
        }

        $imageInfo = @getimagesize($sourcePath);

        if ($imageInfo === false) {
            throw new RuntimeException(
                'The uploaded file is not a valid image.'
            );
        }

        $width = (int) ($imageInfo[0] ?? 0);
        $height = (int) ($imageInfo[1] ?? 0);
        $mime = strtolower(
            (string) ($imageInfo['mime'] ?? '')
        );

        if ($width < 1 || $height < 1) {
            throw new RuntimeException(
                'Invalid image dimensions.'
            );
        }

        $source = match ($mime) {
            'image/jpeg',
            'image/jpg' => @imagecreatefromjpeg($sourcePath),

            'image/png' => @imagecreatefrompng($sourcePath),

            'image/webp' => function_exists('imagecreatefromwebp')
                ? @imagecreatefromwebp($sourcePath)
                : false,

            'image/gif' => @imagecreatefromgif($sourcePath),

            default => false,
        };

        if (! $source) {
            throw new RuntimeException(
                'Unsupported image format: ' .
                    ($mime ?: 'unknown')
            );
        }

        $temporaryDirectory = storage_path(
            'app/image-temp'
        );

        if (
            ! is_dir($temporaryDirectory) &&
            ! mkdir($temporaryDirectory, 0755, true) &&
            ! is_dir($temporaryDirectory)
        ) {
            imagedestroy($source);

            throw new RuntimeException(
                'Unable to create image temporary directory.'
            );
        }

        $temporaryPath =
            $temporaryDirectory .
            '/' .
            Str::uuid() .
            '.jpg';

        $targetWidth = $width;
        $targetHeight = $height;

        $largestDimension = max(
            $targetWidth,
            $targetHeight
        );

        if ($largestDimension > $this->maxDimension) {
            $scale =
                $this->maxDimension /
                $largestDimension;

            $targetWidth = max(
                1,
                (int) round($width * $scale)
            );

            $targetHeight = max(
                1,
                (int) round($height * $scale)
            );
        }

        try {
            $quality = 85;

            for ($attempt = 0; $attempt < 40; $attempt++) {
                $output = imagecreatetruecolor(
                    $targetWidth,
                    $targetHeight
                );

                if (! $output) {
                    throw new RuntimeException(
                        'Unable to create compressed image canvas.'
                    );
                }

                /*
                 * JPEG does not support transparency.
                 * Use white background for PNG/GIF transparency.
                 */
                $white = imagecolorallocate(
                    $output,
                    255,
                    255,
                    255
                );

                imagefill(
                    $output,
                    0,
                    0,
                    $white
                );

                if (! imagecopyresampled(
                    $output,
                    $source,
                    0,
                    0,
                    0,
                    0,
                    $targetWidth,
                    $targetHeight,
                    $width,
                    $height
                )) {
                    imagedestroy($output);

                    throw new RuntimeException(
                        'Unable to resize uploaded image.'
                    );
                }

                if (! imagejpeg(
                    $output,
                    $temporaryPath,
                    $quality
                )) {
                    imagedestroy($output);

                    throw new RuntimeException(
                        'Unable to create compressed JPEG image.'
                    );
                }

                imagedestroy($output);

                clearstatcache(true, $temporaryPath);

                $size = filesize($temporaryPath);

                if (
                    $size !== false &&
                    $size <= self::MAX_BYTES
                ) {
                    $binary = file_get_contents(
                        $temporaryPath
                    );

                    if ($binary === false) {
                        throw new RuntimeException(
                            'Unable to read compressed image.'
                        );
                    }

                    $storedPath =
                        trim($directory, '/') .
                        '/' .
                        Str::uuid()->toString() .
                        '.jpg';

                    $stored = Storage::disk('public')
                        ->put(
                            $storedPath,
                            $binary
                        );

                    if (! $stored) {
                        throw new RuntimeException(
                            'Unable to save compressed image.'
                        );
                    }

                    clearstatcache(
                        true,
                        Storage::disk('public')
                            ->path($storedPath)
                    );

                    $savedSize = Storage::disk('public')
                        ->size($storedPath);

                    if (
                        $savedSize > self::MAX_BYTES
                    ) {
                        Storage::disk('public')
                            ->delete($storedPath);

                        throw new RuntimeException(
                            'Stored image exceeds the 5 MB limit.'
                        );
                    }

                    return [
                        'path' => $storedPath,
                        'file_name' =>
                        pathinfo(
                            $file->getClientOriginalName(),
                            PATHINFO_FILENAME
                        ) . '.jpg',
                        'mime_type' => 'image/jpeg',
                        'file_size' => (int) $savedSize,
                    ];
                }

                /*
                 * Lower quality first.
                 */
                if ($quality > 40) {
                    $quality -= 5;
                    continue;
                }

                /*
                 * Then reduce dimensions.
                 */
                $targetWidth = max(
                    640,
                    (int) round(
                        $targetWidth * 0.85
                    )
                );

                $targetHeight = max(
                    480,
                    (int) round(
                        $targetHeight * 0.85
                    )
                );

                $quality = 75;
            }

            throw new RuntimeException(
                'Unable to compress image to 5 MB or less.'
            );
        } finally {
            imagedestroy($source);

            if (is_file($temporaryPath)) {
                @unlink($temporaryPath);
            }
        }
    }
}
