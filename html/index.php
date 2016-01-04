<?php
require '../vendor/autoload.php';
use \Respect\Validation\Validator as v;

$usernameValidator = v::alnum()->noWhitespace()->length(1, 15);
var_dump($usernameValidator->validate('alganet'));