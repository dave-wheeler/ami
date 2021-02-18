<?php

namespace App\Http\Controllers;

use App\AMIParser;
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
        $result = $parser->parseFile($request->file('data'));
        return view('upload.result', $result);
    }
}
