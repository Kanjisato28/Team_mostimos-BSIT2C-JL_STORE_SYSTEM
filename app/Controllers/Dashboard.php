<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        $saleModel    = new SaleModel();
        $productModel = new ProductModel();
        $customerModel = new CustomerModel();

        $todaySales  = $saleModel->getTodaySales();
        $monthlySales = $saleModel->getMonthlySales();

        $data['today_total']       = $todaySales['total'] ?? 0;
        $data['today_count']       = $todaySales['count'] ?? 0;
        $data['total_products']    = $productModel->countAll();
        $data['low_stock_count']   = count($productModel->getLowStockProducts());
        $data['total_customers']   = $customerModel->countAll();
        $data['monthly_sales']     = $monthlySales;

        return view('dashboard', $data);
    }
}
