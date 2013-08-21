<?php namespace CaptchaGen;

use CaptchaGen\Interfaces\GeneratorInterface;

class Captcha
{
    private $config;
    private $generator;
    private $imaging;

    public function __construct(Config $config, Imaging $imaging, GeneratorInterface $generator)
    {
        $this->config = $config;
        $this->imaging = $imaging;
        $this->generator = $generator;
    }

    public function generateCaptcha()
    {
        return $this->generator->build($this->config);
    }
}