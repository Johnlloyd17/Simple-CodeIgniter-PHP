<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $DBGroup      = 'default';
    protected $table      = 'users_tbl'; 
    protected $primaryKey = 'id';

    protected $allowedFields = [ 'first_name', 'middle_name', 'last_name', 'age', 'gender', 'birthdate'];
   
}
