<?php

namespace App\Models;
use CodeIgniter\Model;

class UsersModel extends Model {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'phone_number', 'location', 'email_verified', 'phone_verified', 'user_group_id'];

    // Validation Rules
    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[255]',
        'email'       => 'required|valid_email|is_unique[users.email]',
        'phone_number' => 'required|numeric|min_length[10]|max_length[15]',
        'password'    => 'required|min_length[8]',
        'location'    => 'required|max_length[255]'
    ];

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data) {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }
}

