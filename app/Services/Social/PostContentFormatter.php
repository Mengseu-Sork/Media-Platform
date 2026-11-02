<?php

namespace App\Services\Social;

use App\Models\Post;

class PostContentFormatter
{
    /*
    |--------------------------------------------------------------------------
    | English Title
    |--------------------------------------------------------------------------
    */

    public function englishTitle(
        Post $post
    ): string {

        return $this->clean(
            (string) $post->title
        );
    }

    /*
    |--------------------------------------------------------------------------
    | English Content
    |--------------------------------------------------------------------------
    */

    public function englishContent(
        Post $post
    ): string {

        return $this->clean(
            (string) $post->content
        );
    }

    /*
    |--------------------------------------------------------------------------
    | English
    |--------------------------------------------------------------------------
    |
    | Title
    |
    | Content
    |
    */

    public function english(
        Post $post
    ): string {

        return $this->clean(
            collect([
                $this->englishTitle($post),
                $this->englishContent($post),
            ])
                ->filter(
                    fn($value) =>
                    trim((string) $value) !== ''
                )
                ->join("\n\n")
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Khmer Title
    |--------------------------------------------------------------------------
    */

    public function khmerTitle(
        Post $post
    ): string {

        return $this->clean(
            (string) $post->title_Kh
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Khmer Content
    |--------------------------------------------------------------------------
    */

    public function khmerContent(
        Post $post
    ): string {

        return $this->clean(
            (string) $post->content_Kh
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Khmer
    |--------------------------------------------------------------------------
    */

    public function khmer(
        Post $post
    ): string {

        return $this->clean(
            collect([
                $this->khmerTitle($post),
                $this->khmerContent($post),
            ])
                ->filter(
                    fn($value) =>
                    trim((string) $value) !== ''
                )
                ->join("\n\n")
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Facebook
    |--------------------------------------------------------------------------
    |
    | Khmer first
    | English second
    |
    */

    public function bilingual(
        Post $post
    ): string {

        return $this->clean(
            collect([
                $this->khmer($post),
                $this->english($post),
            ])
                ->filter(
                    fn($value) =>
                    trim((string) $value) !== ''
                )
                ->join("\n\n")
        );
    }

    /*
    |--------------------------------------------------------------------------
    | YouTube Title
    |--------------------------------------------------------------------------
    */

    public function youtubeTitle(
        Post $post
    ): string {

        $title = $this->englishTitle(
            $post
        );

        if ($title === '') {
            $title =
                'The NGO Forum on Cambodia';
        }

        return mb_substr(
            $this->clean($title),
            0,
            100,
            'UTF-8'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | YouTube Description
    |--------------------------------------------------------------------------
    */

    public function youtubeDescription(
        Post $post
    ): string {

        return mb_substr(
            $this->clean(
                $this->englishContent(
                    $post
                )
            ),
            0,
            5000,
            'UTF-8'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Clean Text
    |--------------------------------------------------------------------------
    */

    protected function clean(
        string $text
    ): string {

        /*
        |--------------------------------------------------------------------------
        | HTML <br>
        |--------------------------------------------------------------------------
        */

        $text = preg_replace(
            '/<br\s*\/?>/i',
            "\n",
            $text
        );

        /*
        |--------------------------------------------------------------------------
        | HTML block closing tags
        |--------------------------------------------------------------------------
        */

        $text = preg_replace(
            '/<\/(?:p|div|li|h1|h2|h3|h4|h5|h6)>/i',
            "\n",
            $text
        );

        /*
        |--------------------------------------------------------------------------
        | Remove remaining HTML
        |--------------------------------------------------------------------------
        */

        $text = strip_tags(
            $text
        );

        /*
        |--------------------------------------------------------------------------
        | Decode entities
        |--------------------------------------------------------------------------
        */

        $text = html_entity_decode(
            $text,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        /*
        |--------------------------------------------------------------------------
        | Normalize line endings
        |--------------------------------------------------------------------------
        */

        $text = str_replace(
            ["\r\n", "\r"],
            "\n",
            $text
        );

        /*
        |--------------------------------------------------------------------------
        | Remove trailing spaces
        |--------------------------------------------------------------------------
        */

        $text = preg_replace(
            '/[ \t]+\n/',
            "\n",
            $text
        );

        /*
        |--------------------------------------------------------------------------
        | Maximum two consecutive newlines
        |--------------------------------------------------------------------------
        */

        $text = preg_replace(
            "/\n{3,}/",
            "\n\n",
            $text
        );

        return trim(
            $text
        );
    }
}
