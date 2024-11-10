<?php

namespace App\Models;

use CodeIgniter\Model;

class UnavailableDatesModel extends Model
{
    protected $table = 'unavailable_dates';
    protected $allowedFields = ['date', 'reason'];

    /**
     * Check if a date is unavailable.
     * @param string $date
     * @return bool
     */
    public function isDateUnavailable(string $date): bool
    {
        return $this->where('date', $date)->countAllResults() > 0;
    }
}
