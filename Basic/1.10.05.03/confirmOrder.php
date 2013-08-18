<?
/***************************************************************************
 *                               confirmOrder.php
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
 	//include("includes/HOP.php"); // For Cybersource Payment
	
	// Get our payment method
 	$paymentMethod = keeptasafe($_GET['paymentMethod']);
	$orderPrice = "";
	$extraRows = "";
	
	// Credit Card form Values
	$postname_on_card = keeptasafe($_POST['name_on_card']);
	$postcard_type = keeptasafe($_POST['card_type']);
	$postcard_number = keeptasafe($_POST['card_number']);
	$postexp_month = keepsafe($_POST['exp_month']);
	$postexp_year = keepsafe($_POST['exp_year']);
	$postcard_sid = keepsafe($_POST['card_sid']);
	$postbank_name = keeptasafe($_POST['bank_name']);
	$postbank_number = keeptasafe($_POST['bank_number']);
	
	// Get card number in the database in case the user didnt change it
	$sql = "SELECT card_number, card_sid FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $_SESSION['userid'] . "';";
	$result = mysql_query($sql);
				
	if ($result && mysql_num_rows($result) != 0) {
		while ($row = mysql_fetch_array($result)) {
			$card_number = $row['card_number'];
			$card_sid = $row['card_sid'];
		}
	}
	mysql_free_result($result);
	
	// Insert our information
	$postcard_number = (!stristr($postcard_number, '*')) ? $postcard_number : $card_number; // If a user didn't change the masked value then don't change the value in the DB
	$postcard_sid = (!stristr($postcard_sid, '*')) ? $postcard_sid : $card_sid; // If a user didn't change the masked value then don't change the value in the DB
		
	// Get Order values
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $_SESSION['orderid'] . "' LIMIT 1";
	$result = mysql_query($sql);
					

	if ($result && mysql_num_rows($result) > 0) {							
		while ($row = mysql_fetch_array($result)) {
			$orderPrice = $row['price'];
		}
		mysql_free_result($result);
	}
	
	// Set our page item values
	switch($paymentMethod) {
		case "payPal":
			$paymentMethod = "PayPal";
			$paymentButton = "
				<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">
 					<input type=\"hidden\" name=\"cmd\" value=\"_xclick\" />
					<input type=\"hidden\" name=\"business\" value=\"" . $ss_config['ftsss_paypal_email'] . "\" />
					<input type=\"hidden\" name=\"item_name\" value=\"" . $ss_config['ftsss_store_name'] . " Online Purchase\" />
					<input type=\"hidden\" name=\"item_number\" value=\"" . $_SESSION['orderid'] . "\" />
					<input type=\"hidden\" name=\"amount\" value=\"" . $orderPrice . "\" />
					<input type=\"submit\" name=\"submit\" class=\"confirmOrderButton\" value=\"Confirm Order\" />
				</form>";
			break;
		case "googleCheckout":
			$paymentMethod = "Google Checkout";
			$paymentButton = "
				<form action=\"https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/" . $ss_config['ftsss_google_checkout_id'] . "\" id=\"BB_BuyButtonForm\" method=\"post\" name=\"BB_BuyButtonForm\">
					<input name=\"item_name_1\" type=\"hidden\" value=\"" . $ss_config['ftsss_store_name'] . " Online Purchase\"/>
					<input name=\"item_description_1\" type=\"hidden\" value=\"Order " . $_SESSION['orderid'] . "\"/>
					<input name=\"item_quantity_1\" type=\"hidden\" value=\"1\"/>
					<input name=\"item_price_1\" type=\"hidden\" value=\"" . $orderPrice . "\"/>
					<input name=\"item_currency_1\" type=\"hidden\" value=\"USD\"/>
					<input name=\"_charset_\" type=\"hidden\" value=\"utf-8\"/>
					<input type=\"submit\" name=\"submit\" class=\"confirmOrderButton\" value=\"Confirm Order\" />
				</form>";
			break;
		case "creditCard":
			$paymentMethod = "Credit Card";
			// CyberSource CC Payment
			/*
			$paymentButton = "
				<form action=\"https://orderpage.ic3.com/hop/ProcessOrder.do\" method=\"post\">
					" . returnSignature3($orderPrice, "usd", "sale") . "
					" . returnCreditCardProcessorFormItems($_SESSION['orderid']) . "
 					<input type=\"hidden\" name=\"card_cardType\" value=\"" . $FTS_CARDS_PROCESSOR[$postcard_type] . "\" />
 					<input type=\"hidden\" name=\"card_accountNumber\" value=\"" . $postcard_number . "\" />
 					<input type=\"hidden\" name=\"card_cvNumber\" value=\"" . $postcard_sid . "\" />
 					<input type=\"hidden\" name=\"card_expirationMonth\" value=\"" . paddString($exp_month, 2, "0", "L") . "\" />
			 		<input type=\"hidden\" name=\"card_expirationYear\" value=\"" . printCreditCardYear($exp_year) . "\" />
 					<input type=\"hidden\" name=\"comments\" value=\"" . $ss_config['ftsss_store_name'] . " Order #" . $_SESSION['orderid'] . "\" />
					<input type=\"submit\" name=\"submit\" class=\"confirmOrderButton\" value=\"Confirm Order\" />
				</form>";
			*/
			
			// Regular CC Payment
			$paymentButton = "
				<form action=\"" . $menuvar['CCPAYMENT'] . "\" method=\"post\">
 					<input type=\"hidden\" name=\"name_on_card\" value=\"" . $name_on_card . "\" />
 					<input type=\"hidden\" name=\"card_type\" value=\"" . $card_type . "\" />
 					<input type=\"hidden\" name=\"card_number\" value=\"" . $card_number . "\" />
 					<input type=\"hidden\" name=\"exp_year\" value=\"" . $exp_year . "\" />
 					<input type=\"hidden\" name=\"card_sid\" value=\"" . $card_sid . "\" />
 					<input type=\"hidden\" name=\"bank_name\" value=\"" . $bank_name . "\" />
 					<input type=\"hidden\" name=\"bank_number\" value=\"" . $bank_number . "\" />
					<input type=\"submit\" name=\"submit\" class=\"confirmOrderButton\" value=\"Confirm Order\" />
				</form>";
			$extraRows = "
								<tr>
									<td class=\"row1\" colspan=\"2\">
										" . $postname_on_card . "<br />" . 
										printCreditCardType($postcard_type) . "<br />" . 
										maskCCNumber($postcard_number) . "<br />" . 
										maskCCSID($postcard_sid) . "<br />" . 
										printCreditCardMonth($postexp_month) . "/" . printCreditCardYear($postexp_year) . "<br /><br />" . 
										$postbank_name . "<br />" . 
										$postbank_number . "<br />
									</td>
								</tr>
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>";
			break;
		case "checkMOWire":
			$paymentMethod = "Check, Money Order, or Wire Transfer";
			$paymentButton = "
					<a href=\"" . $menuvar['CHECKMOWIRE'] . "\" class=\"confirmOrderLink\"><span>Confirm Order</span></a>";
			break;
		default:
			$paymentMethod = "Unknown Payment Type";
			$paymentButton = "";
			break;
	}
	
	// Update Order Status and Info					
	changeOrderStatus($_SESSION['orderid'], STATUS_STEP4);
	
	// If this is a credit card order we need to insert it into the database since we wont get a second chance
	if ($paymentMethod == "Credit Card") {
		// Kill old order creditcard entries for this orderid
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "orders_creditcards` WHERE orders_creditcards_order_id='" . $_SESSION['orderid'] . "';";
		$result = mysql_query($sql);
		
		// Get card number in the database in case the user didnt change it
		$sql = "SELECT card_number, card_sid FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $_SESSION['userid'] . "';";
		$result = mysql_query($sql);
					
		if ($result && mysql_num_rows($result) != 0) {
			while ($row = mysql_fetch_array($result)) {
				$card_number = $row['card_number'];
				$card_sid = $row['card_sid'];
			}
		}
		mysql_free_result($result);
		
		// Insert our information
		$updateCardNumber = (!stristr($postcard_number, '*')) ? ", card_number='" . $postcard_number . "'" : ", card_number='" . $card_number . "'"; // If a user didn't change the masked value then don't change the value in the DB
		$updateCardNumber .= (!stristr($postcard_sid, '*')) ? ", card_sid='" . $postcard_sid . "'" : ", card_sid='" . $card_sid . "'"; // If a user didn't change the masked value then don't change the value in the DB
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "orders_creditcards` (`orders_creditcards_order_id`) VALUES ('" . $_SESSION['orderid'] . "');";
		$result = mysql_query($sql);
		
		$sql = "UPDATE `" . DBTABLEPREFIX . "orders_creditcards` SET `name_on_card`='$postname_on_card', `card_type`='$postcard_type', `exp_month`='$postexp_month', `exp_year`='$postexp_year', `bank_name`='$postbank_name', `bank_number`='$postbank_number'" . $updateCardNumber . " WHERE `orders_creditcards_order_id`='" . $_SESSION['orderid'] . "';";
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
		
		$sql = "UPDATE `" . DBTABLEPREFIX . "creditcards` SET `name_on_card`='$postname_on_card', `card_type`='$postcard_type', `exp_month`='$postexp_month', `exp_year`='$postexp_year', `bank_name`='$postbank_name', `bank_number`='$postbank_number'" . $updateCardNumber . " WHERE `user_id`='" . $_SESSION['userid'] . "';";
		$result = mysql_query($sql);	
	}
		
	$page_content .= "
						<table width=\"723\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td><img src=\"images/checkout/checkoutText_off.png\" width=\"244\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep1_off.png\" width=\"123\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep2_off.png\" width=\"160\" height=\"51\"></td>
								<td><a href=\"" . $menuvar['PAYMENT'] . "\"><img src=\"images/checkout/checkoutStep3_next.png\" width=\"93\" height=\"51\"></a></td>
								<td><img src=\"images/checkout/checkoutStep4_on.png\" width=\"103\" height=\"51\"></td>
							</tr>
                        </table>
						<br /><br />
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Submit Order</td>
								</tr>
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>Order Number</strong></td>
									<td class=\"row1\">" . $_SESSION['orderid'] . "</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Order Total</strong></td>
									<td class=\"row1\">" . formatCurrency($orderPrice) . "</td>
								</tr>
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>		
								<tr>
									<td class=\"title2\"><strong>Payment Method</strong></td>
									<td class=\"row1\">" . $paymentMethod . "</td>
								</tr>	
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>	
								" . $extraRows . "
							</table>
							<br /><br />
							<a href=\"" . $menuvar['CART'] . "\" class=\"editOrderLink\"><span>Edit Order</span></a>
							<a href=\"" . $menuvar['PAYMENT'] . "\" class=\"editPaymentLink\"><span>Edit Payment</span></a>
							" . $paymentButton . "
							<br /><br />" . returnInvoice($_SESSION['orderid'], $_SESSION['userid']);
	
	// Print to page					
	$page->setTemplateVar("PageContent", $page_content);
?>