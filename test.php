<?php

require 'vendor/autoload.php';

use Vonage\Client\Credentials\Basic;
use Vonage\Client;

$basic  = new Basic('9ac493e1', 'wdJvqJxYA25La4C9');
$client = new Client($basic);

$response = $client->sms()->send(
    new \Vonage\SMS\Message\SMS('+201114990063', 'realEstate', 'Hello from Vonage!')
);

print_r($response->current());
