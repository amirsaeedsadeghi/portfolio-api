<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\NavbarItemFilter;
use App\Http\Requests\V1\NavbarItem\StoreNavbarItemRequest;
use App\Http\Requests\V1\NavbarItem\UpdateNavbarItemRequest;
use App\Http\Resources\NavbarItemResource;
use App\Services\NavbarItemService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Navbar Items
 *
 * Manage navigation items used in the portfolio header.
 */
class NavbarItemController extends Controller
{
    use ApiResponse;

    public function __construct(protected NavbarItemService $service) {}

    /**
     * Get navbar items
     *
     * Returns the list of navbar items (ordered).
     *
     * @unauthenticated 
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "OK",
     *   "data": [
     *     {
     *       "type": "NavbarItems",
     *       "id": 1,
     *       "label": "Home",
     *       "link": "/",
     *       "order": 1,
     *       "links": {
     *         "self": "/api/v1/navbar-items/1"
     *       }
     *     }
     *   ],
     *   "meta": null,
     *   "links": null
     * }
     */
    public function index(NavbarItemFilter $filters): JsonResponse
    {
        $navbar = $this->service->all($filters);
        return $this->success(NavbarItemResource::collection($navbar));
    }

    /**
     * Create navbar item
     *
     * Stores a new navbar item.
     *
     * @bodyParam label string required Visible text. Example: Home
     * @bodyParam link string required URL or hash. Example: /#projects
     * @bodyParam order integer required Display order. Example: 1
     *
     * @response 201 {
     *   "status": true,
     *   "message": "Navbar Item created successfully.",
     *   "data": {
     *     "type": "NavbarItems",
     *     "id": 5,
     *     "label": "Blog",
     *     "link": "/#blog",
     *     "order": 5,
     *     "links": {
     *       "self": "/api/v1/navbar-items/5"
     *     }
     *   }
     * }
     */
    public function store(StoreNavbarItemRequest $request): JsonResponse
    {
        $navbar = $this->service->create($request->validated());
        return $this->success(new NavbarItemResource($navbar), 'Navbar Item created successfully.', 201);
    }

    /**
     * Get one navbar item
     *
     * Returns a navbar item by ID.
     *
     * @urlParam id integer required Navbar item ID. Example: 2
     *
     * @response 200 {
     *   "status": true,
     *   "message": "OK",
     *   "data": {
     *     "type": "NavbarItems",
     *     "id": 2,
     *     "label": "About",
     *     "link": "/#about",
     *     "order": 2,
     *     "links": {
     *       "self": "/api/v1/navbar-items/2"
     *     }
     *   }
     * }
     */
    public function show(string $id): JsonResponse
    {
        $navbar = $this->service->findById($id);
        return $this->success(new NavbarItemResource($navbar));
    }

    /**
     * Update navbar item
     *
     * Updates the specified navbar item.
     *
     * @urlParam id integer required Navbar item ID. Example: 3
     * @bodyParam label string optional Visible text. Example: Projects
     * @bodyParam link string optional URL or hash. Example: /#projects
     * @bodyParam order integer optional Display order. Example: 3
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Navbar Item updated successfully.",
     *   "data": {
     *     "type": "NavbarItems",
     *     "id": 3,
     *     "label": "Projects",
     *     "link": "/#projects",
     *     "order": 3,
     *     "links": {
     *       "self": "/api/v1/navbar-items/3"
     *     }
     *   }
     * }
     */
    public function update(UpdateNavbarItemRequest $request, string $id): JsonResponse
    {
        $navbar = $this->service->update($id, $request->validated());
        return $this->success(new NavbarItemResource($navbar), 'Navbar Item updated successfully.');
    }

    /**
     * Delete navbar item
     *
     * Removes a navbar item by ID.
     *
     * @urlParam id integer required Navbar item ID. Example: 4
     *
     * @response 204 {}
     */
    public function destroy(string $id): Response
    {
        $this->service->delete($id);
        return $this->noContent();
    }
}
