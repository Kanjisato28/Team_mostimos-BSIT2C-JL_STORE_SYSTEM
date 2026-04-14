<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Categories extends Controller
{
    public function index()
    {
        return view('categories/index');
    }

    public function save()
    {
        $model    = new CategoryModel();
        $logModel = new LogModel();

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($model->insert($data)) {
            $logModel->addLog('Category added: ' . $data['name'], 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save category.']);
    }

    public function edit($id)
    {
        $model  = new CategoryModel();
        $record = $model->find($id);

        if ($record) {
            return $this->response->setJSON(['data' => $record]);
        }

        return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
    }

    public function update()
    {
        $model    = new CategoryModel();
        $logModel = new LogModel();
        $id       = $this->request->getPost('id');

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($model->update($id, $data)) {
            $logModel->addLog('Category updated: ' . $data['name'], 'UPDATED');
            return $this->response->setJSON(['success' => true, 'message' => 'Category updated successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Error updating category.']);
    }

    public function delete($id)
    {
        $model    = new CategoryModel();
        $logModel = new LogModel();
        $record   = $model->find($id);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Category not found.']);
        }

        if ($model->delete($id)) {
            $logModel->addLog('Category deleted: ' . $record['name'], 'DELETED');
            return $this->response->setJSON(['success' => true, 'message' => 'Category deleted successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete category.']);
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model   = new CategoryModel();

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