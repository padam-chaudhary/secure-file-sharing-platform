<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;

class FileController extends Controller
{
    public function index()
    {
        return "index files";
    }

    public function show($id){
        return "show file $id";
    }

    public function store(Request $request, FileService $service)
    {
        return $service->upload($request);
    }
}
