<?php

namespace App\Services;

use App\Repositories\Course\CourseRepositoryInterface;

class CourseService extends Service
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function register($attributes)
    {
        try {
            $newData = $this->courseRepository->create($attributes);
            return $this->jsonResponse(compact('newData'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
        
    }

    public function destroy($id)
    {
        try {
            $this->courseRepository->delete($id);
            return $this->jsonResponse(['message' => 'コースを削除しました。']);
        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }
}
