<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
public function __construct()
{
    $this->middleware('auth');
}
 public function index()
    {
        $files = File::where('user_id', auth->id())->get();
        return view('files.index', compact('files'));
    }
    // public function show($id){
    //     return "show file $id";
    // }

    // public function store(Request $request, FileService $service)
    // {
    //     return $service->upload($request);
    // }
}
