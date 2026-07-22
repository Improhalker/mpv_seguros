<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leads\StoreLeadInteractionRequest;
use App\Http\Requests\Leads\UpdateLeadInteractionRequest;
use App\Http\Resources\LeadInteractionResource;
use App\Models\Lead;
use App\Models\LeadInteraction;
use App\Services\LeadInteractionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class LeadInteractionController extends Controller
{
    public function __construct(private readonly LeadInteractionService $leadInteractionService) {}

    public function index(Lead $lead): AnonymousResourceCollection
    {
        return LeadInteractionResource::collection($lead->interactions()->latest('occurred_at')->paginate(20));
    }

    public function store(StoreLeadInteractionRequest $request, Lead $lead): JsonResponse
    {
        return (new LeadInteractionResource($this->leadInteractionService->create($lead, $request->validated())))->response()->setStatusCode(201);
    }

    public function update(UpdateLeadInteractionRequest $request, Lead $lead, LeadInteraction $interaction): LeadInteractionResource
    {
        return new LeadInteractionResource($this->leadInteractionService->update($lead, $this->interactionForLead($lead, $interaction), $request->validated()));
    }

    public function destroy(Lead $lead, LeadInteraction $interaction): Response
    {
        $this->leadInteractionService->delete($lead, $this->interactionForLead($lead, $interaction));

        return response()->noContent();
    }

    private function interactionForLead(Lead $lead, LeadInteraction $interaction): LeadInteraction
    {
        abort_unless($interaction->lead_id === $lead->id, 404);

        return $interaction;
    }
}
