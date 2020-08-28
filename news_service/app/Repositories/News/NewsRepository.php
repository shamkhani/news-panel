<?php

namespace Grutto\News\Repositories;

use Grutto\News\Models\News;

class NewsRepository extends MariaDBRepository implements NewsRepositoryInterface
{

    public function getModelClass()
    {
        return News::class ;
    }

}
