<?php 
/***************************************************************************
 *                               ccreceipt.php
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
 // This page records the credit card information returned from CyberSource
 
 	include("includes/HOP.php"); 
 
	//=======================================================
	// Set up our variables
	//=======================================================
 	$comments = split("#", keeptasafe($_REQUEST['comments']));
 	$orderid = trim($comments[1]);
 	$transactionSignature = keeptasafe($_REQUEST['transactionSignature']);
 	$decision = keeptasafe($_REQUEST['decision']);
 	$reasonCode = keeptasafe($_REQUEST['reasonCode']);
 	$timestamp = time();
	
 	$_SESSION['orderid'] = $orderid;
	$_SESSION['userid'] = getUserIDByOrderID($orderid);
 	
	if ($orderid <> "") {
		//=======================================================
 		// Update credit card DB
		//=======================================================
		$sql = "UPDATE `" . DBTABLEPREFIX . "creditcards` SET trans_signature='" . $transactionSignature . "', trans_result='" . $decision . "', trans_code='" . $reasonCode . "', trans_timestamp='" . $timestamp . "', trans_tried=trans_tried+1, trans_verify_signature='" . VerifyTransactionSignature($_POST) . "' WHERE order_id='" . $_SESSION['orderid'] . "'";
		$result = mysql_query($sql);
	 
		//=======================================================
	 	//Notify the user of the new order	
		//=======================================================
		$sql = "SELECT email_address, first_name, last_name FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $_SESSION['userid'] . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				if ($decision == "ACCEPT") {
					$emailMessage = "To our valued customer " . $row['first_name'] . " " . $row['last_name'] . ",<br />
						Thank you for placing an order with " . $ss_config['ftsss_store_name'] . ". Your payment has been processed, and your order is currently being built. You can always check on your order status by logging into your userpanel at: <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a>.
						<br /><br />
						Sincerely,
						<br />
						The " . $ss_config['ftsss_store_name'] . " Team<br /><br />" . returnInvoice($_SESSION['orderid'], $_SESSION['userid']);
				}
				else {
					$emailMessage = "To our valued customer " . $row['first_name'] . " " . $row['last_name'] . ",<br />
						Thank you for placing an order with " . $ss_config['ftsss_store_name'] . ". Unfortunately, your credit card payment has been declined. Please contact your bank to authorize the charge, or <a href=\"" . $ss_config['ftsss_store_url'] . "/?p=ccretry&id=" . $_SESSION['orderid'] . "\">click here to retry your card</a>. You can also retry your card by logging into your userpanel at: <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a> and clicking the link to 'Retry Credit Card'.
						<br /><br />
						Sincerely,
						<br />
						The " . $ss_config['ftsss_store_name'] . " Team<br /><br />" . returnInvoice($_SESSION['orderid'], $_SESSION['userid']);
				}		
						
				$emailSubject = $ss_config['ftsss_store_name'] . " Order #" . $_SESSION['orderid'];
				$success = emailMessage($row['email_address'], $emailSubject, $emailMessage);
			}
			mysql_free_result($result);
		}
		
		//=======================================================
		//Notify the company of the new order
		//=======================================================
		$EmailSubject = "New Order #" . $_SESSION['orderid'];
		$EmailMessage = "<strong>Order #:</strong> " . $_SESSION['orderid'] . "<br /><br />
						A new order has been submitted, you can view it on the orders page by signing into <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a>";
		$success = emailMessage($ss_config['ftsss_sales_email'], $emailSubject, $emailMessage);
			
		// Update Order Status and Info
		if ($decision == "ACCEPT") {
			changeOrderStatus($_SESSION['orderid'], STATUS_PAYMENT_PROCESSED);
		}
		else {
			changeOrderStatus($_SESSION['orderid'], STATUS_CREDIT_CARD_PAYMENT);	
		}
		
		//=======================================================
		// Print page
		//=======================================================
		$processingResultText = ($decision == "ACCEPT") ? "<span style=\"color: green;\">Your credit card has been successfully charged.</span>" : "<span style=\"color: red;\">Your credit card has been denied or an error has occurred. Please contact your bank to authorize our attempt to charge your card.</span>";
		
		$page_content .= "
							Order <strong>#" . $_SESSION['orderid'] . "</strong> has been entered into our system. " . $processingResultText . "
							<br /><br />
							To view a printer friendly version of this invoice, <a href=\"" . $menuvar['VIEWINVOICE'] . "&id=" . $_SESSION['orderid'] . "&style=printerFriendly\" target=\"_blank\">please click here.</a><br />";
		
		$page_content .= returnInvoice($_SESSION['orderid'], $_SESSION['userid']);	
		
		// Kill the orderid
		$_SESSION['orderid'] = "";
		
		// Print to page					
		$page->setTemplateVar("PageContent", $page_content);
 	}
 ?>