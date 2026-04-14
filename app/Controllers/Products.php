<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\SupplierModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Products extends Controller
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $supplierModel = new SupplierModel();

        $data['categories'] = $categoryModel->findAll();
        $data['suppliers']  = $supplierModel->findAll();

        return view('products/index', $data);
    }

    public function save()
    {
        $model    = new ProductModel();
        $logModel = new LogModel();

        $sku = $this->request->getPost('sku');

        // Check SKU uniqueness
        if (!empty($sku) && $model->where('sku', $sku)->first()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'SKU already exists.']);
        }

        $data = [
            'category_id'   => $this->request->getPost('category_id') ?: null,
            'supplier_id'   => $this->request->getPost('supplier_id') ?: null,
            'sku'           => $sku ?: null,
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'unit'          => $this->request->getPost('unit') ?: 'pcs',
            'cost_price'    => $this->request->getPost('cost_price') ?: 0,
            'selling_price' => $this->request->getPost('selling_price'),
            'stock_quantity'=> $this->request->getPost('stock_quantity') ?: 0,
            'reorder_level' => $this->request->getPost('reorder_level') ?: 5,
        ];

        if ($model->insert($data)) {
            $logModel->addLog('Product added: ' . $data['name'], 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save product.']);
    }

    public function edit($id)
    {
        $model  = new ProductModel();
        $record = $model->find($id);

        if ($record) {
            return $this->response->setJSON(['data' => $record]);
        }

        return $this->response->setStatusCode(404)->setJSON(['error' => 'Not found']);
    }

    public function update()
    {
        $model    = new ProductModel();
        $logModel = new LogModel();
        $id       = $this->request->getPost('id');
        $sku      = $this->request->getPost('sku');

        // Check SKU uniqueness excluding self
        if (!empty($sku) && $model->where('sku', $sku)->where('id !=', $id)->first()) {
            return $this->response->setJSON(['success' => false, 'message' => 'SKU already exists.']);
        }

        $data = [
            'category_id'   => $this->request->getPost('category_id') ?: null,
            'supplier_id'   => $this->request->getPost('supplier_id') ?: null,
            'sku'           => $sku ?: null,
            'name'          => $this->request->getPost('name'),
            'description'   => $this->request->getPost('description'),
            'unit'          => $this->request->getPost('unit') ?: 'pcs',
            'cost_price'    => $this->request->getPost('cost_price') ?: 0,
            'selling_price' => $this->request->getPost('selling_price'),
            'stock_quantity'=> $this->request->getPost('stock_quantity') ?: 0,
            'reorder_level' => $this->request->getPost('reorder_level') ?: 5,
        ];

        if ($model->update($id, $data)) {
            $logModel->addLog('Product updated: ' . $data['name'], 'UPDATED');
            return $this->response->setJSON(['success' => true, 'message' => 'Product updated successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Error updating product.']);
    }

    public function delete($id)
    {
        $model    = new ProductModel();
        $logModel = new LogModel();
        $record   = $model->find($id);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found.']);
        }

        if ($model->delete($id)) {
            $logModel->addLog('Product deleted: ' . $record['name'], 'DELETED');
            return $this->response->setJSON(['success' => true, 'message' => 'Product deleted successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete product.']);
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model   = new ProductModel();

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

    public function search()
    {
        $keyword = $this->request->getGet('q') ?? '';
        $model   = new ProductModel();

        return $this->response->setJSON($model->searchProducts($keyword));
    }
}