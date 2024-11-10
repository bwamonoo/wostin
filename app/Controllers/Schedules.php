<?php

namespace App\Controllers;

use App\Models\SchedulesModel;
use App\Models\PendingSchedulesModel;
use App\Models\ScheduleBinsModel;
use App\Models\PendingScheduleBinsModel;
use App\Models\BinSizesModel;
use App\Models\UsersModel;
use App\Models\WasteTypesModel;
use App\Models\UnavailableDatesModel; // Corrected model name
use CodeIgniter\Controller;

class Schedules extends Controller
{
    protected $schedulesModel;
    protected $pendingSchedulesModel;
    protected $scheduleBinsModel;
    protected $pendingScheduleBinsModel;
    protected $usersModel;
    protected $wasteTypesModel;
    protected $binSizesModel;
    protected $unavailableDatesModel;

    public function __construct()
    {
        $this->schedulesModel = new SchedulesModel();
        $this->pendingSchedulesModel = new PendingSchedulesModel();
        $this->scheduleBinsModel = new ScheduleBinsModel();
        $this->pendingScheduleBinsModel = new PendingScheduleBinsModel();
        $this->usersModel = new UsersModel();
        $this->wasteTypesModel = new WasteTypesModel();
        $this->binSizesModel = new BinSizesModel();
        $this->unavailableDatesModel = new UnavailableDatesModel();
    }

    public function index() {
        $status = $this->request->getVar('status');
        $userId = session()->get('user_id'); // Ensure you fetch schedules for the logged-in user
    
        $builder = $this->schedulesModel->where('user_id', $userId);
    
        // Apply the filter if a status is provided
        if ($status) {
            $builder->where('status', $status);
        }
    
        $data['schedules'] = $builder->orderBy('collection_date', 'DESC')->findAll();
        $data['status'] = $status;
    
        return view('schedules/index', $data);
    }

