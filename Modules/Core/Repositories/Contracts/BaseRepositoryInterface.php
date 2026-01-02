<?php

namespace Modules\Core\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get all records
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Find record by id
     */
    public function find(int $id, array $columns = ['*']): ?Model;

    /**
     * Find record by id or fail
     */
    public function findOrFail(int $id, array $columns = ['*']): Model;

    /**
     * Find record by field
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model;

    /**
     * Find records by field
     */
    public function findAllBy(string $field, mixed $value, array $columns = ['*']): Collection;

    /**
     * Find records where field is in array
     */
    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection;

    /**
     * Find records where field is not in array
     */
    public function findWhereNotIn(string $field, array $values, array $columns = ['*']): Collection;

    /**
     * Create a new record
     */
    public function create(array $data): Model;

    /**
     * Update a record
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a record
     */
    public function delete(int $id): bool;

    /**
     * Delete records by field
     */
    public function deleteBy(string $field, mixed $value): bool;

    /**
     * Count records
     */
    public function count(): int;

    /**
     * Search records
     */
    public function search(string $query, array $columns = ['*']): Collection;

    /**
     * Get records with relationships
     */
    public function with(array $relations): BaseRepositoryInterface;

    /**
     * Apply where clause
     */
    public function where(string $field, string $operator = '=', mixed $value = null): BaseRepositoryInterface;

    /**
     * Apply orderBy clause
     */
    public function orderBy(string $field, string $direction = 'asc'): BaseRepositoryInterface;

    /**
     * Apply limit clause
     */
    public function limit(int $limit): BaseRepositoryInterface;

    /**
     * Get fresh instance
     */
    public function fresh(): BaseRepositoryInterface;

    /**
     * Get the model instance
     */
    public function getModel(): Model;
}
