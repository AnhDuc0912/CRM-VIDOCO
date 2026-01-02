<?php

namespace Modules\Order\Repositories\Contracts;

interface OrderServiceRepositoryInterface
{
    /**
     * Find order service by ID
     */
    public function findById($id);

    /**
     * Create new order service
     */
    public function create(array $data);

    /**
     * Update order service
     */
    public function update($id, array $data);

    /**
     * Delete order service
     */
    public function delete($id);
}