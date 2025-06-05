<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    protected ContentService $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['tckr_symb', 'rpt_dt']);

        $contents = $this->service->getContent($filters);

        return response()->json($contents);
    }
}
