<? 
/***************************************************************************
 *                               orders.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
 *   email                : sales@fasttracksites.com
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
	if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
		if ($actual_action == "emailorder") {
			// Add breadcrumb
			$page->addBreadCrumb("Email Order", "");
			
			if (isset($_POST['emailAddress'])) {
				$emailAddress = $_POST['emailAddress'];
				$emailSubject = $_POST['emailSubject'];
				$emailText = $_POST['emailText'];
				
				$success = emailMessage($emailAddress, $emailSubject, nl2br($emailText) . "<br /><br />" . returnInvoice($actual_id, $_SESSION['userid']));
				
				$page_content = "Your email has been sent.";
			}
			else {
				$page_content = "
						<form name=\"frmEmailOrder\" action=\"" . $menuvar['ORDERS'] . "&amp;action=emailorder&amp;id=" . $actual_id . "\" method=\"post\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">
										Email Order
									</td>
								</tr>							
								<tr class=\"row1\">
									<td><strong>To</strong></td>
									<td>
										<input type=\"text\" name=\"emailAddress\" size=\"60\" value=\"" . getEmailAddressFromOrderID($actual_id) . "\" />
									</td>
								</tr>						
								<tr class=\"row1\">
									<td><strong>Email Subject</strong></td>
									<td>
										<input type=\"text\" name=\"emailSubject\" size=\"60\" value=\"\" />
									</td>
								</tr>				
								<tr class=\"row1\">
									<td><strong>Email Text (the order will be attached below this text)</strong></td>
									<td>
										<textarea name=\"emailText\" cols=\"45\" rows=\"10\"></textarea>
									</td>
								</tr>
							</table>
							<br />
							<input type=\"submit\" class=\"button\" name=\"submit\" value=\"Send Email\" />
						</form>";
			}
		}
		elseif ($actual_action == "viewCreditCard" && isset($actual_id)) {
			// Add breadcrumb
			$page->addBreadCrumb("View Credit Card", "");
			
			$page_content .= getOrderCreditCard($actual_id);
		}	
		elseif ($actual_action == "editorders" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Order", "");
			
			if(isset($_POST['status'])) {				
				//=======================================================
				// Set up our variables
				//=======================================================
				$user_id = keeptasafe($_POST['user_id']);
				$status = keeptasafe($_POST['status']);
				$shipping_price = keeptasafe($_POST['shipping_price']);
				$items_total = keeptasafe($_POST['items_total']);
				$discount = keeptasafe($_POST['discount']);
				$coupon_name = keeptasafe($_POST['coupon_name']);
				$coupon_code = keeptasafe($_POST['coupon_code']);
				$coupon_discount = keeptasafe($_POST['coupon_discount']);
				$coupon_discount_percentage = keeptasafe($_POST['coupon_discount_percentage']);
				$rush_fee = keeptasafe($_POST['rush_fee']);
				//$tax = keeptasafe($_POST['tax']);
				//$price = keeptasafe($_POST['price']);
				$shipping = keeptasafe($_POST['shipping']);
				$shipping_price = keeptasafe($_POST['shipping_price']);
				$shipped_by = keeptasafe($_POST['shipped_by']);
				$date_shipped = keeptasafe($_POST['date_shipped']);
				$comments = keeptasafe($_POST['comments']);
				$serials = keeptasafe($_POST['serials']);
				
				$Ship_first_name = keeptasafe($_POST['Ship_first_name']);
				$Ship_last_name = keeptasafe($_POST['Ship_last_name']);
				$Ship_street_1 = keeptasafe($_POST['Ship_street_1']);
				$Ship_street_2 = keeptasafe($_POST['Ship_street_2']);
				$Ship_city = keeptasafe($_POST['Ship_city']);
				$Ship_country = keeptasafe($_POST['Ship_country']);
				$Ship_state = keeptasafe($_POST['Ship_state']);
				$Ship_zip = keeptasafe($_POST['Ship_zip']);
				$Ship_day_phone = keeptasafe($_POST['Ship_day_phone']);
				$Ship_day_phone_ext = keeptasafe($_POST['Ship_day_phone_ext']);
				$Ship_night_phone = keeptasafe($_POST['Ship_night_phone']);
				$Ship_night_phone_ext = keeptasafe($_POST['Ship_night_phone_ext']);
				$Ship_fax = keeptasafe($_POST['Ship_fax']);
				
				$Bill_first_name = keeptasafe($_POST['Bill_first_name']);
				$Bill_last_name = keeptasafe($_POST['Bill_last_name']);
				$Bill_street_1 = keeptasafe($_POST['Bill_street_1']);
				$Bill_street_2 = keeptasafe($_POST['Bill_street_2']);
				$Bill_city = keeptasafe($_POST['Bill_city']);
				$Bill_country = keeptasafe($_POST['Bill_country']);
				$Bill_state = keeptasafe($_POST['Bill_state']);
				$Bill_zip = keeptasafe($_POST['Bill_zip']);
				$Bill_day_phone = keeptasafe($_POST['Bill_day_phone']);
				$Bill_day_phone_ext = keeptasafe($_POST['Bill_day_phone_ext']);
				$Bill_night_phone = keeptasafe($_POST['Bill_night_phone']);
				$Bill_night_phone_ext = keeptasafe($_POST['Bill_night_phone_ext']);
				$Bill_fax = keeptasafe($_POST['Bill_fax']);
				
				$postname_on_card = keeptasafe($_POST['name_on_card']);
				$postcard_type = keeptasafe($_POST['card_type']);
				$postcard_number = keeptasafe($_POST['card_number']);
				$postexp_month = keepsafe($_POST['exp_month']);
				$postexp_year = keepsafe($_POST['exp_year']);
				$postcard_sid = keepsafe($_POST['card_sid']);
				$postbank_name = keeptasafe($_POST['bank_name']);
				$postbank_number = keeptasafe($_POST['bank_number']);
				
				//=======================================================
				// Update the DB
				//=======================================================
				// Do all the general updates
				$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET user_id='" . $user_id . "', status = '" . $status . "', shipping_price = '" . $shipping_price . "', items_total='" . $items_total . "', discount='" . $discount . "', coupon_name = '" . $coupon_name . "', coupon_code = '" . $coupon_code . "', coupon_discount = '" . $coupon_discount . "', coupon_discount_percentage = '" . $coupon_discount_percentage . "', rush_fee = '" . $rush_fee . "', shipping='" . $shipping . "', shipped_by='" . $shipped_by . "', date_shipped = '" . $date_shipped . "', comments = '" . $comments . "', serials = '" . $serials . "' WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
				//echo $sql;
				
				// Kill old addresses for this orderid, this is used in case a user constantly hits the refresh button
				$sql = "DELETE FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id = '" . $actual_id . "';";
				$result = mysql_query($sql);
				
				// Insert our Billing Address
				$sql = "INSERT INTO `" . DBTABLEPREFIX . "addresses` (`order_id`, `type`, `first_name`, `last_name`, `street_1`, `street_2`, `city`, `country`, `state`, `zip`, `day_phone`, `day_phone_ext`, `night_phone`, `night_phone_ext`, `fax`)  VALUES ('" . $actual_id . "', '0', '" . $Bill_first_name . "', '" . $Bill_last_name . "', '" . $Bill_street_1 . "', '" . $Bill_street_2 . "', '" . $Bill_city . "', '" . $Bill_country . "', '" . $Bill_state . "', '" . $Bill_zip . "', '" . $Bill_day_phone . "', '" . $Bill_day_phone_ext . "', '" . $Bill_night_phone . "', '" . $Bill_night_phone_ext . "', '" . $Bill_fax . "');";
				$result = mysql_query($sql);
				
				// Insert our Shipping Address
				$sql = "INSERT INTO `" . DBTABLEPREFIX . "addresses` (`order_id`, `type`, `first_name`, `last_name`, `street_1`, `street_2`, `city`, `country`, `state`, `zip`, `day_phone`, `day_phone_ext`, `night_phone`, `night_phone_ext`, `fax`)  VALUES ('" . $actual_id . "', '1', '" . $Ship_first_name . "', '" . $Ship_last_name . "', '" . $Ship_street_1 . "', '" . $Ship_street_2 . "', '" . $Ship_city . "', '" . $Ship_country . "', '" . $Ship_state . "', '" . $Ship_zip . "', '" . $Ship_day_phone . "', '" . $Ship_day_phone_ext . "', '" . $Ship_night_phone . "', '" . $Ship_night_phone_ext . "', '" . $Ship_fax . "');";
				$result = mysql_query($sql);
				
				// Update our Credit Card details for this order
				$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "creditcards` WHERE order_id='" . $actual_id . "';";
				$result = mysql_query($sql);
				
				if (!$result || mysql_num_rows($result) == 0) {
					$sql = "INSERT INTO `" . DBTABLEPREFIX . "creditcards` (`user_id`) VALUES ('" . $actual_id . "');";
					$result = mysql_query($sql);
				}
				$sql = "UPDATE `" . DBTABLEPREFIX . "creditcards` SET `name_on_card`='" . $postname_on_card . "', `card_type`='" . $postcard_type . "', `exp_month`='" . $postexp_month . "', `exp_year`='" . $postexp_year . "', `bank_name`='" . $postbank_name . "', `bank_number`='" . $postbank_number . "', card_number='" . $postcard_number . "', card_sid='" . $postcard_sid . "' WHERE `order_id`='" . $actual_id . "';";
				$result = mysql_query($sql);
				
				//=======================================================
				// Update the total cost as well as tax, etc
				//=======================================================
				$totalPrice = $items_total - $discount - $coupon_discount;
				$totalPrice -= (($coupon_discount_percentage / 100) * $totalPrice);
				$totalPrice += $rush_fee;
				
				// Calculate the tax
				$tax = calculateTax($totalPrice, $user_id);
				
				//Only calculate shipping costs on new orders, otherwise we need to use the cost in database
				//$ShipTotal = calculateShippingCost($actual_id, $user_id, "-1");
				//$totalPrice += $ShipTotal;
				$totalPrice += $shipping_price;
				
				// Update our order
				$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET tax='" . $tax . "', price = '" . ($totalPrice + $tax) . "' WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);			
				
				//=======================================================
				// Finish up
				//=======================================================
				// Unset the variable we use to check if we are posting
				unset($_POST['status']);
				
			    // confirm
 				$page_content .= "Your order has been updated, and you are being redirected to the main page.
 									<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['ORDERS'] . "&action=editorders&id=" . $actual_id . "\">";
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['ORDERS'] . "&action=editorders&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Order</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"30%\"><strong>Order ID: </strong></td>
														<td>" . $row['id'] . "</td>
													</tr>
													<tr class=\"row2\">
														<td><strong>User: </strong></td>
														<td>";
															
															$sql2 = "SELECT id, first_name, last_name FROM `" . USERSDBTABLEPREFIX . "users` ORDER BY last_name";
															$result2 = mysql_query($sql2);
															
															if (!$result2 || mysql_num_rows($result2) == 0) { // No orders yet!
																$page_content .= "\n					<tr class=\"greenRow\">
																											<td colspan=\"2\">There are no users in the database.</td>
																										</tr>";	
															}
															else {	 // Print all our orders		
																	$page_content .= "\n										<select name=\"user_id\">";						
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n											<option value=\"" . $row2['id'] . "\"" . testSelected($row['user_id'], $row2['id']) . ">" . $row2['last_name'] . ", " . $row2['first_name'] . "</option>";
																}
																	$page_content .= "\n										</select>";	
															}
															mysql_free_result($result2);
					$page_content .= "\n							
														</td>
													</tr>
													<tr class=\"row1\">
														<td><strong>User's IP Address: </strong></td>
														<td>
															" . $row['user_ip'] . "
														</td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Order Comments: </strong></td>
														<td>
															" . nl2br($row['comments']) . "
														</td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Status: </strong></td>
														<td>
															" . createDropdown("orderstatus", "status", $row['status'], "") . "
														</td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Shipping: </strong></td>
														<td>" . createDropdown("shipping", "shipping", $row['shipping'], "") . "</td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Shipping Cost: </strong></td>
														<td><input type=\"text\" name=\"shipping_price\" size=\"40\" value=\"" . $row['shipping_price'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Total Cost of Items: </strong></td>
														<td><input type=\"text\" name=\"items_total\" size=\"40\" value=\"" . $row['items_total'] . "\" /></td>
													</tr>";
					/* Disabled for now
					$page_content .= "\n							
													<tr class=\"row1\">
														<td><strong>Discount: </strong></td>
														<td><input type=\"text\" name=\"discount\" size=\"40\" value=\"" . $row['discount'] . "\" /></td>
													</tr>";
					*/
													
					$page_content .= "\n							
													<tr class=\"row1\">
														<td><strong>Coupon Name: </strong></td>
														<td><input type=\"text\" name=\"coupon_name\" size=\"40\" value=\"" . $row['coupon_name'] . "\" /></td>
													</tr>	
													<tr class=\"row2\">
														<td><strong>Coupon Code: </strong></td>
														<td><input type=\"text\" name=\"coupon_code\" size=\"40\" value=\"" . $row['coupon_code'] . "\" /></td>
													</tr>	
													<tr class=\"row1\">
														<td><strong>Coupon Discount (in Dollars): </strong></td>
														<td><input type=\"text\" name=\"coupon_discount\" size=\"40\" value=\"" . $row['coupon_discount'] . "\" /></td>
													</tr>	
													<tr class=\"row2\">
														<td><strong>Coupon Discount (in Percents): </strong></td>
														<td><input type=\"text\" name=\"discount_percentage\" size=\"40\" value=\"" . $row['discount_percentage'] . "\" /></td>
													</tr>	
													<tr class=\"row1\">
														<td><strong>Rush Fee: </strong></td>
														<td><input type=\"text\" name=\"rush_fee\" size=\"40\" value=\"" . $row['rush_fee'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Tax: </strong></td>
														<td><input type=\"text\" name=\"tax\" size=\"40\" disabled=\"disabled\" value=\"" . $row['tax'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Total Order Cost: </strong></td>
														<td><input type=\"text\" name=\"price\" size=\"40\" disabled=\"disabled\" value=\"" . $row['price'] . "\" /></td>
													</tr>
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Shipping Notes</strong></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Shipped By: </strong></td>
														<td><input type=\"text\" name=\"shipped_by\" size=\"40\" value=\"" . $row['shipped_by'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Date Shipped: </strong></td>
														<td><input type=\"text\" name=\"date_shipped\" size=\"40\" value=\"" . $row['date_shipped'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Order Notes: </strong></td>
														<td width=\"80\"><textarea name=\"comments\" cols=\"45\" rows=\"10\">" . $row['comments'] . "</textarea></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Order Serials: </strong></td>
														<td width=\"80\"><textarea name=\"serials\" cols=\"45\" rows=\"10\">" . $row['serials'] . "</textarea></td>
													</tr>
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Billing Information</strong></td>
													</tr>";
								
									// Pull user's billing address and rip it out of the array
									$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='" . $actual_id . "' AND type='" . BILL_ADDRESS . "'";
									$result2 = mysql_query($sql2);
										
									if ($result2 && mysql_num_rows($result2) > 0) {
										while ($row2 = mysql_fetch_array($result2)) {	
											extract($row2);	
										}
									}			
									$billShowStates = ($country != "USA") ? " style=\"display: none;\"" : "";
									
									$page_content .= "					
													<tr>
														<td class=\"row1\" style=\"width: 200px;\"><strong>First Name</strong></td>
														<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_first_name\" id=\"Bill_first_name\" value=\"" . $first_name . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>Last Name</strong></td>
														<td class=\"row2\"><input type=\"text\" class=\"required\" name=\"Bill_last_name\" id=\"Bill_last_name\" value=\"" . $last_name . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Address</strong></td>
														<td class=\"row1\">
															<input type=\"text\" class=\"required\" name=\"Bill_street_1\" id=\"Bill_street_1\" size=\"60\" value=\"" . $street_1 . "\" /><br />
															<input type=\"text\" name=\"Bill_street_2\" id=\"Bill_street_2\" size=\"60\" value=\"" . $street_2 . "\" />
														</td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>City</strong></td>
														<td class=\"row2\"><input type=\"text\" class=\"required\" name=\"Bill_city\" id=\"Bill_city\" value=\"" . $city . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Country</strong></td>
														<td class=\"row1\">
															" . createDropdown("countries", "Bill_country", $country, "showStateDropBox(this, 'bill')") . "
														</td>
													</tr>									
													<tr>
														<td class=\"row2\"><strong>State / Province</strong></td>
														<td class=\"row2\">
															<span id=\"billStateRow\"" . $billShowStates . ">
																" . createDropdown("states", "Bill_state", $state, "") . "
															</span>
															<span id=\"billStateRow2\"" . $billShowStates2 . "><input type=\"text\" name=\"Bill_state2\" id=\"Bill_state2\" value=\"" . $state . "\" size=\"60\" /></span>
														</td>
													</tr>
													<tr>
														<td class=\"row1\"><strong>Zip</strong></td>
														<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_zip\" id=\"Bill_zip\" value=\"" . $zip . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>Primary Phone Number</strong></td>
														<td class=\"row2\"><input type=\"text\" name=\"Bill_day_phone\" id=\"Bill_day_phone\" value=\"" . $day_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Bill_day_phone_ext\" id=\"Bill_day_phone_ext\" value=\"" . $day_phone_ext . "\" size=\"6\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Secondary Phone Number</strong></td>
														<td class=\"row1\"><input type=\"text\" name=\"Bill_night_phone\" id=\"Bill_night_phone\" value=\"" . $night_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Bill_night_phone_ext\" id=\"Bill_night_phone_ext\" value=\"" . $night_phone_ext . "\" size=\"6\" /></td>
													</tr>
													<tr>
														<td class=\"row2\"><strong>Fax</strong></td>
														<td class=\"row2\"><input type=\"text\" name=\"Bill_fax\" id=\"Bill_fax\" value=\"" . $fax . "\" size=\"60\" /></td>
													</tr>
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Shipping Information</strong></td>
													</tr>";
													
									// Pull user's billing address and rip it out of the array
									$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='" . $actual_id . "' AND type='" . SHIP_ADDRESS . "'";
									$result2 = mysql_query($sql2);
										
									if ($result2 && mysql_num_rows($result2) > 0) {
										while ($row2 = mysql_fetch_array($result2)) {	
											extract($row2);	
										}
									}			
									$shipShowStates = ($country != "USA") ? " style=\"display: none;\"" : "";
									
									$page_content .= "					
													<tr>
														<td class=\"row1\" style=\"width: 200px;\"><strong>First Name</strong></td>
														<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_first_name\" id=\"Ship_first_name\" value=\"" . $first_name . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>Last Name</strong></td>
														<td class=\"row2\"><input type=\"text\" class=\"required\" name=\"Ship_last_name\" id=\"Ship_last_name\" value=\"" . $last_name . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Address</strong></td>
														<td class=\"row1\">
															<input type=\"text\" class=\"required\" name=\"Ship_street_1\" id=\"Ship_street_1\" size=\"60\" value=\"" . $street_1 . "\" /><br />
															<input type=\"text\" name=\"Ship_street_2\" id=\"Ship_street_2\" size=\"60\" value=\"" . $street_2 . "\" />
														</td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>City</strong></td>
														<td class=\"row2\"><input type=\"text\" class=\"required\" name=\"Ship_city\" id=\"Ship_city\" value=\"" . $city . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Country</strong></td>
														<td class=\"row1\">
															" . createDropdown("countries", "Ship_country", $country, "showStateDropBox(this, 'ship')") . "
														</td>
													</tr>	
														<td class=\"row2\"><strong>State / Province</strong></td>
														<td class=\"row2\">
															<span id=\"shipStateRow\"" . $shipShowStates . ">
																" . createDropdown("states", "Ship_state", $state, "") . "
															</span>
															<span id=\"shipStateRow2\"" . $shipShowStates2 . "><input type=\"text\" name=\"Ship_state2\" id=\"Ship_state2\" value=\"" . $state . "\" size=\"60\" /></span>
														</td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Zip</strong></td>
														<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_zip\" id=\"Ship_zip\" value=\"" . $zip . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>Primary Phone Number</strong></td>
														<td class=\"row2\"><input type=\"text\" name=\"Ship_day_phone\" id=\"Ship_day_phone\" value=\"" . $day_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Ship_day_phone_ext\" id=\"Ship_day_phone_ext\" value=\"" . $day_phone_ext . "\" size=\"6\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Secondary Phone Number</strong></td>
														<td class=\"row1\"><input type=\"text\" name=\"Ship_night_phone\" id=\"Ship_night_phone\" value=\"" . $night_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Ship_night_phone_ext\" id=\"Ship_night_phone_ext\" value=\"" . $night_phone_ext . "\" size=\"6\" /></td>
													</tr>
													<tr>
														<td class=\"row2\"><strong>Fax</strong></td>
														<td class=\"row2\"><input type=\"text\" name=\"Ship_fax\" id=\"Ship_fax\" value=\"" . $fax . "\" size=\"60\" /></td>
													</tr>
													<tr>
														<td class=\"title1\" colspan=\"2\">Credit Card Information</td>
													</tr>";
									// Pull user's billing address and rip it out of the array
									$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "creditcards` WHERE order_id='" . $actual_id . "' LIMIT 1";
									$result2 = mysql_query($sql2);
										
									if ($result2 && mysql_num_rows($result2) > 0) {
										while ($row2 = mysql_fetch_array($result2)) {	
											extract($row2);	
										}
									}
									
									$retryCreditCardLink = ($trans_result == "ACCEPT") ? "" : " <a href=\"" . $menuvar['CCRETRY'] . "&id=" . $actual_id . "\">Retry credit card</a>";
									
									$page_content .= "					
													<tr>
														<td class=\"row1\" style=\"width: 200px;\"><strong>Name as it Appears on Card</strong></td>
														<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"name_on_card\" id=\"name_on_card\" value=\"" . $name_on_card . "\" size=\"60\" /></td>
													</tr>				
													<tr>
														<td class=\"row2\" style=\"width: 200px;\"><strong>Card Type</strong></td>
														<td class=\"row2\">" . createDropdown("ccType", "card_type", $card_type, "") . "</td>
													</tr>			
													<tr>
														<td class=\"row1\" style=\"width: 200px;\"><strong>Card Number</strong></td>
														<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"card_number\" id=\"card_number\" value=\"" . $card_number . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>Security ID on the Back of the Card</strong></td>
														<td class=\"row2\"><input type=\"text\" class=\"required\" name=\"card_sid\" id=\"card_sid\" value=\"" . $card_sid . "\" size=\"60\" /></td>
													</tr>	
													<tr>
														<td class=\"row1\"><strong>Expiration Date</strong></td>
														<td class=\"row1\">" . createDropdown("ccExpMonth", "exp_month", $exp_month, "") . createDropdown("ccExpYear", "exp_year", $exp_year, "") . "</td>
													</tr>	
													<tr>
														<td class=\"row2\"><strong>Bank Name</strong></td>
														<td class=\"row2\"><input type=\"text\" name=\"bank_name\" id=\"bank_name\" size=\"60\" value=\"" . $bank_name . "\" /></td>
													</tr>
													<tr>
														<td class=\"row1\"><strong>Bank phone Number</strong></td>
														<td class=\"row1\"><input type=\"text\" name=\"bank_number\" id=\"bank_number\" size=\"60\" value=\"" . $bank_number . "\" /></td>
													</tr>
													<tr>
														<td class=\"title2\" colspan=\"2\">Credit Card Processing Information</td>
													</tr>
													<tr>
														<td class=\"row1\"><strong>Processing Result</strong></td>
														<td class=\"row1\">" . $trans_result . $retryCreditCardLink . "</td>
													</tr>
													<tr>
														<td class=\"row2\"><strong>Return Code</strong></td>
														<td class=\"row2\">" . $trans_code . "</td>
													</tr>
													<tr>
														<td class=\"row1\"><strong>Transaction Signature</strong></td>
														<td class=\"row1\">" . $trans_signature . "</td>
													</tr>
													<tr>
														<td class=\"row2\"><strong>Transaction Date</strong></td>
														<td class=\"row2\">" . makeDateTime($trans_timestamp) . "</td>
													</tr>
													<tr>
														<td class=\"row1\"><strong>Num of Tries</strong></td>
														<td class=\"row1\">" . $trans_tried . "</td>
													</tr>
													<tr>
														<td class=\"row2\"><strong>Verify Transaction Signature</strong></td>
														<td class=\"row2\">" . $trans_verify_signature . "</td>
													</tr>
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" /></div>
											</form>
											<script language = \"Javascript\">
												var valid = new Validation('checkoutForm', {immediate : true, useTitles:true});
											</script>
											<br /><br />";
				}
				else { $page_content .= "No such ID was found in the database!"; }
			}			
		}
		elseif ($actual_action == "editorderparts" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Order Parts", "");
			
			$page_content .= returnEditOrderTable($actual_id);		
		}			
		else {
			//==================================================
			// Print out our orders table
			//==================================================
			$showCount = $_POST['showCount'];
			$showCount = ($showCount == "") ? "50" : $showCount;
			$status = (isset($_POST['status']) && $_POST['status'] != "") ? $_POST['status'] : 0;
			$searchstopdateTimestamp = strtotime(gmdate('Y-m-d', strtotime($_POST['searchstopdate'] . "+1 day") + (3600 * '-7.00')));
			$limitSQL = "";
			
			if ($showCount != "") {
				$limitSQL = ($showCount != "showAll") ? " LIMIT 0, " . $showCount : "";
			}					
			
			$extraSQL = "";
			$extraSQL .= (isset($_POST['status']) && $_POST['status'] != "" && $_POST['status'] != "AllStatuses") ? " AND o.status = '" . $_POST['status'] . "'" : "";
			$extraSQL .= (isset($_POST['status']) && $_POST['status'] == "") ? " AND o.status = '0'" : "";
			$extraSQL .= (isset($_POST['userid']) && $_POST['userid'] != "") ? " AND o.user_id = '" . $_POST['userid'] . "'" : "";
			$extraSQL .= (isset($_POST['email_address']) && $_POST['email_address'] != "") ? " AND u.email_address LIKE '%" . $_POST['email_address'] . "%'" : "";
			$extraSQL .= (isset($_POST['userfirstname']) && $_POST['userfirstname'] != "") ? " AND u.first_name LIKE '%" . $_POST['userfirstname'] . "%'" : "";
			$extraSQL .= (isset($_POST['userlastname']) && $_POST['userlastname'] != "") ? " AND u.last_name LIKE '%" . $_POST['userlastname'] . "%'" : "";
			$extraSQL .= (isset($_POST['modelid']) && $_POST['modelid'] != "") ? " AND s.model_id = '" . $_POST['modelid'] . "'" : "";
			$extraSQL .= (isset($_POST['searchstartdate']) && $_POST['searchstartdate'] != "") ? " AND o.datetime >= '" . strtotime($_POST['searchstartdate']) . "'" : "";
			$extraSQL .= (isset($_POST['searchstopdate']) && $_POST['searchstopdate'] != "") ? " AND o.datetime <= '" . $searchstopdateTimestamp . "'" : "";
						
			$extraTables = "";
			$extraTables .= (isset($_POST['modelid']) && $_POST['modelid'] != "") ? " LEFT JOIN `" . DBTABLEPREFIX . "systems` s ON o.id = s.order_id" : "";
			
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` o LEFT JOIN `" . USERSDBTABLEPREFIX . "users` u ON o.user_id = u.id" . $extraTables . " WHERE 1" . $extraSQL . " GROUP BY o.id ORDER BY o.id DESC" . $limitSQL;
			$result = mysql_query($sql);
			//echo $sql;
				
			$x = 1; //reset the variable we use for our row colors	
				
			$page_content .= "
						<form name=\"searchOrdersForm\" id=\"searchOrdersForm\" action=\"\" method=\"post\">
							<input type=\"hidden\" name=\"showCount\" value=\"" . $showCount . "\" />
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Search Orders</td>
								</tr>	
								<tr>
									<td class=\"title2\" colspan=\"2\">Choose any or all of the following to search by.</td>
								</tr>	
								<tr class=\"row1\">
									<td><strong>User: </strong></td>
									<td>" . createDropdown("users", "userid", $_POST['userid'], "") . "</td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Email Address: </strong></td>
									<td><input type=\"text\" name=\"email_address\" size=\"40\" value=\"" . $_POST['email_address'] . "\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>User's First name: </strong></td>
									<td><input type=\"text\" name=\"userfirstname\" size=\"40\" value=\"" . $_POST['userfirstname'] . "\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>User's Last name: </strong></td>
									<td><input type=\"text\" name=\"userlastname\" size=\"40\" value=\"" . $_POST['userlastname'] . "\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>Order Status: </strong></td>
									<td>
										" . createDropdown("orderstatusforsearch", "status", $status, "") . "
									</td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Containing this Model: </strong></td>
									<td>" . createDropdown("models", "modelid", $_POST['modelid'], "") . "</td>
								</tr>
								<tr class=\"row1\">
									<td><strong>Search Starting at this Date: </strong></td>
									<td><input type=\"text\" name=\"searchstartdate\" size=\"40\" value=\"" . $_POST['searchstartdate'] . "\" /> ex. 01/22/08</td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Search Stopping at this Date: </strong></td>
									<td><input type=\"text\" name=\"searchstopdate\" size=\"40\" value=\"" . $_POST['searchstopdate'] . "\" /> ex. 01/22/08</td>
								</tr>
							</table>
							<br />
							<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Search!\" />
						</form>
						<br />
						<div id=\"updateMe\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"7\">Current Orders</td>
								</tr>						
								<tr class=\"title2\">
									<td><strong>Show Orders this Status</strong></td>
									<td class=\"row1\" colspan=\"6\">
										<form name=\"orderOrdersForm\" id=\"orderOrdersForm\" action=\"\" method=\"post\">
											" . createDropdown("orderstatusforsearch", "status", $status, "") . "
											<select name=\"showCount\">
												<option value=\"10\"" . testSelected($showCount, "10") . ">10</option>
												<option value=\"50\"" . testSelected($showCount, "50") . ">50</option>
												<option value=\"100\"" . testSelected($showCount, "100") . ">100</option>
												<option value=\"200\"" . testSelected($showCount, "200") . ">200</option>
												<option value=\"500\"" . testSelected($showCount, "500") . ">500</option>
												<option value=\"1000\"" . testSelected($showCount, "1000") . ">1000</option>
												<option value=\"showAll\"" . testSelected($showCount, "showAll") . ">Show All</option>
											</select>
											<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Go!\" />
										</form>
									</td>
								</tr>							
								<tr class=\"title2\">
									<td style=\"width: 20%;\"><strong>ID</strong></td><td><strong>User</strong></td><td><strong>Date</strong></td><td><strong>Rush</strong></td><td><strong>Total</strong></td><td><strong>Status</strong></td><td></td>
								</tr>";
			$ordersids = array();
			if (!$result || mysql_num_rows($result) == 0) { // No orders yet!
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"7\">There are no orders in the database.</td>
														</tr>";	
			}
			else {	 // Print all our orders								
				while ($row = mysql_fetch_array($result)) {
					$rush = ($row['rush_fee'] > 0) ? "<strong style=\"color: red;\">RUSH!</strong>" : "";

					$page_content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><a href=\"" . $menuvar['VIEWINVOICE'] . "&id=" . $row['id'] . "\">" . $row['id'] . "</a></td>
											<td><a href=\"" . $menuvar['USERS'] . "&action=edituser&id=" . $row['id'] . "\">" . getUsersNameByOrderID($row['id']) . "</a></td>
											<td>" . makeOrderDateTime($row['datetime']) . "</td>
											<td>" . $rush . "</td>
											<td><div id=\"" . $row['id'] . "_price\">" . formatCurrency($row['price']) . "</div></td>
											<td>" . printOrderStatusIncPageStatus($row['status']) . "</td>
											<td>
												<span class=\"center\"><a href=\"" . $menuvar['ORDERS'] . "&action=editorders&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a href=\"" . $menuvar['ORDERS'] . "&amp;action=emailorder&amp;id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/message.png\" alt=\"Email Order\" /></a> &nbsp; <a href=\"" . $menuvar['ORDERS'] . "&action=editorderparts&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check2.png\" alt=\"Edit Parts\" /></a> &nbsp; " . returnViewCreditCardlink($row['id']) . "<a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "OrdersSpinner', 'ajax.php?action=deleteitem&table=orders&id=" . $row['id'] . "', 'order', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Orders\" /></a><span id=\"" . $row['id'] . "OrdersSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
											</td>
										</tr>";
					$ordersids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$page_content .= "
									</table>
								</div>";
		}
		
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
	}
?>