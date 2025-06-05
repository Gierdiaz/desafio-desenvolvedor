<?php

namespace App\Services;

use App\Repositories\ContentRepository;

class ContentService
{
    protected ContentRepository $repository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->repository = $contentRepository;
    }

    public function getContent(array $filters)
    {
        return $this->repository->filteringByTckrSymbOrRptDt($filters);
    }
}
