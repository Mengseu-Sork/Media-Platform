<?php

namespace App\Http\Controllers;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use App\Models\SocialAccount;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\PostMedia;
use App\Exports\PostsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Social\ImageCompressionService;


class PostController extends Controller
{

    protected function sharedAccountUserId(): int
    {
        return (int) config('services.social.shared_account_user_id');
    }

    public function index(): View
    {
        $posts = Post::query()
            ->with([
                'targets.socialAccount',
                'media',
            ])
            ->latest()
            ->paginate(20);

        return view('posts.index', compact('posts'));
    }


    public function create(): View
    {
        $accounts = SocialAccount::query()
            ->where('is_active', true)
            ->where('is_shared', true)
            ->orderBy('platform')
            ->orderBy('display_name')
            ->get();

        return view('posts.create', compact('accounts'));
    }

    // public function store(Request $request): RedirectResponse
    // {
    //     $validated = $request->validate([
    //         'title' => [
    //             'nullable',
    //             'string',
    //             'max:255',
    //         ],

    //         'title_Kh' => [
    //             'nullable',
    //             'string',
    //             'max:255',
    //         ],

    //         'content' => [
    //             'nullable',
    //             'string',
    //         ],

    //         'content_Kh' => [
    //             'nullable',
    //             'string',
    //         ],

    //         'publish_now' => [
    //             'required',
    //             'boolean',
    //         ],

    //         'scheduled_at' => [
    //             'nullable',
    //             'date',
    //         ],

    //         'website_department' => [
    //             'nullable',
    //             'string',
    //             'max:100',
    //             Rule::in([
    //                 'PALI',
    //                 'RITI',
    //                 'SACHAS',
    //                 'MACOR',
    //             ]),
    //         ],

    //         'social_accounts' => [
    //             'required',
    //             'array',
    //             'min:1',
    //         ],

    //         'social_accounts.*' => [
    //             'integer',
    //             Rule::exists('social_accounts', 'id')
    //                 ->where(function ($query) {
    //                     $query
    //                         ->where('is_active', true)
    //                         ->where('is_shared', true);
    //                 }),
    //         ],

    //         'media' => [
    //             'nullable',
    //             'array',
    //         ],

    //         'media.*' => [
    //             'file',
    //             'max:204800', // 100 MB
    //             function ($attribute, $value, $fail) {
    //                 if (!$value->isValid()) {
    //                     $fail('The uploaded media file is invalid.');
    //                     return;
    //                 }

    //                 $allowed = [
    //                     'jpg',
    //                     'jpeg',
    //                     'png',
    //                     'webp',
    //                     'gif',
    //                     'mp4',
    //                     'mov',
    //                     'webm',
    //                     'mkv',
    //                     'avi',
    //                 ];

    //                 $extension = strtolower(
    //                     $value->getClientOriginalExtension()
    //                 );

    //                 if (!in_array($extension, $allowed, true)) {
    //                     $fail(
    //                         'The media file must be JPG, JPEG, PNG, WEBP, GIF, MP4, MOV, WEBM, MKV, or AVI.'
    //                     );
    //                 }
    //             },
    //         ],
    //     ]);

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Validate title/content
    //     |--------------------------------------------------------------------------
    //     */

    //     if (
    //         empty($validated['title']) &&
    //         empty($validated['title_Kh'])
    //     ) {
    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'title' =>
    //                 'Please provide an English or Khmer title.',
    //             ]);
    //     }

    //     if (
    //         empty($validated['content']) &&
    //         empty($validated['content_Kh'])
    //     ) {
    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'content' =>
    //                 'Please provide English or Khmer content.',
    //             ]);
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Scheduled time
    //     |--------------------------------------------------------------------------
    //     */

    //     $scheduledAt = null;

    //     if (
    //         (int) $validated['publish_now'] === 0
    //     ) {
    //         if (empty($validated['scheduled_at'])) {
    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'scheduled_at' =>
    //                     'Please select a scheduled date and time.',
    //                 ]);
    //         }

