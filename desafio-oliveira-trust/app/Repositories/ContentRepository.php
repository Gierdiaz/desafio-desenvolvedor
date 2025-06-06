<?php

namespace App\Repositories;

use App\Models\Content;

class ContentRepository
{
    private $model;

    public function __construct(Content $model)
    {
        $this->model = $model;
    }

    public function filteringByTckrSymbOrRptDt(array $filters = [])
    {
        $query = $this->model->query();

        if (!empty($filters['TckrSymb'])) {
            $query->where('TckrSymb', $filters['TckrSymb']);
        }

        if (!empty($filters['RptDt'])) {
            $query->where('RptDt', 'like', $filters['RptDt'] . '%');
        }

        $hasFilters = collect($filters)->filter()->isNotEmpty();

        return $hasFilters ? $query->get() : $query->paginate(10);
    }
}
