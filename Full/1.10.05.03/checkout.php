<?
/***************************************************************************
 *                               checkout.php
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
 
 	// Handle checkout link in email
	if ($actual_action == "checkoutOrder") { 
		$_SESSION['orderid'] = $actual_id;
	}
	
	if (!isset($_SESSION['email_address'])) {
		header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN'] . "&action=updateOrder&id=" . $_SESSION['orderid'] . "&redirect_to=checkout"));
	}
	else {
		// Update Order Status and Info					
		changeOrderStatus($_SESSION['orderid'], STATUS_STEP2);		
		
		// Update order to verify that the 
		updateOrder($_SESSION['userid'], $_SESSION['orderid'], 0);
		
		// Start our page		
		$page_content .= "
						<table width=\"723\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td><img src=\"images/checkout/checkoutText_off.png\" width=\"244\" height=\"51\"></td>
								<td><a href=\"" . $menuvar['CART'] . "\"><img src=\"images/checkout/checkoutStep1_next.png\" width=\"123\" height=\"51\"></a></td>
								<td><img src=\"images/checkout/checkoutStep2_on.png\" width=\"160\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep3_off.png\" width=\"93\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep4_off.png\" width=\"103\" height=\"51\"></td>
							</tr>
                        </table>
						<br /><br />
						<form name=\"checkoutForm\" id=\"checkoutForm\" action=\"$menuvar[PAYMENT]\" method=\"post\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Order Checkout</td>
								</tr>";		
								
		if (!isset($_SESSION['orderid'])) {		
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"2\">There was a problem accessing your order, please return to your cart and try again.</td>
														</tr>";	
		}
		else {				
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $_SESSION['orderid'] . "'";
			$result = mysql_query($sql);
					
			$x = 1; //reset the variable we use for our row colors	
			$ordersids = array();
			if (!$result || mysql_num_rows($result) == 0) { // No orders yet!
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"2\">There was a problem accessing your order in the database, please return to your cart and try again.</td>
														</tr>";	
			}
			else {	 // Print all our orders								
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
									<td class=\"title1\" colspan=\"2\">Billing Information</td>
								</tr>";
								
				// Pull user's billing address and rip it out of the array
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $_SESSION['userid'] . "' AND type='" . BILL_ADDRESS . "'";
				$result2 = mysql_query($sql2);
					
				if ($result2 && mysql_num_rows($result2) > 0) {
					while ($row2 = mysql_fetch_array($result2)) {	
						extract($row2);	
					}
				}			
				$billShowStates = ($country != "USA") ? " style=\"display: none;\"" : "";
				$billShowStates2 = ($country != "USA") ? "" : " style=\"display: none;\"";
				$page_content .= "					
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>First Name</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_first_name\" id=\"Bill_first_name\" value=\"". $first_name ."\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Last Name</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_last_name\" id=\"Bill_last_name\" value=\"". $last_name ."\" size=\"60\" /></td>
								</tr>				
								<tr>
									<td class=\"title2\"><strong>Company</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_company\" id=\"Bill_company\" value=\"". $company ."\" size=\"60\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Address</strong></td>
									<td class=\"row1\">
										<input type=\"text\" class=\"required\" name=\"Bill_street_1\" id=\"Bill_street_1\" size=\"60\" value=\"". $street_1 ."\" /><br />
										<input type=\"text\" name=\"Bill_street_2\" id=\"Bill_street_2\" size=\"60\" value=\"". $street_2 ."\" />
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>City</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_city\" id=\"Bill_city\" value=\"". $city ."\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Country</strong></td>
									<td class=\"row1\">
										" . createDropdown("countries", "Bill_country", $country, "showStateDropBox(this, 'bill')") . "
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>State / Province</strong></td>
									<td class=\"row1\">
										<span id=\"billStateRow\"" . $billShowStates . ">
											" . createDropdown("states", "Bill_state", $state, "") . "
										</span>
										<span id=\"billStateRow2\"" . $billShowStates2 . "><input type=\"text\" name=\"Bill_state2\" id=\"Bill_state2\" value=\"". $state ."\" size=\"60\" /></span>
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Postal Code</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_zip\" id=\"Bill_zip\" value=\"". $zip ."\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Primary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_day_phone\" id=\"Bill_day_phone\" value=\"". $day_phone ."\" size=\"60\" /> ext. <input type=\"text\" name=\"Bill_day_phone_ext\" id=\"Bill_day_phone_ext\" value=\"". $day_phone_ext . "\" size=\"6\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Secondary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_night_phone\" id=\"Bill_night_phone\" value=\"". $night_phone ."\" size=\"60\" /> ext. <input type=\"text\" name=\"Bill_night_phone_ext\" id=\"Bill_night_phone_ext\" value=\"". $night_phone_ext . "\" size=\"6\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Fax</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_fax\" id=\"Bill_fax\" value=\"". $fax ."\" size=\"60\" /></td>
								</tr>	
								</table>
											<br />
											** Note -- If your shipping address does not match the billing address on file with your bank, your order will likely be delayed by several days for extended validation. Please contact your bank to add an alternate shipping address and send us an email with your order number to confirm this has been done for faster verification.
								<br /><br />
								
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">
										<div class=\"floatRight\">
											<input type=\"checkbox\" name=\"sameAsBilling\" value=\"1\" onClick=\"sameAsBillingCheck(this);\" /> Same as Billing
										</div>										
										Shipping Information
									</td>
								</tr>";
								
				// Pull user's billing address and rip it out of the array
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $_SESSION['userid'] . "' AND type='" . SHIP_ADDRESS . "'";
				$result2 = mysql_query($sql2);
					
				if ($result2 && mysql_num_rows($result2) > 0) {
					while ($row2 = mysql_fetch_array($result2)) {	
						extract($row2);	
					}
				}			
				$page_content .= "					
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>First Name</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_first_name\" id=\"Ship_first_name\" value=\"". $first_name ."\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Last Name</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_last_name\" id=\"Ship_last_name\" value=\"". $last_name ."\" size=\"60\" /></td>
								</tr>				
								<tr>
									<td class=\"title2\"><strong>Company</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_company\" id=\"Ship_company\" value=\"". $company ."\" size=\"60\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Address</strong></td>
									<td class=\"row1\">
										<input type=\"text\" class=\"required\" name=\"Ship_street_1\" id=\"Ship_street_1\" size=\"60\" value=\"". $street_1 ."\" /><br />
										<input type=\"text\" name=\"Ship_street_2\" id=\"Ship_street_2\" size=\"60\" value=\"". $street_2 ."\" />
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>City</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_city\" id=\"Ship_city\" value=\"". $city ."\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Country</strong></td>
									<td class=\"row1\">
										" . createDropdown("countries", "Ship_country", $country, "showStateDropBox(this, 'ship')") . "
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>State / Province</strong></td>
									<td class=\"row1\">
										<span id=\"shipStateRow\"" . $shipShowStates . ">
											" . createDropdown("states", "Ship_state", $state, "") . "
										</span>
										<span id=\"shipStateRow2\"" . $shipShowStates2 . "><input type=\"text\" name=\"Ship_state2\" id=\"Ship_state2\" value=\"". $state ."\" size=\"60\" /></span>
									</td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Postal Code</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_zip\" id=\"Ship_zip\" value=\"". $zip ."\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Primary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_day_phone\" id=\"Ship_day_phone\" value=\"". $day_phone ."\" size=\"60\" /> ext. <input type=\"text\" name=\"Ship_day_phone_ext\" id=\"Ship_day_phone_ext\" value=\"". $day_phone_ext ."\" size=\"6\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Secondary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_night_phone\" id=\"Ship_night_phone\" value=\"". $night_phone ."\" size=\"60\" /> ext. <input type=\"text\" name=\"Ship_night_phone_ext\" id=\"Ship_night_phone_ext\" value=\"". $night_phone_ext ."\" size=\"6\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Fax</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_fax\" id=\"Ship_fax\" value=\"". $fax ."\" size=\"60\" /></td>
								</tr>";
				}
			}
			mysql_free_result($result);
		}
		
		$page_content .= "					</table>
											<br /><br />
											<strong>Order Comments</strong><br />
											<textarea name=\"orderComments\" cols=\"80\" rows=\"10\"></textarea>
											<br /><br />
											<div id=\"termsAndConditions\">
												" . nl2br($ss_config['ftsss_terms']) . "
											</div>
											<br /><br />
											<input type=\"checkbox\" class=\"required\" name=\"termsagree\" value=\"1\" title=\"You must agree to the terms to checkout.\" />I agree to all " . $ss_config['ftsss_store_name'] . " Terms and Conditions of Sale.
											<br /><br />
											<div class=\"center\">
												<a href=\"" . $menuvar['CART'] . "\" class=\"goBackLink\"><span>Go Back</span></a>
												&nbsp;&nbsp;
												<input type=\"submit\" name=\"submit\" class=\"checkoutButton\" value=\"Checkout\" />
											</div>
										</form>
										<script language = \"Javascript\">
											var valid = new Validation('checkoutForm', {immediate : true, useTitles:true});
										</script>";
	}
	// Print to page					
	$page->setTemplateVar("PageContent", $page_content);
?>
