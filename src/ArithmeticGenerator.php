<?php namespace CaptchaGen;

use CaptchaGen\Interfaces\GeneratorInterface;

class ArithmeticGenerator implements GeneratorInterface
{
    private $config;
    private $imaging;

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
            $challenge = $int_a.' - '.$int_b.' = x';
            $response = $int_a - $int_b;
        } else {
            $challenge = $int_a.' + '.$int_b.' = x';
            $response = $int_a + $int_b;
        }

        $this->config->setKey('challenge', $challenge);
        $this->config->setKey('response', $response);
    }
}