<?php

declare(strict_types=1);

namespace BushlanovDev\MaxMessengerBot\Models;

/**
 * Contains URLs for a video attachment in various resolutions.
 */
final class VideoUrls extends AbstractModel
{
    /**
     * @var string|null
     * @readonly
     */
    public $mp4_1080;
    /**
     * @var string|null
     * @readonly
     */
    public $mp4_720;
    /**
     * @var string|null
     * @readonly
     */
    public $mp4_480;
    /**
     * @var string|null
     * @readonly
     */
    public $mp4_360;
    /**
     * @var string|null
     * @readonly
     */
    public $mp4_240;
    /**
     * @var string|null
     * @readonly
     */
    public $mp4_144;
    /**
     * @var string|null
     * @readonly
     */
    public $hls;
    /**
     * @param string|null $mp4_1080 Video URL in 1080p resolution, if available.
     * @param string|null $mp4_720 Video URL in 720p resolution, if available.
     * @param string|null $mp4_480 Video URL in 480p resolution, if available.
     * @param string|null $mp4_360 Video URL in 360p resolution, if available.
     * @param string|null $mp4_240 Video URL in 240p resolution, if available.
     * @param string|null $mp4_144 Video URL in 144p resolution, if available.
     * @param string|null $hls Live streaming URL (HLS), if available.
     */
    public function __construct(?string $mp4_1080 = null, ?string $mp4_720 = null, ?string $mp4_480 = null, ?string $mp4_360 = null, ?string $mp4_240 = null, ?string $mp4_144 = null, ?string $hls = null)
    {
        $this->mp4_1080 = $mp4_1080;
        $this->mp4_720 = $mp4_720;
        $this->mp4_480 = $mp4_480;
        $this->mp4_360 = $mp4_360;
        $this->mp4_240 = $mp4_240;
        $this->mp4_144 = $mp4_144;
        $this->hls = $hls;
    }
}
