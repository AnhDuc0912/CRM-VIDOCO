<?php

namespace Modules\Work\Repositories;

use Modules\Work\Models\Work;
use Modules\Work\Repositories\Contracts\WorkRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Carbon\Carbon;

class WorkRepository extends BaseRepository implements WorkRepositoryInterface
{
    public function getModelClass(): String
    {
        return Work::class;
    }

    /**
     * Get active work
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getActiveWork($customerId = null)
    {
        $query = $this->model->with([
            'customer',
            'workServices.service',
            'workServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('workServices', function ($q) {
            $q->where('status', true)
                ->whereDate('end_date', '>', Carbon::now()->toDateString());
        })->get();
    }

    /**
     * Get expiring work
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getExpiringWork($customerId = null)
    {
        $now = Carbon::now()->toDateString();
        $expiredDate = Carbon::now()->addDays(7)->toDateString();
        $query = $this->model->with([
            'customer',
            'workServices.service',
            'workServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('workServices', function ($q) use ($now, $expiredDate) {
            $q->where('status', true)
                ->whereDate('end_date', '>', $now)
                ->whereDate('end_date', '<=', $expiredDate);
        })->get();
    }

    /**
     * Get expired work
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getExpiredWork($customerId = null)
    {
        $now = Carbon::now()->toDateString();
        $query = $this->model->with([
            'customer',
            'workServices.service',
            'workServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('workServices', function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->where('status', false)
                    ->orWhereDate('end_date', '<', $now);
            });
        })->get();
    }

    public function createWork(array $data)
    {
        return $this->model->create($data);
    }

    public function findWorkService(int $workServiceId)
    {
        return $this->model->workServices()->where('id', $workServiceId)->first();
    }

    public function updateWorkService(int $workServiceId, array $data)
    {
        $workService = $this->findWorkService($workServiceId);
        if ($workService) {
            $workService->update($data);
            return $workService;
        }
        return false;
    }
}
