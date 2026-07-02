<?php

namespace App\Services;

class ScaleService
{
    public static function start()
    {
        exec("python scale-bridge.py");
    }
}