    //         $scheduledAt = Carbon::parse(
    //             $validated['scheduled_at']
    //         );

    //         if ($scheduledAt->isPast()) {
    //             return back()
    //                 ->withInput()
    //                 ->withErrors([
    //                     'scheduled_at' =>
    //                     'Scheduled time must be in the future.',
    //                 ]);
    //         }
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Get selected accounts belonging to current user
    //     |--------------------------------------------------------------------------
    //     */

    //     $accountIds = array_values(
    //         array_unique(
    //             array_map(
    //                 'intval',
    //                 $validated['social_accounts']
    //             )
    //         )
    //     );

    //     $accounts = SocialAccount::query()
    //         ->where('is_active', true)
    //         ->where('is_shared', true)
    //         ->whereIn('id', $accountIds)
    //         ->get();

    //     if ($accounts->count() !== count($accountIds)) {
    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'social_accounts' =>
    //                 'One or more selected accounts are invalid.',
    //             ]);
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Website department required when Website is selected
    //     |--------------------------------------------------------------------------
    //     */

    //     $websiteSelected = $accounts->contains(
    //         fn(SocialAccount $account) =>
    //         strtolower((string) $account->platform) === 'website'
    //     );

    //     if (
    //         $websiteSelected &&
    //         empty($validated['website_department'])
    //     ) {
    //         return back()
    //             ->withInput()
    //             ->withErrors([
    //                 'website_department' =>
    //                 'Please select a Website department.',
    //             ]);
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Create Post + Targets + Media
    //     |--------------------------------------------------------------------------
    //     */

    //     $post = DB::transaction(function () use (
    //         $request,
    //         $validated,
    //         $scheduledAt,
    //         $accounts
    //     ) {
    //         /*
    //         |--------------------------------------------------------------------------
    //         | IMPORTANT
    //         |--------------------------------------------------------------------------
    //         | Your current posts table uses:
    //         | title
    //         | title_Kh
    //         | content
    //         | content_Kh
    //         |--------------------------------------------------------------------------
    //         */

    //         $post = Post::create([
    //             'user_id' => auth()->id(),

    //             'title' => $validated['title'] ?? null,
    //             'title_Kh' => $validated['title_Kh'] ?? null,

    //             'content' => $validated['content'] ?? null,
    //             'content_Kh' => $validated['content_Kh'] ?? null,

    //             /*
    //             | Publish Now = publishing
    //             | Schedule   = scheduled
    //             */
    //             'status' => $scheduledAt
    //                 ? 'scheduled'
    //                 : 'publishing',

    //             'scheduled_at' => $scheduledAt,

    //             'website_department' =>
    //             $validated['website_department'] ?? null,
    //         ]);

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Targets
    //         |--------------------------------------------------------------------------
    //         */

    //         foreach ($accounts as $account) {
    //             $post->targets()->create([
    //                 'social_account_id' => $account->id,
    //                 'status' => 'pending',
    //                 'error_message' => null,
    //             ]);
    //         }

    //         /*
    //         |--------------------------------------------------------------------------
    //         | Media
    //         |--------------------------------------------------------------------------
    //         */

    //         if ($request->hasFile('media')) {

    //             $imageCompressor =
    //                 app(ImageCompressionService::class);

    //             foreach (
    //                 $request->file('media')
    //                 as $index => $file
    //             ) {

    //                 if (
    //                     ! $file->isValid()
    //                 ) {
    //                     continue;
    //                 }

    //                 /*
    //                 |--------------------------------------------------------------------------
    //                 | IMAGE
    //                 |
    //                 | Every image becomes <= 5 MB.
    //                 |--------------------------------------------------------------------------
    //                 */

    //                 $mimeType =
    //                     strtolower(
    //                         (string) (
    //                             $file->getMimeType()
    //                             ?: $file->getClientMimeType()
    //                             ?: ''
    //                         )
    //                     );

    //                 if (
    //                     str_starts_with(
    //                         $mimeType,
    //                         'image/'
    //                     )
    //                 ) {

