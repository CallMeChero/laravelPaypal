<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaymentController extends Controller
{
    public function storeInfo() {
    	$apiContext = new \PayPal\Rest\ApiContext(
	        new \PayPal\Auth\OAuthTokenCredential(
	            'AZM6QPUQsSAV6LGUjnYk6OzR8V7hvqlxVCKk1kiFA77DyzSRWqTNE71Y_QXIYBdKosB1OQTpGXr_iZzM',     // ClientID
	            'EEzOTTHejBI9itN-y2c7Ikt_bIZ68AGXQsyDAPxOz-hHUph7SZNITuhqd4JwiXFPBnJr5cW1D6j1fkeU'      // ClientSecret
        	)
		);

		$paymentId = request('paymentId');
    	$payment = Payment::get($paymentId, $apiContext);

    	$execution = new PaymentExecution();
    	$execution->setPayerId(request('PayerID'));

    	$transaction = new Transaction();
	    $amount = new Amount();
	    $details = new Details();

	    $details->setShipping(2.2)
	           ->setTax(1.3)
	           ->setSubtotal(17.50);

	    $amount->setCurrency('USD');
	    $amount->setTotal(21);
	    $amount->setDetails($details);
	    $transaction->setAmount($amount);

	    $execution->addTransaction($transaction);

	    $result = $payment->execute($execution, $apiContext);

	    return $result;
    }
}
