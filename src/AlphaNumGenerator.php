<?php namespace CaptchaGen;

use CaptchaGen\Interfaces\GeneratorInterface;

class AlphaNumGenerator implements GeneratorInterface
{
    private $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    private $length;
    private $config;
    private $imaging;
    private $challenge = '';

    public function __construct(Config $config, Imaging $imaging) {
        $this->config = $config;
        $this->imaging = $imaging;

        $length_cfg = $this->config->getKey('length');
        $this->length = rand($length_cfg['min'], $length_cfg['max']);
    }

    public function build()
    {
        while( strlen($this->challenge) < $this->length ) {
            $this->challenge .= substr($this->characters, rand() % (strlen($this->characters)), 1);
        }

        $this->config->setKey('challege', $this->challenge);

        return array(
            'captcha'   =>  $this->challenge,
            'image'     =>  $this->imaging->generate()
        );
    }
}