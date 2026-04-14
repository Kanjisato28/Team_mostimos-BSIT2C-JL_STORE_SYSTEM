<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Customers extends Controller
{
    public function index()
    {
        return view('customers/index');
    }

    public function save()
    {
        $model    = new CustomerModel();
        $logModel = new LogModel();

        $data = [
            'name'    => $this->request->getPost('name'),
            'phone'   => $this->request->getPost('phone'),
            'email'   => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
        ];

        if ($model->insert($data)) {
            $logModel->addLog('Customer added: ' . $data['name'], 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save customer.']);
    }

    public function edit($id)
    {
        $model  = new CustomerModel();
        $record = $model->find($id);

        if ($record) {
            return $this->response->setJSON(['data' => $record]);
        }

        return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
    }

    public function update()
    {
        $model    = new CustomerModel();
        $logModel = new LogModel();
        $id       = $this->request->getPost('id');

        $data = [
            'name'    => $this->request->getPost('name'),
            'phone'   => $this->request->getPost('phone'),
            'email'   => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
        ];

        if ($model->update($id, $data)) {
            $logModel->addLog('Customer updated: ' . $data['name'], 'UPDATED');
            return $this->response->setJSON(['success' => true, 'message' => 'Customer updated successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Error updating customer.']);
    }

    public function delete($id)
    {
        $model    = new CustomerModel();
        $logModel = new LogModel();
        $record   = $model->find($id);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Customer not found.']);
        }

        if ($model->delete($id)) {
            $logModel->addLog('Customer deleted: ' . $record['name'], 'DELETED');
            return $this->response->setJSON(['success' => true, 'message' => 'Customer deleted successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete customer.']);
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model   = new CustomerModel();

        $start       = $request->getPost('start') ?? 0;
        $length      = $request->getPost('length') ?? 10;
        $searchValue = $request->getPost('search')['value'] ?? '';

        $totalRecords = $model->countAll();
        $result       = $model->getRecords($start, $length, $searchValue);

        $data    = [];
        $counter = $start + 1;
        foreach ($result['data'] as $row) {
            $row['row_number'] = $counter++;
            $data[]            = $row;
        }

        return $this->response->setJSON([
            'draw'            => intval($request->getPost('draw')),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $result['filtered'],
            'data'            => $data,
        ]);
    }
}