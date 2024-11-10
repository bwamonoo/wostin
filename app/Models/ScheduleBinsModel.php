<?php

namespace App\Models;
use CodeIgniter\Model;

class ScheduleBinsModel extends Model {
    protected $table = 'schedule_bins';
    protected $primaryKey = 'id';
    protected $allowedFields = ['schedule_id', 'bin_size_id', 'waste_type_id', 'quantity', 'cost'];

    // Validation Rules
    protected $validationRules = [
        'schedule_id'   => 'required|is_natural_no_zero',
        'bin_size_id'   => 'required|is_natural_no_zero',
        'waste_type_id' => 'required|is_natural_no_zero',
        'quantity'      => 'required|integer|greater_than[0]',
        'cost'          => 'required|decimal'
    ];
}

