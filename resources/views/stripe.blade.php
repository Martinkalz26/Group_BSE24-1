<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Redirecting to Stripe...</h1>
    <script type="text/javascript">
        var stripe = Stripe("{{ config('stripe.stripe_pk') }}");

        stripe.redirectToCheckout({
            sessionId: "{{ $session_id }}"
        }).then(function (result) {
            if (result.error) {
                // Display error.message in your UI.
                alert(result.error.message);
            }
        });
    </script>
</body>
</html>
