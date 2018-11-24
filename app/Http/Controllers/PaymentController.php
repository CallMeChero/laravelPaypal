<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Amount; 
use PayPal\Api\Details; 
use PayPal\Api\Item; 
use PayPal\Api\ItemList; 
use PayPal\Api\Payer; 
use PayPal\Api\Payment; 
use PayPal\Api\RedirectUrls; 
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

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

    public function createPayment() {

    	$apiContext = new \PayPal\Rest\ApiContext(
	        new \PayPal\Auth\OAuthTokenCredential(
	            'AZM6QPUQsSAV6LGUjnYk6OzR8V7hvqlxVCKk1kiFA77DyzSRWqTNE71Y_QXIYBdKosB1OQTpGXr_iZzM',     // ClientID
	            'EEzOTTHejBI9itN-y2c7Ikt_bIZ68AGXQsyDAPxOz-hHUph7SZNITuhqd4JwiXFPBnJr5cW1D6j1fkeU'      // ClientSecret
        	)
		);

    	$payer = new Payer(); 
    	$payer->setPaymentMethod("paypal");

    	$item1 = new Item();
		$item1->setName('Ground Coffee 40 oz')
			  ->setCurrency('USD')
			  ->setQuantity(1)
			  ->setSku("123123") // Similar to `item_number` in Classic API
			  ->setPrice(7.5);

		$item2 = new Item();
		$item2->setName('Granola bars')
			  ->setCurrency('USD')
			  ->setQuantity(5)
			  ->setSku("321321") // Similar to `item_number` in Classic API
			  ->setPrice(2);

		$itemList = new ItemList();
		$itemList->setItems(array($item1, $item2));

		$details = new Details();
		$details->setShipping(1.2)
			    ->setTax(1.3)
			    ->setSubtotal(17.50);

		$amount = new Amount();
		$amount->setCurrency("USD")
			   ->setTotal(20)
			   ->setDetails($details);

		$transaction = new Transaction(); 
		$transaction->setAmount($amount) 
					->setItemList($itemList) 
					->setDescription("Payment description") 
					->setInvoiceNumber(uniqid());


		$redirectUrls = new RedirectUrls(); 
		$redirectUrls->setReturnUrl("http://localhost:8000/execute-payment") 
		  			 ->setCancelUrl("http://localhost:8000/cancel");

		$payment = new Payment(); 
		$payment->setIntent("sale") 
				->setPayer($payer) 
				->setRedirectUrls($redirectUrls) 
				->setTransactions(array($transaction));

		$payment->create($apiContext);

		return redirect($payment->getApprovalLink());
	}
}
