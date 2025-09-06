<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\ContactMeFilter;
use App\Http\Requests\V1\ContactMe\StoreContactMeRequest;
use App\Http\Resources\ContactMeResource;
use App\Services\ContactMeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Contact Me
 *
 * Manage contact messages submitted through the portfolio.
 */
class ContactMeController extends Controller
{
    use ApiResponse;

    public function __construct(protected ContactMeService $contactMeService) {}

    /**
     * Get all contact messages
     *
     * Returns a paginated list of contact messages.
     *
     * @response 200 {
     *  "status": true,
     *  "message": "OK",
     *  "data": [
     *    {
     *      "type": "contactMe",
     *      "id": 1,
     *      "name": "Roham Tehrani",
     *      "email": "rohamtehrani.business@gmail.com",
     *      "messageBody": "This is first message that I created.",
     *      "links": { "self": "/api/v1/contact-me/1" }
     *    }
     *  ],
     *  "meta": { ... },
     *  "links": { ... }
     * }
     */
    public function index(ContactMeFilter $filter): JsonResponse
    {
        $contacts = $this->contactMeService->paginate($filter);
        return $this->success(ContactMeResource::collection($contacts));
    }

    /**
     * Submit a contact message
     *
     * Stores a new contact message.
     *
     * @bodyParam name string required Sender name. Example: Roham Tehrani
     * @bodyParam email string required Sender email. Example: rohamtehrani.business@gmail.com
     * @bodyParam messageBody string required Message body. Example: This is first message that I created.
     *
     * @response 201 {
     *  "status": true,
     *  "message": "Your message submitted successfully.",
     *  "data": {
     *    "type": "contactMe",
     *    "id": 6,
     *    "name": "Roham Tehrani",
     *    "email": "rohamtehrani.business@gmail.com",
     *    "messageBody": "This is first message that I created.",
     *    "links": { "self": "/api/v1/contact-me/6" }
     *  }
     * }
     */
    public function store(StoreContactMeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $contactMe = $this->contactMeService->create($data);
        return $this->success(new ContactMeResource($contactMe), 'Your message submitted successfully.', 201);
    }

    /**
     * Get a single contact message
     *
     * Returns the details of a contact message.
     *
     * @urlParam id integer required The ID of the contact message. Example: 1
     *
     * @response 200 {
     *  "status": true,
     *  "message": "OK",
     *  "data": {
     *    "type": "contactMe",
     *    "id": 1,
     *    "name": "Roham Tehrani",
     *    "email": "rohamtehrani.business@gmail.com",
     *    "messageBody": "This is first message that I created.",
     *    "links": { "self": "/api/v1/contact-me/1" }
     *  }
     * }
     */
    public function show(string $id): JsonResponse
    {
        $contactMe = $this->contactMeService->findById($id);
        return $this->success(new ContactMeResource($contactMe));
    }

    /**
     * Delete a contact message
     *
     * Removes a contact message by ID.
     *
     * @urlParam id integer required The ID of the contact message. Example: 1
     *
     * @response 204 {}
     */
    public function destroy(string $id): Response
    {
        $this->contactMeService->deleteById($id);
        return $this->noContent();
    }
}
