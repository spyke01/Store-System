<?
/***************************************************************************
 *                               ccpayment.php
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
 // This page records the credit card information for manual processing
 
	// Insert CC for this order
 	$postname_on_card = keeptasafe($_POST['name_on_card']);
	$postcard_type = keeptasafe($_POST['card_type']);
	$postcard_number = keeptasafe($_POST['card_number']);
	$postexp_month = keepsafe($_POST['exp_month']);
	$postexp_year = keepsafe($_POST['exp_year']);
	$postcard_sid = keepsafe($_POST['card_sid']);
	$postbank_name = keeptasafe($_POST['bank_name']);
	$postbank_number = keeptasafe($_POST['bank_number']);
	$updateCardNumber = "";
	
	// Kill old order creditcard entries for this orderid
	$sql = "DELETE FROM `" . DBTABLEPREFIX . "creditcards` WHERE order_id='" . $_SESSION['orderid'] . "';";
	$result = mysql_query($sql);
	
	// Get card number in the database in case the user didnt change it
	$sql = "SELECT card_number, card_sid FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $_SESSION['userid'] . "';";
	$result = mysql_query($sql);
				
	if (mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_array($result)) {
			$card_number = $row['card_number'];
			$card_sid = $row['card_sid'];
		}
	}
	mysql_free_result($result);
	
	// Insert our information
	$updateCardNumber = (!stristr($postcard_number, '*')) ? ", card_number='" . $postcard_number . "'" : ", card_number='" . $card_number . "'"; // If a user didn't change the masked value then don't change the value in the DB
	$updateCardNumber .= (!stristr($postcard_sid, '*')) ? ", card_sid='" . $postcard_sid . "'" : ", card_sid='" . $card_sid . "'"; // If a user didn't change the masked value then don't change the value in the DB
	
	$sql = "INSERT INTO `" . DBTABLEPREFIX . "creditcards` (`order_id`) VALUES ('" . $_SESSION['orderid'] . "');";
	$result = mysql_query($sql);
	
	$sql = "UPDATE `" . DBTABLEPREFIX . "creditcards` SET `name_on_card`='" . $postname_on_card . "', `card_type`='" . $postcard_type . "', `exp_month`='" . $postexp_month . "', `exp_year`='" . $postexp_year . "', `bank_name`='" . $postbank_name . "', `bank_number`='" . $postbank_number . "'" . $updateCardNumber . " WHERE `order_id`='" . $_SESSION['orderid'] . "';";
	$result = mysql_query($sql);
	
	// Update user's credit card details
	// Check and see if theres already a card in the DB
	$sql = "SELECT card_number, card_sid FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $_SESSION['userid'] . "';";
	$result = mysql_query($sql);
				
	if ($result && mysql_num_rows($result) > 0) {
		// Theres already a credit card in the DB so just update it
	}
	else {
		// Theres no credit card in the DB so insert it
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "creditcards` (`user_id`) VALUES ('" . $_SESSION['userid'] . "');";
		$result = mysql_query($sql);	
	}
	mysql_free_result($result);
	
	// Update our information
	$updateCardNumber = (!stristr($postcard_number, '*')) ? ", card_number='" . $postcard_number . "'" : ""; // If a user didn't change the masked value then don't change the value in the DB
	$updateCardNumber .= (!stristr($postcard_sid, '*')) ? ", card_sid='" . $postcard_sid . "'" : ""; // If a user didn't change the masked value then don't change the value in the DB
	
	$sql = "UPDATE `" . DBTABLEPREFIX . "creditcards` SET `name_on_card`='" . $postname_on_card . "', `card_type`='" . $postcard_type . "', `exp_month`='" . $postexp_month . "', `exp_year`='" . $postexp_year . "', `bank_name`='" . $postbank_name . "', `bank_number`='" . $postbank_number . "'" . $updateCardNumber . " WHERE `user_id`='" . $_SESSION['userid'] . "';";
	$result = mysql_query($sql);
				
	//Notify the user of the new order	
	$sql = "SELECT email_address, first_name, last_name FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $_SESSION['userid'] . "' LIMIT 1";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
		$emailMessage = "To our valued customer " . $row['first_name'] . " " . $row['last_name'] . ",<br />
					Thank you for placing an order with " . $ss_config['ftsss_store_name'] . ". Your order will be processed as soon as your payment is recieved. You can always check on your order status by logging into your userpanel at: <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a>.
					<br /><br />
					Sincerely,
					<br />
					The " . $ss_config['ftsss_store_name'] . " Team<br /><br />" . returnInvoice($_SESSION['orderid'], $_SESSION['userid']);
		$emailSubject = $ss_config['ftsss_store_name'] . " Order #" . $_SESSION['orderid'];
		$success = emailMessage($row['email_address'], $emailSubject, $emailMessage);
		}
		mysql_free_result($result);
	}
	
	//Notify the company of the new order
	$EmailSubject = "New Order #" . $_SESSION['orderid'];
	$EmailMessage = "<strong>Order #:</strong> " . $_SESSION['orderid'] . "<br /><br />
					A new order has been submitted, you can view it on the orders page by signing into <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a>";
	$success = emailMessage($ss_config['ftsss_sales_email'], $emailSubject, $emailMessage);
		
	// Update Order Status and Info					
	changeOrderStatus($_SESSION['orderid'], STATUS_CREDIT_CARD_PAYMENT);
	
	$page_content .= "
						Order <strong>#" . $_SESSION['orderid'] . "</strong> has been entered into our system and your payment is being processed by our Accounting Department.
						<br /><br />
						To view a printer friendly version of this invoice, <a href=\"" . $menuvar['VIEWINVOICE'] . "&id=" . $_SESSION['orderid'] . "&style=printerFriendly\" target=\"_blank\">please click here.</a><br />";
	
	$page_content .= returnInvoice($_SESSION['orderid'], $_SESSION['userid']);	
	
	// Kill the orderid
	$_SESSION['orderid'] = "";
	
	// Print to page					
	$page->setTemplateVar("PageContent", $page_content);
?>