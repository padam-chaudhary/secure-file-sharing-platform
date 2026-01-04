<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;

class TestController extends Controller
{
    public function index(FileService $fileService)
    {
        return $fileService->test();
    }
}
