<?php

namespace App\Http\Controllers;

use App\Http\Resources\{ContentResource};
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
        $filters = $request->only(['TckrSymb', 'RptDt']);

        $contents = $this->service->getContent($filters);

        return ContentResource::collection($contents);

    }
}
