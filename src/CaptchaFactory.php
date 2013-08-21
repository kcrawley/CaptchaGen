<?php namespace CaptchaGen;

class CaptchaFactory {

    private $captchaInstance;

    public function __construct(array $config = array())
    {
        $config = new Config($config);
        $imaging = new Imaging($config);

        $this->captchaInstance = new Captcha($config, $imaging, $this->initGenerator($config, $imaging));
    }

    public function getInstance()
    {
        return $this->captchaInstance;
    }

    protected function initGenerator(Config $config, Imaging $imaging)
    {
        $config_mode = $config->getKey('mode');
        $generators = $config->getKey('generators');

        return new $generators[$config_mode]($config, $imaging);
    }
}