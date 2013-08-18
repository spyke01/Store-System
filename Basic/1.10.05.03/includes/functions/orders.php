<?php 
/***************************************************************************
 *                               orders.php
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
// Prints the status of an order
//=========================================================
function printOrderStatus($statusID) {
	switch($statusID) {
		case STATUS_ORDER_SUBMITTED:
			return STATUS_ORDER_SUBMITTED_STATUS_TXT;
			break;
		case STATUS_ORDER_SAVED:
			return STATUS_ORDER_SAVED_STATUS_TXT;
			break;
		case STATUS_PAYMENT_PROCESSING:
			return STATUS_PAYMENT_PROCESSING_STATUS_TXT;
			break;
		case STATUS_PAYMENT_PROCESSED:
			return STATUS_PAYMENT_PROCESSED_STATUS_TXT;
			break;
		case STATUS_PREPARING_ORDER:
			return STATUS_PREPARING_ORDER_STATUS_TXT;
			break;
		case STATUS_PACKAGING_PRODUCT:
			return STATUS_PACKAGING_PRODUCT_STATUS_TXT;
			break;
		case STATUS_ORDER_SHIPPED:
			return STATUS_ORDER_SHIPPED_STATUS_TXT;
			break;
		case STATUS_STEP2:
			return STATUS_STEP2_STATUS_TXT;
			break;
		case STATUS_STEP3:
			return STATUS_STEP3_STATUS_TXT;
			break;
		case STATUS_STEP4:
			return STATUS_STEP4_STATUS_TXT;
			break;
		case STATUS_AWAITING_PAYMENT:
			return STATUS_AWAITING_PAYMENT_STATUS_TXT;
			break;
		case STATUS_CREDIT_CARD_PAYMENT:
			return STATUS_CREDIT_CARD_PAYMENT_STATUS_TXT;
			break;
		case STATUS_CHECK_PAYMENT:
			return STATUS_CHECK_PAYMENT_STATUS_TXT;
			break;
		case STATUS_WIRE_TRANSFER_PAYMENT:
			return STATUS_WIRE_TRANSFER_PAYMENT_STATUS_TXT;
			break;
		case STATUS_ORDER_CANCELLED:
			return STATUS_ORDER_CANCELLED_STATUS_TXT;
			break;
		case STATUS_ORDER_ON_HOLD:
			return STATUS_ORDER_ON_HOLD_STATUS_TXT;
			break;
		case STATUS_IN_BUILD_QUEUE:
			return STATUS_IN_BUILD_QUEUE_STATUS_TXT;
			break;
		case STATUS_BUILD_STARTED:
			return STATUS_BUILD_STARTED_STATUS_TXT;
			break;
		default:
			return STATUS_ORDER_SUBMITTED_STATUS_TXT;
			break;
	}
}

//=========================================================
// Prints the status of an order
//=========================================================
function printOrderStatusIncPageStatus($statusID) {
	switch($statusID) {
		case STATUS_ORDER_SUBMITTED:
			return STATUS_ORDER_SUBMITTED_STATUS_TXT;
			break;
		case STATUS_ORDER_SAVED:
			return STATUS_ORDER_SAVED_STATUS_TXT;
			break;
		case STATUS_PAYMENT_PROCESSING:
			return STATUS_PAYMENT_PROCESSING_STATUS_TXT;
			break;
		case STATUS_PAYMENT_PROCESSED:
			return STATUS_PAYMENT_PROCESSED_STATUS_TXT;
			break;
		case STATUS_PREPARING_ORDER:
			return STATUS_PREPARING_ORDER_STATUS_TXT;
			break;
		case STATUS_PACKAGING_PRODUCT:
			return STATUS_PACKAGING_PRODUCT_STATUS_TXT;
			break;
		case STATUS_ORDER_SHIPPED:
			return STATUS_ORDER_SHIPPED_STATUS_TXT;
			break;
		case STATUS_STEP2:
			return STATUS_STEP2_STATUS_TXT;
			break;
		case STATUS_STEP3:
			return STATUS_STEP3_STATUS_TXT;
			break;
		case STATUS_STEP4:
			return STATUS_STEP4_STATUS_TXT;
			break;
		case STATUS_AWAITING_PAYMENT:
			return STATUS_AWAITING_PAYMENT_STATUS_TXT;
			break;
		case STATUS_CREDIT_CARD_PAYMENT:
			return STATUS_CREDIT_CARD_PAYMENT_STATUS_TXT;
			break;
		case STATUS_CHECK_PAYMENT:
			return STATUS_CHECK_PAYMENT_STATUS_TXT;
			break;
		case STATUS_WIRE_TRANSFER_PAYMENT:
			return STATUS_WIRE_TRANSFER_PAYMENT_STATUS_TXT;
			break;
		case STATUS_ORDER_CANCELLED:
			return STATUS_ORDER_CANCELLED_STATUS_TXT;
			break;
		case STATUS_ORDER_ON_HOLD:
			return STATUS_ORDER_ON_HOLD_STATUS_TXT;
			break;
		case STATUS_IN_BUILD_QUEUE:
			return STATUS_IN_BUILD_QUEUE_STATUS_TXT;
			break;
		case STATUS_BUILD_STARTED:
			return STATUS_BUILD_STARTED_STATUS_TXT;
			break;
		default:
			return STATUS_ORDER_SUBMITTED_STATUS_TXT;
			break;
	}
}

//=========================================================
// Prints the long status of an order
//=========================================================
function printOrderStatusLong($statusID) {
	switch($statusID) {
		case STATUS_ORDER_SUBMITTED:
			return STATUS_ORDER_SUBMITTED_TXT;
			break;
		case STATUS_ORDER_SAVED:
			return STATUS_ORDER_SAVED_TXT;
			break;
		case STATUS_PAYMENT_PROCESSED:
			return STATUS_PAYMENT_PROCESSED_TXT;
			break;
		case STATUS_PREPARING_ORDER:
			return STATUS_PREPARING_ORDER_TXT;
			break;
		case STATUS_PACKAGING_PRODUCT:
			return STATUS_PACKAGING_PRODUCT_TXT;
			break;
		case STATUS_ORDER_SHIPPED:
			return STATUS_ORDER_SHIPPED_TXT;
			break;
		case STATUS_AWAITING_PAYMENT:
			return STATUS_AWAITING_PAYMENT_TXT;
			break;
		case STATUS_CREDIT_CARD_PAYMENT:
			return STATUS_CREDIT_CARD_PAYMENT_TXT;
			break;
		case STATUS_CHECK_PAYMENT:
			return STATUS_CHECK_PAYMENT_TXT;
			break;
		case STATUS_WIRE_TRANSFER_PAYMENT:
			return STATUS_WIRE_TRANSFER_PAYMENT_TXT;
			break;
		case STATUS_ORDER_CANCELLED:
			return STATUS_ORDER_CANCELLED_TXT;
			break;
		case STATUS_ORDER_ON_HOLD:
			return STATUS_ORDER_ON_HOLD_TXT;
			break;
		case STATUS_IN_BUILD_QUEUE:
			return STATUS_IN_BUILD_QUEUE_TXT;
			break;
		case STATUS_BUILD_STARTED:
			return STATUS_BUILD_STARTED_TXT;
			break;
		default:
			return STATUS_ORDER_SUBMITTED_TXT;
			break;
	}
}

//=========================================================
// Changes the status of an order
//=========================================================
function changeOrderStatus($orderID, $statusID) {
	$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET status='" . $statusID . "' WHERE id='" . $orderID . "'";
	$result = mysql_query($sql);
	
}

//=========================================================
// Gets the order status from an orderid
//=========================================================
function returnOrderStatusByID($orderID) {
	$sql = "SELECT status FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $orderID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['status'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets the ship to country from an orderid
//=========================================================
function returnOrderShipCountryByID($orderID) {
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='" . $orderID . "' AND type='1'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['country'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets the ship to country from an orderid
//=========================================================
function returnOrderShipStateByID($orderID) {
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='" . $orderID . "' AND type='1'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['state'];
		}
	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets an address from an orderid
//=========================================================
function getAddress($type, $orderID) {
	global $FTS_COUNTRIES;
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='" . $orderID . "' AND type='" . $type . "'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$addressLine2 = (trim($row['street_2']) != "") ? $row['street_2'] . "<br />" : "";
		$companyName = (trim($row['company']) != "") ? $row['company'] . "<br />" : "";
		$adressVar = $companyName . 
					$row['first_name'] . " " . $row['last_name'] . "<br />" .
					$row['street_1'] . "<br />" .
					$addressLine2 .
					$row['city'] . ", " . $row['state'] . " " . $FTS_COUNTRIES[$row['country']] . " " . $row['zip'] . "<br /><br />" .
					//"<strong>Email Address: </strong>" . $row['email_address'] . "<br />" .
					"<strong>Primary Phone Number: </strong>" . $row['day_phone'] . "<br />" .
					"<strong>Secondary Phone Number: </strong>" . $row['night_phone'] . "<br />" .
					"<strong>Fax: </strong>" . $row['fax'] . "<br />";
		return $adressVar;
	}
	
	mysql_free_result($result);
}

//=========================================================
// Gets an order's CC info from the DB
//=========================================================
function getOrderCreditCard($orderID) {
	global $_SESSION;
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders_creditcards` WHERE orders_creditcards_order_id='" . $orderID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	$row = mysql_fetch_array($result);
	
	$adressVar = "
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">
									Credit Card Information
								</td>
							</tr>		
							<tr>
								<td class=\"title2\" style=\"width: 200px;\"><strong>Name as it Appears on Card</strong></td>
								<td class=\"row1\">" . $row['name_on_card'] . "</td>
							</tr>				
							<tr>
								<td class=\"title2\"><strong>Card Type</strong></td>
								<td class=\"row1\">" . printCreditCardType($row['card_type']) . "</td>
							</tr>			
							<tr>
								<td class=\"title2\"><strong>Card Number</strong></td>
								<td class=\"row1\">" . $row['card_number'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Security ID on the Back of the Card</strong></td>
								<td class=\"row1\">" . $row['card_sid'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Expiration Date</strong></td>
								<td class=\"row1\">" . printCreditCardMonth($row['exp_month']) . "/" . printCreditCardYear($row['exp_year']) . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Bank Name</strong></td>
								<td class=\"row1\">" . $row['bank_name'] . "</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>Bank phone Number</strong></td>
								<td class=\"row1\">" . $row['bank_number'] . "</td>
							</tr>
						</table>";
	return $adressVar;
	
	mysql_free_result($result);
}

//=========================================================
// Returns a link to the viewCreditCard page with an image link
//=========================================================
function returnViewCreditCardlink($orderID) {
	global $_SESSION, $menuvar;
	$returnVar = "";
	
	$sql = "SELECT card_type FROM `" . DBTABLEPREFIX . "orders_creditcards` WHERE orders_creditcards_order_id='" . $orderID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			if ($row['card_type'] == "MC") { $ccType = "creditcard_mastercard"; }
			else { $ccType = "creditcard_visa"; }
		
			$returnVar = "<a href=\"" . $menuvar['ORDERS'] . "&action=viewCreditCard&id=" . $orderID . "\"><img src=\"images/" . $ccType . ".png\" alt=\"\" style=\"width: 20px; height: 20px; border: 0px;\" /></a> &nbsp;";
		}
	}
	else {}
	
	return $returnVar;
	
	mysql_free_result($result);
}

//=========================================================
// Returns 1 if this order has at least 1 system with a 
// discount
//=========================================================
function showOrderDiscountColumn($orderID) {
	$sql = "SELECT COUNT(discount) AS totalDiscountedSystems FROM `" . DBTABLEPREFIX . "orders_products` WHERE order_id = '" . $orderID . "' AND discount > 0";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) { return 1; }
	else { return 0; }
}

//=========================================================
// Returns 1 if this order has a coupon attached to it
//=========================================================
function showOrderCouponRow($orderID) {
	$sql = "SELECT coupon_code FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $orderID . "' AND coupon_code != ''";
	$result = mysql_query($sql);	
	
	if ($result && mysql_num_rows($result) > 0) { return 1; }
	else { return 0; }
}

//=========================================================
// Updates the many values in an order so that it matches 
// the systems in it
//=========================================================
function updateOrder($userID, $orderID, $isNewOrder = 0) {
	$totalWeight = 0;
	$totalPrice = 0;
	$featuresPrice = 0;
	$orderRushFee = 0;
	$shippingCostInDB = 0;
	
	// Make sure all orders_products are up to date with quantity * cost = total_cost
	$sql = "UPDATE  `" . DBTABLEPREFIX . "orders_products` SET total_cost = (((price + shipping_costs + profit) * qty) - discount) WHERE order_id = '" . $orderID . "'";
	$result = mysql_query($sql);
	//echo $sql . "<br />";
	
	//Get a new total of weights and prices, this way the data is fresh and takes into account our order having other systems
	$sql = "SELECT SUM(weight * qty) AS total_weight, (SUM((price + shipping_costs + profit) * qty) - discount) AS total_price FROM `" . DBTABLEPREFIX . "orders_products` WHERE order_id = '" . $orderID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$totalWeight = $row['total_weight'];
			$itemsTotal = $row['total_price'];
		}
		mysql_free_result($result);
	}
	//echo $sql . "<br />";
	
	// Add up feature prices
	$sql = "SELECT SUM(opf.feature_value_price) AS totalPrice FROM `" . DBTABLEPREFIX . "orders_products_features` opf LEFT JOIN `" . DBTABLEPREFIX . "orders_products` op ON op.product_id = opf.product_id WHERE op.order_id = '" . $orderID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$featuresPrice += $row['totalPrice'];
		}
		mysql_free_result($result);
	}
	//echo $sql . "<br />";
	
	// Apply discount and coupon to total price
	$sql = "SELECT discount, coupon_discount, coupon_discount_percentage, rush_fee, shipping_price FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $orderID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$orderDiscount = $row['discount'];
			$orderCouponDiscount = $row['coupon_discount'];
			$orderCouponDiscountPercentage = $row['coupon_discount_percentage'];
			$orderRushFee = $row['rush_fee'];
			$shippingCostInDB = $row['shipping_price'];
			$totalPrice = $itemsTotal + $featuresPrice - $orderDiscount - $orderCouponDiscount;
			$totalPrice = $totalPrice - ($totalPrice * ($orderCouponDiscountPercentage / 100));
		}
		mysql_free_result($result);
	}
	//echo $sql . "<br />";
	
	// Calculate the tax
	$tax = calculateTax($totalPrice, $userID);
	
	// Add the rush fee
	$totalPrice += $orderRushFee;
	
	//Only calculate shipping costs on new orders, otherwise we need to use the cost in database
	if ($isNewOrder) {
		$ShipTotal = calculateShippingCost($orderID, $userID, "-1");
	}
	else {
		$ShipTotal = $shippingCostInDB;
	}
	$totalPrice += $ShipTotal;
	
	// Update our order
	$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET user_ip='" . $_SERVER['REMOTE_ADDR'] . "', tax='" . $tax . "', items_total = '" . $itemsTotal . "', price = '" . ($totalPrice + $tax) . "', discount = '" . $orderDiscount . "' WHERE id='" . $orderID . "'";
	$result = mysql_query($sql);
}

//=========================================================
// Creates an order using arrays and IDs supplied to the function
//=========================================================
function createOrder($productFeatures, $productCatID, $productID, $userID, $orderID = "") {
	$totalWeight = 0;
	$totalPrice = 0;
	$isNewOrder = 0;
	$totalDiscount = 0;
	$totalDiscountPercentage = 0;
	
	if ($orderID == "") {
		// Insert a blank order
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "orders` (`user_id`, `datetimestamp`) VALUES ('" . $userID . "', '" . time() . "')";
		$result = mysql_query($sql);
	
		$orderID = mysql_insert_id();
		$isNewOrder = 1; // Calculate shipping cost since this is a new order
	}
	
	// Get products items
	$sql = "SELECT id, item_num, name, weight, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "products` WHERE id = '" . $productID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {	
		while ($row = mysql_fetch_array($result)) {
			$totalWeight = $row['weight'];
			$productPrice = $row['price'] + $row['shipping_costs'] + $row['profit'];
			
			$sql2 = "INSERT INTO `" . DBTABLEPREFIX . "orders_products` (`order_id`, `product_id`, `productcat_id`, `name`, `weight`, `price`, `shipping_costs`, `profit`, `total_cost`) VALUES ('" . $orderID . "', '" . $row['id'] . "', '" . $productCatID . "', '" . keeptasafe($row['name']) . "', '" . $row['weight'] . "', '" . $row['price'] . "', '" . $row['shipping_costs'] . "', '" . $row['profit'] . "', '" . $productPrice . "')";
			$result2 = mysql_query($sql2);
			//echo $sql2 . "<br />";
			$orderProductID = mysql_insert_id();
		}
		mysql_free_result($result);
	}
	//echo $sql . "<br />";
	
	// Get product features
	if (is_array($productFeatures)) {
		foreach ($productFeatures as $productFeaturesID => $productFeaturesValueID) {
			$sql = "SELECT pf.name, pfv.price, pfv.name FROM `" . DBTABLEPREFIX . "products_features_values` pfv LEFT JOIN `" . DBTABLEPREFIX . "products_features` pf ON pf.id=pfv.feature_id WHERE pfv.id='" . $productFeaturesValueID . "'";
			$result = mysql_query($sql);
	
			if ($result && mysql_num_rows($result) > 0) {	
				while ($row = mysql_fetch_array($result)) {
					$sql2 = "INSERT INTO `" . DBTABLEPREFIX . "orders_products_features` (`product_id`, `feature_id`, `feature_name`, `feature_value_id`, `feature_value_price`, `feature_value_name`) VALUES ('" . $orderProductID . "', '" . $productFeaturesID . "', '" . $row['name'] . "', '" . $productFeaturesValueID . "', '" . $row['price'] . "', '" . $row['name'] . "')";
					$result2 = mysql_query($sql2);
					//echo $sql2 . "<br />";
				}
				mysql_free_result($result);
			}
			//echo $sql . "<br />";
		}
	}
	
	// Update order totals
	updateOrder($userID, $orderID, $isNewOrder);
	
	return $orderID;
}

//=========================================================
// Returns the total coupons discount
//=========================================================
function getOrderCouponDiscount($orderID) {
	// Apply discount to total price
	$sql = "SELECT items_total, coupon_discount, coupon_discount_percentage, rush_fee, shipping_price FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $orderID . "'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$orderCouponDiscount = $row['coupon_discount'];
		$orderCouponDiscountPercentage = $row['coupon_discount_percentage'];
		$totalPrice = $row['items_total'];
		$totalDiscount = $orderCouponDiscount;
		$totalDiscount += ($totalPrice * ($orderCouponDiscountPercentage / 100));
	}
	mysql_free_result($result);
	
	return $totalDiscount;
}

//=========================================================
// Sends an email out about an order that was built
//=========================================================
function emailOrder($productsArray, $modelID, $emailAddress) {
	global $ss_config;
	$totalPrice = 0;
	$defaultPartCatIDs = array();	

	// subject
	$subject = "Custom System Datasheet";
		
	// message
	//$message = "Custom System Datasheet: <br />\n<br /><br />\n\n";
	
	// Get model items
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` WHERE models_id = '" . $modelID . "'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$totalPrice = $row['models_base_price'] + $row['models_base_profit'] - $row['models_discount'];
		$message .= "<strong>" . $row['models_name'] . "</strong>\n<br />\n<br /><img src=\"" . returnHttpLinks($ss_config['ftsss_store_url']) . "/" . $row['models_image_thumb'] . "\" alt=\"" . $row['models_name'] . "\" />\n<br />\n<br />" . $row['models_description'] . "<br /><br />\n\n";
	}
	mysql_free_result($result);
	
	// Handle putting our productcats into the proper order since we cannot use SQL down the road to order things.
	$extraSQL = "";
		
	foreach($productsArray as $productCatID => $selectedPartID) {
		$extraSQL .= ($extraSQL == "") ? " WHERE" : " OR";
		$extraSQL .= " id = '" . $productCatID . "'";
	}
	
	$sql = "SELECT id FROM `" . DBTABLEPREFIX . "productcats`" . $extraSQL . " ORDER BY sort_order ASC";
	$result = mysql_query($sql);	
		
	if ($result && mysql_num_rows($result) > 0) {				
		while ($row = mysql_fetch_array($result)) {
			// Push the values onto our temp arrays
			array_push($defaultPartCatIDs, $row['id']);
		}
		mysql_free_result($result);
	}
	
	// Get parts items
	foreach ($defaultPartCatIDs as $key => $productCatID) {
		$productID = $productsArray[$productCatID];
		
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "productcats` WHERE id ='" . $productCatID . "'";
		$result = mysql_query($sql);
			
		while ($row = mysql_fetch_array($result)) {			
			$sql2 = "SELECT item_num, name, weight, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "products` WHERE id='" . $productID . "' AND UCASE(name)!='NONE'";
			$result2 = mysql_query($sql2);
			
			if ($result2 && mysql_num_rows($result2) > 0) {
				while ($row2 = mysql_fetch_array($result2)) {	
					$totalPrice += $row2['price'] + $row2['shipping_costs'] + $row2['profit'];
					$message .= "\n<strong>" . $row['name'] . ": </strong>" . $row2['name'] . "<br />\n";
				}
			}		
			mysql_free_result($result2);
		}
		mysql_free_result($result);
	}	
	
	$message .= "\n<br /><strong>Price: </strong>" . formatCurrency($totalPrice) . "<br /><br />Shop with us at <a href=\"" . returnHttpLinks($ss_config['ftsss_store_url']) . "\">" . $ss_config['ftsss_store_name'] . "</a>";
		

	// Additional headers
	//$headers .= "To: " . $emailAddress . "\n";
	$headers .= "From: " . $ss_config['ftsss_sales_email'] . "\n";
	
	// To send HTML mail, the Content-type header must be set
	$headers .= "MIME-Version: 1.0" . "\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1";
	
	// Mail it
	$emailResult = mail($emailAddress, $subject, returnEmailHeader() . $message, $headers);
	
	if ($emailResult) {
		echo "Your configuration has been sent to " . $emailAddress . ".";
	}
	else {
		echo "There was an error while attempting to send your configuration to " . $emailAddress . ".";
	}
}

//=========================================================
// Returns the edit order table
//=========================================================
function returnEditOrderTable($orderID) {
	global $ss_config, $_SESSION, $menuvar;
	
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			if (isset($_POST['submit'])) {
				$productList = $_POST['productsFeatures'];
				//print_r($_POST);
				$userID = "";
				
				foreach ($productList as $productID => $productFeatureIDArray) {					
					// Get order owner's userid
					$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $orderID . "'";
					$result = mysql_query($sql);
					
					if ($result && mysql_num_rows($result) != 0) {
						while ($row = mysql_fetch_array($result)) {
							$userID = $row['user_id'];
						}
						mysql_free_result($result);
					}
					
					// Delete current featre for this product
					$sql = "DELETE FROM `" . DBTABLEPREFIX . "orders_products_features` WHERE product_id = '" . $productID . "'";
					$result = mysql_query($sql);
					//echo $sql . "<br />";
						
					// Insert product features
					foreach ($productFeatureIDArray as $productFeatureID => $featureID) {	
						$sql = "SELECT pf.name, pfv.price, pfv.name FROM `" . DBTABLEPREFIX . "products_features_values` pfv LEFT JOIN `" . DBTABLEPREFIX . "products_features` pf ON pf.id=pfv.feature_id WHERE pfv.id='" . $featureID . "'";
						$result = mysql_query($sql);
						//echo $sql . "<br />";
	
						if ($result && mysql_num_rows($result) > 0) {	
							while ($row = mysql_fetch_array($result)) {
								$sql2 = "INSERT INTO `" . DBTABLEPREFIX . "orders_products_features` (`product_id`, `feature_id`, `feature_name`, `feature_value_id`, `feature_value_price`, `feature_value_name`) VALUES ('" . $productID . "', '" . $productFeatureID . "', '" . $row['name'] . "', '" . $featureID . "', '" . $row['price'] . "', '" . $row['name'] . "')";
								$result2 = mysql_query($sql2);
								//echo $sql2 . "<br />";
							}
							mysql_free_result($result);
						}
					}
				}
	
				// Update the order
				updateOrder($userID, $orderID, 0);
						
				//confirm
 				$page_content .= "Your order has been updated, and you are being redirected to the main page.
 									<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['ORDERS'] . "&action=editorderparts&id=" . $orderID . "\">";
						
				unset($_POST['submit']);
			}
			else {		
				$page_content = "
							<form name=\"editOrderForm\" id=\"editOrderForm\" action=\"" . $menuvar['ORDERS'] . "&action=editorderparts&id=" . $orderID . "\" method=\"post\">
							<strong>Order # " . $orderID . "</strong>
							<br /><br />";	
							
					// Print out the systems info	
					$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders_products` WHERE order_id = '" . $orderID . "' ORDER BY name";
					$result2 = mysql_query($sql2);
							
					$x = 1; //reset the variable we use for our row colors	

					if (!$result2 || mysql_num_rows($result2) == 0) {
						$page_content .= "\n						Unable to pull products.";
					}
					else {	 // Print all our orders								
						while ($row2 = mysql_fetch_array($result2)) {	
							$page_content .= "
							<div id=\"" . $row2['id'] . "_holder\">	
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr id=\"" . $row2['id'] . "_row\" class=\"title1\">
									<td colspan=\"2\"> 
										<div class=\"floatRight\"> 
											<a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row2['id'] . "ProductSpinner', 'ajax.php?action=deleteitem&table=orders_products&id=" . $row2['id'] . "', 'product', '" . $row2['id'] . "_holder');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Product\" /></a><span id=\"" . $row2['id'] . "ProductSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
										</div>
										Product: " . $row2['name'] . " (" . $row2['id'] . ")
									</td>
								</tr>
								<tr class=\"title2\">
									<th>Feature Name</th>
									<th>Selected Feature</th>
								</tr>";
										
							// Spit out the parts for the system			
							$sql3 = "SELECT * FROM `" . DBTABLEPREFIX . "orders_products_features` WHERE product_id = '" . $row2['id'] . "' ORDER BY feature_name";
							$result3 = mysql_query($sql3);
							
							if (!$result3 || mysql_num_rows($result3) == "0") {
								$page_content .= "\n					<tr class=\"greenRow\">
																			<td colspan=\"2\">This Product has no Features.</td>
																		</tr>";	
							}
							else {					
								while ($row3 = mysql_fetch_array($result3)) {	
									$page_content .= "											
										<tr class=\"row" . $x . "\">
											<td style=\"width: 30%\">
												" . $row3['feature_name'] . "
											</td>
											<td>
												<select name=\"productsFeatures[" . $row2['id'] . "][" . $row3['feature_id'] . "]\">";
									
					
									// Pull our other parts in this category for this model			
									$sql4 = "SELECT * FROM `" . DBTABLEPREFIX . "products_features_values` WHERE feature_id = '" . $row3['feature_id'] . "' ORDER BY name";
									$result4 = mysql_query($sql4);
									
									if (mysql_num_rows($result4) > 0) { 						
										while ($row4 = mysql_fetch_array($result4)) {
											$page_content .= "							
												<option value=\"" . $row4['id'] . "\"" . testSelected($row3['feature_value_id'], $row4['id']) . ">" . $row4['name'] . "</option>";
										}		
										mysql_free_result($result4);
									}
									
									$page_content .= "
												</select>
											</td>
										</tr>";
									$x = ($x==2) ? 1 : 2;
								}		
								mysql_free_result($result3);
							}		
							$page_content .= "					
										</table>
									</div>
									<br /><br />";
												
						}		
						mysql_free_result($result2);
					}
					
				
			$page_content .= "					
									<div class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Update\" /></div>
								</form>";
		}
	}
	// Return the invoice to the page that called it
	return $page_content;
}

?>