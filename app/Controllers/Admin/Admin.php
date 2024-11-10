<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\UsersModel;
use App\Models\SchedulesModel;
use App\Models\ScheduleBinsModel;
use App\Models\BinSizesModel;
use App\Models\WasteTypesModel;

class Admin extends BaseController {
    protected $usersModel;
    protected $schedulesModel;
    protected $scheduleBinsModel;
    protected $binSizesModel;
    protected $wasteTypesModel;

    public function __construct() {
        $this->usersModel = new UsersModel();
        $this->schedulesModel = new SchedulesModel();
        $this->scheduleBinsModel = new ScheduleBinsModel();
        $this->binSizesModel = new BinSizesModel();
        $this->wasteTypesModel = new WasteTypesModel();
    }


    //Admin Dashboard and Reports
    public function dashboard() {
        // Fetch key metrics
        $data = [
            'totalUsers' => $this->usersModel->countAllResults(),
            'pendingSchedules' => $this->schedulesModel->where('status', 'pending')->countAllResults(),
            'approvedSchedules' => $this->schedulesModel->where('status', 'approved')->countAllResults(),
            'completedCollections' => $this->schedulesModel->where('status', 'completed')->countAllResults(),
            'totalIncome' => $this->schedulesModel->selectSum('total_cost')->get()->getRow()->total_cost,
            
            // Data for charts
            'schedulesByDay' => $this->getSchedulesByDay(),
            'wasteTypeDistribution' => $this->getWasteTypeDistribution(),
            'monthlyRevenue' => $this->getMonthlyRevenue(),
            'statusCounts' => $this->getStatusCounts()
        ];
    
        return view('admin/dashboard', $data);
    }
    
    // Example functions to fetch data for each chart
    private function getSchedulesByDay() {
        return $this->schedulesModel->select("DATE(collection_date) as date, COUNT(*) as count")
                                    ->groupBy("DATE(collection_date)")
                                    ->orderBy("date", "ASC")
                                    ->findAll();
    }
    
    private function getWasteTypeDistribution() {
        return $this->scheduleBinsModel->select("waste_types.type as waste_type, COUNT(*) as count")
                                       ->join("waste_types", "waste_types.id = schedule_bins.waste_type_id")
                                       ->groupBy("waste_types.type")
                                       ->findAll();
    }
    
    private function getMonthlyRevenue() {
        return $this->schedulesModel->select("DATE_FORMAT(collection_date, '%Y-%m') as month, SUM(total_cost) as revenue")
                                    ->groupBy("month")
                                    ->orderBy("month", "ASC")
                                    ->findAll();
    }
    
    private function getStatusCounts() {
        return $this->schedulesModel->select("status, COUNT(*) as count")
                                    ->groupBy("status")
                                    ->findAll();
    }
    

    public function reports()
    {
        $reportsModel = $this->schedulesModel;

        $data['totalIncome'] = $reportsModel->getTotalIncome();
        $data['schedulesPerMonth'] = $reportsModel->getSchedulesPerMonth();
        $data['averageCost'] = $reportsModel->getAverageCost();

        return view('admin/reports', $data);
    }


    // ****______User Management_____****** 

    // Get all Users
    public function userManagement()
    {
        $data['users'] = $this->usersModel->findAll();
        return view('admin/user_management', $data);
    }

    // Deactivate User
    public function deactivateUser($id)
    {
        $user = $this->usersModel->find($id);
        
        if ($user) {
            $this->usersModel->update($id, [
                'email_verified' => false,
                'phone_verified' => false,
            ]);
            return redirect()->to('/admin/users')->with('success', 'User deactivated successfully.');
        } else {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }
    }

    // Reactivate User
    public function reactivateUser($id)
    {
        $user = $this->usersModel->find($id);
        
        if ($user) {
            $this->usersModel->update($id, [
                'email_verified' => true,
                'phone_verified' => true,
            ]);
            return redirect()->to('/admin/users')->with('success', 'User reactivated successfully.');
        } else {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }
    }

