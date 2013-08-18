<?
/***************************************************************************
 *                               payment.php
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

	if ($actual_action == "oldOrder") { 
		$_SESSION['orderid'] = $actual_id;
	}
	
 	// Only update the addresses if they were posted to this page, we don't want to blank anything out if we are coming here from the confirm order page
	if (isset($_POST['Ship_first_name'])) {
		$orderComments = keeptasafe($_POST['orderComments']);
	
		$Ship_first_name = keeptasafe($_POST['Ship_first_name']);
		$Ship_last_name = keeptasafe($_POST['Ship_last_name']);
		$Ship_company = keeptasafe($_POST['Ship_company']);
		$Ship_street_1 = keeptasafe($_POST['Ship_street_1']);
		$Ship_street_2 = keeptasafe($_POST['Ship_street_2']);
		$Ship_city = keeptasafe($_POST['Ship_city']);
		$Ship_country = keeptasafe($_POST['Ship_country']);
		$Ship_state = keeptasafe($_POST['Ship_state']);
		$Ship_state2 = keeptasafe($_POST['Ship_state2']);
		$Ship_state = ($Ship_country == "USA") ? $Ship_state : $Ship_state2;
		$Ship_zip = keeptasafe($_POST['Ship_zip']);
		$Ship_day_phone = keeptasafe($_POST['Ship_day_phone']);
		$Ship_day_phone_ext = keeptasafe($_POST['Ship_day_phone_ext']);
		$Ship_night_phone = keeptasafe($_POST['Ship_night_phone']);
		$Ship_night_phone_ext = keeptasafe($_POST['Ship_night_phone_ext']);
		$Ship_fax = keeptasafe($_POST['Ship_fax']);
		
		$Bill_first_name = keeptasafe($_POST['Bill_first_name']);
		$Bill_last_name = keeptasafe($_POST['Bill_last_name']);
		$Bill_company = keeptasafe($_POST['Bill_company']);
		$Bill_street_1 = keeptasafe($_POST['Bill_street_1']);
		$Bill_street_2 = keeptasafe($_POST['Bill_street_2']);
		$Bill_city = keeptasafe($_POST['Bill_city']);
		$Bill_country = keeptasafe($_POST['Bill_country']);
		$Bill_state = keeptasafe($_POST['Bill_state']);
		$Bill_state2 = keeptasafe($_POST['Bill_state2']);
		$Bill_state = ($Bill_country == "USA") ? $Bill_state : $Bill_state2;
		$Bill_zip = keeptasafe($_POST['Bill_zip']);
		$Bill_day_phone = keeptasafe($_POST['Bill_day_phone']);
		$Bill_day_phone_ext = keeptasafe($_POST['Bill_day_phone_ext']);
		$Bill_night_phone = keeptasafe($_POST['Bill_night_phone']);
		$Bill_night_phone_ext = keeptasafe($_POST['Bill_night_phone_ext']);
		$Bill_fax = keeptasafe($_POST['Bill_fax']);
		
		// Kill old addresses for this orderid, this is used in case a user constantly hits the refresh button
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id = '" . $_SESSION['orderid'] . "';";
		$result = mysql_query($sql);
		
		// Insert our Billing Address
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "addresses` (`order_id`, `type`, `first_name`, `last_name`, `company`, `street_1`, `street_2`, `city`, `country`, `state`, `zip`, `day_phone`, `day_phone_ext`, `night_phone`, `night_phone_ext`, `fax`)  VALUES ('" . $_SESSION['orderid'] . "', '0', '" . $Bill_first_name . "', '" . $Bill_last_name . "', '" . $Bill_company . "', '" . $Bill_street_1 . "', '" . $Bill_street_2 . "', '" . $Bill_city . "', '" . $Bill_country . "', '" . $Bill_state . "', '" . $Bill_zip . "', '" . $Bill_day_phone . "', '" . $Bill_day_phone_ext . "', '" . $Bill_night_phone . "', '" . $Bill_night_phone_ext . "', '" . $Bill_fax . "');";
		$result = mysql_query($sql);

		// Insert our Shipping Address
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "addresses` (`order_id`, `type`, `first_name`, `last_name`, `company`, `street_1`, `street_2`, `city`, `country`, `state`, `zip`, `day_phone`, `day_phone_ext`, `night_phone`, `night_phone_ext`, `fax`)  VALUES ('" . $_SESSION['orderid'] . "', '1', '" . $Ship_first_name . "', '" . $Ship_last_name . "', '" . $Ship_company . "', '" . $Ship_street_1 . "', '" . $Ship_street_2 . "', '" . $Ship_city . "', '" . $Ship_country . "', '" . $Ship_state . "', '" . $Ship_zip . "', '" . $Ship_day_phone . "', '" . $Ship_day_phone_ext . "', '" . $Ship_night_phone . "', '" . $Ship_night_phone_ext . "', '" . $Ship_fax . "');";
		$result = mysql_query($sql);
		
		// Recalculate shipping based off of actual shipping address and update the order
		updateOrder($_SESSION['userid'], $_SESSION['orderid'], 0);
		
		// Kill old addresses for this user
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id = '" . $_SESSION['userid'] . "';";
		$result = mysql_query($sql);
		
		// Insert our Billing Address
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "useraddresses` (`user_id`, `type`, `first_name`, `last_name`, `company`, `street_1`, `street_2`, `city`, `country`, `state`, `zip`, `day_phone`, `day_phone_ext`, `night_phone`, `night_phone_ext`, `fax`)  VALUES ('" . $_SESSION['userid'] . "', '0', '" . $Bill_first_name . "', '" . $Bill_last_name . "', '" . $Bill_company . "', '" . $Bill_street_1 . "', '" . $Bill_street_2 . "', '" . $Bill_city . "', '" . $Bill_country . "', '" . $Bill_state . "', '" . $Bill_zip . "', '" . $Bill_day_phone . "', '" . $Bill_day_phone_ext . "', '" . $Bill_night_phone . "', '" . $Bill_night_phone_ext . "', '" . $Bill_fax . "');";
		$result = mysql_query($sql);

		// Insert our Shipping Address
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "useraddresses` (`user_id`, `type`, `first_name`, `last_name`, `company`, `street_1`, `street_2`, `city`, `country`, `state`, `zip`, `day_phone`, `day_phone_ext`, `night_phone`, `night_phone_ext`, `fax`)  VALUES ('" . $_SESSION['userid'] . "', '1', '" . $Ship_first_name . "', '" . $Ship_last_name . "', '" . $Ship_company . "', '" . $Ship_street_1 . "', '" . $Ship_street_2 . "', '" . $Ship_city . "', '" . $Ship_country . "', '" . $Ship_state . "', '" . $Ship_zip . "', '" . $Ship_day_phone . "', '" . $Ship_day_phone_ext . "', '" . $Ship_night_phone . "', '" . $Ship_night_phone_ext . "', '" . $Ship_fax . "');";
		$result = mysql_query($sql);

		// Update order comments
		$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET comments = '" . $orderComments . "' WHERE id = '" . $_SESSION['orderid'] . "';";
		$result = mysql_query($sql);		
	}
		// Update Order Status and Info					
		changeOrderStatus($_SESSION['orderid'], STATUS_STEP3);
		
		// Start our page		
		$page_content .= "
						<table width=\"723\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td><img src=\"images/checkout/checkoutText_off.png\" width=\"244\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep1_off.png\" width=\"123\" height=\"51\"></td>
								<td><a href=\"" . $menuvar['CHECKOUT'] . "\"><img src=\"images/checkout/checkoutStep2_next.png\" width=\"160\" height=\"51\"></a></td>
								<td><img src=\"images/checkout/checkoutStep3_on.png\" width=\"93\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep4_off.png\" width=\"103\" height=\"51\"></td>
							</tr>
                        </table>
						<br /><br />
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Order Payment Options</td>
								</tr>";		
								
		if (!isset($_SESSION['orderid'])) {		
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"2\">There was a problem accessing your order, please return to your cart and try again.</td>
														</tr>";	
		}
		else {				
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $_SESSION['orderid'] . "' LIMIT 1";
			$result = mysql_query($sql);
					
			$x = 1; //reset the variable we use for our row colors	
			$ordersids = array();
			if (!$result || mysql_num_rows($result) == 0) { // Order doesnt exist
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"2\">There was a problem accessing your order in the database, please return to your cart and try again.</td>
														</tr>";	
			}
			else {	 // Print our order
				while ($row = mysql_fetch_array($result)) {
					$processingType = ($row['rush_fee'] > 0) ? "Rush Processing" : "Standard Processing";					
					$couponRow = ($row['coupon_code'] != "") ? 
								"<tr>
									<td class=\"title2\"><strong>Coupon</strong></td>
									<td class=\"row1\">-" . formatCurrency($row['coupon_discount'] + ($row['items_total'] * ($row['coupon_discount_percentage'] / 100))) . "</td>
								</tr>" : "";
					
					$page_content .= "
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>Order Number</strong></td>
									<td class=\"row1\">" . $_SESSION['orderid'] . "</td>
								</tr>	
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>		
								<tr>
									<td class=\"title2\"><strong>Email Address</strong></td>
									<td class=\"row1\">" . $_SESSION['email_address'] . "</td>
								</tr>	
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>			
								<tr>
									<td class=\"title2\"><strong>Subtotal</strong></td>
									<td class=\"row1\">" . formatCurrency($row['items_total']) . "</td>
								</tr>								
								" . $couponRow . "
								<tr>
									<td class=\"title2\"><strong>Sales Tax</strong></td>
									<td class=\"row1\">" . formatCurrency($row['tax']) . "</td>
								</tr>		
								<tr>
									<td class=\"title2\"><strong>" . printShippingType($row['shipping']) . "</strong></td>
									<td class=\"row1\">" . formatCurrency($row['shipping_price']) . "</td>
								</tr>		
								<tr>
									<td class=\"title2\"><strong>" . $processingType . "</strong></td>
									<td class=\"row1\">" . formatCurrency($row['rush_fee']) . "</td>
								</tr>		
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>		
								<tr>
									<td class=\"title2\"><strong>Order Total</strong></td>
									<td class=\"row1\">" . formatCurrency($row['price']) . "</td>
								</tr>
								</table>
								<br /><br />
								
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Billing and Shipping Information</td>
								</tr>			
								<tr>
									<td class=\"row1\">
										<strong style=\"text-decoration: underline;\">Bill To: </strong><br />
										" . getAddress(0, $row['id']) . "
									</td>
									<td class=\"row1\">
										<strong style=\"text-decoration: underline;\">Ship To: </strong><br />
										" . getAddress(1, $row['id']) . "
									</td>
								</tr>	
								</table>
								<br /><br />";
					
					// Print Credit Card Section
					if ($ss_config['ftsss_credit_card_payment_active'] == ACTIVE) {
						//==================================================
						// Print out our users credit card info
						//==================================================
						$page_content .= "				
								<form name=\"frmCCPayment\" id=\"frmCCPayment\" action=\"" . $menuvar['CONFIRMORDER'] . "&paymentMethod=creditCard\" method=\"post\">					
									<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
										<tr>
											<td class=\"title1\" colspan=\"2\">Pay by Credit Card</td>
										</tr>";
						// Pull user's billing address and rip it out of the array
						$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $_SESSION['userid'] . "'";
						$result2 = mysql_query($sql2);
					
						if ($result2 && mysql_num_rows($result2) > 0) {
							while ($row2 = mysql_fetch_array($result2)) {	
								extract($row2);	
							}
						}			
						$page_content .= "					
										<tr>
											<td class=\"row1\" colspan=\"2\">
												<img src=\"images/creditCards.png\" alt=\"\" />
											</td>
										</tr>
										<tr>
											<td class=\"title2\"><strong>Name as it Appears on Card</strong></td>
											<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"name_on_card\" id=\"name_on_card\" value=\"" . $name_on_card . "\" size=\"60\" /></td>
										</tr>				
										<tr>
											<td class=\"title2\"><strong>Card Type</strong></td>
											<td class=\"row1\">" . createDropdown("ccType", "card_type", $card_type, "") . "</td>
										</tr>			
										<tr>
											<td class=\"title2\" style=\"width: 200px;\"><strong>Card Number</strong></td>
											<td class=\"row1\"><input type=\"text\" class=\"required validate-CCNumber\" name=\"card_number\" id=\"card_number\" value=\"" . maskCCNumber($card_number) . "\" size=\"60\" /></td>
										</tr>	
										<tr>
											<td class=\"title2 noWrap\"><strong>Security ID on the Back of the Card</strong> <a href=\"ajax.php?action=showCreditCardSIDInfo\" title=\"Credit Card SID\" rel=\"lyteframe\" rev=\"width: 400px; height: 400px; scrolling: yes;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/helpIcon.png\" alt=\"\" /></a></td>
											<td class=\"row1\"><input type=\"text\" class=\"required validate-CCSID\" name=\"card_sid\" id=\"card_sid\" value=\"" . maskCCSID($card_sid) . "\" size=\"60\" /></td>
										</tr>	
										<tr>
											<td class=\"title2\"><strong>Expiration Date</strong></td>
											<td class=\"row1\">" . createDropdown("ccExpMonth", "exp_month", $exp_month, "") . createDropdown("ccExpYear", "exp_year", $exp_year, "") . "</td>
										</tr>	
										<tr>
											<td class=\"title2\"><strong>Bank Name</strong> <a href=\"ajax.php?action=showCreditCardSIDInfo\" title=\"Credit Card SID\" rel=\"lyteframe\" rev=\"width: 400px; height: 400px; scrolling: yes;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/helpIcon.png\" alt=\"\" /></a></td>
											<td class=\"row1\"><input type=\"text\" name=\"bank_name\" id=\"bank_name\" size=\"60\" value=\"" . $bank_name . "\" /></td>
										</tr>
										<tr>
											<td class=\"title2\"><strong>Bank phone Number</strong> <a href=\"ajax.php?action=showCreditCardSIDInfo\" title=\"Credit Card SID\" rel=\"lyteframe\" rev=\"width: 400px; height: 400px; scrolling: yes;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/helpIcon.png\" alt=\"\" /></a></td>
											<td class=\"row1\"><input type=\"text\" name=\"bank_number\" id=\"bank_number\" size=\"60\" value=\"" . $bank_number . "\" /></td>
										</tr>
									</table>
									<br />
									<input type=\"submit\" class=\"payWithCreditCardButton\" value=\"Pay With Credit Card\" />
								</form>
								<script type=\"text/javascript\">
									var valid = new Validation('frmCCPayment', {immediate : true, useTitles:true});
									Validation.addAllThese([
										['validate-CCNumber', 'Your credit card number should be 16 digits long.', {
											minLength : 16
									}],
										['validate-CCSID', 'Your credit card security id should be 3 digits long.', {
											minLength : 3
										}]
									]);
								</script>
								<br /><br />";
					}
					
					// Print Google Checkout Section
					if ($ss_config['ftsss_google_checkout_active'] == ACTIVE) {
						$page_content .= "
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\">Pay Through Google Checkout</td>
									</tr>			
									<tr>
										<td class=\"row1\">		
											<img src=\"images/googleCreditCards.png\" alt=\"\" />
											<br />
											<a href=\"" . $menuvar['CONFIRMORDER'] . "&paymentMethod=googleCheckout\" class=\"payWithGoogleLink\"><span>Pay Through Google Checkout</span></a>
										</td>
									</tr>
								</table>
								<br /><br />";
					}
					
					// Print PayPal Section
					if ($ss_config['ftsss_paypal_active'] == ACTIVE) {
						$page_content .= "
					
								
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\">Pay Through PayPal</td>
									</tr>			
									<tr>
										<td class=\"row1\">
											Credit Card payments and Paypal payments are handled through a secure server to keep your information safe. To complete your purchase, click on the button below and you will be taken to the PayPal website to finish your order.
											<a href=\"" . $menuvar['CONFIRMORDER'] . "&paymentMethod=payPal\" class=\"payWithPayPalLink\"><span>Pay Through PayPal</span></a>
										</td>
									</tr>
								</table>
								<br /><br />";
					}
		
					// Print Check, Money Order, Wire Transfer Section
					if ($ss_config['ftsss_checkmowire_active'] == ACTIVE) {
						$page_content .= "
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\">Pay by Check, money Order, or Wire Transfer</td>
									</tr>			
									<tr>
										<td class=\"row1\">
											<a href=\"" . $menuvar['CONFIRMORDER'] . "&paymentMethod=checkMOWire\" class=\"payWithCheckWireLink\"><span>Pay by Check, money Order, or Wire Transfer</span></a>
										</td>
									</tr>
								</table>
								<br /><br />";
					}
				}
			}
			mysql_free_result($result);
		}
			
		$page_content .= "
								<div class=\"center\">
										<a href=\"" . $menuvar['CHECKOUT'] . "\" class=\"goBackLink\"><span>Go Back</span></a>
									</ul>
								</div>";
				
		// Print to page					
		$page->setTemplateVar("PageContent", $page_content);
?>

