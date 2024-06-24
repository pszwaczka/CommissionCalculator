<?php

namespace CommissionCalculator\Tools;

class FileReader
{
    /** @return resource|false */
    public function open(string $path): mixed
    {
        return fopen($path, 'r');
    }

    /** @param resource $file */
    public function close(mixed $file): void
    {
        fclose($file);
    }

    public function readLine(mixed $file): string|false
    {
        return fgets($file);
    }
}
