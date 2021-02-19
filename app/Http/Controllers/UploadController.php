<?php

namespace App\Http\Controllers;

use App\AMIParser;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('upload.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param AMIParser $parser
     * @return View
     */
    public function store(Request $request, AMIParser $parser): View
    {
        $uploadedFile = $request->file('data');
        if (is_null($uploadedFile)) {
            abort(400, 'No file was uploaded.');
        }
        if (!$uploadedFile?->isValid()) {
            abort(400, $uploadedFile->getErrorMessage());
        }
        if ($uploadedFile->getMimeType() != 'application/json') {
            abort(400, "Invalid file type was uploaded.");
        }

        try {
            $fileContent = $uploadedFile->get();
        } catch (FileNotFoundException $e) {
            abort(500, $e->getMessage());
        }

        $result = $parser->parseFile($fileContent);
        return view('upload.result', $result);
    }
}
