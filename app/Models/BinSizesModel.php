<?php

namespace App\Models;
use CodeIgniter\Model;

class BinSizesModel extends Model {
    protected $table = 'bin_sizes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['size', 'size_multiplier', 'description'];

    // Validation Rules
    protected $validationRules = [
        'size'  => 'required|min_length[1]|max_length[50]',
        'size_multiplier' => 'required|decimal',
        'description'        => 'required|min_length[3]|max_length[255]',
    ];
}

