<?php

namespace App\Controllers;

use App\Models\SchedulesModel;
use App\Models\ScheduleBinsModel;

class Dashboard extends BaseController
{
    protected $schedulesModel;
    protected $scheduleBinsModel;

    public function __construct()
    {
        $this->schedulesModel = new SchedulesModel();
        $this->scheduleBinsModel = new ScheduleBinsModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

        // Get upcoming collection for the user
        $upcomingSchedule = $this->schedulesModel
            ->where('user_id', $userId)
            ->where('status !=', 'completed')
            ->orderBy('collection_date', 'ASC')
            ->first();

        // If there’s an upcoming schedule, retrieve its bins
        if ($upcomingSchedule) {
            $upcomingSchedule['bins'] = $this->scheduleBinsModel
                ->select('schedule_bins.quantity, schedule_bins.cost, bin_sizes.size, waste_types.type AS waste_type')
                ->join('bin_sizes', 'bin_sizes.id = schedule_bins.bin_size_id')
                ->join('waste_types', 'waste_types.id = schedule_bins.waste_type_id')
                ->where('schedule_id', $upcomingSchedule['id'])
                ->findAll();
        }

        // Get recent schedules and their bins
        $recentSchedules = $this->schedulesModel
            ->where('user_id', $userId)
            ->orderBy('collection_date', 'DESC')
            ->findAll(5);

        foreach ($recentSchedules as &$schedule) {
            $schedule['bins'] = $this->scheduleBinsModel
                ->select('schedule_bins.quantity, schedule_bins.cost, bin_sizes.size, waste_types.type AS waste_type')
                ->join('bin_sizes', 'bin_sizes.id = schedule_bins.bin_size_id')
                ->join('waste_types', 'waste_types.id = schedule_bins.waste_type_id')
                ->where('schedule_id', $schedule['id'])
                ->findAll();
        }

        // Calculate quick stats
        $totalCollections = $this->schedulesModel->where('user_id', $userId)->countAllResults();
        $totalAmountSpent = $this->schedulesModel->where('user_id', $userId)->selectSum('total_cost')->get()->getRow()->total_cost;
        $pendingCollections = $this->schedulesModel->where(['user_id' => $userId, 'status' => 'pending'])->countAllResults();

        // Pass data to the view
        return view('dashboard/index', [
            'upcomingSchedule' => $upcomingSchedule,
            'recentSchedules' => $recentSchedules,
            'totalCollections' => $totalCollections,
            'totalAmountSpent' => $totalAmountSpent,
            'pendingCollections' => $pendingCollections,
        ]);
    }
}
