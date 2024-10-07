<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plans\PlanRequest;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Services\PlanService;

class PlanController extends Controller
{
    protected PlanService $planService;
    public function __construct(PlanService $planService){
        $this->planService = $planService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->response_success("Plans fetched successfully", $this->planService->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request)
    {
        $data = $request->validated();
        $plan = $this->planService->create($data);
        return response()->response_success("Plan created successfully", $plan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return response()->response_success("Plan fetched successfully", $plan);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanRequest $request, Plan $plan)
    {
        $data = $request->validated();
        $plan = $this->planService->update($data, $plan->id);
        return response()->response_success("Plan updated successfully", $plan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $this->planService->delete($plan->id);
        return response()->response_success("Plan deleted successfully");
    }
}
