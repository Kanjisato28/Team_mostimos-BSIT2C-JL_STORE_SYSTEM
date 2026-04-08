<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SuppliersModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Suppliers extends Controller
{
    public function index(){
        $model = new SuppliersModel();
        $data['suppliers'] = $model->findAll();
        return view('suppliers/index', $data);
    }

    public function save(){
        $supplier_name = $this->request->getPost('supplier_name');
        $contact_number = $this->request->getPost('contact_number');
        $address = $this->request->getPost('address');

        $userModel = new \App\Models\SuppliersModel();
        $logModel = new LogModel();

        $data = [
            'supplier_name'       => $supplier_name,
            'contact_number'  => $contact_number,
            'address'    => $address
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Supplier has been added: ' . $supplier_name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save Supplier']);
        }
    }

    public function update(){
        $model = new SuppliersModel();
        $logModel = new LogModel();
        $userId = $this->request->getPost('id');
        $supplier_name = $this->request->getPost('supplier_name');
        $contact_number = $this->request->getPost('contact_number');
        $address = $this->request->getPost('address');

        $userData = [
            'supplier_name'       => $supplier_name,
            'contact_number'       => $contact_number,
            'address'    => $address
        ];

        $updated = $model->update($userId, $userData);

        if ($updated) {
            $logModel->addLog('New Supplier has been updated: ' . $supplier_name, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Supplier updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating Supplier.'
            ]);
        }
    }

    public function edit($id){
        $model = new SuppliersModel();
    $user = $model->find($id); // Fetch user by ID

    if ($user) {
        return $this->response->setJSON(['data' => $user]); // Return user data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Suplier not found']);
    }
}

public function delete($id){
    $model = new SuppliersModel();
    $logModel = new LogModel();
    $user = $model->find($id);
    if (!$user) {
        return $this->response->setJSON(['success' => false, 'message' => 'Supplier not found.']);
    }

    $deleted = $model->delete($id);

    if ($deleted) {
        $logModel->addLog('Delete Supplier', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Supplier deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Supplier.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\SuppliersModel();

    $start = $request->getPost('start') ?? 0;
    $length = $request->getPost('length') ?? 10;
    $searchValue = $request->getPost('search')['value'] ?? '';

    $totalRecords = $model->countAll();
    $result = $model->getRecords($start, $length, $searchValue);

    $data = [];
    $counter = $start + 1;
    foreach ($result['data'] as $row) {
        $row['row_number'] = $counter++;
        $data[] = $row;
    }

    return $this->response->setJSON([
        'draw' => intval($request->getPost('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $result['filtered'],
        'data' => $data,
    ]);
}

}