<?php

namespace App\Http\Controllers;

use App\AMIParser;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use JsonException;

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

        // NOTE: Mime guessers see JSON files as text if the size is over 2^20+1 bytes!
        $uploadedFileType = $uploadedFile->getMimeType();
        if ($uploadedFileType != 'application/json' && $uploadedFileType != 'text/plain') {
            abort(400, "Invalid file type was uploaded.");
        }

        try {
            $fileContent = $uploadedFile->get();
            if ($fileContent === false) {
                abort(500, "Failed to read contents of uploaded file");
            }
        } catch (FileNotFoundException $e) {
            abort(500, $e->getMessage());
        }

        try {
            $result = $parser->parseFile($fileContent, null);
        } catch (JsonException) {
            abort(400, $uploadedFile->getClientOriginalName(). " does not contain well-formed JSON!");
        }
        return view('upload.result', $result);
    }
}
