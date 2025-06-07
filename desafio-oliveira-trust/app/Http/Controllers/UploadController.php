<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Services\UploadService;
use Illuminate\Http\{Request, Response};

class UploadController extends Controller
{
    protected UploadService $service;

    public function __construct(UploadService $uploadService)
    {
        $this->service = $uploadService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['file_name', 'reference_date']);

        $uploads = $this->service->getUploads($filters);

        return response()->json($uploads);
    }

    public function store(Request $request)
    {
        try {
            $result = $this->service->uploadFile($request);
           
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(
                ['error' => true, 'message' => $e->getMessage()],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
