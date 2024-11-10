<?php

namespace App\Controllers;

use App\Libraries\Paystack;

class PaymentController extends BaseController
{
    protected $paystack;

    public function __construct()
    {
        $this->paystack = new Paystack();
    }

    public function initializePayment()
    {
        $email = $this->request->getPost('email');
        $amount = $this->request->getPost('amount');
        $callbackUrl = base_url('/payment/callback'); // Replace with your callback URL

        try {
            $paymentData = $this->paystack->initializePayment($email, $amount, $callbackUrl);
            return redirect()->to($paymentData['authorization_url']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function verifyTransaction($reference)
    {
        try {
            $transactionData = $this->paystack->verifyTransaction($reference);
            if ($transactionData['status'] === 'success') {
                // Process successful payment
                // e.g., update your database records
                return redirect()->to('/schedules')->with('success', 'Payment successful.');
            } else {
                return redirect()->back()->with('error', 'Payment verification failed.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
