<?php

namespace Modules\Project\Repositories;

use Modules\Project\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Modules\Project\Repositories\Contracts\ProjectRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Carbon\Carbon;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    public function getModelClass(): String
    {
        return Project::class;
    }

    /**
     * Get active project
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getActiveProject($customerId = null)
    {
        $query = $this->model->with([
            'customer',
            'projectServices.service',
            'projectServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('projectServices', function ($q) {
            $q->where('status', true)
                ->whereDate('end_date', '>', Carbon::now()->toDateString());
        })->get();
    }

    public function getAllProject(): Collection
    {
        return $this->model->get();
    }

    /**
     * Get expiring project
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getExpiringProject($customerId = null)
    {
        $now = Carbon::now()->toDateString();
        $expiredDate = Carbon::now()->addDays(7)->toDateString();
        $query = $this->model->with([
            'customer',
            'projectServices.service',
            'projectServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('projectServices', function ($q) use ($now, $expiredDate) {
            $q->where('status', true)
                ->whereDate('end_date', '>', $now)
                ->whereDate('end_date', '<=', $expiredDate);
        })->get();
    }

    /**
     * Get expired project
     *
     * @param int|null $customerId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getExpiredProject($customerId = null)
    {
        $now = Carbon::now()->toDateString();
        $query = $this->model->with([
            'customer',
            'projectServices.service',
            'projectServices.service.category',
        ]);

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->whereHas('projectServices', function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->where('status', false)
                    ->orWhereDate('end_date', '<', $now);
            });
        })->get();
    }

    public function createProject(array $data)
    {
        return $this->model->create($data);
    }

    public function findProjectService(int $projectServiceId)
    {
        return $this->model->projectServices()->where('id', $projectServiceId)->first();
    }

    public function updateProjectService(int $projectServiceId, array $data)
    {
        $projectService = $this->findProjectService($projectServiceId);
        if ($projectService) {
            $projectService->update($data);
            return $projectService;
        }
        return false;
    }
}
