<?php

namespace Modules\Core\Repositories;

use Modules\Core\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
        $this->query = $this->model->newQuery();
    }

    /**
     * Specify Model class name
     */
    abstract protected function getModelClass(): string;

    /**
     * Make Model instance
     */
    protected function makeModel(): void
    {
        $model = app($this->getModelClass());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->getModelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query->get($columns);
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, $columns);
    }

    /**
     * Find record by id
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->query->find($id, $columns);
    }

    /**
     * Find record by id or fail
     */
    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->query->findOrFail($id, $columns);
    }

    /**
     * Find record by field
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->query->where($field, $value)->first($columns);
    }

    /**
     * Find records by field
     */
    public function findAllBy(string $field, mixed $value, array $columns = ['*']): Collection
    {
        return $this->query->where($field, $value)->get($columns);
    }

    /**
     * Find records where field is in array
     */
    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection
    {
        return $this->query->whereIn($field, $values)->get($columns);
    }

    /**
     * Find records where field is not in array
     */
    public function findWhereNotIn(string $field, array $values, array $columns = ['*']): Collection
    {
        return $this->query->whereNotIn($field, $values)->get($columns);
    }

    /**
     * Create a new record
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record
     */
    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }

    /**
     * Delete a record
     */
    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Delete records by field
     */
    public function deleteBy(string $field, mixed $value): bool
    {
        return $this->query->where($field, $value)->delete();
    }

    /**
     * Count records
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Search records
     */
    public function search(string $query, array $columns = ['*']): Collection
    {
        // This is a basic search implementation
        // Override in specific repositories for advanced search
        $searchableFields = $this->getSearchableFields();

        $queryBuilder = $this->query->where(function ($q) use ($query, $searchableFields) {
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'LIKE', "%{$query}%");
            }
        });

        return $queryBuilder->get($columns);
    }

    /**
     * Get records with relationships
     */
    public function with(array $relations): BaseRepositoryInterface
    {
        $this->query = $this->query->with($relations);
        return $this;
    }

    /**
     * Apply where clause
     */
    public function where(string $field, string $operator = '=', mixed $value = null): BaseRepositoryInterface
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $this->query = $this->query->where($field, $operator, $value);
        return $this;
    }

    /**
     * Apply orderBy clause
     */
    public function orderBy(string $field, string $direction = 'asc'): BaseRepositoryInterface
    {
        $this->query = $this->query->orderBy($field, $direction);
        return $this;
    }

    /**
     * Apply limit clause
     */
    public function limit(int $limit): BaseRepositoryInterface
    {
        $this->query = $this->query->limit($limit);
        return $this;
    }

    /**
     * Apply whereHas clause
     */
    public function whereHas(string $relation, callable $callback): BaseRepositoryInterface
    {
        $this->query = $this->query->whereHas($relation, $callback);
        return $this;
    }

    /**
     * Get fresh instance
     */
    public function fresh(): BaseRepositoryInterface
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    /**
     * Get the model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Get searchable fields for search method
     * Override in specific repositories
     */
    protected function getSearchableFields(): array
    {
        return ['name', 'title', 'description']; // Default searchable fields
    }

    /**
     * Execute query and get results
     */
    public function get(array $columns = ['*']): Collection
    {
        $results = $this->query->get($columns);
        $this->fresh(); // Reset query for next use
        return $results;
    }

    /**
     * Execute query and get first result
     */
    public function first(array $columns = ['*']): ?Model
    {
        $result = $this->query->first($columns);
        $this->fresh(); // Reset query for next use
        return $result;
    }

    /**
     * Bulk insert
     */
    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    /**
     * Bulk update
     */
    public function bulkUpdate(array $data, string $key = 'id'): bool
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $record) {
            if (!isset($record[$key])) {
                continue;
            }

            $id = $record[$key];
            unset($record[$key]);

            $this->model->where($key, $id)->update($record);
        }

        return true;
    }

    /**
     * Get records by multiple conditions
     */
    public function findWhere(array $conditions, array $columns = ['*']): Collection
    {
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $this->query = $this->query->whereIn($field, $value);
            } else {
                $this->query = $this->query->where($field, $value);
            }
        }

        return $this->get($columns);
    }

    /**
     * Check if record exists
     */
    public function exists(int $id): bool
    {
        return $this->query->where('id', $id)->exists();
    }

    /**
     * Get latest records
     */
    public function latest(int $limit = 10, array $columns = ['*']): Collection
    {
        return $this->query->latest()->limit($limit)->get($columns);
    }

    /**
     * Get oldest records
     */
    public function oldest(int $limit = 10, array $columns = ['*']): Collection
    {
        return $this->query->oldest()->limit($limit)->get($columns);
    }
}
