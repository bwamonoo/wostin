<?php

namespace App\Models;

use CodeIgniter\Model;

class VerificationCodesModel extends Model
{
    protected $table = 'verification_codes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'code', 'expires_at', 'type', 'created_at'];

    // Validation Rules
    protected $validationRules = [
        'user_id'    => 'required|is_not_unique[users.id]',
        'code'       => 'required|min_length[5]|max_length[10]|alpha_numeric|is_unique[verification_codes.code]',
        'expires_at' => 'required|valid_date[Y-m-d H:i:s]',
        'type'       => 'required|in_list[email,phone]',
    ];

    // Callbacks

    protected $beforeInsert = ['setTimestamps'];
    protected $beforeUpdate = ['setCreatedAt'];

    protected function setTimestamps(array $data)
    {
        $currentTimestamp = date('Y-m-d H:i:s');
        $data['data']['created_at'] = $currentTimestamp;
        $data['data']['expires_at'] = date('Y-m-d H:i:s', strtotime($currentTimestamp . ' + 15 minutes'));
        return $data;
    }
}
