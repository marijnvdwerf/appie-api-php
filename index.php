<?php

require 'vendor/autoload.php';

$appie = new \Appie\Appie('12345678', '5e8a5709f662f8d401f7a00e6137f9ca');
$data = $appie->getProductsBoughtBefore();
dump($data);
