<div id="paypal-button"></div>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
  paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
      sandbox: 'AZM6QPUQsSAV6LGUjnYk6OzR8V7hvqlxVCKk1kiFA77DyzSRWqTNE71Y_QXIYBdKosB1OQTpGXr_iZzM',
      production: 'demo_production_client_id'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
      size: 'small',
      color: 'gold',
      shape: 'pill',
    },

    // Enable Pay Now checkout flow (optional)
    commit: true,

    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        redirect_urls: {
          return_url:'http://localhost:8000/execute-payment'
        },
        //just mimic, real transaction is on server side
        transactions: [{
          amount: {
            total: '21',
            currency: 'USD'
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      console.log(data);
      return actions.redirect();
    }
  }, '#paypal-button');

</script>