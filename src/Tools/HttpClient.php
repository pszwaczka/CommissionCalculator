<?php

namespace CommissionCalculator\Tools;

class HttpClient
{
    public function get(string $url): string|false
    {
        return file_get_contents($url);
    }
}
