<?php

namespace App\Models;

use App\Core\BaseModel;

class Activity extends BaseModel
{
    protected $table = 'activities';
    protected $scope_to_tenant = true;
}
