<?php namespace CaptchaGen;

class Generator
{
    protected $config;
    protected $imaging;

    protected function captchaData()
    {
        ob_start();
        imagepng($this->imaging->generate());
        $image = ob_get_clean();

        return array(
            'challenge' =>  $this->config->getKey('challenge'),
            'response'  =>  $this->config->getKey('response'),
            'image_b64' =>  base64_encode($image)
        );
    }
}