<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class UploadDTO
{
    public function __construct(
        public string $file_name,
        public string $file_path,
        public string $reference_date,
    ) {}

    public static function fromRequest(Request $request, string $filePath): self
    {
        return new self(
            file_name: $request->file('file')->getClientOriginalName(),
            file_path: $filePath,
            reference_date: now()->toDateString()
        );
    }
}