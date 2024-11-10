<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\PendingScheduleBinsModel;

class PendingSchedulesModel extends Model {
    protected $table = 'pending_schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'collection_date', 'collection_time', 'total_cost', 'bin_quantity'];

    // Validation Rules
    protected $validationRules = [
        'user_id'         => 'required|is_natural_no_zero',
        'collection_date' => 'required|valid_date',
        'bin_quantity'      => 'required|integer|greater_than[0]',
        'total_cost'          => 'required|decimal',
    ];

    // protected $beforeInsert = ['calculateTotalCost'];
   // protected $beforeUpdate = ['calculateTotalCost'];

    // Calculate total cost by summing up all related bins
    protected function calculateTotalCost(array $data) {
        if (isset($data['data']['user_id'])) {
            $pendingScheduleBinsModel = new PendingScheduleBinsModel();
            $bins = $pendingScheduleBinsModel->where('pending_schedule_id', $this->id)->findAll();
            $total = array_reduce($bins, function($sum, $bin) {
                return $sum + $bin['cost'];
            }, 0);
            $data['data']['total_cost'] = $total;
        }
        return $data;
    }
}

