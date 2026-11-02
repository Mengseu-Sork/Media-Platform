<?php

namespace App\Exports;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithDrawings,
    WithColumnWidths
{
    protected $posts;

    public function __construct(
        protected Carbon $from,
        protected Carbon $to
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Get Posts
    |--------------------------------------------------------------------------
    */

    public function collection()
    {
        if ($this->posts === null) {
            $this->posts = Post::query()
                ->with([
                    'user',
                    'media',
                    'targets.socialAccount',
                ])
                ->whereBetween('created_at', [
                    $this->from
                        ->copy()
                        ->timezone(config('app.timezone')),

                    $this->to
                        ->copy()
                        ->timezone(config('app.timezone')),
                ])
                ->orderBy('created_at')
                ->get();
        }

        return $this->posts;
    }

    /*
    |--------------------------------------------------------------------------
    | Get Image Media
    |--------------------------------------------------------------------------
    */

    protected function getImages($post)
    {
        return $post->media->filter(function ($media) {
            return str_starts_with(
                strtolower((string) $media->mime_type),
                'image/'
            );
        })->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Maximum Number Of Images
    |--------------------------------------------------------------------------
    */

    protected function maxImageCount(): int
    {
        $max = 0;

        foreach ($this->collection() as $post) {
            $count = $this->getImages($post)->count();

            if ($count > $max) {
                $max = $count;
            }
        }

        return max($max, 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Excel Headings
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        $headings = [
            'ID',
        ];

        /*
        |--------------------------------------------------------------------------
        | Dynamic Image Columns
        |--------------------------------------------------------------------------
        */

        for ($i = 1; $i <= $this->maxImageCount(); $i++) {
            $headings[] = "Image {$i}";
        }

        $headings = array_merge($headings, [
            'Title (English)',
            'Title (Khmer)',
            'Content (English)',
            'Content (Khmer)',
            'Website Department',
            'Created At',
            'Created By',
            'Media Count',
        ]);

        return $headings;
    }

    /*
    |--------------------------------------------------------------------------
    | Map Data
    |--------------------------------------------------------------------------
    */

    public function map($post): array
    {
        $maxImages = $this->maxImageCount();

        $images = $this->getImages($post);

        $row = [
            $post->id,
        ];

        /*
        |--------------------------------------------------------------------------
        | Image Columns
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $maxImages; $i++) {
            $row[] = '';
        }

        /*
        |--------------------------------------------------------------------------
        | Post Data
        |--------------------------------------------------------------------------
        */

        $row = array_merge($row, [
            $post->title,

            $post->title_Kh,

            $post->content,

            $post->content_Kh,

            $post->website_department,

            $post->created_at
                ? $post->created_at
                    ->timezone('Asia/Phnom_Penh')
                    ->format('d M Y, h:i A')
                : '',

            $post->user?->name
                ?? $post->user?->email
                ?? 'Unknown',

            $post->media->count(),
        ]);

        return $row;
    }

    /*
    |--------------------------------------------------------------------------
    | Insert ALL Images
    |--------------------------------------------------------------------------
    */

    public function drawings(): array
    {
        $drawings = [];

        $posts = $this->collection();

        /*
        |--------------------------------------------------------------------------
        | First Excel Image Column = B
        |--------------------------------------------------------------------------
        */

        foreach ($posts as $index => $post) {

            /*
            |--------------------------------------------------------------------------
            | Excel row
            |--------------------------------------------------------------------------
            */

            $row = $index + 2;

            /*
            |--------------------------------------------------------------------------
            | Get ALL images
            |--------------------------------------------------------------------------
            */

            $images = $this->getImages($post);

            foreach ($images as $imageIndex => $image) {

                $disk = Storage::disk('public');

                if (! $disk->exists($image->file_path)) {
                    continue;
                }

                $path = $disk->path($image->file_path);

                if (! is_file($path)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Excel Column
                |--------------------------------------------------------------------------
                |
                | Image 1 = B
                | Image 2 = C
                | Image 3 = D
                | ...
                |
                */

                $columnIndex = 2 + $imageIndex;

                $column = $this->excelColumnName(
                    $columnIndex
                );

                /*
                |--------------------------------------------------------------------------
                | Drawing
                |--------------------------------------------------------------------------
                */

                $drawing = new Drawing();

                $drawing->setName(
                    $image->file_name
                    ?: "Post {$post->id} Image " . ($imageIndex + 1)
                );

                $drawing->setDescription(
                    "Post {$post->id} Image " . ($imageIndex + 1)
                );

                $drawing->setPath($path);

                /*
                |--------------------------------------------------------------------------
                | Image Size
                |--------------------------------------------------------------------------
                */

                $drawing->setHeight(90);

                /*
                |--------------------------------------------------------------------------
                | Position
                |--------------------------------------------------------------------------
                */

                $drawing->setCoordinates(
                    "{$column}{$row}"
                );

                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);

                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    /*
    |--------------------------------------------------------------------------
    | Excel Column Name
    |--------------------------------------------------------------------------
    */

    protected function excelColumnName(int $number): string
    {
        $column = '';

        while ($number > 0) {
            $number--;

            $column =
                chr(65 + ($number % 26)) .
                $column;

            $number = intdiv($number, 26);
        }

        return $column;
    }

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

    public function columnWidths(): array
    {
        $widths = [];

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $widths['A'] = 8;

        /*
        |--------------------------------------------------------------------------
        | Image Columns
        |--------------------------------------------------------------------------
        */

        $maxImages = $this->maxImageCount();

        for ($i = 1; $i <= $maxImages; $i++) {

            $column = $this->excelColumnName(
                1 + $i
            );

            $widths[$column] = 18;
        }

        /*
        |--------------------------------------------------------------------------
        | Data Columns
        |--------------------------------------------------------------------------
        */

        $titleEnColumn = $this->excelColumnName(
            2 + $maxImages
        );

        $titleKhColumn = $this->excelColumnName(
            3 + $maxImages
        );

        $contentEnColumn = $this->excelColumnName(
            4 + $maxImages
        );

        $contentKhColumn = $this->excelColumnName(
            5 + $maxImages
        );

        $departmentColumn = $this->excelColumnName(
            6 + $maxImages
        );

        $createdAtColumn = $this->excelColumnName(
            7 + $maxImages
        );

        $createdByColumn = $this->excelColumnName(
            8 + $maxImages
        );

        $mediaCountColumn = $this->excelColumnName(
            9 + $maxImages
        );

        $widths[$titleEnColumn] = 35;
        $widths[$titleKhColumn] = 35;
        $widths[$contentEnColumn] = 50;
        $widths[$contentKhColumn] = 50;
        $widths[$departmentColumn] = 18;
        $widths[$createdAtColumn] = 22;
        $widths[$createdByColumn] = 25;
        $widths[$mediaCountColumn] = 12;

        return $widths;
    }

    /*
    |--------------------------------------------------------------------------
    | Styling
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet)
    {
        $maxImages = $this->maxImageCount();

        $lastColumn = $this->excelColumnName(
            9 + $maxImages
        );

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle("A1:{$lastColumn}1")
            ->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],

                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
            ]);

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $lastRow = $sheet->getHighestRow();

        if ($lastRow >= 2) {

            $sheet
                ->getStyle("A2:{$lastColumn}{$lastRow}")
                ->getAlignment()
                ->setVertical('top');

            /*
            |--------------------------------------------------------------------------
            | All text columns
            |--------------------------------------------------------------------------
            */

            $textStartColumn =
                $this->excelColumnName(
                    2 + $maxImages
                );

            $sheet
                ->getStyle(
                    "{$textStartColumn}2:{$lastColumn}{$lastRow}"
                )
                ->getAlignment()
                ->setWrapText(true);

            /*
            |--------------------------------------------------------------------------
            | Image columns center
            |--------------------------------------------------------------------------
            */

            $imageStartColumn = 'B';

            $imageEndColumn =
                $this->excelColumnName(
                    1 + $maxImages
                );

            $sheet
                ->getStyle(
                    "{$imageStartColumn}2:{$imageEndColumn}{$lastRow}"
                )
                ->getAlignment()
                ->setHorizontal('center')
                ->setVertical('center');
        }

        /*
        |--------------------------------------------------------------------------
        | Header Height
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getRowDimension(1)
            ->setRowHeight(25);

        /*
        |--------------------------------------------------------------------------
        | Data Row Height
        |--------------------------------------------------------------------------
        */

        for ($row = 2; $row <= $lastRow; $row++) {

            $sheet
                ->getRowDimension($row)
                ->setRowHeight(75);
        }

        return [];
    }
}