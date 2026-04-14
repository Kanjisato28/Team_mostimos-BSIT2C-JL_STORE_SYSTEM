<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockAdjustmentModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Inventory extends Controller
{
    public function index()
    {
        $productModel = new ProductModel();
        $data['low_stock'] = $productModel->getLowStockProducts();
        return view('inventory/index', $data);
    }

    public function adjust()
    {
        $productModel    = new ProductModel();
        $adjustmentModel = new StockAdjustmentModel();
        $logModel        = new LogModel();
        $session         = session();

        $productId = $this->request->getPost('product_id');
        $type      = $this->request->getPost('type'); // in, out, adjustment
        $quantity  = intval($this->request->getPost('quantity'));
        $reason    = $this->request->getPost('reason');
        $userId    = $session->get('user_id');

        $product = $productModel->find($productId);
        if (!$product) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product not found.']);
        }

        $previousStock = $product['stock_quantity'];

        if ($type === 'in') {
            $newStock = $previousStock + $quantity;
        } elseif ($type === 'out') {
            if ($quantity > $previousStock) {
                return $this->response->setJSON(['success' => false, 'message' => 'Insufficient stock.']);
            }
            $newStock = $previousStock - $quantity;
        } else {
            // Direct adjustment — set absolute value
            $newStock = $quantity;
        }

        $productModel->update($productId, ['stock_quantity' => $newStock]);

        $adjustmentModel->insert([
            'product_id'     => $productId,
            'user_id'        => $userId,
            'type'           => $type,
            'quantity'       => $quantity,
            'previous_stock' => $previousStock,
            'new_stock'      => $newStock,
            'reason'         => $reason,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        $logModel->addLog('Stock adjusted for: ' . $product['name'] . ' (' . $type . ' ' . $quantity . ')', 'UPDATED');

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Stock adjusted successfully.',
            'new_stock' => $newStock,
        ]);
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model   = new StockAdjustmentModel();

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