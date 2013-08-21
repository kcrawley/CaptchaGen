<?php namespace CaptchaGen;

class Captcha
{
    private $config;
    private $generator;
    private $imaging;

    public function __construct(array $config = array())
    {
        $this->config = new Config($config);
        $this->imaging = new Imaging($this->config);
        $this->generator = $this->initGenerator();
    }

    public function generateCaptcha()
    {
        return $this->generator->build($this->config);
    }

    protected function initGenerator()
    {
        $config_mode = $this->config->getKey('mode');
        $generators = $this->config->getKey('generators');

        return new $generators[$config_mode]($this->config, $this->imaging);
    }
}