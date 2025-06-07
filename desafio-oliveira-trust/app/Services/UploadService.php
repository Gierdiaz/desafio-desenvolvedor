<?php
namespace App\Services;

use App\DTOs\UploadDTO;
use App\Http\Requests\FileRequest;
use App\Repositories\UploadRepository;
use Exception;
use Illuminate\Support\Facades\Storage;

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
        dd($request);
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $file_path = $file->storeAs('files', $fileName, 'public');

        try {

            $result = $this->repository->storeFile(UploadDTO::fromRequest($request, $file_path));

            return $result;
        } catch (Exception $e) {
            Storage::disk('public')->delete($file_path);

            throw $e;
        }
    }
}
