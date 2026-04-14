<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'category_id', 'supplier_id', 'sku', 'name', 'description',
        'unit', 'cost_price', 'selling_price', 'stock_quantity', 'reorder_level'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->db->table('products p');
        $builder->select('p.*, c.name as category_name, s.name as supplier_name');
        $builder->join('categories c', 'c.id = p.category_id', 'left');
        $builder->join('suppliers s', 's.id = p.supplier_id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('p.name', $searchValue)
                ->orLike('p.sku', $searchValue)
                ->orLike('c.name', $searchValue)
                ->groupEnd();
        }

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }

    public function getLowStockProducts()
    {
        return $this->db->query(
            'SELECT p.*, c.name as category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.stock_quantity <= p.reorder_level
             ORDER BY p.stock_quantity ASC'
        )->getResultArray();
    }

    public function getActiveProducts()
    {
        return $this->db->table('products p')
            ->select('p.id, p.name, p.sku, p.selling_price, p.stock_quantity, p.unit, c.name as category_name')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.stock_quantity >', 0)
            ->orderBy('p.name', 'ASC')
            ->get()->getResultArray();
    }

    public function searchProducts($keyword)
    {
        return $this->db->table('products p')
            ->select('p.id, p.name, p.sku, p.selling_price, p.stock_quantity, p.unit')
            ->where('p.stock_quantity >', 0)
            ->groupStart()
                ->orLike('p.name', $keyword)
                ->orLike('p.sku', $keyword)
            ->groupEnd()
            ->limit(20)
            ->get()->getResultArray();
    }
}
