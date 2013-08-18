<?php 
/***************************************************************************
 *                               invoices.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
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
 
//=========================================================
// Returns an order invoice
//=========================================================
function returnInvoice($orderID, $userID) {
	global $ss_config, $_SESSION, $menuvar;
	
	//see if the user is trying to view someone elses order
		if ($_SESSION['user_level'] == USER || $_SESSION['user_level'] == BANNED) {
			$sql = "SELECT id FROM `" . DBTABLEPREFIX . "orders` WHERE id='$orderID' AND user_id='$userID'";
			$result = mysql_query($sql);
			$authorized = mysql_num_rows($result);
		}
		else { $authorized = 1; } 
		
		if ($authorized == 1) {
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id='$orderID'";
			$result = mysql_query($sql);
					
			$x = 1; //reset the variable we use for our row colors	
					
			$page_content = "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"6\">" . $ss_config['ftsss_store_name'] . " Invoice</td>
								</tr>";
			$ordersids = array();
			if (!$result || mysql_num_rows($result) == "0") { // No orders yet!
				$page_content .= "\n					<tr class=\"greenRow\">";
				$page_content .= "\n						<td colspan=\"5\">Unable to pull order info.</td>";
				$page_content .= "\n					</tr>";	
			}
			else {	 // Print all our orders								
				while ($row = mysql_fetch_array($result)) {
					$processingType = ($row['rush_fee'] > 0) ? "Rush Processing" : "Standard Processing";			
					$showDiscountColumn = showOrderDiscountColumn($orderID);
					$priceColSpan = ($showDiscountColumn) ? "" : " colspan=\"2\"";
					$discountColumn = ($showDiscountColumn) ? "<td class=\"title2\"><strong>Discount</strong></td>" : "";
					$techInfoBlock = getTechUserInfoFromID($row['tech_user_id']);
					$techInfoBlock = (trim($techInfoBlock) != "") ? $techInfoBlock . "<br />" : "";
					$storeInfoBlock = getStoreInfo();
					$storeInfoBlock = (trim($storeInfoBlock) != "") ? "<br />" . $storeInfoBlock : "";
					
					$page_content .= "								
								<tr class=\"title2\">
									<td colspan=\"6\">
										<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">
											<tr>
												<td width=\"34%\" style=\"vertical-align: top;\">
													<strong>Order Number: </strong>" . $row['id'] . "<br />
													<strong>Ordered On: </strong>" . makeOrderDateTime($row['datetimestamp']) . "<br />
													<strong>Email Address: </strong>" . getEmailAddressFromID($row['user_id']) . "<br />
													" . $techInfoBlock . "
													" . $storeInfoBlock . "
												</td>
												<td width=\"33%\" style=\"vertical-align: top;\">
													<strong style=\"text-decoration: underline;\">Bill To: </strong><br />
													" . getAddress(0, $row['id']) . "
												</td>
												<td width=\"33%\" style=\"vertical-align: top;\">
													<strong style=\"text-decoration: underline;\">Ship To: </strong><br />
													" . getAddress(1, $row['id']) . "
												</td>
											</tr>
										</table>
									</td>
								</tr>	
								<tr>
									<td class=\"title1\" colspan=\"6\"><strong>Products</strong></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>ID</strong></td>
									<td class=\"title2\"><strong>Description</strong></td>
									<td class=\"title2\"" . $priceColSpan . "><strong>Price</strong></td>
									" . $discountColumn . "
									<td class=\"title2\"><strong>Qty</strong></td>
									<td class=\"title2\"><strong>Item Total</strong></td>
								</tr>";	
					// Print out the systems info	
					$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders_products` WHERE order_id = '" . $orderID . "' ORDER BY name";
					$result2 = mysql_query($sql2);
							
					$x = 1; //reset the variable we use for our row colors	

					if (!$result2 || mysql_num_rows($result2) == 0) { // No orders yet!
						$page_content .= "\n					<tr class=\"greenRow\">
																	<td colspan=\"6\">Unable to pull order info.</td>
																</tr>";	
					}
					else {	 // Print all our orders								
						while ($row2 = mysql_fetch_array($result2)) {	
							$featureList = "";
							$modelDiscount = ($showDiscountColumn) ? "<td>" . formatCurrency($row2['discount']) . "</td>" : "";
							$productCost = $row2['price'] + $row2['shipping_costs'] + $row2['profit'];
							
							$sql3 = "SELECT feature_value_price, feature_name, feature_value_name FROM `" . DBTABLEPREFIX . "orders_products_features` WHERE product_id = '" . $row2['id'] . "' ORDER BY feature_name";
							$result3 = mysql_query($sql3);
							
							if ($result3 && mysql_num_rows($result3) > 0) {
								while ($row3 = mysql_fetch_array($result3)) {
									$productCost += $row3['feature_value_price'];
									$featureList .= $row3['feature_name'] . " - " . $row3['feature_value_name'] . "<br />";
								}
								mysql_free_result($result3);
							}
							$featureList = ($featureList == "") ? "" : "
										<br /><br />
										<strong>Features</strong><br />" . $featureList;
							
							$page_content .= "			
								<tr id=\"" . $row2['id'] . "_row\" class=\"row" . $x . "\">
									<td>" . $row2['id'] . "</td>
									<td>
										" . $row2['name'] . "
										" . $featureList . "										
									</td>
									<td" . $priceColSpan . ">" . formatCurrency($row2['price']) . "</td>
									" . $modelDiscount . "
									<td>" . $row2['qty'] . "</td>
									<td>" . formatCurrency(($row2['qty'] * $productCost) - $row2['discount']) . "</td>
								</tr>";
								$x = ($x==2) ? 1 : 2;
						}		
						mysql_free_result($result2);
					}
					$showCouponRow = showOrderCouponRow($orderID);
					
					$page_content .= "				
								<tr>
									<td class=\"title1\" colspan=\"6\"><strong>Order Info</strong></td>
								</tr>		
								<tr>
									<td class=\"title2\" colspan=\"5\" style=\"text-align: right;\"><strong>Subtotal</strong></td><td class=\"row1\">" . formatCurrency($row['items_total']) . "</td>
								</tr>";
									
					$hideCouponRow = ($showCouponRow) ? "" : " style=\"display: none;\"";
					$page_content .= "			
								<tr id=\"" . $_SESSION['orderid'] . "couponRow\"" . $hideCouponRow . ">
									<td class=\"title2\" colspan=\"5\" style=\"text-align: right;\"><strong>Coupon</strong></td><td class=\"row1\">-" . formatCurrency(getOrderCouponDiscount($orderID)) . "</td>
								</tr>";
									
					/* This is disabled for now
					$page_content .= "			
								<tr>
									<td class=\"title2\" colspan=\"6\" style=\"text-align: right;\"><strong>Discount</strong></td><td class=\"row1\"><span id=\"discountContainer\"><input name=\"discount\" id=\"discount\" type=\"text\" size=\"10\" value=\"" . $row['discount'] . "\" /></span><span id=\"" . $orderID . "taxSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></td>
								</tr>";
					*/
								
					$page_content .= "		
								<tr>
									<td class=\"title2\" colspan=\"5\" style=\"text-align: right;\"><strong>Sales Tax</strong></td><td class=\"row1\">" . formatCurrency($row['tax']) . "</td>
								</tr>		
								<tr>
									<td class=\"title2\" colspan=\"5\" style=\"text-align: right;\"><strong>Shipping - " . printShippingType($row['shipping_type']) . "</strong></td><td class=\"row1\">" . formatCurrency($row['shipping_price']) . "</td>
								</tr>		
								<tr>
									<td class=\"title2\" colspan=\"5\" style=\"text-align: right;\"><strong>" . $processingType . "</strong></td><td class=\"row1\">" . formatCurrency($row['rush_fee']) . "</td>
								</tr>		
								<tr>
									<td class=\"title2\" colspan=\"5\" style=\"text-align: right;\"><strong>Order Total</strong></td><td class=\"row1\">" . formatCurrency($row['price']) . "</td>
								</tr>	";
				}
			}
			mysql_free_result($result);
					
				
			$page_content .= "					</table>";
		}
		else { $page_content = "You Are Not Authorized To Access Other User's Orders. Please Refrain From Trying To Do So Again."; }
	
	// Return the invoice to the page that called it
	return $page_content;
}

?>