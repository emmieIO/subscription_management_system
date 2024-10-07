<?php

namespace App\Services;


use App\Interfaces\PlanServiceInterface;
use App\Models\Plan;

class PlanService implements PlanServiceInterface
{
    public function create(array $data): Plan
    {
        return Plan::create($data);
    }

    public function update(array $data, int $id): Plan
    {
        $plan = Plan::find($id);
        $plan->update($data);
        return $plan;
    }

    public function delete(int $id): bool
    {
        return Plan::destroy($id);
    }

    public static function  find(int $id): Plan
    {
        return Plan::find($id);
    }

    public function all(): array
    {
        return Plan::where('is_featured', true)->get()->toArray();
    }
}
