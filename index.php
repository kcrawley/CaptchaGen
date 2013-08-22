<?php
include('src/Exception/FileMissingException.php');
include('src/Exception/GdLibraryMissingException.php');
include('src/Exception/UnknownImageTypeException.php');
include('src/Interfaces/GeneratorInterface.php');
include('src/Generator.php');
include('src/AlphaNumGenerator.php');
include('src/ArithmeticGenerator.php');
include('src/Captcha.php');
include('src/CaptchaFactory.php');
include('src/Imaging.php');
include('src/Config.php');

$captcha = new \CaptchaGen\CaptchaFactory();

$captcha = $captcha->getInstance();
$results = $captcha->generateCaptcha();
var_dump($results);