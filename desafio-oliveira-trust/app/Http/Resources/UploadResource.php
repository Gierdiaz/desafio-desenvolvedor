<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UploadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->isMethod('post')) {
            return [
                'file_name' => $this->file_name,
                'file_path' => $this->file_path,
            ];
        }

        return [
            'file_name'      => $this->file_name,
            'reference_date' => $this->reference_date->format('Y-m-d'),
        ];
    }
}
