<?php namespace CaptchaGen;

use CaptchaGen\Interfaces\GeneratorInterface;

class ArithmeticGenerator extends Generator implements GeneratorInterface
{
    private $challenge;
    private $response;

    public function __construct(Config $config, Imaging $imaging)
    {
        $this->config = $config;
        $this->imaging = $imaging;
    }

    public function build()
    {
        $int_a = rand(1,9);
        $int_b = rand(1,9);

        if ($int_a >= $int_b) {
            $this->challenge = $int_a.' - '.$int_b.' = x';
            $this->response = $int_a - $int_b;
        } else {
            $this->challenge = $int_a.' + '.$int_b.' = x';
            $this->response = $int_a + $int_b;
        }

        $this->config->setKey('challenge', $this->challenge);
        $this->config->setKey('response', $this->response);

        return $this->captchaData();
    }
}