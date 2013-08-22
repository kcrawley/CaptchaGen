<?php namespace CaptchaGen;

use \CaptchaGen\Exception\FileMissingException;
use \CaptchaGen\Exception\GdLibraryMissingException;
use \CaptchaGen\Exception\UnknownImageTypeException;

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

        if (function_exists('gd_info') === false) {
            throw new GdLibraryMissingException('Required GD library is missing');
        }

        if (file_exists($this->config->getKey('font')) === false) {
            throw new FileMissingException('File not found: ' . $this->config->getKey('font'));
        }

        if (file_exists($background_image) === false) {
            throw new FileMissingException('File not found: ' . $background_image);
        }

        $bg_params = getimagesize($background_image);

        $captcha = $this->executeImageLib($background_image);
        imagealphablending($captcha, true);
        imagesavealpha($captcha, true);

        $color = self::hex2rgb($this->config->getKey('color'));
        $color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);

        $text_box_size = imagettfbbox(
            $this->config->getKey('font_size'),
            $this->config->getKey('angle'),
            $this->config->getKey('font'),
            $this->config->getKey('challenge')
        );

        $width = abs($text_box_size[6] - $text_box_size[2]);
        $height = abs($text_box_size[5] - $text_box_size[1]);
        $text_x_ceil = ($bg_params[0]) - ($width);
        $text_x = rand(0, $text_x_ceil);
        $text_y_floor = $height;
        $text_y_ceil = ($bg_params[1]) - ($height / 2);
        $text_y = rand($text_y_floor, $text_y_ceil);

        if( $this->config->getKey('shadow') ){
            $shadow = $this->config->getKey('shadow');

            $shadow_color = $this->hex2rgb($shadow['color']);
            $shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
            imagettftext(
                $captcha,
                $this->config->getKey('font_size'),
                $this->config->getKey('angle'),
                $text_x + $shadow['x'],
                $text_y + $shadow['y'],
                $shadow_color,
                $this->config->getKey('font'),
                $this->config->getKey('challenge')
            );
        }

        imagettftext(
            $captcha,
            $this->config->getKey('font_size'),
            $this->config->getKey('angle'),
            $text_x,
            $text_y,
            $color,
            $this->config->getKey('font'),
            $this->config->getKey('challenge')
        );

        return $captcha;
    }

    /**
     * Converts hex color codes (#EF8BCC) to RGB (#,#,#)
     *
     * @param $hex_str
     * @return array
     */
    private function hex2rgb($hex_str) {
        $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str);

        if( strlen($hex_str) == 6 ) {
            $color_val = hexdec($hex_str);
            $r = 0xFF & ($color_val >> 0x10);
            $g = 0xFF & ($color_val >> 0x8);
            $b = 0xFF & $color_val;
        } elseif( strlen($hex_str) == 3 ) {
            $r = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
        } else {
            $this->hex2rgb('#000000');
        }

        return array('r' => $r, 'g' => $g, 'b' => $b);
    }

    protected function executeImageLib($file)
    {
        switch (exif_imagetype($file)) {
            case IMAGETYPE_GIF:
                return imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                return imagecreatefrompng($file);
                break;
            case IMAGETYPE_WBMP:
                return imagecreatefromwbmp($file);
                break;
            default:
                throw new UnknownImageTypeException('GD library could not determine image type.');
        }
    }
}