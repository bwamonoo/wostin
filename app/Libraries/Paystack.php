<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Exceptions\HTTPException;

class Paystack
{
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        $this->secretKey = getenv('PAYSTACK_SECRET_KEY');
        $this->baseUrl = 'https://api.paystack.co';
    }

    /**
     * Initialize a payment with Paystack
     *
     * @param string $email
     * @param float $amount
     * @param string $callbackUrl
     * @return array|bool
     */
    public function initializePayment($email, $amount, $callbackUrl)
    {
        $url = "{$this->baseUrl}/transaction/initialize";
        $amountInKobo = $amount * 100;

        $data = [
            'email' => $email,
            'amount' => $amountInKobo,
            'callback_url' => $callbackUrl,
        ];

        $response = $this->sendRequest('POST', $url, $data);

        if (isset($response['status']) && $response['status'] === true) {
            return $response['data'];
        }

        throw new \Exception($response['message'] ?? 'Payment initialization failed');
    }

    /**
     * Verify the status of a transaction
     *
     * @param string $reference
     * @return array|bool
     */
    public function verifyTransaction($reference)
    {
        $url = "{$this->baseUrl}/transaction/verify/{$reference}";

        $response = $this->sendRequest('GET', $url);

        if (isset($response['status']) && $response['status'] === true) {
            return $response['data'];
        }

        throw new \Exception($response['message'] ?? 'Payment verification failed');
    }

    /**
     * Send an HTTP request to Paystack
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @return array
     */
    private function sendRequest($method, $url, $data = [])
    {
        $client = \Config\Services::curlrequest();
        $options = [
            'headers' => [
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
            'http_errors' => false,
        ];

        $response = $client->request($method, $url, $options);

        if ($response->getStatusCode() !== 200) {
            throw new HTTPException("HTTP Error: {$response->getBody()}", $response->getStatusCode());
        }

        return json_decode($response->getBody(), true);
    }
}
