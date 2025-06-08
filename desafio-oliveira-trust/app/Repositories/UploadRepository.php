<?php

namespace App\Repositories;

use App\DTOs\UploadDTO;
use App\Jobs\ImportContentJob;
use App\Models\Upload;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UploadRepository
{
    private $model;

    public function __construct(Upload $model)
    {
        $this->model = $model;
    }

    public function getUploadHistory(array $filters = []): LengthAwarePaginator
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

    private function fileExists(string $file): bool
    {
        return $this->model->where('file_name', $file)->exists();
    }

    public function storeFile(UploadDTO $dto): Upload
    {
        if ($this->fileExists($dto->file_name)) {
            throw new \Exception('File already exists');
        }

        $upload = $this->model->create([
            'file_name'      => $dto->file_name,
            'file_path'      => $dto->file_path,
            'reference_date' => $dto->reference_date,
            'status'         => 'pending',
        ]);

        ImportContentJob::dispatch($upload);

        return $upload;
    }
}
