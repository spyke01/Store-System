<?
/***************************************************************************
 *                               paypalipn.php
 *                            -------------------
 *   begin                : Saturday', Sept 24', 2005
 *   copyright            : ('C) 2005 Paden Clayton
 *   email                : padenc2001@gmail.com
 *
 *
 ***************************************************************************/

/***************************************************************************
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ***************************************************************************/
	include 'includes/header.php';

	// initialise a variable with the requried cmd parameter
	$req = 'cmd=_notify-validate';

	//=========================================================
	// Hit the PayPal Servers to make sure the payment processed
	//=========================================================	
	// go through each of the POSTed vars and add them to the variable
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$req .= "&" . $key . "=" . $value;
	}
	
	// post back to PayPal system to validate
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	// Connect to the PayPal server
	// If possible, securely post back to paypal using HTTPS
	// Your PHP server will need to be SSL enabled
	$fp = ($ss_config['ftsss_use_https'] == 1) ? fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30) : fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
	
	if (!$fp) {
		// HTTP ERROR Failed to connect		
		// Email us that an error occurred
		emailMessage($ss_config['ftsss_sales_email'], "IPN Error", $errstr);
		
		// If you want to log to a file as well then uncomment the following lines
		// You can use these later on in the script as well
		// 
		// $fh = fopen("logipn.txt", 'a');//open file and create if does not exist
		// fwrite($fh, "\r\n/////////////////////////////////////////\r\n HTTP ERROR \r\n");//Just for spacing in log file
		//
		// fwrite($fh, $errstr);//write data
		// fclose($fh);//close file	
	}
	else {
		fputs ($fp, $header . $req);
		
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp ($res, "VERIFIED") == 0) {
				// assign posted variables to local variables
				// the actual variables POSTed will vary depending on your application.
				// there are a huge number of possible variables that can be used. See the paypal documentation.
				
				// the ones shown here are what is needed for a simple purchase
				// a "custom" variable is available for you to pass whatever you want in it. 
				// if you have many complex variables to pass it is possible to use session variables to pass them.
				
				$item_name = $_POST['item_name'];
				$item_number = $_POST['item_number'];
				$item_colour = $_POST['custom'];  
				$payment_status = $_POST['payment_status'];
				$payment_amount = $_POST['mc_gross'];         //full amount of payment. payment_gross in US
				$payment_currency = $_POST['mc_currency'];
				$txn_id = $_POST['txn_id'];                   //unique transaction id
				$receiver_email = $_POST['receiver_email'];
				$payer_email = $_POST['payer_email'];
				
				// use the above params to look up what the price of an order should be.
				$orderPrice = 0; // you need to create this code to find out what the price for the item they bought really is so that you can check it against what they have paid. This is an anti hacker check.
				$orderTXID = ""; // you need to create this code to find out what the price for the item they bought really is so that you can check it against what they have paid. This is an anti hacker check.
				
				// Get order cost and txn_id
				$sql = "SELECT price, txn_id FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $item_number . "' LIMIT 1";
				$result = mysql_query($sql);
			
				if (mysql_num_rows($result) > 0) {							
					while ($row = mysql_fetch_array($result)) {
						$orderPrice = $row['price'];
						$orderTXID = $row['txn_id'];
					}
					mysql_free_result($result);
				}
				
				// the next part is also very important from a security point of view
				// you must check at the least the following...
				
				if (($payment_status == 'Completed') &&   //payment_status = Completed
					($receiver_email == $ss_config['ftsss_paypal_email']) &&   // receiver_email is same as your account email
					($payment_amount == $orderPrice ) &&  //check they payed what they should have
					($payment_currency == $FTS_PAYPAL_CURRENCIES[$ss_config['ftsss_currency_type']]) &&  // and its the correct currency 
					($orderTXID == "")) {  //txn_id isn't same as previous to stop duplicate payments.
					
					// We received a good payment so update our ourder
					$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET status='" . STATUS_PAYMENT_PROCESSED . "', txn_id = '" . $txn_id . "' WHERE id='" . $item_number . "' LIMIT 1";
					$result = mysql_query($sql);
					
					// uncomment this section during development to receive an email to indicate whats happened
					// emailMessage($ss_config['ftsss_sales_email'], "IPN Success", "completed: " . $item_number . " " . $txn_id);
				}
				else {
					//
					// paypal replied with something other than completed or one of the security checks failed.
					// you might want to do some extra processing here
					//
					//in this application we only accept a status of "Completed" and treat all others as failure. You may want to handle the other possibilities differently
					//payment_status can be one of the following
					//Canceled_Reversal: A reversal has been canceled. For example, you won a dispute with the customer, and the funds for
					//                           Completed the transaction that was reversed have been returned to you.
					//Completed:            The payment has been completed, and the funds have been added successfully to your account balance.
					//Denied:                 You denied the payment. This happens only if the payment was previously pending because of possible
					//                            reasons described for the PendingReason element.
					//Expired:                 This authorization has expired and cannot be captured.
					//Failed:                   The payment has failed. This happens only if the payment was made from your customer’s bank account.
					//Pending:                The payment is pending. See pending_reason for more information.
					//Refunded:              You refunded the payment.
					//Reversed:              A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from
					//                          your account balance and returned to the buyer. The reason for the
					//                           reversal is specified in the ReasonCode element.
					//Processed:            A payment has been accepted.
					//Voided:                 This authorization has been voided.
					//
					
					// we will send an email to say that something went wrong
					emailMessage($ss_config['ftsss_sales_email'], "IPN Error", "PayPal IPN status not completed or security check failed. <br />The transaction ID number is: " . $txn_id . " <br /> Payment status = " . $payment_status . " <br /> Payment amount = " . $payment_amount);
				}
			}
			else if (strcmp ($res, "INVALID") == 0) {
				// Paypal didnt like what we sent. If you start getting these after system was working ok in the past, check if Paypal has altered its IPN format
				emailMessage($ss_config['ftsss_sales_email'], "IPN Error", "We have had an INVALID response. <br />The transaction ID number is: " . $txn_id . " <br /> username = " . $username);
			}
		} //end of while
		fclose ($fp);
	}

?>