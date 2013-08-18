<? 
/***************************************************************************
 *                               cart.php
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
 	if (isset($_SESSION['email_address'])) {
		if ($actual_action == "oldOrder") { 
			$_SESSION['orderid'] = $actual_id; 
			$page_content .= "Your order " . $_SESSION['orderid'] . " has been added to your shopping cart.<br /><br />";
		}
		elseif ($actual_action == "quoteToOrder") { 
			$page_content .= convertQuoteToOrder($actual_id, "1");
		}
		// Handle updating systems
 		if ($actual_action == "updateSystem" && isset($actual_id)) { 
			// Update system 
			updateSystem($_POST['Parts'], $actual_id, $_SESSION['userid'], $_SESSION['orderid']);
		}
		else {
			if (isset($_POST['Parts'])) { $_SESSION['orderid'] = createOrder($_POST['Parts'], $_POST['ModelID'], $_SESSION['userid'], $_SESSION['orderid']); }
		}
	}
	else {
		if (isset($_POST['Parts'])) {
			$oderid = createOrder($_POST['Parts'], $_POST['ModelID'], '999999');
			$_SESSION['userid'] = "999999"; // We need to set our userid to the guest # so that our JS updates and queries will work
			$_SESSION['orderid'] = $oderid;
		}
		// Disable redirect for login
		// header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN'] . "&action=updateOrder&id=" . $oderid));
	}
		// Start our page		
		$page_content .= "
						<table width=\"723\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td><img src=\"images/checkout/checkoutText_on.png\" width=\"244\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep1_on.png\" width=\"123\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep2_off.png\" width=\"160\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep3_off.png\" width=\"93\" height=\"51\"></td>
								<td><img src=\"images/checkout/checkoutStep4_off.png\" width=\"103\" height=\"51\"></td>
							</tr>
                        </table>
						<br /><br />
						<form name=\"cart\" id=\"cart\" action=\"" . $menuvar['CHECKOUT'] . "\" method=\"post\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"7\">Shopping Cart</td>
								</tr>";		
								
		if (!isset($_SESSION['orderid'])) {		
				$page_content .= "\n					<tr class=\"greenRow\">
													<td colspan=\"7\">Your cart is either empty or there was a problem adding parts to your cart.</td>
											</tr>";	
		}
		else {				
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $_SESSION['orderid'] . "'";
			$result = mysql_query($sql);
					
			$x = 1; //reset the variable we use for our row colors	
			$ordersids = array();
			$systemsids = array();
			
			if (!$result || mysql_num_rows($result) == 0) { // No orders yet!
				$page_content .= "\n					<tr class=\"greenRow\">
													<td colspan=\"7\">Unable to pull order info.</td>
												</tr>";	
			}
			else {	 // Print all our orders								
				while ($row = mysql_fetch_array($result)) {
					$processingType = ($row['rush_fee'] > 0) ? "Rush Processing" : "Standard Processing";				
					$showDiscountColumn = showOrderDiscountColumn($_SESSION['orderid']);
					$priceColSpan = ($showDiscountColumn) ? "" : " colspan=\"2\"";
					$discountColumn = ($showDiscountColumn) ? "<td class=\"title2\"><strong>Discount</strong></td>" : "";
					
					$page_content .= "
								<tr>
									<td class=\"title1\" colspan=\"7\"><strong>Products for Order #" . $_SESSION['orderid'] . "</strong></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>ID</strong></td>
									<td class=\"title2\"><strong>Description</strong></td>
									<td class=\"title2\"" . $priceColSpan . "><strong>Price</strong></td>
									" . $discountColumn . "
									<td class=\"title2\"><strong>Qty</strong></td>
									<td class=\"title2\"><strong>Item Total</strong></td>
									<td class=\"title2\"><strong>&nbsp;</strong></td>
								</tr>";	
					// Print out the systems info	
					$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "models` m ON s.model_id=m.id WHERE s.order_id = '" . $_SESSION['orderid'] . "' ORDER BY m.name";
					$result2 = mysql_query($sql2);
							
					$x = 1; //reset the variable we use for our row colors	

					if (mysql_num_rows($result2) == "0") { // No orders yet!
						$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"7\">Unable to pull order info.</td>
														</tr>";	
					}
					else {	 // Print all our orders								
						while ($row2 = mysql_fetch_array($result2)) {	
							$systemsids[$row2['id']] = "";
							$modelDiscount = ($showDiscountColumn) ? "<td>" . formatCurrency($row2['discount']) . "</td>" : "";
							
							$page_content .= "			
								<tr id=\"" . $row2['id'] . "_row\" class=\"row" . $x . "\">
									<td>" . $row2['id'] . "</td>
									<td>
										<strong>Model Name: </strong>" . $row2['name'] . "<br />";
										
							// Spit out the parts for the system			
							$sql3 = "SELECT part_name FROM `" . DBTABLEPREFIX . "systemparts` WHERE system_id = '" . $row2['id'] . "' AND UCASE(part_name)!='NONE' ORDER BY part_name";
							$result3 = mysql_query($sql3);
		
							if (mysql_num_rows($result3) > 0) { 						
								while ($row3 = mysql_fetch_array($result3)) {	
									$page_content .= $row3['part_name'] . "<br />";
								}		
								mysql_free_result($result3);
							}										
							$page_content .= "											
									</td>
									<td" . $priceColSpan . ">" . formatCurrency($row2['price']) . "</td>
									" . $modelDiscount . "
									<td><span id=\"" . $row2['id'] . "_qty\" class=\"cursorPointer\">" . $row2['qty'] . "</span></td>
									<td><span id=\"" . $row2['id'] . "itemsContainer\">" . formatCurrency($row2['qty'] * ($row2['price'] - $row2['discount'])) . "</span></td>
									<td class=\"title2\">												
										<span class=\"center\"><a href=\"" . $menuvar['CUSTOMIZE'] . "&action=editSystem&id=" . $row2['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit System\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteSystemNotifier('" . $row2['id'] . "', '" . $_SESSION['orderid'] . "', 'ajax.php?action=deleteitem&table=systems&id=" . $row2['id'] . "', 'system', '" . $row2['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete System\" /></a><span id=\"" . $row2['id'] . "SystemSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
									</td>
								</tr>";
								$x = ($x==2) ? 1 : 2;
						}		
						mysql_free_result($result2);
					}			
					$showCouponRow = showOrderCouponRow($_SESSION['orderid']);
					
					$page_content .= "				
									<tr>
										<td class=\"title1\" colspan=\"7\"><strong>Order Info</strong></td>
									</tr>		
									<tr>
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\"><strong>Subtotal</strong></td><td class=\"row1\"><span id=\"subtotalContainer\">" . formatCurrency($row['items_total']) . "</span><span id=\"" . $_SESSION['orderid'] . "itemsTotalSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>";
									
						$hideCouponRow = ($showCouponRow) ? "" : " style=\"display: none;\"";
						$page_content .= "			
									<tr id=\"" . $_SESSION['orderid'] . "couponRow\"" . $hideCouponRow . ">
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\"><strong>Coupon</strong></td><td class=\"row1\"><span id=\"couponContainer\">-" . formatCurrency(getOrderCouponDiscount($_SESSION['orderid'])) . "</span><span id=\"" . $_SESSION['orderid'] . "couponSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>";
									
						/* This is disabled for now
						$page_content .= "			
									<tr>
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\"><strong>Discount</strong></td><td class=\"row1\"><span id=\"discountContainer\"><input name=\"discount\" id=\"discount\" type=\"text\" size=\"10\" value=\"" . $row['discount'] . "\" /></span><span id=\"" . $orderID . "taxSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>";
						*/
						$shippingPrice = ($row['shipping_price'] < 1 || $row['shipping_price'] == "") ? "Enter Zip Code" : $row['shipping_price'];
						
						$page_content .= "	
									<tr>
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\"><strong>Sales Tax</strong></td><td class=\"row1\"><span id=\"taxContainer\">" . formatCurrency($row['tax']) . "</span><span id=\"" . $_SESSION['orderid'] . "taxSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>		
									<tr>
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\">
											<input type=\"text\" name=\"shippingZipCode\" id=\"shippingZipCode\" size=\"20\" value=\"Enter Zip Code\" onfocus=\"if (this.value=='Enter Zip Code') { this.value = ''; }\" onblur=\"if (this.value == '') { this.value = 'Enter Zip Code'; }\" />
											<input type=\"button\" class=\"button\" onClick=\"ajaxCalculateShippingInfo('" . $_SESSION['orderid'] . "', document.cart.shippingZipCode.value, document.cart.shipping.options[document.cart.shipping.selectedIndex].value);\" value=\"Calculate\" />											
											<strong>Shipping - " . createDropdown("shipping", "shipping", $row['shipping'], "ajaxUpdateShippingInfo('" . $_SESSION['orderid'] . "', document.cart.shipping.options[document.cart.shipping.selectedIndex].value);") . "</strong>
										</td>
										<td class=\"row1\"><span id=\"shippingFeeContainer\">" . formatCurrency($shippingPrice) . "</span><span id=\"" . $_SESSION['orderid'] . "shippingFeeSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>		
									<tr>
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\">
											<strong>Processing - " . createDropdown("processing", "processing", $row['rush_fee'], "ajaxUpdateRushFee('" . $_SESSION['orderid'] . "', document.cart.processing.options[document.cart.processing.selectedIndex].value);") . "</strong>
										</td><td class=\"row1\"><span id=\"rushFeeContainer\">" . formatCurrency($row['rush_fee']) . "</span><span id=\"" . $_SESSION['orderid'] . "rushFeeSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>		
									<tr>
										<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\"><strong>Order Total</strong></td><td class=\"row1\"><span id=\"priceContainer\">" . formatCurrency($row['price']) . "</span><span id=\"" . $_SESSION['orderid'] . "priceSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
									</tr>";
				}
			}
			mysql_free_result($result);
		}
		
		$page_content .= "					</table>
											<br /><br />
											<div class=\"center\">
												<a href=\"" . $menuvar['USERPANEL'] . "&action=updateOrder&id=" . $_SESSION['orderid'] . "\" class=\"saveOrderLink\"><span>Save Order</span></a>
												&nbsp;&nbsp;
												<input type=\"submit\" name=\"submit\" class=\"checkoutButton\" value=\"Checkout\" />
											</div>
										</form>
										<br class=\"clear\" /><br class=\"clear\" />
										
										<strong>Add a Coupon</strong><br />
										<em>Only one Coupon per Order is allowed. If you add another coupon it will replace your current one.</em><br />
										<div id=\"couponResponse\"></div>
										<form name=\"couponForm\" id=\"couponForm\" action=\"\" method=\"post\" onSubmit=\"return false;\">
											<input type=\"text\" name=\"coupon_code\" id=\"coupon_code\" />
											<input type=\"submit\" name=\"submit\" id=\"submit\" class=\"applyCouponButton\" value=\"Apply\" onClick=\"ajaxAddOrderCoupon('" . $_SESSION['orderid'] . "', document.couponForm.coupon_code.value);\" />
										</form>
									<script type=\"text/javascript\">";
			
			// Generate the AJAX code for inPlaceEditors for our main table
			$x = 1; //reset the variable we use for our highlight colors
			if (is_array($systemsids)) {
				foreach($systemsids as $key => $value) {
					$highlightColors = ($x == 1) ? "highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6'" : "highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC'";
					$page_content .=  "\n							new Ajax.InPlaceEditor('" . $key . "_qty', 'ajax.php?action=updateitem&table=systems&item=qty&id=" . $key . "', {rows:1,cols:5," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=systems&item=qty&id=" . $key . "', onComplete:function(){ ajaxUpdateOrderTotals(" . $_SESSION['orderid'] . ", " . $key . "); }});";
					$x = ($x==2) ? 1 : 2;
				}
			}
			$page_content .= "\n						
									</script>";
		
		// Print to page					
		$page->setTemplateVar("PageContent", $page_content);
?>