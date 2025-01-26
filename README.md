
# BrightCWeb PayPal Integration Package

**Brightcweb/Paypal** is a Laravel package that simplifies PayPal integration for your e-commerce system or donation platform. With this package, you can handle payments efficiently while maintaining the flexibility to customize routes, views, and authentication requirements.

---

## Features
- Seamless PayPal checkout integration.
- Customizable success and cancellation routes.
- Records all payment transactions in the `Brightcwebpayment` table.
- Optionally enforce user authentication before checkout.
- Pre-built, modifiable views for success and cancellation pages.

---

## Installation

### Install the package via Composer:

```bash
composer require brightcweb/paypal
```

### Publish the package configuration assets:

```bash
php artisan vendor:publish --tag=brightpaypalconfig
```

### Run the migrations to create the `Brightcwebpayment` table:

```bash
php artisan migrate
```

### Add the following entries to your `.env` file:

```bash
PAYPAL_CLIENT_ID=your-paypal-client-id
PAYPAL_CLIENT_SECRET=your-paypal-client-secret
```

### Clear and cache the configuration:

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

## Usage

### Controller Setup

- In your controller, import the PayPal class and inject it into the constructor:

```php
use Brightcweb\Paypal\Myclass\BrightCWebPaypalClass;

class YourController extends Controller
{
    protected $BrightCwebPaypalClass;

    public function __construct(BrightCWebPaypalClass $BrightCWebPaypalClass)
    {
        $this->BrightCwebPaypalClass = $BrightCWebPaypalClass;
    }
}
```

### Checkout Endpoint

Create a route and controller method for initiating the checkout process. Example:

```php
public function checkout(Request $req)
{
    $amount = $req->amount; // Get order or donation amount
    $paymentData = $this->BrightCwebPaypalClass->createPaypalOrder($amount);

    return redirect($paymentData['links'][1]['href']);
}
```

Define the route in `web.php`:

```php
Route::get('/checkout', [YourController::class, 'checkout'])->name('checkout');
```

### Success and Cancel Routes

Add routes for handling successful and canceled payments:

```php
public function successpaypal(Request $request)
{
    $orderId = $request->query('token');
    $payerId = $request->query('PayerID');

    $paymentDetails = $this->BrightCwebPaypalClass->handlePaymentSuccess($orderId, $payerId);

    if ($paymentDetails) {
        // Optionally send notifications or update tables
        return view('vendor.brightcwebpaypal.sucesspaypal', $paymentDetails);
    }

    return view('vendor.brightcwebpaypal.canceled');
}

public function cancelpaypal()
{
    return view('vendor.brightcwebpaypal.canceled');
}
```

Define these routes in `web.php`:

```php
Route::get('/successpaypal', [YourController::class, 'successpaypal'])->name('successpaypal');
Route::get('/cancelpaypal', [YourController::class, 'cancelpaypal'])->name('cancelpaypal');
```

### Add these routes in your `.env` file:

```bash
PAYPAL_SUCCESS_ROUTE="success payment route name"
PAYPAL_CANCELLED_ROUTE="cancelled payment route name"
```

---

## Views Customization

The package includes default views for success and cancellation located in `vendor/brightcwebpaypal`. Feel free to modify or rename them according to your needs.

### Authentication

This package allows you to decide whether users should be authenticated before proceeding with checkout or not. You can customize your middleware as needed.

---

## Key Features

### Payment Recording

Every successful payment is recorded in the `Brightcwebpayment` table for easy tracking.

### Optional Authentication

You have full control over whether users need to be authenticated before initiating a checkout or not. This package works perfectly without authentication, which is useful in scenarios where registration is not required for checkout.

---

## Clearing and Caching Configuration

Whenever you make changes to your `.env` file or configuration, remember to run the following commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

## Notes

- Ensure that your PayPal credentials in the `.env` file are correct.
- Ensure that `PAYPAL_SUCCESS_ROUTE` and `PAYPAL_CANCELLED_ROUTE` are correctly set in your `.env` file.
- Always test your integration in PayPal's sandbox environment before going live.
- Use `php artisan optimize:clear` if you encounter any cache-related issues.

---

## Support

For support, please send us an email at: [chikanwazuo@gmail.com](mailto:chikanwazuo@gmail.com)

---

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Contributing

We welcome contributions to enhance this package. Please contact us at [chikanwazuo@gmail.com](mailto:chikanwazuo@gmail.com) for any inquiries or contributions.

---

## Conclusion

The Brightcweb PayPal Package is an excellent tool for integrating a payment gateway into your Laravel application, suitable for any project requiring PayPal payment integration.
