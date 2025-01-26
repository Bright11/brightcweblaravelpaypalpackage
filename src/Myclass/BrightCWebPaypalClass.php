<?php

namespace Brightcweb\Paypal\Myclass;

use Brightcweb\Paypal\Models\Brightcwebpayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class BrightCWebPaypalClass
{
    /**
     * Create a new class instance.
     */
    protected $clientId;
    protected $clientSecret;
    protected $apiUrl;
   

    public function __construct()
    {
        // gettingpaypalid
        $this->clientId=config("brightpaypalconfig.client_id");
        $this->clientSecret=config("brightpaypalconfig.client_secret");
        $this->apiUrl="https://api.sandbox.paypal.com";
        //$this->successRoute = config('brightpaypalconfig.successroute');
    }
public function getAccessToken()
{
    $response=Http::withBasicAuth($this->clientId,$this->clientSecret)->asForm()->post("{$this->apiUrl}/v1/oauth2/token",[
        'grant_type'=>'client_credentials'
    ]);
    return $response->json()['access_token']??null;
}

public function createPaypalOrder($amount)
{
    $accessToken=$this->getAccessToken();
    $successRoute = config('brightpaypalconfig.sucesspaypal');
    $canceledRouteURL=config('brightpaypalconfig.canceledRouteURL');
    if($accessToken){
        if (!Route::has($successRoute)) {
            abort(500, "Error: Route '{$successRoute}' is not defined in routes/web.php.");
        }
        if (!Route::has($canceledRouteURL)) {
            abort(500, "Error: Route '{$canceledRouteURL}' is not defined in routes/web.php.");
        }

        // Check if amount is provided
    if (is_null($amount)) {
        return redirect()->back();
    }

    // Check if amount is numeric
    if (!is_numeric($amount)) {
        return redirect()->back();
    }

    // Check if amount is greater than 0
    if ($amount <= 0) {
        return redirect()->back();
    }

        $response=Http::withToken($accessToken)->post("{$this->apiUrl}/v2/checkout/orders",[
            'intent'=>'CAPTURE',
           'purchase_units'=>[
                [
                    'amount'=>[
                        'currency_code'=>'USD',
                       'value'=>$amount,
                    ],
                ]
                ],
                'application_context'=>[
                //     'brand_name'=>'Laravel paypal',
                //     'logo_url'=>'https://avatars.githubusercontent.com/u/34070274?s=96&v=4',
                //    'support_email'=>'support@my-app.com',
                //    'support_phone'=>'+1-800-555-5555',
               
                   'return_url'=>route($successRoute),
                    'cancel_url'=>route($canceledRouteURL),
                ],
        ]);
        return $response->json();
    }

    
}

public function captureOrder($orderId, $payerId){
    $accessToken=$this->getAccessToken();
    if($accessToken){
        $response=Http::withToken($accessToken)->post("{$this->apiUrl}/v2/checkout/orders/{$orderId}/capture",[
            'payer_id'=>$payerId
        ]);
        if(!$response->successful()){
            abort(500,"Error capture order with paypal");
        }
        return $response->json();
    }
}

// order completed
public function handlePaymentSuccess($orderId, $payerId)
    {
     
        $order = $this->captureOrder($orderId, $payerId);

        if (isset($order['status']) && $order['status'] === 'COMPLETED') {
            $payerName = $order['payer']['name']['given_name'] ?? 'No Name';
            $paymentId = $order['id'] ?? null;
            $amount = $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0;
            $currency = $order['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] ?? 'USD';
            $paymentStatus = $order['status'] ?? 'Failed';

            // Optional payment source details
            $paymentMethod = $order['payment_source']['type'] ?? 'Unknown';
            $cardNumber = $order['payment_source']['card']['last4'] ?? null;
            $bankName = $order['payment_source']['bank']['name'] ?? 'No Bank';
            $cardType = $order['payment_source']['card']['type'] ?? 'No Card';

            // Save payment details to the database
            $payment = new Brightcwebpayment();
            if(Auth::check()){
                $payment->user_id = Auth::id();
            }
            $payment->payer_name = $payerName;
            $payment->payment_id = $paymentId;
            $payment->paid_amount = $amount;
            $payment->payment_type = 'Paypal';
            $payment->currency = $currency;
            $payment->payment_status = $paymentStatus;
            $payment->payment_channel = $paymentMethod;
            $payment->bank = $bankName;
            $payment->card_type = $cardType;
            $payment->card_no = $cardNumber;
            $payment->save();

            // Return details for the success view
            return compact('payerName', 'paymentId', 'amount', 'paymentStatus', 'currency', 'cardType');
        }

        return false; // Indicate payment failure
    }
}
