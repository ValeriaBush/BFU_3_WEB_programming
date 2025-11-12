<?php

class ClientFactory
{
    public static function make(string $baseUri)
    {
        return new GuzzleHttp\Client([
            'base_uri' => $baseUri,
            'timeout'  => 5.0,
        ]);
    }
}