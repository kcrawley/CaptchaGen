<?php
include('src/Interfaces/GeneratorInterface.php');
include('src/AlphaNumGenerator.php');
include('src/ArithmeticGenerator.php');
include('src/Captcha.php');
include('src/Imaging.php');
include('src/Config.php');

$captcha = new \CaptchaGen\Captcha();

var_dump($captcha->generateCaptcha());