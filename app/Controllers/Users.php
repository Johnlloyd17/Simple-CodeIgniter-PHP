<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Users extends BaseController
{
    protected $helpers = ['url', 'form'];
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }
    public function index()
    {
        $model = new UsersModel();
        $data['users'] = $model->findAll(); // Fetch all users

        echo view('admin/users', $data); // Pass users data to the view
    }
    public function add()
    {
        $session = session();
        $fname = $this->request->getPost('firstName');
        $mname = $this->request->getPost('middleName');
        $lname = $this->request->getPost('lastName');
        $age = $this->request->getPost('age');
        $gender = $this->request->getPost('gender');
        $birthdate = $this->request->getPost('birthdate');

        // Check if $birthdate is not null and not an array
        if ($birthdate !== null && !is_array($birthdate)) {
            // Parse the birthdate in the format "dd/mm/yyyy" to "yyyy-mm-dd"
            $dateParts = explode('/', $birthdate);
            // Reformat the date parts to "yyyy-mm-dd"
            $birthdate = implode('-', array_reverse($dateParts));
        } else {
            // Handle the case when $birthdate is null or an array
            $birthdate = null; // Or set it to a default value as needed
        }

        $data = [
            'first_name' => $fname,
            'middle_name' => $mname,
            'last_name' => $lname,
            'age' => $age,
            'gender' => $gender,
            'birthdate' => $birthdate,
        ];

        $usersModel = new UsersModel();

        try {
            $usersModel->insert($data);
            $session->setFlashdata('success_message', 'User added successfully!');
            return redirect()->to(site_url('users'));
        } catch (\Exception $e) {
            $session->setFlashdata('error_message', 'Error adding user: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function getUser($id)
    {
        try {
            $user = $this->usersModel->find($id);
            if ($user) {
                return $this->response->setJSON($user);
            } else {
                return $this->response->setStatusCode(404, 'User not found');
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500, 'Internal Server Error - ' . $e->getMessage());
        }
    }
    public function update()
    {
        $session = session();
        $id = $this->request->getPost('id'); // Assuming the ID is being sent as part of the form data
        $fname = $this->request->getPost('firstName'); // Corrected key names to match form fields
        $mname = $this->request->getPost('middleName');
        $lname = $this->request->getPost('lastName');
        $age = $this->request->getPost('age');
        $gender = $this->request->getPost('gender');
        $birthdate = $this->request->getPost('birthdate');

        // Handle date conversion
        if ($birthdate !== null && !is_array($birthdate)) {
            $dateParts = explode('/', $birthdate);
            $birthdate = implode('-', array_reverse($dateParts));
        } else {
            $birthdate = null;
        }

        $data = [
            'first_name' => $fname,
            'middle_name' => $mname,
            'last_name' => $lname,
            'age' => $age,
            'gender' => $gender,
            'birthdate' => $birthdate,
        ];

        try {
            $this->usersModel->update($id, $data); // Update user data
            $session->setFlashdata('success_message', 'User updated successfully!');
            return redirect()->to(site_url('users'));
        } catch (\Exception $e) {
            $session->setFlashdata('error_message', 'Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function delete($id)
    {
        $session = session();
        try {
            $this->usersModel->delete($id);
            $session->setFlashdata('success_message', 'User deleted successfully!');
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            $session->setFlashdata('error_message', 'Error deleting user: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function deleteAll()
    {
        $session = session();
        $ids = $this->request->getPost('ids');
    
        if (empty($ids)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'No user IDs provided']);
        }
    
        try {
            $this->usersModel->delete($ids); // Delete users with the provided IDs
            $session->setFlashdata('success_message', 'Selected users deleted successfully!');
            return $this->response->setStatusCode(200)->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete users: ' . $e->getMessage()]);
        }
    }
    
    
}
