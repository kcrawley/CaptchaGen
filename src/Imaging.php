<?php namespace CaptchaGen;

class Imaging
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function generate()
    {
        $background_image = $this->config->getKey('background');
        $background_lib = $this->determineImageLib($background_image);

        $bg_params = getimagesize($bg);

        $captcha = $background_lib($background_image);
        imagealphablending($captcha, true);
        imagesavealpha($captcha, true);



        var_dump(exif_imagetype($bg));
        return $bg_params;

    }

    protected function determineImageLib($file)
    {
        switch (exif_imagetype($file)) {
            case IMAGETYPE_GIF:
                return 'imagecreatefromgif';
                break;
            case IMAGETYPE_JPEG:
                return 'imagecreatefromjpeg';
                break;
            case IMAGETYPE_PNG:
                return 'imagecreatefrompng';
                break;
            case IMAGETYPE_WBMP:
                return 'imagecreatefromwbmp';
                break;
            default:
                // TODO :: implement Exception
                break;
        }
    }
}