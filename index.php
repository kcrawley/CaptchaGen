<?php
include('src/Interfaces/GeneratorInterface.php');
include('src/AlphaNumGenerator.php');
include('src/ArithmeticGenerator.php');
include('src/Captcha.php');
include('src/CaptchaFactory.php');
include('src/Imaging.php');
include('src/Config.php');

$captcha = new \CaptchaGen\CaptchaFactory();

$captcha = $captcha->getInstance();
$captcha->generateCaptcha();
var_dump($captcha);