    public function create_schedule($id = null)
    {
        $data['wasteTypes'] = $this->wasteTypesModel->findAll();
        $data['binSizes'] = $this->binSizesModel->findAll();
    
        if ($this->request->getMethod() === 'POST') {
            $pendingScheduleData = [
                'collection_date' => $this->request->getPost('collection_date'),
                'user_id' => session()->get('user_id'),
            ];
    
            $pendingScheduleDate = $this->request->getPost('collection_date');
    
            // Block scheduling if date is unavailable
            if ($this->unavailableDatesModel->isDateUnavailable($pendingScheduleDate)) {
                return redirect()->back()->withInput()->with('error', 'The selected date is unavailable.');
            }
    
            // Calculate total cost of bins and set approval status if conditions met
            $totalCost = 0;
            $bins = $this->request->getPost('bins');
            $binQuantity = 0;
    
            foreach ($bins as $bin) {
                $quantity = (int)$bin['quantity'];
                $binQuantity += $quantity;
                $totalCost += $this->calculateBinCost($bin['bin_size'], $bin['waste_type']) * $quantity;
            }
    
            $pendingScheduleData['total_cost'] = $totalCost;
            $pendingScheduleData['bin_quantity'] = $binQuantity;

            // print_r($pendingScheduleData);
            // exit();
    
            // Insert pending schedule
            if (!$this->pendingSchedulesModel->insert($pendingScheduleData)) {
                return redirect()->back()->withInput()->with('errors', $this->pendingSchedulesModel->errors());
            };
            $pendingScheduleId = $this->pendingSchedulesModel->getInsertID();

            // var_dump($pendingScheduleId);
            // exit();
    
            // Create bins for pending schedule
            foreach ($bins as $bin) {
                $binCost = $this->calculateBinCost($bin['bin_size'], $bin['waste_type']) * $bin['quantity'];
                $binData = [
                    'pending_schedule_id' => $pendingScheduleId,
                    'bin_size_id' => $bin['bin_size'],
                    'waste_type_id' => $bin['waste_type'],
                    'quantity' => $bin['quantity'],
                    'cost' => $binCost,
                ];

                // var_dump($pendingScheduleId);
                // exit();
    
                $this->pendingScheduleBinsModel->insert($binData);
            }
    
            // Initiate payment using Paystack helper
            $paystack = new \App\Libraries\Paystack();
            try {
                $email = session()->get('email'); // Assume user's email is in session
                $callbackUrl = base_url('/payment/confirm-payment'); // Set callback URL
                $paymentData = $paystack->initializePayment($email, $totalCost, $callbackUrl);
    
                $data['payment'] = $paymentData;
                // Redirect to Paystack payment URL
                return redirect()->to($paymentData['authorization_url']);
            } catch (\Exception $e) {
                $this->pendingSchedulesModel->delete($pendingScheduleId);
                $this->pendingScheduleBinsModel->where('pending_schedule_id', $pendingScheduleId)->delete();

                // var_dump(session()->get('email'));
                // var_dump(session()->get('user_id'));
                // exit();

                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    
        return view('schedules/manage', $data);
    }    

    public function confirm_payment()
    {
        // Accessing the query parameters
        $trxref = $this->request->getGet('trxref');
        $reference = $this->request->getGet('reference');

        if (!$trxref || !$reference) {
            return redirect()->back()->with('error', 'Invalid transaction reference');
        }
        // Verify payment with Paystack helper
        $paystack = new \App\Libraries\Paystack();
        try {
            $paymentDetails = $paystack->verifyTransaction($reference);
    
            if (!$paymentDetails || $paymentDetails['status'] !== 'success') {
                return redirect()->back()->with('error', 'Payment not confirmed.');
            }
    
            $user_id = session()->get('user_id');
            $pendingSchedule = $this->pendingSchedulesModel->where('user_id', $user_id)->first();

            // $error = 'error';
            // var_dump(session()->get('user_id'));
            // var_dump($pendingSchedule);
            // exit(); 
    
            $scheduleData = [
                'user_id' => $user_id,
                'collection_date' => $pendingSchedule['collection_date'],
                'total_cost' => $pendingSchedule['total_cost'],
                'bin_quantity' => $pendingSchedule['bin_quantity'],
                'status' => 'pending',
            ];

    
            $pendingScheduleBins = $this->pendingScheduleBinsModel
                ->select('bin_size_id, waste_type_id, quantity, cost')
                ->where('pending_schedule_id', $pendingSchedule['id'])
                ->findAll();
                
            // Set approval status if conditions met
            $requiresApproval = $pendingSchedule['bin_quantity'] > 20;
    
            // Set status to awaiting_approval if approval is required
            if ($requiresApproval) {
                $scheduleData['status'] = 'awaiting_approval';
                $scheduleData['pending_approval'] = true;
            }
    
            // Insert the schedule
            if (!$this->schedulesModel->insert($scheduleData)) {
                return redirect()->back()->withInput()->with('errors', $this->schedulesModel->errors());
            };
            $scheduleId = $this->schedulesModel->getInsertID();

    
            // Create bins for the new schedule
            foreach ($pendingScheduleBins as $bin) {
                $binData = [
                    'schedule_id' => $scheduleId,
                    'bin_size_id' => $bin['bin_size_id'],
                    'waste_type_id' => $bin['waste_type_id'],
                    'quantity' => $bin['quantity'],
                    'cost' => $bin['cost'],
                ];
    
                $this->scheduleBinsModel->insert($binData);
            }

            $this->pendingSchedulesModel->delete($pendingSchedule['id']);
            $this->pendingScheduleBinsModel->where('pending_schedule_id', $pendingSchedule['id'])->delete();
    
            return redirect()->to('/schedules')->with('success', 'Schedule created and payment confirmed.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    public function edit_schedule($id = null)
    {
        $data['wasteTypes'] = $this->wasteTypesModel->findAll();
        $data['binSizes'] = $this->binSizesModel->findAll();
        $data['schedule'] = [];
        $data['scheduleBins'] = [];
    
        if (!$id) {
            return redirect()->to('/schedules')->with('error', 'Invalid schedule ID.');
        }
    
        $schedule = $this->schedulesModel->find($id);
        if (!$schedule || !in_array($schedule['status'], ['pending', 'editable'])) {
            return redirect()->to('/schedules')->with('error', 'Schedule cannot be edited.');
        }
    
        $data['schedule'] = $schedule;
        $data['scheduleBins'] = $this->scheduleBinsModel->where('schedule_id', $id)->findAll();
    
        if ($this->request->getMethod() === 'POST') {
            $scheduleData = [
                'collection_date' => $this->request->getPost('collection_date'),
                'status' => $schedule['status'],
                'user_id' => session()->get('user_id'),
            ];
    
            $scheduleDate = $this->request->getPost('collection_date');
    
            // Block scheduling if date is unavailable
            if ($this->unavailableDatesModel->isDateUnavailable($scheduleDate)) {
                return redirect()->back()->withInput()->with('error', 'The selected date is unavailable.');
            }
    
            // Calculate total cost of bins
            $totalCost = 0;
            $bins = $this->request->getPost('bins');
            $binQuantity = 0;
            $requiresApproval = false;
    
            foreach ($bins as $bin) {
                $quantity = (int)$bin['quantity'];
                $binQuantity += $quantity;
                $totalCost += $this->calculateBinCost($bin['bin_size'], $bin['waste_type']) * $quantity;
            }
    
            // Condition: If bin quantities exceed a limit, require approval
            if ($binQuantity > 20) {
                $requiresApproval = true;
            }
    
            $scheduleData['total_cost'] = $totalCost;
    
            // Set status to awaiting_approval if approval is required
            if ($requiresApproval) {
                $scheduleData['status'] = 'awaiting_approval';
                $scheduleData['pending_approval'] = true;
            }
    
            // Update schedule
            $this->schedulesModel->update($id, $scheduleData);
    
            // Update bins for schedule by first removing old entries
            $this->scheduleBinsModel->where('schedule_id', $id)->delete();
            foreach ($bins as $bin) {
                $binCost = $this->calculateBinCost($bin['bin_size'], $bin['waste_type']) * $bin['quantity'];
                $binData = [
                    'schedule_id' => $id,
                    'bin_size_id' => $bin['bin_size'],
                    'waste_type_id' => $bin['waste_type'],
                    'quantity' => $bin['quantity'],
                    'cost' => $binCost,
                ];
    
                $this->scheduleBinsModel->insert($binData);
            }
    
            return redirect()->to('/schedules')->with('success', 'Schedule updated successfully.');
        }
    
        return view('schedules/manage', $data);
    }
    

    public function details($id) {
        $schedule = $this->schedulesModel->find($id);
    
        if (!$schedule) {
            return redirect()->to('/schedules')->with('error', 'Schedule not found.');
        }
    
        $scheduleBins = $this->scheduleBinsModel->where('schedule_id', $id)->findAll();
        $data = [
            'schedule' => $schedule,
            'scheduleBins' => $scheduleBins,
            'wasteTypes' => $this->wasteTypesModel->findAll(),
            'binSizes' => $this->binSizesModel->findAll(),
        ];
    
        return view('schedules/details', $data);
    }
    

    public function delete($id) {
        try {
            $this->schedulesModel->delete($id);
            return redirect()->to('/schedules')->with('success', 'Schedule deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete schedule.');
        }
    }
    

    private function calculateBinCost($binSizeId, $wasteTypeId)
    {
        $binSize = $this->binSizesModel->find($binSizeId);
        $wasteType = $this->wasteTypesModel->find($wasteTypeId);

        if (!$binSize || !$wasteType) {
            throw new \RuntimeException("Invalid bin size or waste type.");
        }

        return $binSize['size_multiplier'] * $wasteType['cost'];
    }
}
