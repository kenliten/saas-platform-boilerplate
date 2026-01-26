<?php

namespace App\Models;

use App\Core\BaseModel;

class Account extends BaseModel
{
    protected $table = 'accounts';

    public function __construct()
    {
        parent::__construct();
    }
}
