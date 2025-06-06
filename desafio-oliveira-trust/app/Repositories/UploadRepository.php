<?php

namespace App\Repositories;

use App\DTOs\UploadDTO;
use App\Imports\ContentImport;
use App\Models\Upload;
use Maatwebsite\Excel\Facades\Excel;

class UploadRepository
{
    private $model;

    public function __construct(Upload $model)
    {
        $this->model = $model;
    }

    public function getUploadHistory(array $filters = [])
    {
        $query = $this->model->query();

        if (!empty($filters['file_name'])) {
            $query->where('file_name', 'like', '%' . $filters['file_name'] . '%');
        }

        if (!empty($filters['reference_date'])) {
            $query->where('reference_date', 'like', $filters['reference_date'] . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    private function fileExists(string $file_name): bool
    {
        return $this->model->where('file_name', $file_name)->exists();
    }

    public function storeFile(UploadDTO $dto, $file)
    {
        if ($this->fileExists($dto->file_name)) {
            throw new \Exception('File already exists');
        }

        $upload = $this->model->create([
            'file_name'      => $dto->file_name,
            'file_path'      => $dto->file_path,
            'reference_date' => $dto->reference_date,
        ]);

        // Excel::import(new ContentImport($upload->id), $file);
        Excel::import(new ContentImport($upload->id), storage_path('app/public/' . $dto->file_path));

        return [
            'status'  => true,
            'message' => 'File uploaded successfully',
            'upload'  => $upload,
        ];
    }
}
