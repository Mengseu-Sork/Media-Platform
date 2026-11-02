import { FFmpeg } from '@ffmpeg/ffmpeg';
import { fetchFile, toBlobURL } from '@ffmpeg/util';

let ffmpeg = null;
let loadingPromise = null;

/**
 * Load the browser video engine.
 */
async function loadFFmpeg(onProgress = () => { }) {
    if (ffmpeg && ffmpeg.loaded) {
        return ffmpeg;
    }

    if (!loadingPromise) {
        loadingPromise = (async () => {
            const instance = new FFmpeg();

            instance.on('log', ({ message }) => {
                console.log('[YouTube Slideshow]', message);
            });

            onProgress('Loading video engine...');

            const baseURL =
                'https://cdn.jsdelivr.net/npm/@ffmpeg/core@0.12.10/dist/esm';

            await instance.load({
                coreURL: await toBlobURL(
                    `${baseURL}/ffmpeg-core.js`,
                    'text/javascript'
                ),
                wasmURL: await toBlobURL(
                    `${baseURL}/ffmpeg-core.wasm`,
                    'application/wasm'
                ),
            });

            ffmpeg = instance;

            return instance;
        })().catch((error) => {
            loadingPromise = null;
            throw error;
        });
    }

    return loadingPromise;
}

/**
 * Get the file extension of an uploaded image.
 */
function getImageExtension(file) {
    const extensions = {
        'image/jpeg': 'jpg',
        'image/png': 'png',
        'image/webp': 'webp',
        'image/bmp': 'bmp',
        'image/gif': 'gif',
    };

    const extension = extensions[file.type];

    if (!extension) {
        throw new Error(
            `Unsupported image type: ${file.name}. Please use JPG, PNG, or WebP.`
        );
    }

    return extension;
}

/**
 * Convert uploaded images into an MP4 slideshow.
 *
 * Each image appears for 3 seconds.
 * Output resolution: 1280 x 720.
 */
window.createYoutubeSlideshow = async function (
    images,
    onProgress = () => { }
) {
    if (!images || images.length === 0) {
        throw new Error('Please upload at least one image.');
    }

    const videoEngine = await loadFFmpeg(onProgress);

    const inputFiles = [];
    const outputFile = `youtube-slideshow-${Date.now()}.mp4`;

    try {
        /*
         * STEP 1:
         * Load each image into the browser video engine.
         */
        for (let i = 0; i < images.length; i++) {
            const image = images[i];

            if (!image.type.startsWith('image/')) {
                throw new Error(`${image.name} is not an image.`);
            }

            const extension = getImageExtension(image);
            const inputName = `slide-${i}.${extension}`;

            inputFiles.push(inputName);

            onProgress(
                `Preparing image ${i + 1} of ${images.length}...`
            );

            const imageData = await fetchFile(image);

            await videoEngine.writeFile(inputName, imageData);
        }

        /*
         * STEP 2:
         * Prepare FFmpeg input arguments.
         */
        const command = [];

        inputFiles.forEach((inputName) => {
            command.push(
                '-loop', '1',
                '-t', '3',
                '-i', inputName
            );
        });

        /*
         * STEP 3:
         * Resize images while preserving their proportions.
         */
        const filters = inputFiles.map((_, index) => {
            return (
                `[${index}:v]` +
                'scale=1280:720:force_original_aspect_ratio=decrease,' +
                'pad=1280:720:(ow-iw)/2:(oh-ih)/2,' +
                'setsar=1,' +
                'fps=30,' +
                'format=yuv420p' +
                `[video${index}]`
            );
        });

        /*
         * STEP 4:
         * Combine the images in the selected order.
         */
        const videoInputs = inputFiles
            .map((_, index) => `[video${index}]`)
            .join('');

        filters.push(
            `${videoInputs}concat=n=${inputFiles.length}:v=1:a=0[outv]`
        );

        /*
         * STEP 5:
         * Export the slideshow as an MP4 video.
         */
        command.push(
            '-filter_complex',
            filters.join(';'),

            '-map',
            '[outv]',

            '-an',

            '-c:v',
            'libx264',

            '-preset',
            'ultrafast',

            '-crf',
            '28',

            '-pix_fmt',
            'yuv420p',

            '-r',
            '30',

            '-t',
            String(images.length * 3),

            '-threads',
            '1',

            '-movflags',
            '+faststart',

            outputFile
        );

        onProgress(
            `Creating slideshow from ${images.length} images...`
        );

        const exitCode = await videoEngine.exec(command);

        if (exitCode !== 0) {
            throw new Error(
                'Video creation failed. Please try again.'
            );
        }

        /*
         * STEP 6:
         * Read the generated MP4.
         */
        onProgress('Preparing the finished video...');

        const data = await videoEngine.readFile(outputFile);

        const videoBlob = new Blob(
            [
                data.buffer.slice(
                    data.byteOffset,
                    data.byteOffset + data.byteLength
                ),
            ],
            {
                type: 'video/mp4',
            }
        );

        const generatedVideo = new File(
            [videoBlob],
            'youtube-slideshow.mp4',
            {
                type: 'video/mp4',
                lastModified: Date.now(),
            }
        );

        onProgress('Slideshow ready.');

        return generatedVideo;

    } catch (error) {
        console.error(
            'YouTube slideshow conversion failed:',
            error
        );

        throw error;

    } finally {
        /*
         * STEP 7:
         * Remove temporary files.
         */
        for (const fileName of [...inputFiles, outputFile]) {
            try {
                await videoEngine.deleteFile(fileName);
            } catch (_) {
                // Ignore files that do not exist.
            }
        }
    }
};