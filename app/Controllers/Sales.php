<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SalesModel;
use CodeIgniter\Controller;
use App\Models\LogModel;

class Sales extends Controller
{
    public function index(){
        $model = new SalesModel();
        $data['sales'] = $model->findAll();
        return view('sales/index', $data);
    }

    public function save(){
        $sale_date = $this->request->getPost('sale_date');
        $total_amount = $this->request->getPost('total_amount');
       
        $userModel = new \App\Models\SalesModel();
        $logModel = new LogModel();

        $data = [
            'sale_date'       => $sale_date,
            'total_amount'       => $total_amount,
           
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Profiling has been added: ' . $sale_date, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save Profiling']);
        }
    }

    public function update(){
        $model = new SalesModel();
        $logModel = new LogModel();
        $userId = $this->request->getPost('id');
        $sale_date = $this->request->getPost('sale_date');
        $total_amount = $this->request->getPost('total_amount');
       

        $userData = [
            'sale_date'       => $sale_date,
            'total_amount'      => $total_amount
            
        ];

        $updated = $model->update($userId, $userData);

        if ($updated) {
            $logModel->addLog('New Profiling has been apdated: ' . $sale_date, 'UPDATED');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profiling updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating Profiling.'
            ]);
        }
    }

    public function edit($id){
        $model = new SalesModel();
    $user = $model->find($id); // Fetch user by ID

    if ($user) {
        return $this->response->setJSON(['data' => $user]); // Return user data as JSON
    } else {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
    }
}

public function delete($id){
    $model = new SalesModel();
    $logModel = new LogModel();
    $user = $model->find($id);
    if (!$user) {
        return $this->response->setJSON(['success' => false, 'message' => 'Profiling not found.']);
    }

    $deleted = $model->delete($id);

    if ($deleted) {
        $logModel->addLog('Delete Profiling', 'DELETED');
        return $this->response->setJSON(['success' => true, 'message' => 'Profiling deleted successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete Profiling.']);
    }
}

public function fetchRecords()
{
    $request = service('request');
    $model = new \App\Models\SalesModel();

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