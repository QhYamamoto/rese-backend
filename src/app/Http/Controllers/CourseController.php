<?php

namespace App\Http\Controllers;

use App\Services\CourseService;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function register(CourseRequest $request)
    {
        $course = $request->only(['shop_id', 'name', 'price', 'description']);

        return $this->courseService->register($course);
    }

    public function destroy($id)
    {
        return $this->courseService->destroy(compact('id'));
    }
}
