<?php

namespace App\Repositories\Course;

interface CourseRepositoryInterface
{
    public function create($attributes);
    public function delete($condition);
}
