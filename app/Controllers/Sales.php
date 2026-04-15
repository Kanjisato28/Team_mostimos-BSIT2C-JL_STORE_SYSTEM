<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\StockAdjustmentModel;
use App\Models\LogModel;
use CodeIgniter\Controller;

class Sales extends Controller
{
    public function index()
    {
        return view('sales/index');
    }

    public function create()
    {
        $customerModel = new CustomerModel();
        $data['customers'] = $customerModel->orderBy('name', 'ASC')->findAll();
        return view('sales/create', $data);
    }

    public function store()
    {
        $saleModel       = new SaleModel();
        $saleItemModel   = new SaleItemModel();
        $productModel    = new ProductModel();
        $adjustmentModel = new StockAdjustmentModel();
        $logModel        = new LogModel();

        $cartJson = $this->request->getPost('cart');
        $cart     = json_decode($cartJson, true);

        if (empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cart is empty.']);
        }

        $session    = session();
        $userId     = $session->get('user_id');
        $customerId = $this->request->getPost('customer_id') ?: null;
        $discount   = floatval($this->request->getPost('discount') ?? 0);
        $tax        = floatval($this->request->getPost('tax') ?? 0);
        $payment    = $this->request->getPost('payment_method') ?: 'cash';
        $amountPaid = floatval($this->request->getPost('amount_paid') ?? 0);
        $notes      = $this->request->getPost('notes') ?? '';

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += floatval($item['subtotal']);
        }
        $totalAmount  = $subtotal - $discount + $tax;
        $changeAmount = $amountPaid - $totalAmount;

        $db = \Config\Database::connect();
        $db->transStart();

        // Create sale record
        $invoiceNo = $saleModel->generateInvoiceNo();
        $saleId    = $saleModel->insert([
            'invoice_no'     => $invoiceNo,
            'customer_id'    => $customerId,
            'user_id'        => $userId,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'tax'            => $tax,
            'total_amount'   => $totalAmount,
            'payment_method' => $payment,
            'amount_paid'    => $amountPaid,
            'change_amount'  => max(0, $changeAmount),
            'status'         => 'completed',
            'sale_date'      => date('Y-m-d'),
            'notes'          => $notes,
        ]);

        // Insert sale items and deduct stock
        foreach ($cart as $item) {
            $product = $productModel->find($item['product_id']);
            if (!$product) continue;

            $saleItemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal'   => $item['subtotal'],
            ]);

            $previousStock = $product['stock_quantity'];
            $newStock      = $previousStock - intval($item['quantity']);

            $productModel->update($item['product_id'], ['stock_quantity' => $newStock]);

            $adjustmentModel->insert([
                'product_id'     => $item['product_id'],
                'user_id'        => $userId,
                'type'           => 'out',
                'quantity'       => $item['quantity'],
                'previous_stock' => $previousStock,
                'new_stock'      => $newStock,
                'reason'         => 'Sale: ' . $invoiceNo,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        $db->transComplete();

        if ($db->transStatus()) {
            $logModel->addLog('Sale created: ' . $invoiceNo, 'ADD');
            return $this->response->setJSON([
                'success'    => true,
                'message'    => 'Sale completed successfully.',
                'invoice_no' => $invoiceNo,
                'sale_id'    => $saleId,
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Transaction failed. Please try again.']);
    }

    public function view($id)
    {
        $saleModel = new SaleModel();
        $sale      = $saleModel->getSaleWithItems($id);

        if (!$sale) {
            return redirect()->to('sales')->with('error', 'Sale not found.');
        }

        return view('sales/view', ['sale' => $sale]);
    }

    public function void($id)
    {
        $saleModel    = new SaleModel();
        $logModel     = new LogModel();
        $productModel = new ProductModel();

        $sale = $saleModel->getSaleWithItems($id);

        if (!$sale) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sale not found.']);
        }

        if ($sale['status'] === 'voided') {
            return $this->response->setJSON(['success' => false, 'message' => 'Sale already voided.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $saleModel->update($id, ['status' => 'voided']);

        // Restore stock
        foreach ($sale['items'] as $item) {
            $product = $productModel->find($item['product_id']);
            if ($product) {
                $productModel->update($item['product_id'], [
                    'stock_quantity' => $product['stock_quantity'] + $item['quantity']
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus()) {
            $logModel->addLog('Sale voided: ' . $sale['invoice_no'], 'DELETED');
            return $this->response->setJSON(['success' => true, 'message' => 'Sale voided successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to void sale.']);
    }

    public function fetchRecords()
    {
        $request = service('request');
        $model   = new SaleModel();

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