    //                     $stored =
    //                         $imageCompressor->store(
    //                             $file,
    //                             'social-posts'
    //                         );

    //                     $post->media()->create([
    //                         'file_path' =>
    //                         $stored['path'],

    //                         'file_name' =>
    //                         $stored['file_name'],

    //                         'mime_type' =>
    //                         $stored['mime_type'],

    //                         'file_size' =>
    //                         $stored['file_size'],

    //                         'sort_order' =>
    //                         $index,
    //                     ]);

    //                     continue;
    //                 }

    //                 /*
    //                 |--------------------------------------------------------------------------
    //                 | VIDEO
    //                 |
    //                 | Keep original video file.
    //                 |--------------------------------------------------------------------------
    //                 */

    //                 $path =
    //                     $file->store(
    //                         'social-posts',
    //                         'public'
    //                     );

    //                 $post->media()->create([
    //                     'file_path' =>
    //                     $path,

    //                     'file_name' =>
    //                     $file->getClientOriginalName(),

    //                     'mime_type' =>
    //                     $file->getMimeType()
    //                         ?: $file->getClientMimeType(),

    //                     'file_size' =>
    //                     $file->getSize(),

    //                     'sort_order' =>
    //                     $index,
    //                 ]);
    //             }
    //         }

    //         return $post;
    //     });

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Queue publishing
    //     |--------------------------------------------------------------------------
    //     */

    //     $targets = $post->targets()->get();

    //     foreach ($targets as $target) {
    //         $job = PublishPostJob::dispatch(
    //             $target->id
    //         );

    //         /*
    //         | Scheduled posts are not executed until scheduled_at.
    //         */
    //         if ($scheduledAt) {
    //             $job->delay($scheduledAt);
    //         }
    //     }

    //     /*
    //     |--------------------------------------------------------------------------
    //     | Response
    //     |--------------------------------------------------------------------------
    //     */

    //     return redirect()
    //         ->route('posts.index')
    //         ->with(
    //             'success',
    //             $scheduledAt
    //                 ? 'Post scheduled successfully.'
    //                 : 'Post publishing has been queued.'
    //         );
    // }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'title_Kh' => [
                'nullable',
                'string',
                'max:255',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'content_Kh' => [
                'nullable',
                'string',
            ],

            'publish_now' => [
                'required',
                'boolean',
            ],

            'scheduled_at' => [
                'nullable',
                'date',
            ],

            'website_department' => [
                'nullable',
                'string',
                'max:100',
                Rule::in([
                    'PALI',
                    'RITI',
                    'SACHAS',
                    'MACOR',
                ]),
            ],

            'social_accounts' => [
                'required',
                'array',
                'min:1',
            ],

            'social_accounts.*' => [
                'integer',
                Rule::exists('social_accounts', 'id')
                    ->where(function ($query) {
                        $query
                            ->where('is_active', true)
                            ->where('is_shared', true);
                    }),
            ],

            // MP4 generated locally in the user's browser for YouTube.
            'youtube_slideshow' => [
                'nullable',
                'file',
                'mimes:mp4',
                'max:204800', // 200 MB, subject to PHP/cPanel upload limits.
            ],

            'media' => [
                'nullable',
                'array',
            ],