    // View User Details and Schedules
    public function viewUser($id)
    {
        $user = $this->usersModel->find($id);
        
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found.');
        }
    
        // Get schedules and their associated bins
        $schedules = $this->schedulesModel
            ->where('user_id', $id)
            ->orderBy('collection_date', 'DESC')
            ->findAll();
    
        foreach ($schedules as &$schedule) {
            $schedule['bins'] = $this->scheduleBinsModel
                ->select('schedule_bins.quantity, schedule_bins.cost, bin_sizes.size, waste_types.type AS waste_type')
                ->join('bin_sizes', 'bin_sizes.id = schedule_bins.bin_size_id')
                ->join('waste_types', 'waste_types.id = schedule_bins.waste_type_id')
                ->where('schedule_id', $schedule['id'])
                ->findAll();
        }
    
        return view('admin/view_user', [
            'user' => $user,
            'schedules' => $schedules,
        ]);
    }


    // ****______Schedule Management_____******     

    // Get all Schedules
    public function schedules()
    {
        // Get filters from request
        $statusFilter = $this->request->getGet('status');
        $dateFilter = $this->request->getGet('date');

        // Base query
        $query = $this->schedulesModel;

        // Apply filters if set
        if ($statusFilter) {
            $query = $query->where('status', $statusFilter);
        }
        if ($dateFilter) {
            $query = $query->where('collection_date', $dateFilter);
        }

        // Fetch all schedules with associated bin and waste details
        $schedules = $query->orderBy('collection_date', 'DESC')->findAll();

        foreach ($schedules as &$schedule) {
            $schedule['bins'] = $this->scheduleBinsModel
                ->select('schedule_bins.quantity, schedule_bins.cost, bin_sizes.size, waste_types.type AS waste_type')
                ->join('bin_sizes', 'bin_sizes.id = schedule_bins.bin_size_id')
                ->join('waste_types', 'waste_types.id = schedule_bins.waste_type_id')
                ->where('schedule_id', $schedule['id'])
                ->findAll();
        }

        // Pass data to the view
        return view('admin/manage_schedules', [
            'schedules' => $schedules,
            'statusFilter' => $statusFilter,
            'dateFilter' => $dateFilter,
        ]);
    }
    
    // Get Schedule Details (by id)
    public function schedule_details($id)
    {
        // Fetch the schedule
        $schedule = $this->schedulesModel->find($id);
    
        if (!$schedule) {
            return redirect()->to('/admin/manage-schedules')->with('error', 'Schedule not found.');
        }
    
        // Fetch associated bins and waste details
        $schedule['bins'] = $this->scheduleBinsModel
            ->select('schedule_bins.quantity, schedule_bins.cost, bin_sizes.size, waste_types.type AS waste_type')
            ->join('bin_sizes', 'bin_sizes.id = schedule_bins.bin_size_id')
            ->join('waste_types', 'waste_types.id = schedule_bins.waste_type_id')
            ->where('schedule_id', $id)
            ->findAll();
    
        // Pass data to the view
        return view('admin/schedule_details', ['schedule' => $schedule]);
    }

    // Update Schedule Status (on schedule management page)
    public function update_schedule_status($id)
    {
        $newStatus = $this->request->getPost('status');
        $this->schedulesModel->update($id, ['status' => $newStatus]);

        return redirect()->to('/admin/schedules')->with('success', 'Schedule status updated successfully.');
    }

    // Update Schedule Status (on schedule details page)
    public function manage_schedule($id)
    {
        $newStatus = $this->request->getPost('status');
    
        // Check if schedule exists
        $schedule = $this->schedulesModel->find($id);
    
        if (!$schedule) {
            return redirect()->to('/admin/manage-schedules')->with('error', 'Schedule not found.');
        }
    
        // Update schedule status
        $this->schedulesModel->update($id, ['status' => $newStatus]);
    
        return redirect()->to('/admin/schedule-details/' . $id)->with('success', 'Schedule status updated successfully.');
    }

    // Approve/Unapprove Schedule
    public function approveSchedule($scheduleId)
    {
        $this->schedulesModel->update($scheduleId, [
            'is_approved' => true,
            'pending_approval' => false,
            'status' => 'pending'
        ]);
        return redirect()->to('/admin/schedules')->with('success', 'Schedule approved successfully.');
    }
    
    public function unapproveSchedule($scheduleId)
    {
        $this->schedulesModel->update($scheduleId, [
            'is_approved' => null,
            'pending_approval' => true,
            'status' => 'awaiting_approval'
        ]);
        return redirect()->to('/admin/schedules')->with('success', 'Schedule unapproved successfully.');
    }

    // ****______Waste Type and Bin Size Management_____******   
    
    // Get all Waste Types and Bin Sizes
    public function binWasteManagement()
    {        
        $data['binSizes'] = $this->binSizesModel->findAll();
        $data['wasteTypes'] = $this->wasteTypesModel->findAll();
        
        return view('admin/bin_waste_management', $data);
    }
    
    // Add Bin Size
    public function addBinSize()
    {        
        $data = [
            'size' => $this->request->getPost('size'),
            'size_multiplier' => $this->request->getPost('size_multiplier'),
            'description' => $this->request->getPost('description'),
        ];

        // var_dump($data);
        // var_dump($this->binSizesModel->save($data));
        // exit();

        if ($this->binSizesModel->save($data)) {
            return redirect()->to('/admin/bin-waste-management')->with('success', 'Bin size added successfully.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->binSizesModel->errors());
        }
    }
    
    // Remove Bin Size
    public function deleteBinSize($id)
    {
        $this->binSizesModel->delete($id);
        return redirect()->to('/admin/bin-waste-management')->with('success', 'Bin size removed successfully.');
    }
    
    // Add Waste Type
    public function addWasteType()
    {
        $data = [
            'type' => $this->request->getPost('type'),
            'description' => $this->request->getPost('description'),
            'cost' => $this->request->getPost('cost'),
        ];
        
        $this->wasteTypesModel->save($data);
        return redirect()->to('/admin/bin-waste-management')->with('success', 'Waste type added successfully.');
    }
    
    // Remove Waste Type
    public function deleteWasteType($id)
    {
        $this->wasteTypesModel->delete($id);
        return redirect()->to('/admin/bin-waste-management')->with('success', 'Waste type removed successfully.');
    }
    
    
    // ****______Schedule Unavailable Dates Management_____******   

    // Get all Unavailable Dates
    public function unavailabilityManagement()
    {
        $unavailableDatesModel = new \App\Models\UnavailableDatesModel();
        $data['unavailableDates'] = $unavailableDatesModel->findAll();
        return view('admin/unavailability_management', $data);
    }

    // Set Unavailable Dates for Collection (Schedules)
    public function addUnavailableDate()
    {
        $unavailableDatesModel = new \App\Models\UnavailableDatesModel();
        $date = $this->request->getPost('date');
        $reason = $this->request->getPost('reason');

        if ($date) {
            $unavailableDatesModel->insert(['date' => $date, 'reason' => $reason]);
            return redirect()->to('/admin/unavailability-management')->with('success', 'Unavailable date added successfully.');
        }

        return redirect()->back()->with('error', 'Please provide a valid date.');
    }

    // Delete Unavailable Dates for Collection (Schedules)
    public function deleteUnavailableDate($id)
    {
        $unavailableDatesModel = new \App\Models\UnavailableDatesModel();
        $unavailableDatesModel->delete($id);
        return redirect()->to('/admin/unavailability-management')->with('success', 'Unavailable date removed successfully.');
    }    
    
    
}
