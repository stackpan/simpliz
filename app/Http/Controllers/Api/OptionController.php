<?php

namespace App\Http\Controllers\Api;

use App\Data\OptionDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\OptionRequest;
use App\Http\Resources\OptionCollection;
use App\Http\Resources\OptionResource;
use App\Models\Option;
use App\Models\Question;
use App\Services\OptionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class OptionController extends Controller
{
    public function __construct(private readonly OptionService $optionService)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Question $question): OptionCollection
    {
        $this->authorize('view', $question->quiz);

        $options = $this->optionService->getAllByQuestion($question);
        return new OptionCollection($options);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(OptionRequest $request, Question $question): JsonResponse
    {
        $this->authorize('update', $question->quiz);
        $validated = $request->validated();

        $data = new OptionDto($validated['body']);
        $savedOption = $this->optionService->create($question, $data);

        return (new OptionResource($savedOption))
            ->additional([
                'message' => __('message.created')
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(OptionRequest $request, Option $option): OptionResource
    {
        $this->authorize('update', $option->question->quiz);
        $validated = $request->validated();

        $data = new OptionDto($validated['body']);
        $updatedOption = $this->optionService->update($option, $data);

        return (new OptionResource($updatedOption))
            ->additional([
                'message' => __('message.resource_updated', ['resource' => 'Option'])
            ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Option $option): JsonResponse
    {
        $this->authorize('update', $option->question->quiz);

        $optionId = $this->optionService->delete($option);

        return response()->json([
            'message' => __('message.resource_deleted', ['resource' => 'Option']),
            'data' => [
                'optionId' => $optionId,
            ]
        ]);
    }
}
