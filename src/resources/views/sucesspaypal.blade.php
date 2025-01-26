
<style>
     body{
        background-color: white;
    }
    .successpayment {
        max-width: 90%;
        margin: 50px auto;
    }
    .successpayment h1 {
        text-align: center;
        font-size: 36px;
        color: black;
    }
    .successpayment p{
        text-align: center;
        font-size: 36px;
        color: black;
    }
    h1{
        font-size: 36px;
        color: black !important;
    }
    p{
        color: gray !important;
        font-size: 24px;
    }
    h2{
        font-size: 36px;
        color: black !important;
        text-align: center;
    }
</style>
<section class="successpayment">
    <h1>Payment Successful</h1>
    <br>
 
    <p>Thank you for your payment, {{ $payerName }}.</p>
    <p>Payment ID: {{ $paymentId }}</p>
    <p>Amount: ${{ $amount }}</p>
    <p>Payment Status: {{ $paymentStatus }}</p>
    @if ($cardType)
        <p>Card Type: {{ $cardType }}</p>
    @endif
</section>

