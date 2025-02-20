<?php

namespace App\Controllers;
use App\Models\AdminModel;

class Login extends BaseController
{
    protected $helpers = ['url', 'form'];

    protected $adminModel;

    public function __construct()
    {
        // Initialize the model in the constructor
        $this->adminModel = new AdminModel();
    }
    public function main()
    {
        $session = session();
        echo view('admin/login');
    }
    
    public function verify()
    {
        $session = session();
        $username = $this->request->getPost('txtusername');
        $password = $this->request->getPost('txtpassword');

        $data = $this->adminModel->where('username', $username)->where('password', $password)->first();
        if($data){
            
            return redirect()->to('/dashboard');
        }else{
            $session->setFlashData('msgnotification', 'Invalid Password, Please try again!');
            return redirect()->to('/admin');
        } 
    }
}
