<?php

namespace App\Models;
use CodeIgniter\Model;
use App\Models\ScheduleBinsModel;

class SchedulesModel extends Model {
    protected $table = 'schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'collection_date', 'collection_time', 'status', 'total_cost', 'bin_quantity', 'pending_approval', 'is_approved'];

    // Validation Rules
    protected $validationRules = [
        'user_id'         => 'required|is_natural_no_zero',
        'collection_date' => 'required|valid_date',
        'status'          => 'in_list[pending,approved,completed,cancelled,awaiting_approval,rejected]',
        'bin_quantity'      => 'required|integer|greater_than[0]',
        'total_cost'          => 'required|decimal',
    ];

    // Total income report
    public function getTotalIncome()
    {
        return $this->selectSum('total_cost')->get()->getRow()->total_cost;
    }

    // Count schedules per month
    public function getSchedulesPerMonth()
    {
        return $this->select('MONTH(collection_date) as month, COUNT(*) as count')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->getResultArray();
    }

    // Average cost per schedule
    public function getAverageCost()
    {
        return $this->selectAvg('total_cost')->get()->getRow()->total_cost;
    }

    // protected $beforeInsert = ['calculateTotalCost'];
   // protected $beforeUpdate = ['calculateTotalCost'];

    // Calculate total cost by summing up all related bins
    protected function calculateTotalCost(array $data) {
        if (isset($data['data']['user_id'])) {
            $scheduleBinsModel = new ScheduleBinsModel();
            $bins = $scheduleBinsModel->where('schedule_id', $this->id)->findAll();
            $total = array_reduce($bins, function($sum, $bin) {
                return $sum + $bin['cost'];
            }, 0);
            $data['data']['total_cost'] = $total;
        }
        return $data;
    }
}

