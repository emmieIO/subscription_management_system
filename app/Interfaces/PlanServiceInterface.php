<?php

namespace App\Interfaces;

use App\Models\Plan;

interface PlanServiceInterface{
    public function create(array $data): Plan;
    public function update(array $data, int $id): Plan;
    public function delete(int $id): bool;
    public static function find(int $id): Plan;
    public function all(): array;
}
