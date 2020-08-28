<?php

namespace Grutto\News\Repositories;

use Grutto\News\Models\Tag;

class TagRepository extends MariaDBRepository implements TagRepositoryInterface
{

    public function getModelClass()
    {
        return Tag::class ;
    }

}
