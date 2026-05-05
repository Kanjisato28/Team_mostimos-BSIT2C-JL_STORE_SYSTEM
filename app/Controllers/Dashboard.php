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

        // Basic stats
        $data['today_total']       = $todaySales['total'] ?? 0;
        $data['today_count']       = $todaySales['count'] ?? 0;
        $data['total_products']    = $productModel->countAll();
        $data['low_stock_count']   = count($productModel->getLowStockProducts());
        $data['total_customers']   = $customerModel->countAll();
        $data['monthly_sales']     = $monthlySales;
        
        // NEW: Advanced Analytics Data
        $data['revenue_analytics'] = $this->getRevenueAnalytics();
        $data['top_products']      = $this->getTopProducts();
        $data['category_sales']    = $this->getCategorySales();
        $data['daily_sales']       = $this->getDailySales();
        $data['profit_margin']     = $this->getProfitMargin();
        $data['yearly_comparison'] = $this->getYearlyComparison();

        return view('dashboard', $data);
    }
    
    /**
     * Get revenue analytics (monthly revenue, cost, profit)
     */
    private function getRevenueAnalytics()
    {
        $db = \Config\Database::connect();
        
        // Check if analytics table exists
        $tableExists = $db->tableExists('monthly_revenue_analytics');
        
        if ($tableExists) {
            // Check if analytics table has data
            $hasData = $db->table('monthly_revenue_analytics')->countAll() > 0;
            
            if ($hasData) {
                // Use pre-calculated analytics
                return $db->table('monthly_revenue_analytics')
                         ->select("DATE_FORMAT(year_month, '%b %Y') as month, 
                                   year_month,
                                   total_revenue, 
                                   total_cost, 
                                   total_profit, 
                                   order_count,
                                   total_units_sold")
                         ->orderBy('year_month', 'DESC')
                         ->limit(12)
                         ->get()
                         ->getResultArray();
            }
        }
        
        // Calculate on-the-fly from sales data - FIXED ambiguous subtotal
        return $db->table('sales s')
                 ->select("DATE_FORMAT(s.sale_date, '%b %Y') as month,
                           DATE_FORMAT(s.sale_date, '%Y-%m') as month_key,
                           SUM(si.subtotal) as total_revenue,
                           SUM(si.quantity * p.cost_price) as total_cost,
                           SUM(si.subtotal - (si.quantity * p.cost_price)) as total_profit,
                           COUNT(DISTINCT s.id) as order_count,
                           SUM(si.quantity) as total_units_sold")
                 ->join('sale_items si', 's.id = si.sale_id')
                 ->join('products p', 'si.product_id = p.id')
                 ->where('s.status', 'completed')
                 ->where('s.sale_date >=', date('Y-m-01', strtotime('-11 months')))
                 ->groupBy('month_key')
                 ->orderBy('month_key', 'ASC')
                 ->get()
                 ->getResultArray();
    }
    
    /**
     * Get top selling products
     */
    private function getTopProducts($limit = 5)
    {
        $db = \Config\Database::connect();
        
        return $db->table('sale_items si')
                 ->select('p.id, p.name, p.sku, SUM(si.quantity) as total_sold, 
                           SUM(si.subtotal) as total_revenue,
                           AVG(si.unit_price) as avg_price')
                 ->join('products p', 'si.product_id = p.id')
                 ->join('sales s', 'si.sale_id = s.id')
                 ->where('s.status', 'completed')
                 ->where('s.sale_date >=', date('Y-m-01', strtotime('-3 months')))
                 ->groupBy('p.id, p.name, p.sku')
                 ->orderBy('total_sold', 'DESC')
                 ->limit($limit)
                 ->get()
                 ->getResultArray();
    }
    
    /**
     * Get sales by category - FIXED ambiguous subtotal
     */
    private function getCategorySales()
    {
        $db = \Config\Database::connect();
        
        // First get total sales for percentage calculation
        $totalSalesQuery = $db->table('sale_items si')
                              ->select('SUM(si.subtotal) as total')
                              ->join('sales s', 'si.sale_id = s.id')
                              ->where('s.status', 'completed')
                              ->where('s.sale_date >=', date('Y-01-01'))
                              ->get()
                              ->getRow();
        
        $totalSales = $totalSalesQuery->total ?? 1;
        
        $categories = $db->table('sale_items si')
                        ->select('c.id, c.name, SUM(si.subtotal) as total_sales, 
                                  SUM(si.quantity) as units_sold')
                        ->join('products p', 'si.product_id = p.id')
                        ->join('categories c', 'p.category_id = c.id')
                        ->join('sales s', 'si.sale_id = s.id')
                        ->where('s.status', 'completed')
                        ->where('s.sale_date >=', date('Y-01-01'))
                        ->groupBy('c.id, c.name')
                        ->orderBy('total_sales', 'DESC')
                        ->get()
                        ->getResultArray();
        
        // Calculate percentage
        foreach ($categories as &$cat) {
            $cat['percentage'] = round(($cat['total_sales'] / $totalSales) * 100, 1);
        }
        
        return $categories;
    }
    
    /**
     * Get daily sales for current month - FIXED ambiguous total_amount
     */
    private function getDailySales()
    {
        $db = \Config\Database::connect();
        
        $currentMonth = date('Y-m');
        $daysInMonth = date('t');
        
        $sales = $db->table('sales')
                   ->select("DATE_FORMAT(sale_date, '%d') as day, 
                             SUM(total_amount) as total_sales,
                             COUNT(*) as transaction_count")
                   ->where('status', 'completed')
                   ->where("DATE_FORMAT(sale_date, '%Y-%m')", $currentMonth)
                   ->groupBy('day')
                   ->orderBy('day', 'ASC')
                   ->get()
                   ->getResultArray();
        
        // Fill in missing days with zero
        $dailyData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $found = false;
            foreach ($sales as $sale) {
                if ($sale['day'] == $day) {
                    $dailyData[] = $sale;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $dailyData[] = ['day' => $day, 'total_sales' => 0, 'transaction_count' => 0];
            }
        }
        
        return $dailyData;
    }
    
    /**
     * Calculate profit margin trends - FIXED ambiguous subtotal
     */
    private function getProfitMargin()
    {
        $db = \Config\Database::connect();
        
        $result = $db->table('sales s')
                    ->select("DATE_FORMAT(s.sale_date, '%b %Y') as month,
                              DATE_FORMAT(s.sale_date, '%Y-%m') as month_key,
                              SUM(si.subtotal) as total_revenue,
                              SUM(si.quantity * p.cost_price) as total_cost")
                    ->join('sale_items si', 's.id = si.sale_id')
                    ->join('products p', 'si.product_id = p.id')
                    ->where('s.status', 'completed')
                    ->where('s.sale_date >=', date('Y-m-01', strtotime('-5 months')))
                    ->groupBy('month_key')
                    ->orderBy('s.sale_date', 'ASC')
                    ->get()
                    ->getResultArray();
        
        // Calculate profit margin percentage
        foreach ($result as &$item) {
            if ($item['total_revenue'] > 0) {
                $profit = $item['total_revenue'] - $item['total_cost'];
                $item['profit_margin'] = round(($profit / $item['total_revenue']) * 100, 1);
            } else {
                $item['profit_margin'] = 0;
            }
            $item['month'] = $item['month'];
        }
        
        return $result;
    }
    
    /**
     * Year-over-year comparison - FIXED ambiguous total_amount
     */
    private function getYearlyComparison()
    {
        $db = \Config\Database::connect();
        $currentYear = date('Y');
        $lastYear = $currentYear - 1;
        
        $currentYearSales = $db->table('sales')
                              ->select("DATE_FORMAT(sale_date, '%b') as month,
                                        MONTH(sale_date) as month_num,
                                        SUM(total_amount) as total")
                              ->where('status', 'completed')
                              ->where('YEAR(sale_date)', $currentYear)
                              ->groupBy('month_num, month')
                              ->orderBy('month_num', 'ASC')
                              ->get()
                              ->getResultArray();
        
        $lastYearSales = $db->table('sales')
                           ->select("DATE_FORMAT(sale_date, '%b') as month,
                                     MONTH(sale_date) as month_num,
                                     SUM(total_amount) as total")
                           ->where('status', 'completed')
                           ->where('YEAR(sale_date)', $lastYear)
                           ->groupBy('month_num, month')
                           ->orderBy('month_num', 'ASC')
                           ->get()
                           ->getResultArray();
        
        return [
            'current_year' => $currentYearSales,
            'last_year' => $lastYearSales,
            'current_year_label' => $currentYear,
            'last_year_label' => $lastYear
        ];
    }
}