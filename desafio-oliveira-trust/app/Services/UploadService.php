<?php

namespace App\Services;

use App\DTOs\UploadDTO;
use App\Repositories\UploadRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\FileRequest;
use Exception;

class UploadService
{
    protected UploadRepository $repository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->repository = $uploadRepository;
    }

    public function getUploads(array $filters)
    {
        return $this->repository->getUploadHistory($filters);
    }

    public function uploadFile(FileRequest $request)
    {
        $file = $request->file('file');

        $file_name = $file->getClientOriginalName();
        $file_path = $file->storeAs('files', $file_name, 'public');

        try {
            $result = $this->repository->storeFile(
                UploadDTO::fromRequest($request, $file_path),
                $file
            );

            return $result;
        } catch (Exception $e) {
            Storage::disk('public')->delete($file_path);
            throw $e;
        }
    }
}
