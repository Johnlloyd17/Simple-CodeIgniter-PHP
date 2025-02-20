<?php

namespace App\Controllers;
use App\Models\AdminModel;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'form'];

    public function index()
    {
        echo view('admin/dashboard');
    }
    
}
