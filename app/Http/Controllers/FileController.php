<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;

class FileController extends Controller
{
    public function index()
    {
        $files = [
            (object)['name' => 'file 1'],
            (object)['name' => 'file 2'],
        ];
        return view('files.index', compact('files'));
    }

    public function show($id){
        return "show file $id";
    }

    public function store(Request $request, FileService $service)
    {
        return $service->upload($request);
    }
}
