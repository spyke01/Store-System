<? 
/***************************************************************************
 *                               userpanel.php
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
 	if (isset($_SESSION['userid'])) {		
		
		// Don't allow guess users access
		if ($_SESSION['userid'] == "999999") {
			if ($actual_action == "updateOrder" && isset($actual_id)) {
				header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN'] . "&action=updateOrder&id=" . $_SESSION['orderid'] . "&redirect_to=userpanel"));
			}
			else {
				header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN'] . "&redirect_to=userpanel"));
			}
		}
		else {
			if ($actual_action == "updateOrder" && isset($actual_id)) {
				// Send user an email about the saved order
				$emailMessageText = "To our valued customer " . $row['first_name'] . " " . $row['last_name'] . ",<br />
						This is a copy of your saved order with " . $ss_config['ftsss_store_name'] . ".<br /><br />
						<a href=\"" . returnHttpLinks($ss_config['ftsss_store_url']) . "/" . $menuvar['CHECKOUT'] . "&action=checkoutOrder&id=" . $_SESSION['orderid'] . "\"><img src=\"" . returnHttpLinks($ss_config['ftsss_store_url']) . "/themes/" . $themeDir . "/images/buttons/checkout.gif\" alt=\"Checkout\" /></a>
						<br /><br />
						Sincerely,
						<br />
						The " . $ss_config['ftsss_store_name'] . " Team<br /><br />" . returnInvoice($_SESSION['orderid'], $_SESSION['userid']);
				$emailSubject = $ss_config['ftsss_store_name'] . " Saved Order #" . $_SESSION['orderid'];
				$success = emailMessage($_SESSION['email_address'], $emailSubject, $emailMessageText);
				
				// Send admins an email about the saved order
				$emailSubject = "Saved Order #" . $_SESSION['orderid'];
				$emailMessageText = $_SESSION['email_address'] . " (" . $_SESSION['userid'] . ") has saved Order #: " . $_SESSION['orderid'] . " at " . makeDateTime(time());
				$success = emailMessage($ss_config['ftsss_sales_email'], $emailSubject, $emailMessageText);
	
				// Mark order as saved
				changeOrderStatus($_SESSION['orderid'], STATUS_ORDER_SAVED);
			}
		}			
			
			//==================================================
			// Print out our orders for our client
			//==================================================
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE user_id = '" . $_SESSION['userid'] . "' ORDER BY datetimestamp";
			$result = mysql_query($sql);
				
			$x = 1; //reset the variable we use for our row colors	
				
			$page_content .= "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"6\">Orders</td>
								</tr>							
								<tr class=\"title2\">
									<td style=\"width: 20%;\"><strong>ID</strong></td><td><strong>Date</strong></td><td><strong>Status</strong></td><td><strong>Rush</strong></td><td><strong>Total</strong></td><td></td>
								</tr>";
			
			if (!$result || mysql_num_rows($result) == "0") { // No dists yet!
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"6\">You do not have any orders on file.</td>
														</tr>";	
			}
			else {	 // Print all our dists								
				while ($row = mysql_fetch_array($result)) {
					$rush = ($row['rush_fee'] > 0) ? "<strong style=\"color: red;\">RUSH!</strong>" : "";
					$retryCreditCardLink = "";
					$status = printOrderStatus($row['status']);
					
					if ($row['status'] == STATUS_CREDIT_CARD_PAYMENT) {
						// Pull user's billing address and rip it out of the array
						$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders_creditcards` WHERE orders_creditcards_order_id='" . $row['id'] . "' LIMIT 1";
						$result2 = mysql_query($sql2);
										
						if ($result2 && mysql_num_rows($result2) > 0) {
							while ($row2 = mysql_fetch_array($result2)) {	
								$retryCreditCardLink = ($row2['trans_result'] == "ACCEPT") ? "" : " <a href=\"" . $menuvar['CCRETRY'] . "&id=" . $row['id'] . "\">Retry credit card</a>";
								$status = ($row2['trans_result'] == "ACCEPT") ? $status : "<span style=\"color: red;\">Credit Card Has Been Declined</span>";
							}
							mysql_free_result($result2);
						}
					}
					
					$page_content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><a href=\"" . $menuvar['VIEWINVOICE'] . "&id=" . $row['id'] . "\">" . $row['id'] . "</a></td>
											<td>" . makeOrderDateTime($row['datetimestamp']) . "</td>
											<td>" . $status . $retryCreditCardLink . "</td>
											<td>" . $rush . "</td>
											<td><div id=\"" . $row['id'] . "_price\">" . formatCurrency($row['price']) . "</div></td>
											<td>";
											
					if ($row['status'] == STATUS_ORDER_SUBMITTED || $row['status'] == STATUS_ORDER_SAVED) {
					 	$page_content .= "				
												<span class=\"center\"><a href=\"" . $menuvar['CART'] . "&action=oldOrder&id=" . $row['id'] . "\" class=\"addToCartLink\"><span>Add to Cart</span></a> <span id=\"" . $row['id'] . "OrdersSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>";
					}			
					elseif ($row['status'] == STATUS_STEP2) {
					 	$page_content .= "				
												<span class=\"center\"><a href=\"" . $menuvar['CHECKOUT'] . "&action=oldOrder&id=" . $row['id'] . "\" class=\"addToCartLink\">Add to Cart</span></a> <span id=\"" . $row['id'] . "OrdersSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>";
					}			
					elseif ($row['status'] == STATUS_STEP3 || $row['status'] == STATUS_STEP4) {
					 	$page_content .= "				
												<span class=\"center\"><a href=\"" . $menuvar['PAYMENT'] . "&action=oldOrder&id=" . $row['id'] . "\" class=\"addToCartLink\">Add to Cart</span></a> <span id=\"" . $row['id'] . "OrdersSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>";
					}
					
					$page_content .=	"	
											</td>
										</tr>";			
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
			
			$page_content .= "					</table>
								<br /><br />";
			
			//==================================================
			// If we posted changes do them now
			//==================================================
			if (isset($_POST['submit'])) {
				$first_name = keeptasafe($_POST['first_name']);
				$last_name = keeptasafe($_POST['last_name']);
				$email_address = keeptasafe($_POST['email_address']);
				$company = keeptasafe($_POST['company']);
				$password = keeptasafe($_POST['password']);
				$password2 = keeptasafe($_POST['password2']);	
				$poston_email_list = keepsafe($_POST['on_email_list']);		
				$poston_email_list = ($poston_email_list != 1) ? 0 : 1;	
								
				// Update User Account
				$passwordSQL = ($password != "" && $password == $password2) ? ", `password`='" . md5($password) . "'" : "";
				$sql = "UPDATE `" . USERSDBTABLEPREFIX . "users` SET `first_name`='" . $first_name . "', `last_name`='" . $last_name . "', `company`='" . $company . "', `email_address`='" . $email_address . "', `on_email_list`='" . $poston_email_list . "'" . $passwordSQL . " WHERE `id`='" . $_SESSION['userid'] . "';";
				$result = mysql_query($sql);
				
				// Unset our checker
				unset($_POST['full_name']);
			}
						
			//==================================================
			// Print out our user settings
			//==================================================
			$page_content .= "									
								<form name=\"updateAddressesForm\" id=\"updateAddressesForm\" action=\"$menuvar[USERPANEL]\" method=\"post\">
									<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
										<tr>
											<td class=\"title1\" colspan=\"2\">User Information</td>
										</tr>";
			// Pull user's billing address and rip it out of the array
				$sql2 = "SELECT first_name, last_name, company, email_address, on_email_list FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $_SESSION['userid'] . "'";
				$result2 = mysql_query($sql2);
					
				if ($result2 && mysql_num_rows($result2) > 0) {
					while ($row2 = mysql_fetch_array($result2)) {	
						extract($row2);	
					}
				}			
				$page_content .= "					
										<tr>
											<td class=\"title2\" style=\"width: 200px;\"><strong>First Name</strong></td>
											<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"first_name\" id=\"first_name\" value=\"" . $first_name . "\" size=\"60\" /></td>
										</tr>				
										<tr>
											<td class=\"title2\" style=\"width: 200px;\"><strong>Last Name</strong></td>
											<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"last_name\" id=\"last_name\" value=\"" . $last_name . "\" size=\"60\" /></td>
										</tr>			
										<tr>
											<td class=\"title2\" style=\"width: 200px;\"><strong>Company</strong></td>
											<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"company\" id=\"company\" value=\"" . $company . "\" size=\"60\" /></td>
										</tr>	
										<tr>
											<td class=\"title2\"><strong>Email Address</strong></td>
											<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"email_address\" id=\"email_address\" value=\"" . $email_address . "\" size=\"60\" /></td>
										</tr>	
										<tr>
											<td class=\"title2\"><strong>Password</strong></td>
											<td class=\"row1\"><input type=\"password\" name=\"password\" id=\"password\" size=\"60\" /></td>
										</tr>	
										<tr>
											<td class=\"title2\"><strong>Confirm Password</strong></td>
											<td class=\"row1\"><input type=\"password\" name=\"password2\" id=\"password2\" size=\"60\" /></td>
										</tr>
										<tr> 
											<td class=\"title2\">Would you like to receive emails from us?</td>
											<td class=\"row1\"><input name=\"on_email_list\" type=\"checkbox\" value=\"1\"" . testChecked(1, $on_email_list) . " /></td>
										</tr>
									</table>
									<br /><br />";
						
			//==================================================
			// Print out our users credit card info
			//==================================================
			$page_content .= "
									<div id=\"userCreditCardHolder\">
									" . getUserPanelUserCreditCard($_SESSION['userid']) . "
									</div>
									<br /><br />";
								
						
			//==================================================
			// Print out our adress tables
			//==================================================
			$page_content .= "
									<div id=\"userBillAddressHolder\">
									" . getUserPanelUserAddress($_SESSION['userid'], BILL_ADDRESS) . "
									</div>
									<br /><br />
									<div id=\"userShipAddressHolder\">
									" . getUserPanelUserAddress($_SESSION['userid'], SHIP_ADDRESS) . "
									</div>";
	}
	else {
		header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN'] . "&action=updateOrder&id=" . $_SESSION['orderid'] . "&redirect_to=userpanel"));
	}
	$page->setTemplateVar("PageContent", $page_content);

?>