<?php

namespace App\Repositories\Course;

use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
    public function create($attributes)
    {
        return Course::create($attributes);
    }

    public function delete($condition)
    {
        Course::where($condition)->delete();
    }
}