            'media.*' => [
                'file',
                'max:204800', // 100 MB
                function ($attribute, $value, $fail) {
                    if (!$value->isValid()) {
                        $fail('The uploaded media file is invalid.');
                        return;
                    }

                    $allowed = [
                        'jpg',
                        'jpeg',
                        'png',
                        'webp',
                        'gif',
                        'mp4',
                        'mov',
                        'webm',
                        'mkv',
                        'avi',
                    ];

                    $extension = strtolower(
                        $value->getClientOriginalExtension()
                    );

                    if (!in_array($extension, $allowed, true)) {
                        $fail(
                            'The media file must be JPG, JPEG, PNG, WEBP, GIF, MP4, MOV, WEBM, MKV, or AVI.'
                        );
                    }
                },
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate title/content
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['title']) &&
            empty($validated['title_Kh'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'title' =>
                    'Please provide an English or Khmer title.',
                ]);
        }

        if (
            empty($validated['content']) &&
            empty($validated['content_Kh'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'content' =>
                    'Please provide English or Khmer content.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Scheduled time
        |--------------------------------------------------------------------------
        */

        $scheduledAt = null;

        if (
            (int) $validated['publish_now'] === 0
        ) {
            if (empty($validated['scheduled_at'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'scheduled_at' =>
                        'Please select a scheduled date and time.',
                    ]);
            }

            $scheduledAt = Carbon::parse(
                $validated['scheduled_at']
            );

            if ($scheduledAt->isPast()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'scheduled_at' =>
                        'Scheduled time must be in the future.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Get selected accounts belonging to current user
        |--------------------------------------------------------------------------
        */

        $accountIds = array_values(
            array_unique(
                array_map(
                    'intval',
                    $validated['social_accounts']
                )
            )
        );

        $accounts = SocialAccount::query()
            ->where('is_active', true)
            ->where('is_shared', true)
            ->whereIn('id', $accountIds)
            ->get();

        if ($accounts->count() !== count($accountIds)) {
            return back()
                ->withInput()
                ->withErrors([
                    'social_accounts' =>
                    'One or more selected accounts are invalid.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Browser-generated YouTube slideshow validation
        |--------------------------------------------------------------------------
        | If YouTube is selected and the user uploaded images only, require the
        | MP4 produced by the browser converter. This prevents the queue from
        | falling back to server-side FFmpeg on cPanel.
        */
        $youtubeSelected = $accounts->contains(
            fn(SocialAccount $account) =>
            strtolower((string) $account->platform) === 'youtube'
        );

        $uploadedMedia = collect($request->file('media', []))
            ->filter(fn($file) => $file && $file->isValid());

        $hasUploadedImage = $uploadedMedia->contains(function ($file) {
            $mime = strtolower((string) (
                $file->getMimeType() ?: $file->getClientMimeType()
            ));

            return str_starts_with($mime, 'image/');
        });

        $hasUploadedVideo = $uploadedMedia->contains(function ($file) {
            $mime = strtolower((string) (
                $file->getMimeType() ?: $file->getClientMimeType()
            ));

            return str_starts_with($mime, 'video/');
        });

        if (
            $youtubeSelected &&
            $hasUploadedImage &&
            ! $hasUploadedVideo &&
            ! $request->hasFile('youtube_slideshow')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'youtube_slideshow' =>
                    'The YouTube slideshow is not ready. Wait for browser conversion to finish, or upload an MP4 video.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Website department required when Website is selected
        |--------------------------------------------------------------------------
        */

        $websiteSelected = $accounts->contains(
            fn(SocialAccount $account) =>
            strtolower((string) $account->platform) === 'website'
        );

        if (
            $websiteSelected &&
            empty($validated['website_department'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'website_department' =>
                    'Please select a Website department.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Post + Targets + Media
        |--------------------------------------------------------------------------
        */

        $post = DB::transaction(function () use (
            $request,
            $validated,
            $scheduledAt,
            $accounts,
            $youtubeSelected
        ) {
            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Your current posts table uses:
            | title
            | title_Kh
            | content
            | content_Kh
            |--------------------------------------------------------------------------
            */

            $post = Post::create([
                'user_id' => auth()->id(),

                'title' => $validated['title'] ?? null,
                'title_Kh' => $validated['title_Kh'] ?? null,

                'content' => $validated['content'] ?? null,
                'content_Kh' => $validated['content_Kh'] ?? null,

                /*
                | Publish Now = publishing
                | Schedule   = scheduled
                */
                'status' => $scheduledAt
                    ? 'scheduled'
                    : 'publishing',

                'scheduled_at' => $scheduledAt,

                'website_department' =>
                $validated['website_department'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Targets
            |--------------------------------------------------------------------------
            */

            foreach ($accounts as $account) {
                $post->targets()->create([
                    'social_account_id' => $account->id,
                    'status' => 'pending',
                    'error_message' => null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('media')) {

                $imageCompressor =
                    app(ImageCompressionService::class);

                foreach (
                    $request->file('media')
                    as $index => $file
                ) {

                    if (
                        ! $file->isValid()
                    ) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | IMAGE
                    |
                    | Every image becomes <= 5 MB.
                    |--------------------------------------------------------------------------
                    */

                    $mimeType =
                        strtolower(
                            (string) (
                                $file->getMimeType()
                                ?: $file->getClientMimeType()
                                ?: ''
                            )
                        );

                    if (
                        str_starts_with(
                            $mimeType,
                            'image/'
                        )
                    ) {

                        $stored =
                            $imageCompressor->store(
                                $file,
                                'social-posts'
                            );

                        $post->media()->create([
                            'file_path' =>
                            $stored['path'],

                            'file_name' =>
                            $stored['file_name'],

                            'mime_type' =>
                            $stored['mime_type'],

                            'file_size' =>
                            $stored['file_size'],

                            'sort_order' =>
                            $index,
                        ]);

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | VIDEO
                    |
                    | Keep original video file.
                    |--------------------------------------------------------------------------
                    */

                    $path =
                        $file->store(
                            'social-posts',
                            'public'
                        );

                    $post->media()->create([
                        'file_path' =>
                        $path,

                        'file_name' =>
                        $file->getClientOriginalName(),

                        'mime_type' =>
                        $file->getMimeType()
                            ?: $file->getClientMimeType(),

                        'file_size' =>
                        $file->getSize(),

                        'sort_order' =>
                        $index,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Save browser-generated YouTube slideshow separately
            |--------------------------------------------------------------------------
            | Keep it out of post_media so that Facebook, Instagram, and other
            | providers continue receiving the original uploaded media only.
            */
            if (
                $youtubeSelected &&
                $request->hasFile('youtube_slideshow') &&
                $request->file('youtube_slideshow')->isValid()
            ) {
                $slideshowPath = $request->file('youtube_slideshow')->store(
                    'generated/youtube-slideshows',
                    'public'
                );

                if (! $slideshowPath) {
                    throw new \RuntimeException(
                        'Unable to save the generated YouTube slideshow.'
                    );
                }

                $post->forceFill([
                    'youtube_slideshow_path' => $slideshowPath,
                ])->save();
            }

            return $post;
        });

        /*
        |--------------------------------------------------------------------------
        | Queue publishing
        |--------------------------------------------------------------------------
        */

        $targets = $post->targets()->get();

        foreach ($targets as $target) {
            $job = PublishPostJob::dispatch(
                $target->id
            );

            /*
            | Scheduled posts are not executed until scheduled_at.
            */
            if ($scheduledAt) {
                $job->delay($scheduledAt);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                $scheduledAt
                    ? 'Post scheduled successfully.'
                    : 'Post publishing has been queued.'
            );
    }

    public function show(Post $post): View
    {

        $post->load([
            'user',
            'media',
            'targets.socialAccount',
            'targets.logs',
        ]);

        return view('posts.show', compact('post'));
    }


    public function destroy(Post $post): RedirectResponse
    {


        abort_unless(
            (int) $post->user_id === (int) auth()->id(),
            403
        );


        $post->load([
            'media',
            'targets.logs',
        ]);


        DB::transaction(function () use ($post) {

            $targetIds =
                $post->targets
                ->pluck('id')
                ->map(
                    fn($id) => (string) $id
                )
                ->values();


            if ($targetIds->isNotEmpty()) {

                DB::table('jobs')
                    ->where(function ($query) use ($targetIds) {

                        foreach ($targetIds as $targetId) {

                            $query->orWhere(
                                'payload',
                                'like',
                                '%' . $targetId . '%'
                            );
                        }
                    })
                    ->delete();
            }

            foreach ($post->targets as $target) {

                $target->logs()->delete();
            }

            $post->targets()->delete();


            $disk =
                Storage::disk('public');


            foreach ($post->media as $media) {

                if (
                    filled($media->file_path) &&
                    $disk->exists(
                        $media->file_path
                    )
                ) {

                    $disk->delete(
                        $media->file_path
                    );
                }


                $media->delete();
            }


            $post->delete();
        });

        return redirect()
            ->route('posts.index')
            ->with(
                'success',
                'Post and all related test data were deleted successfully.'
            );
    }


    public function publish(Post $post): RedirectResponse
    {
        abort_unless(
            $post->user_id === auth()->id(),
            403
        );

        $targets = $post->targets()
            ->whereIn(
                'status',
                [
                    'pending',
                    'failed',
                ]
            )
            ->get();

        if ($targets->isEmpty()) {
            /*
            | Allow retry of publishing targets too.
            | This avoids the old "no pending targets" problem
            | when a previous queue execution is still finishing.
            */
            $publishingTargets = $post->targets()
                ->where('status', 'publishing')
                ->get();

            if ($publishingTargets->isNotEmpty()) {
                return back()->with(
                    'error',
                    'This post is already being published.'
                );
            }

            return back()->with(
                'error',
                'There are no targets available to publish or retry.'
            );
        }

        $post->update([
            'status' => 'publishing',
            'scheduled_at' => null,
        ]);

        foreach ($targets as $target) {
            $target->update([
                'status' => 'pending',
                'error_message' => null,
            ]);

            PublishPostJob::dispatch(
                $target->id
            );
        }

        return back()->with(
            'success',
            'Publishing has been queued.'
        );
    }


    public function media(PostMedia $media): BinaryFileResponse
    {
        $media->load('post');

        $disk = Storage::disk('public');

        if (!$disk->exists($media->file_path)) {
            abort(404, 'Media file not found.');
        }

        $path = $disk->path($media->file_path);

        if (!is_file($path)) {
            abort(404, 'Media file does not exist.');
        }

        $filename = $media->file_name ?: basename($path);

        return response()->file($path, [
            'Content-Type' => $media->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' .
                str_replace('"', '', $filename) .
                '"',
        ]);
    }


    public function exportExcel(Request $request): Response
    {
        $request->validate([
            'period' => [
                'nullable',
                'in:1,3,6,custom',
            ],

            'from_month' => [
                'nullable',
                'date_format:Y-m',
            ],

            'to_month' => [
                'nullable',
                'date_format:Y-m',
            ],
        ]);

        $period = $request->input('period', '1');


        if ($period === 'custom') {

            if (
                ! $request->filled('from_month') ||
                ! $request->filled('to_month')
            ) {
                abort(422, 'Please select both From Month and To Month.');
            }

            $from = Carbon::createFromFormat(
                'Y-m',
                $request->from_month,
                'Asia/Phnom_Penh'
            )->startOfMonth();

            $to = Carbon::createFromFormat(
                'Y-m',
                $request->to_month,
                'Asia/Phnom_Penh'
            )->endOfMonth();

            if ($from->gt($to)) {
                abort(422, 'From Month cannot be later than To Month.');
            }
        } else {


            $months = (int) $period;

            $to = now('Asia/Phnom_Penh')
                ->endOfMonth();

            $from = now('Asia/Phnom_Penh')
                ->subMonths($months - 1)
                ->startOfMonth();
        }


        $hasData = Post::query()
            ->whereBetween('created_at', [
                $from->copy()->timezone(config('app.timezone')),
                $to->copy()->timezone(config('app.timezone')),
            ])
            ->exists();

        if (! $hasData) {
            return redirect()
                ->route('posts.index')
                ->with(
                    'error',
                    'No posts found for the selected date range.'
                );
        }

        $fileName =
            'NGOF-Posts-' .
            $from->format('Y-m') .
            '-to-' .
            $to->format('Y-m') .
            '.xlsx';

        return Excel::download(
            new PostsExport($from, $to),
            $fileName,
            ExcelFormat::XLSX
        );
    }
}
