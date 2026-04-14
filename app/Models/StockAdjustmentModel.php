<?php

namespace App\Models;

use CodeIgniter\Model;

class StockAdjustmentModel extends Model
{
    protected $table      = 'stock_adjustments';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    protected $allowedFields = [
        'product_id', 'user_id', 'type', 'quantity',
        'previous_stock', 'new_stock', 'reason', 'created_at'
    ];

    public function getRecords($start, $length, $searchValue = '')
    {
        $builder = $this->db->table('stock_adjustments sa');
        $builder->select('sa.*, p.name as product_name, p.sku, u.name as adjusted_by');
        $builder->join('products p', 'p.id = sa.product_id', 'left');
        $builder->join('users u', 'u.id = sa.user_id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->orLike('p.name', $searchValue)
                ->orLike('p.sku', $searchValue)
                ->groupEnd();
        }

        $builder->orderBy('sa.id', 'DESC');

        $filteredBuilder = clone $builder;
        $filteredRecords = $filteredBuilder->countAllResults();

        $builder->limit($length, $start);
        $data = $builder->get()->getResultArray();

        return ['data' => $data, 'filtered' => $filteredRecords];
    }
}
