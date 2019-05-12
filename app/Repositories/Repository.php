<?php

namespace App\Repositories;

use App\Models\KuviaModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Repository
{
    /** @var \Illuminate\Database\Eloquent\Builder  */
    public $query;
    private $class = '';

    public function __construct(string $class)
    {
        /** @var KuviaModel $class */
        $this->query = $class::query();
        $this->class = $class;
    }

    public function orderBy(string $column, string $direction = 'asc') : self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function get(array $columns = ['*']) : Collection
    {
        return $this->query->get($columns);
    }

    public function getOrFail(array $columns = ['*']) : Collection
    {
        $models = $this->get($columns);
        if(!$models->count()) {
            throw new ModelNotFoundException("No {$this->class}s found");
        }
        return $models;
    }

    public function first(array $columns = ['*']) : ?KuviaModel
    {
        /** @var KuviaModel $model */
        $model = $this->query->first($columns);
        return $model;
    }

    public function firstOrFail(array $columns = ['*']) : KuviaModel
    {
        /** @var KuviaModel $model */
        $model = $this->query->firstOrFail($columns);
        return $model;
    }

    public function firstN(int $limit, array $columns = ['*']) : Collection
    {
        $this->query->limit($limit);
        return $this->get($columns);
    }

    public function firstNOrFail(int $limit, array $columns = ['*']) : Collection
    {
        $this->query->limit($limit);
        $models = $this->get($columns);
        if($models->count() !== $limit) {
            throw new ModelNotFoundException("Only {$models->count()} {$this->class}s found");
        }
        return $models;
    }
}
