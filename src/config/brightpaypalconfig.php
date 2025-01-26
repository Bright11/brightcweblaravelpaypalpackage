<?php
// #eceff1

return[
       'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'api_url' => env('PAYPAL_API_URL', 'https://api.sandbox.paypal.com'),
      
       'sucesspaypal' => env('PAYPAL_SUCCESS_ROUTE'),
         "canceledRouteURL"=>env("PAYPAL_CANCELLED_ROUTE"),

];