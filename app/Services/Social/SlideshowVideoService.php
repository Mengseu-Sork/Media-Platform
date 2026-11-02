<?php

namespace App\Services\Social;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;

class SlideshowVideoService
{
    /**
     * Create an MP4 slideshow from the post's image media.
     *
     * landscape = YouTube
     * portrait  = TikTok
     */
    public function create(
        Post $post,
        string $orientation = 'landscape'
    ): string {

        $images = $post->media()
            ->orderBy('sort_order')
            ->get()
            ->filter(
                fn($media) =>
                str_starts_with(
                    (string) $media->mime_type,
                    'image/'
                )
            )
            ->values();

        if ($images->isEmpty()) {
            throw new RuntimeException(
                'No images are available for slideshow creation.'
            );
        }

        $disk = Storage::disk('public');

        $directory = 'generated/slideshows';

        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        $orientation = $orientation === 'portrait'
            ? 'portrait'
            : 'landscape';

        $outputRelativePath =
            "{$directory}/post-{$post->id}-{$orientation}.mp4";

        $outputPath =
            $disk->path($outputRelativePath);

        $tempDirectory =
            storage_path('app/slideshow-temp');

        if (!is_dir($tempDirectory)) {
            mkdir(
                $tempDirectory,
                0755,
                true
            );
        }

        $concatFile =
            "{$tempDirectory}/post-{$post->id}-{$orientation}.txt";

        /*
        |--------------------------------------------------------------------------
        | Build FFmpeg concat file
        |--------------------------------------------------------------------------
        */

        $validImages = [];

        foreach ($images as $image) {

            $path = $disk->path(
                $image->file_path
            );

            if (is_file($path)) {
                $validImages[] = $path;
            }
        }

        if (empty($validImages)) {
            throw new RuntimeException(
                'No readable image files were found.'
            );
        }

        $lines = [];

        foreach ($validImages as $path) {

            $safePath = str_replace(
                [
                    '\\',
                    "'",
                ],
                [
                    '\\\\',
                    "'\\''",
                ],
                $path
            );

            $lines[] =
                "file '{$safePath}'";

            $lines[] =
                'duration 3';
        }

        /*
        |--------------------------------------------------------------------------
        | FFmpeg concat needs the last image again.
        |--------------------------------------------------------------------------
        */

        $lastPath = end($validImages);

        $safeLastPath = str_replace(
            [
                '\\',
                "'",
            ],
            [
                '\\\\',
                "'\\''",
            ],
            $lastPath
        );

        $lines[] =
            "file '{$safeLastPath}'";

        file_put_contents(
            $concatFile,
            implode(
                PHP_EOL,
                $lines
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Dimensions
        |--------------------------------------------------------------------------
        */

        if ($orientation === 'portrait') {

            // TikTok
            $width = 1080;
            $height = 1920;
        } else {

            // YouTube
            $width = 1280;
            $height = 720;
        }

        /*
        |--------------------------------------------------------------------------
        | Scale while preserving aspect ratio and pad.
        |--------------------------------------------------------------------------
        */

        $filter =
            "scale={$width}:{$height}:force_original_aspect_ratio=decrease," .
            "pad={$width}:{$height}:(ow-iw)/2:(oh-ih)/2," .
            "format=yuv420p";

        /*
        |--------------------------------------------------------------------------
        | Create MP4
        |--------------------------------------------------------------------------
        */

        $process = new Process([
            'ffmpeg',

            '-y',

            '-f',
            'concat',

            '-safe',
            '0',

            '-i',
            $concatFile,

            '-vf',
            $filter,

            '-r',
            '30',

            '-c:v',
            'libx264',

            '-preset',
            'veryfast',

            '-crf',
            '23',

            '-pix_fmt',
            'yuv420p',

            '-movflags',
            '+faststart',

            $outputPath,
        ]);

        $process->setTimeout(600);

        $process->run();

        /*
        |--------------------------------------------------------------------------
        | Remove temporary file
        |--------------------------------------------------------------------------
        */

        if (is_file($concatFile)) {
            @unlink($concatFile);
        }

        if (!$process->isSuccessful()) {

            throw new RuntimeException(
                'FFmpeg slideshow creation failed: ' .
                    $process->getErrorOutput()
            );
        }

        if (!is_file($outputPath)) {

            throw new RuntimeException(
                'Slideshow video was not created.'
            );
        }

        return $outputRelativePath;
    }
}
