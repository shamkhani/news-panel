<?php

namespace Grutto\News\Repositories;

use Grutto\News\Models\NewsCategory;

class NewsCategoryRepository extends MariaDBRepository implements NewsCategoryRepositoryInterface
{

    public function getModelClass()
    {
        return NewsCategory::class ;
    }

}
