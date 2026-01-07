<?php

namespace App\Http\Controllers;
use Policies\FilePolicy;

abstract class Controller
{
    public function download(File $file)
    {
        $this->authorize('download', $file);
    }
}
