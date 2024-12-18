<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\VerificationCodesModel;
use CodeIgniter\Controller;

class Users extends Controller {
    protected $usersModel;
    protected $verificationCodesModel;

    public function __construct() {
        $this->usersModel = new UsersModel();
        $this->verificationCodesModel = new VerificationCodesModel();
    }

    // Registration method
    public function register() {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
    
            $existingUser = $this->usersModel->where('email', $data['email'])->first();
    
            if ($existingUser) {
                if (!$existingUser['email_verified'] || !$existingUser['phone_verified']) {
                    // Redirect to verify page with option to resend verification code
                    session()->set('user_id', $existingUser['id']);
                    return redirect()->to('/users/verify-email')->with('info', 'Account already exists but is unverified. Please verify your account.');
                } else {
                    return redirect()->back()->with('error', 'An account with this email already exists.');
                }
            }
    
            // Proceed with new registration
            if ($this->usersModel->save($data)) {
                $userId = $this->usersModel->insertID();
                session()->set('user_id', $userId); // Store in session persistently
                $name = $this->request->getPost('name');
                $email = $this->request->getPost('email');
                $this->sendVerificationCodes($userId, $email, $name);
    
                return redirect()->to('/users/verify-email');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->usersModel->errors());
            }
        }
    
        return view('users/register');
    }
    

    // Combined method for verifying and resending email verification code
    public function verifyEmail() {
        $userId = session()->get('user_id');  // This will now persist
    
        if (!$userId) {
            return redirect()->to('/users/register')->with('error', 'Session expired. Please register again.');
        }
    
        if ($this->request->getMethod() === 'POST') {
            $inputCode = $this->request->getPost('email_code');
            $verificationCode = $this->verificationCodesModel->where('user_id', $userId)
                ->where('code', $inputCode)
                ->where('type', 'email')
                ->first();
    
            if ($verificationCode) {
                if ($verificationCode['code'] === $inputCode && strtotime($verificationCode['expires_at']) > time()) {
                    $this->usersModel->update($userId, ['email_verified' => true]);
                    $this->verificationCodesModel->delete($verificationCode['id']);
    
                    return redirect()->to('/users/login')->with('success', 'Email verified successfully. You can now log in.');
                } else {
                    return redirect()->back()->with('error', 'Invalid or expired verification code. Please try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid or expired verification code. Please try again.');
            }
        }
    
        return view('users/verify_email'); // Display verification form
    }
    

    // Resend a new email verification code
    public function resendEmailCode() {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/register')->with('error', 'Session expired. Please register again.');
        }

        $this->sendVerificationCodes($userId, 'email');

        return redirect()->back()->with('success', 'A new verification code has been sent to your email.');
    }

    // Combined method for verifying and resending phone verification code
    public function verifyPhone() {
        $userId = session()->get('user_id');

        if ($this->request->getMethod() === 'POST') {
            $inputCode = $this->request->getPost('phone_code');
            $verificationCode = $this->verificationCodesModel->where('user_id', $userId)
                ->where('code', $inputCode)
                ->where('type', 'phone')
                ->first();

            // var_dump($this->request->getMethod());
            // var_dump(session()->get('user_id'));

            if ($verificationCode) {
                if ($verificationCode['code'] === $inputCode && strtotime($verificationCode['expires_at']) > time()) {
                    $this->usersModel->update($userId, ['phone_verified' => true]);
                    $this->verificationCodesModel->delete($verificationCode['id']);

                    return redirect()->to('/users/login')->with('success', 'Phone verified successfully. You can now log in.');
                } else {
                    return redirect()->back()->with('error', 'Invalid or expired verification code.');
                }
            }  else {
                return redirect()->back()->with('error', 'Invalid or expired verification code.');
            };
        }

        return view('users/verify_phone'); // Display verification form
    }

    // Resend a new phone verification code
    public function resendPhoneCode() {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/users/register')->with('error', 'Session expired. Please register again.');
        }

        $this->sendVerificationCodes($userId, 'phone');

        return redirect()->back()->with('success', 'A new verification code has been sent to your phone.');
    }

    // Function to generate and send verification codes (generalized for email/phone)
    private function sendVerificationCodes($userId, $recipientEmail, $recipientName, $type = null) {
        if ($type === 'email' || $type === null) {
            $emailCode = $this->generateVerificationCode();
            $this->verificationCodesModel->save([
                'user_id' => $userId,
                'code' => $emailCode,
                'type' => 'email',
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
            ]);

            // Send the email verification code here
            $email = \Config\Services::email();
            $email->setFrom('benjaminamonoowilberforce8@gmail.com', 'Wostin');
            $email->setTo($recipientEmail);
            $email->setSubject('Your Verification Code');
            
            // Email Body
            $emailBody = "
                <p>Dear $recipientName,</p>
                <p>Your verification code is: <strong>$emailCode</strong></p>
                <p>Please enter this code on the verification page to complete your process.</p>
                <p>If you did not request this, please ignore this email.</p>
                <p>Thank you,<br>Wostin</p>
            ";
            $email->setMessage($emailBody);

            // Step 3: Send the Email and Handle Response
            if ($email->send()) {
                return true; // Email sent successfully
            } else {
                // Log or debug email errors
                log_message('error', $email->printDebugger(['headers', 'subject', 'body']));
                return false; // Email failed to send
            }
        }

        if ($type === 'phone' || $type === null) {
            $phoneCode = $this->generateVerificationCode();
            $this->verificationCodesModel->save([
                'user_id' => $userId,
                'code' => $phoneCode,
                'type' => 'phone',
                'expires_at' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
            ]);
            // Send the phone verification code via SMS here
        }
    }

    // Generate secure verification code
    private function generateVerificationCode($length = 6) {
        return strtoupper(bin2hex(random_bytes($length / 2)));
    }

    // Login method (redirect unverified users to verification)
    public function login() {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $user = $this->usersModel->where('email', $data['email'])->first();

            // var_dump($this->request->getMethod());
            // print_r($this->request->getPost());

            if ($user && password_verify($data['password'], $user['password'])) {
                if (!$user['email_verified']) {
                    session()->set('user_id', $user['id']);
                    return redirect()->to('/users/verify-email')->with('error', 'Please verify your email to proceed.');
                };
                // if (!$user['phone_verified']) {
                //     session()->set('user_id', $user['id']);
                //     return redirect()->to('/users/verify-phone')->with('error', 'Please verify your phone to proceed.');
                // };
                
                // session()->set('user_id', $user['id']);
                // return redirect()->to('/dashboard')->with('success', 'Logged in successfully.');

                // Store user ID and role in session
                session()->set([
                    'user_id' => $user['id'],
                    'role' => $user['user_group_id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'isLoggedIn' => true
                ]);

                // Redirect based on role
                return ($user['user_group_id'] == 1)
                    ? redirect()->to('/admin/dashboard')->with('success', 'Logged in successfully.')
                    : redirect()->to('/dashboard')->with('success', 'Logged in successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
            };
        };
        return view('users/login');
    }

    public function logout() {
        session()->set('isLoggedIn', false);
        session()->remove('user_id');
        session()->remove('role');
        session()->remove('email');
        session()->remove('name');
        session()->remove('isLoggedIn');
        session()->destroy(); // Destroys the entire session
        return redirect()->to('/users/login');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->usersModel->find($userId);

        return view('users/view_profile', ['user' => $user]);
    }

    public function edit_profile()
    {
        $userId = session()->get('user_id');
        $user = $this->usersModel->find($userId);
    
        if ($this->request->getMethod() === 'POST') {
            // Update logic when form is submitted
            $data = $this->request->getPost(['name', 'location']);

            // var_dump($data);
            // exit();
    
            if ($this->usersModel->update($userId, $data)) {
                return redirect()->to('/users/profile')->with('success', 'Profile updated successfully.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->usersModel->errors());
            }
        }
    
        return view('users/edit_profile', ['user' => $user]);
    }
    
    

}
