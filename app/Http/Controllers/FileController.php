<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Add impor model file
use App\File;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Storage;

class FileController extends Controller
{
   public function index(): View
    {
        $files = File::orderBy('created_at', 'DESC')
            ->paginate(30);
        return view('file.index', compact('files'));
    }
    public function form(): View
    {
        return view('file.form');
    }
    public function upload(Request $request): RedirectResponse
    {
         $this->validate($request, [
        'title' => 'nullable|max:100',
        'file' => 'required|file|max:2000'
    ]);
    $uploadedFile = $request->file('file');        
    $path = $uploadedFile->store('public/files');
    $file = File::create([
        'title' => $request->title ?? $uploadedFile->getClientOriginalName(),
        'filename' => $path
    ]);
    return redirect()
        ->back()
        ->withSuccess(sprintf('File %s has been uploaded.', $file->title));   
    }
}
