<?php namespace CaptchaGen\Interfaces;

use CaptchaGen\Config;
use CaptchaGen\Imaging;

interface GeneratorInterface
{
    public function __construct(Config $config, Imaging $imaging);
    public function build();
}