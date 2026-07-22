<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leads\ListLeadsRequest;
use App\Http\Requests\Leads\StoreLeadRequest;
use App\Http\Requests\Leads\UpdateLeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class LeadController extends Controller
{
    public function __construct(private readonly LeadService $leadService) {}

    public function index(ListLeadsRequest $request): AnonymousResourceCollection
    {
        return LeadResource::collection($this->leadService->paginate($request->validated()));
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        return (new LeadResource($this->leadService->create($request->validated())))->response()->setStatusCode(201);
    }

    public function show(Lead $lead): LeadResource
    {
        return new LeadResource($lead->load('assignedUser'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead): LeadResource
    {
        return new LeadResource($this->leadService->update($lead, $request->validated()));
    }

    public function destroy(Lead $lead): Response
    {
        $lead->delete();

        return response()->noContent();
    }
}
