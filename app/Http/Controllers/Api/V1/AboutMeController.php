<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ImageService;
use App\Services\AboutMeService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AboutMeResource;
use App\Http\Requests\V1\AboutMe\StoreAboutMeRequest;
use App\Http\Requests\V1\AboutMe\UpdateAboutMeRequest;

/**
 * @group About Me
 *
 * Manage your About Me information in the portfolio.
 */
class AboutMeController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected AboutMeService $aboutMeService,
        protected ImageService $imageService
    ) {}

    /** 
     * Get About Me content
     * 
     * Returns the stored information about the user.
     * 
     * @unauthenticated 
     * 
     * @response 200 {
     *  "status": true,
     *  "message": "OK",
     *  "data": {
     *      "id": 1,
     *      "title": "Full Stack Developer",
     *      "summary": "Experienced developer...",
     *      "portfolioImage": "/storage/portfolio/abc123.jpg"
     *      ...
     *  }
     * }
     */
    public function index(): JsonResponse
    {
        $aboutMe = $this->aboutMeService->find();
        return $this->success(new AboutMeResource($aboutMe));
    }

    /**
     * Create About Me information
     *
     * Stores a new About Me record (only one is allowed).
     *
     * @authenticated
     *
     * @bodyParam title string required Professional title. Example: Full Stack Developer
     * @bodyParam summary string required Short summary about yourself. Example: Experienced Laravel + React developer.
     * @bodyParam location string required Your current location. Example: Dublin, Ireland
     * @bodyParam yearsOfExperience integer required Number of years of professional experience. Example: 5
     * @bodyParam language string required Languages you speak. Example: English, Persian
     * @bodyParam currentlyLearning string optional Technologies or skills you're currently learning. Example: Rust, AWS
     * @bodyParam availability string optional Availability status. Example: Open to work
     * @bodyParam cvUrl string optional Link to your CV or resume. Example: https://example.com/cv.pdf
     * @bodyParam linkedinUrl string optional Link to your LinkedIn profile. Example: https://linkedin.com/in/yourname
     * @bodyParam githubUrl string optional Link to your GitHub profile. Example: https://github.com/yourusername
     * @bodyParam portfolioImage file optional Portfolio image file.
     *
     * @response 201 {
     *  "status": true,
     *  "message": "About Me created successfully",
     *  "data": {
     *    "id": 1,
     *    "title": "Full Stack Developer",
     *    "summary": "...",
     *    "location": "...",
     *    "yearsOfExperience": 5,
     *    "language": "...",
     *    ...
     *  }
     * }
     */
    public function store(StoreAboutMeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['portfolio_image'] = $this->handelImageUpload($request);
        return $this->saveAndRespond($data);
    }

    /**
     * Update About Me information
     *
     * Updates the existing About Me record.
     *
     * @authenticated
     *
     * @bodyParam title string Professional title. Example: Full Stack Developer
     * @bodyParam summary string Short summary about yourself. Example: Experienced Laravel + React developer.
     * @bodyParam location string Your current location. Example: Dublin, Ireland
     * @bodyParam yearsOfExperience integer Number of years of professional experience. Example: 5
     * @bodyParam language string Languages you speak. Example: English, Persian
     * @bodyParam currentlyLearning string Technologies or skills you're currently learning. Example: Rust, AWS
     * @bodyParam availability string Availability status. Example: Open to work
     * @bodyParam cvUrl string Link to your CV or resume. Example: https://example.com/cv.pdf
     * @bodyParam linkedinUrl string Link to your LinkedIn profile. Example: https://linkedin.com/in/yourname
     * @bodyParam githubUrl string Link to your GitHub profile. Example: https://github.com/yourusername
     * @bodyParam portfolioImage file Portfolio image file.
     *
     * @response 200 {
     *  "status": true,
     *  "message": "About Me updated successfully",
     *  "data": {
     *    ...
     *  }
     * }
     */
    public function update(UpdateAboutMeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $aboutMe = $this->aboutMeService->find();
        $data['portfolio_image'] = $this->imageService->handleImageUpload(
            $request->file('portfolioImage'),
            config('paths.portfolio_images'),
            $aboutMe->portfolio_image
        );
        return $this->saveAndRespond($data);
    }

    /**
     * Delete About Me information
     *
     * Removes the About Me record from the system.
     *
     * @authenticated
     *
     * @response 204 {}
     * @response 403 {
     *  "message": "This action is unauthorized."
     * }
     */
    public function destroy(): Response
    {
        $this->aboutMeService->delete();
        return $this->noContent();
    }

    /**
     * Save About Me data and return response.
     *
     * Internal helper method that determines whether to create or update,
     * and returns an appropriate success response.
     *
     * @param array $data The validated data including image path.
     * @return JsonResponse
     */
    private function saveAndRespond(array $data): JsonResponse
    {
        $status = $this->aboutMeService->find() ? 200 : 201;
        $message = $status === 200 ? 'About Me updated successfully' : 'About Me created successfully';
        $aboutMe = $this->aboutMeService->save($data);
        return $this->success(new AboutMeResource($aboutMe), $message, $status);
    }
}
