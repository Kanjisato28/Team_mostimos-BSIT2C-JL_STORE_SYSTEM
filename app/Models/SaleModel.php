<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table      = 'sales';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'invoice_no', 'customer_id', 'user_id', 'subtotal', 'discount',
        'tax', 'total_amount', 'payment_method', 'amount_paid', 'change_amount',
        'status', 'sale_date', 'notes'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->db->table('sales s');
        $builder->select('s.*, c.name as customer_name, u.name as cashier_name');
        $builder->join('customers c', 'c.id = s.customer_id', 'left');
        $builder->join('users u', 'u.id = s.user_id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('s.invoice_no', $searchValue)
                ->orLike('c.name', $searchValue)
                ->groupEnd();
        }

        $builder->orderBy('s.id', 'DESC');

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }

    public function generateInvoiceNo()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $last = $this->db->table('sales')
            ->like('invoice_no', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if ($last) {
            $parts = explode('-', $last['invoice_no']);
            $seq = intval(end($parts)) + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function getTodaySales()
    {
        return $this->db->table('sales')
            ->selectSum('total_amount', 'total')
            ->selectCount('id', 'count')
            ->where('sale_date', date('Y-m-d'))
            ->where('status', 'completed')
            ->get()->getRowArray();
    }

    public function getMonthlySales()
    {
        return $this->db->table('sales')
            ->select("DATE_FORMAT(sale_date, '%b %Y') as month, SUM(total_amount) as total, COUNT(id) as count")
            ->where('status', 'completed')
            ->where('sale_date >=', date('Y-m-d', strtotime('-6 months')))
            ->groupBy("DATE_FORMAT(sale_date, '%Y-%m')")
            ->orderBy('sale_date', 'ASC')
            ->get()->getResultArray();
    }

    public function getSaleWithItems($id)
    {
        $sale = $this->db->table('sales s')
            ->select('s.*, c.name as customer_name, u.name as cashier_name')
            ->join('customers c', 'c.id = s.customer_id', 'left')
            ->join('users u', 'u.id = s.user_id', 'left')
            ->where('s.id', $id)
            ->get()->getRowArray();

        if ($sale) {
            $sale['items'] = $this->db->table('sale_items si')
                ->select('si.*, p.name as product_name, p.unit')
                ->join('products p', 'p.id = si.product_id', 'left')
                ->where('si.sale_id', $id)
                ->get()->getResultArray();
        }

        return $sale;
    }
}
