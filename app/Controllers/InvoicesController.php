<?php

namespace App\Controllers;

use App\Helpers\SQL;
use App\Services\PaytabsService;
use stdClass;

class InvoicesController extends Controller
{
    const REQUEQST_PAYMENT = 'payment/request';
    const NGROK_URL = "https://0851-41-235-214-115.ngrok-free.app";
    const CALLBACK_URL = self::NGROK_URL . "/paytabs-dev/callback-url";
    const RETURN_URL = self::NGROK_URL . "/paytabs-dev/return-url";

    private PaytabsService $paytabsService;

    public function __construct()
    {
        parent::__construct();
        $this->paytabsService = new PaytabsService();
    }

    public function showForm()
    {
        $fileName = 'new-invoice';
        return view($fileName);
    }

    public function createInvoice()
    {
        $postData = $this->getPostData();
        $invoiceData = $this->getInvoiceData();
        $postData['invoice'] = $invoiceData;

        try{

            $resp = $this->paytabsService->send_api_request(self::REQUEQST_PAYMENT, $postData);
            // echo 'test' . '<br>' . '<pre>';
            if($resp && array_key_exists('tran_ref', $resp) && !empty($resp['tran_ref'])){
                header('Location: '.$resp['redirect_url']);
            }else{
                echo 'error: <br>';
                print_r($resp);
            }

        }catch(\PDOException $e){
            return $e->getMessage();
        }
    }

    private function getInvoiceData()
    {
        return [
            "shipping_charges" => 0,
            "extra_charges" => 0,
            "extra_discount" => 0,
            "total" => 30,
            "line_items" => [
                $this->getItemData(),
                $this->getItemData(),
            ]
            ];
    }

    private function getItemData()
    {
        return [
            "url" => "https://paytabs.com",
            "unit_cost" => 5,
            "quantity" => 3,
            "net_total" => 15,
            "discount_rate" => 0,
            "discount_amount" => 0,
            "tax_rate" => 0,
            "tax_total" => 0,
            "total" => 15
        ];
    }

    private function getPostData()
    {
        return [
            "tran_type"         => "sale",
            "tran_class"        => "ecom",
            "cart_id"           => "1212",
            "cart_currency"     => "EGP",
            "cart_amount"       => 30,
            "cart_description"  => "Description of the items/services",
            "paypage_lang"      => "en",
            "customer_details"  => [
                "name"      => "first last",
                "email"     => "email@domain.com",
                "phone"     => "0522222222",
                "street1"   => "address street",
                "city"      => "dubai",
                "state"     => "du",
                "country"   => "AE",
                "zip"       => "12345"
            ],
            "shipping_details"      => [
                "name"          => "name1 last1",
                "email"         => "email1@domain.com",
                "phone"         => "971555555555",
                "street1"       => "street2",
                "city"          => "dubai",
                "state"         => "dubai",
                "country"       => "AE",
                "zip"           => "54321"
            ],
            "callback"      => self::CALLBACK_URL,
            "return"        => self::RETURN_URL,
        ]; 
    }

    public function callbackUrl()
    {
        $myfile = "paytabs-callback.txt";
        $requestBody = file_get_contents('php://input');
        // $decodedData = json_decode($requestBody);

        $signatureHeader = $_SERVER['HTTP_SIGNATURE'];
        
        if($this->paytabsService->is_genuine($requestBody, $signatureHeader)){
            return file_put_contents(
                $myfile,
                "GENUINE SOURCE:  " . 
                $this->paytabsService->get_signature($requestBody) .
                '  headerIs : ' .
                $signatureHeader . 
                '   ' .
                json_encode($requestBody)
            );
        }

        return file_put_contents(
                $myfile,
                "NOT GENUINE:  " . 
                $this->paytabsService->get_signature($requestBody) .
                '  headerIs : ' .
                $signatureHeader
            );
    }

    public function returnUrl()
    {
        $myfile = "paytabs-return.txt";
        
        $postData = filter_input_array(INPUT_POST);

        // file_put_contents($myfile, json_encode($postData) );

        if(! $this->paytabsService->is_valid_redirect($postData)){
            $_SESSION['error'] = 'Failed Payment';
            return header('Location: '. self::NGROK_URL . "/paytabs-dev");
        }

        $_SESSION['success'] = 'Success Payment. Here is Transaction ref : "' . $postData['tranRef'] . '"';
        $_SESSION['transRef'] = $postData['tranRef'];
        return header('Location: '. self::NGROK_URL . "/paytabs-dev");

    }
    public function followRefund()
    {
        $transRef = $_SESSION['transRef'];
        unset($_SESSION['transRef']);

        $refundData = [
            "profile_id"        => PaytabsService::PROFILE_ID,
            "tran_type"         => "refund",
            "tran_class"        => "ecom",
            "cart_id"           => "cart_66666",
            "cart_currency"     => "EGP",
            "cart_amount"       => 1.5,
            "cart_description"  => "Refund reason",
            "tran_ref"          => $transRef
        ];

        try{

            $resp = $this->paytabsService->send_api_request(self::REQUEQST_PAYMENT, $refundData);

            if($resp && array_key_exists('tran_ref', $resp) && !empty($resp['tran_ref'])){
                $_SESSION['success'] = 'Success Refund. Here is Transaction ref : "' . $resp['tran_ref'] . '"';
                return header('Location: '. self::NGROK_URL . "/paytabs-dev");
            }else{
                $_SESSION['error'] = 'Error Refund';
                return header('Location: '. self::NGROK_URL . "/paytabs-dev");
            }

        }catch(\PDOException $e){
            return $e->getMessage();
        }
    }

    public function ownFormPayment()
    {
        $postData = $this->getPostData();
        $invoiceData = $this->getInvoiceData();
        $postData['invoice'] = $invoiceData;
        $postData['card_details'] = $this->getCardDetails();

        try{

            $resp = $this->paytabsService->send_api_request(self::REQUEQST_PAYMENT, $postData);
            // echo 'test' . '<br>' . '<pre>';
            if($resp && array_key_exists('tran_ref', $resp) && !empty($resp['tran_ref'])){
                print_r($resp);
            }else{
                echo 'error: <br>';
                print_r($resp);
            }

        }catch(\PDOException $e){
            return $e->getMessage();
        }
    }

    private function getCardDetails()
    {
        return [
            "pan"               => "5123450000000008",
            "cvv"               => "100",
            "expiry_month"      => 1,
            "expiry_year"       => 2039
        ];
    }
}

?>