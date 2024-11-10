<?php

namespace App\Models;
use CodeIgniter\Model;

class WasteTypesModel extends Model {
    protected $table = 'waste_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['type', 'description', 'cost'];

    // Validation Rules
    protected $validationRules = [
        'type'        => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[3]|max_length[255]',
        'cost'        => 'required|decimal',
    ];
}

