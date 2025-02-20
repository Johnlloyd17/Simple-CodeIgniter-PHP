<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $DBGroup      = 'default';
    protected $table      = 'admin_tbl';
    protected $primaryKey = 'id';

    protected $allowedFields =
    [
        'username',
        'password'
    ];
}
