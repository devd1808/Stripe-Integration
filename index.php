<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.stripe.com/v3/"></script>
    <title>Debit Card Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            width: 40%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 50px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #45e2a9;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background: #35c29a;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- <h2>Debit Card Payment</h2> -->
    <form action="payment.php" method="post" id="payment-form">
  <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display Element errors. -->
    <div id="card-errors" role="alert"></div>
  </div>

  <button>Submit Payment</button>
</form>

</div>

<script>
    // Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/apikeys
const stripe = Stripe('pk_test_51QxXWG2Ln4kaPfjVZVqzG0bruuRF96BNbEathgsPFMEbWPAyNslK509xImatIOaddaExcF2RJBumEbkZTMRWvuoK00sgWioHOb');
const elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
const style = {
  base: {
    // Add your base input styles here. For example:
    fontSize: '16px',
    color: '#32325d',
  },
};

// Create an instance of the card Element.
const card = elements.create('card', {style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Create a token or display an error when the form is submitted.
const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
  event.preventDefault();

  const {token, error} = await stripe.createToken(card);

  if (error) {
    // Inform the customer that there was an error.
    const errorElement = document.getElementById('card-errors');
    errorElement.textContent = error.message;
  } else {
    // Send the token to your server.
    stripeTokenHandler(token);
  }
});


const stripeTokenHandler = (token) => {
  // Insert the token ID into the form so it gets submitted to the server
  const form = document.getElementById('payment-form');
  const hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Get user_id (e.g., from query params or a predefined value)
  const urlParams = new URLSearchParams(window.location.search);
  const userId = urlParams.get('user_id') || 'default_user';
  const chargeAmount = urlParams.get('amount') || '50'; // Default to 1000 cents ($10)

// Modify form action to include user_id and amount
form.action = `payment.php?user_id=${encodeURIComponent(userId)}&amount=${encodeURIComponent(chargeAmount)}`;

  // Submit the form
  form.submit();
}



</script>

</body>
</html>
