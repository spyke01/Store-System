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
// Gets the number of systems sold based on a date range and id
//=========================================================
function getNumOfSystemsFromModelID($modelID, $startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetime >= '" . $startDatetimestamp . "' AND o.datetime < '" . $stopDatetimestamp . "'";
	$sql = "SELECT COUNT(s.id) AS numSold FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = s.order_id WHERE s.model_id='" . $modelID . "' AND o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
	$result = mysql_query($sql);
	//echo $sql . "<br />";
					
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['numSold'];
		}
	
		mysql_free_result($result);
	}
	else {
		return "0";
	}
}

//=========================================================
// Gets the total dollar amount for orders containing 
// systesm sold based on a date range and id
//=========================================================
function getTotalDollarAmountOfOrderFromModelID($modelID, $startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetime >= '" . $startDatetimestamp . "' AND o.datetime < '" . $stopDatetimestamp . "'";
	$sql = "SELECT SUM(o.price) AS totalSold FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = s.order_id WHERE s.model_id='" . $modelID . "' AND o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
	$result = mysql_query($sql);
	//echo $sql . "<br />";
					
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['totalSold'];
		}
	
		mysql_free_result($result);
	}
	else {
		return "0";
	}
}

//=========================================================
// Gets the profit vs loss for orders containing 
// systesm sold based on a date range and id
//=========================================================
function getProfitVsLossOfOrderFromModelID($modelID, $startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetime >= '" . $startDatetimestamp . "' AND o.datetime < '" . $stopDatetimestamp . "'";
	$sql = "SELECT SUM(o.items_total) AS totalSold, SUM(po.purchaseprice) AS totalCost FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = s.order_id LEFT JOIN `" . DBTABLEPREFIX . "purchaseorders` po ON po.purchaseorder_id = o.id WHERE s.model_id='" . $modelID . "' AND o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
	$result = mysql_query($sql);
	//echo $sql . "<br />";
					
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return ($row['totalSold'] - $row['totalCost']);
		}
	
		mysql_free_result($result);
	}
	else {
		return "0";
	}
}

//=========================================================
// Gets the shipping cost a customer is paying us for 
// orders containing systesm sold based on a date range and id
//=========================================================
function getShippingCostForCustomerOfOrderFromModelID($modelID, $startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetime >= '" . $startDatetimestamp . "' AND o.datetime < '" . $stopDatetimestamp . "'";
	$sql = "SELECT SUM(o.shipping_price) AS totalCost FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = s.order_id LEFT JOIN `" . DBTABLEPREFIX . "purchaseorders` po ON po.purchaseorder_id = o.id WHERE s.model_id='" . $modelID . "' AND o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
	$result = mysql_query($sql);
	//echo $sql . "<br />";
					
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['totalCost'];
		}
	
		mysql_free_result($result);
	}
	else {
		return "0";
	}
}

//=========================================================
// Gets an address from an orderid
//=========================================================
function getAddress($type, $orderID) {
	global $FTS_COUNTRIES;
	
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='$orderID' AND type='$type'";
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
	
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "creditcards` WHERE order_id='" . $orderID . "' LIMIT 1";
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
	
	$sql = "SELECT card_type FROM `" . DBTABLEPREFIX . "creditcards` WHERE order_id='" . $orderID . "' LIMIT 1";
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
	$sql = "SELECT COUNT(discount) AS totalDiscountedSystems FROM `" . DBTABLEPREFIX . "systems` WHERE order_id = '" . $orderID . "' AND discount > 0";
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
function updateOrder($userID, $orderID, $isNewOrder = 0) {$totalWeight = 0;
	$totalPrice = 0;
	$orderRushFee = 0;
	$shippingCostInDB = 0;
	
	//Get a new total of weights and prices, this way the data is fresh and takes into account our order having other systems
	$sql = "SELECT SUM(weight * qty) AS total_weight, (SUM(price * qty) - discount) AS total_price FROM `" . DBTABLEPREFIX . "systems` WHERE order_id = '" . $orderID . "'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$totalWeight = $row['total_weight'];
		$itemsTotal = $row['total_price'];
	}
	mysql_free_result($result);
	
	// Apply discount and coupon to total price
	$sql = "SELECT discount, coupon_discount, coupon_discount_percentage, rush_fee, shipping_price FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $orderID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$orderDiscount = $row['discount'];
		$orderCouponDiscount = $row['coupon_discount'];
		$orderCouponDiscountPercentage = $row['coupon_discount_percentage'];
		$orderRushFee = $row['rush_fee'];
		$shippingCostInDB = $row['shipping_price'];
		$totalPrice = $itemsTotal - $orderDiscount - $orderCouponDiscount;
		$totalPrice = $totalPrice - ($totalPrice * ($orderCouponDiscountPercentage / 100));
	}
	mysql_free_result($result);
	
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
function createOrder($partsArray, $modelID, $userID, $orderID = "") {$totalWeight = 0;
	$totalPrice = 0;
	$isNewOrder = 0;
	
	// Get model items
	$sql = "SELECT base_weight, base_price, base_profit, discount, discount_percentage FROM `" . DBTABLEPREFIX . "models` WHERE id = '" . $modelID . "'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$totalWeight = $row['base_weight'];
		$totalPrice = $row['base_price'] + $row['base_profit'];
		$totalDiscount = $row['discount'];
		$totalDiscountPercentage = $row['discount_percentage'];
		$totalDiscount = str_replace("-", "", $totalDiscount); // Remove negative sign
	}
	mysql_free_result($result);
	
	if ($orderID == "") {
		// Insert a blank order
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "orders` (`user_id`, `datetime`) VALUES ('" . $userID . "', '" . time() . "')";
		$result = mysql_query($sql);
	
		$orderID = mysql_insert_id();
		$isNewOrder = 1; // Calculate shipping cost since this is a new order
	}
	
	// Insert blank system
	$sql = "INSERT INTO `" . DBTABLEPREFIX . "systems` (`order_id`, `model_id`, `qty`, `weight`, `price`) VALUES ('" . $orderID . "', '" . $modelID . "', '1', '', '')";
	$result = mysql_query($sql);
	
	$id = mysql_insert_id();
	
	// Get parts items
	foreach ($partsArray as $partcatID => $partID) {
		$sql = "SELECT id, item_num, name, weight, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "parts` WHERE id = '" . $partID . "'";
		$result = mysql_query($sql);
	
		while ($row = mysql_fetch_array($result)) {
			$totalWeight += $row['weight'];
			$totalPrice += $row['price'] + $row['shipping_costs'] + $row['profit'];
			
			$sql2 = "INSERT INTO `" . DBTABLEPREFIX . "systemparts` (`system_id`, `part_id`, `partcat_id`, `part_name`, `weight`, `price`, `shipping_costs`, `profit`) VALUES ('" . $id . "', '" . $row['id'] . "', '" . $partcatID . "', '" . $row['name'] . "', '" . $row['weight'] . "', '" . $row['price'] . "', '" . $row['shipping_costs'] . "', '" . $row['profit'] . "')";
			$result2 = mysql_query($sql2);
		}
		mysql_free_result($result);
	}
	
	// Apply our discount percentage
	$totalDiscount = ($totalDiscountPercentage == 0 || $totalDiscountPercentage == "") ?  $totalDiscount : $totalDiscount + ($totalPrice * ($totalDiscountPercentage / 100));
	
	// Update this system's price
	$sql = "UPDATE `" . DBTABLEPREFIX . "systems` SET weight='" . $totalWeight . "', price='" . $totalPrice . "', discount='" . $totalDiscount . "' WHERE id='" . $id . "'";
	$result = mysql_query($sql);
	
	updateOrder($userID, $orderID, $isNewOrder);
	
	return $orderID;
}

//=========================================================
// Update a system using arrays and IDs supplied to the function
//=========================================================
function updateSystem($partsArray, $id, $userID, $orderID) {$totalWeight = 0;
	$totalPrice = 0;
	$authorized = 0;
	
	// Get modelid and verify that this system belongs to this user
	$sql = "SELECT model_id FROM `" . DBTABLEPREFIX . "systems` WHERE id = '" . $id . "' and order_id = '" . $orderID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$modelID = $row['model_id'];
			$authorized = 1;
		}
		mysql_free_result($result);
	}
	
	if ($authorized == 1) {
		// Get model items
		$sql = "SELECT base_weight, base_price, base_profit, discount, discount_percentage FROM `" . DBTABLEPREFIX . "models` WHERE id = '" . $modelID . "'";
		$result = mysql_query($sql);
		
		while ($row = mysql_fetch_array($result)) {
			$totalWeight = $row['base_weight'];
			$totalPrice = $row['base_price'] + $row['base_profit'];
			$totalDiscount = ($row['discount_percentage'] == 0 || $row['discount_percentage'] == "") ?  $row['discount'] : $totalPrice - $row['discount'] - (($totalPrice - $row['discount']) * ($row['discount_percentage'] / 100));
		}
		mysql_free_result($result);
		
		// Kill system parts for this system
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "systemparts` WHERE `system_id` = '" . $id . "'";
		$result = mysql_query($sql);
		
		// Get parts items
		foreach ($partsArray as $partcatID => $partID) {
			$sql = "SELECT id, item_num, name, weight, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "parts` WHERE id = '" . $partID . "'";
			$result = mysql_query($sql);
		
			while ($row = mysql_fetch_array($result)) {
				$totalWeight += $row['weight'];
				$totalPrice += $row['price'] + $row['shipping_costs'] + $row['profit'];
				
				$sql2 = "INSERT INTO `" . DBTABLEPREFIX . "systemparts` (`system_id`, `part_id`, `partcat_id`, `part_name`, `weight`, `price`, `shipping_costs`, `profit`) VALUES ('" . $id . "', '" . $row['id'] . "', '" . $partcatID . "', '" . $row['name'] . "', '" . $row['weight'] . "', '" . $row['price'] . "', '" . $row['shipping_costs'] . "', '" . $row['profit'] . "')";
				$result2 = mysql_query($sql2);
			}
			mysql_free_result($result);
		}
			
		// Update this system's price
		$sql = "UPDATE `" . DBTABLEPREFIX . "systems` SET weight='" . $totalWeight . "', price='" . $totalPrice . "', discount='" . $totalDiscount . "' WHERE id='" . $id . "'";
		$result = mysql_query($sql);
		
		// Update order totals
		updateOrder($userID, $orderID, 0);
	}
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
function emailOrder($partsArray, $modelID, $emailAddress) {
	global $ss_config;
	$totalPrice = 0;
	$defaultPartCatIDs = array();	

	// subject
	$subject = "Custom System Datasheet";
		
	// message
	//$message = "Custom System Datasheet: <br />\n<br /><br />\n\n";
	
	// Get model items
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` WHERE id = '" . $modelID . "'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$totalPrice = $row['base_price'] + $row['base_profit'] - $row['discount'];
		$message .= "<strong>" . $row['name'] . "</strong>\n<br />\n<br /><img src=\"" . returnHttpLinks($ss_config['ftsss_store_url']) . "/" . $row['image_thumb'] . "\" alt=\"" . $row['name'] . "\" />\n<br />\n<br />" . $row['description'] . "<br /><br />\n\n";
	}
	mysql_free_result($result);
	
	// Handle putting our partcats into the proper order since we cannot use SQL down the road to order things.
	$extraSQL = "";
		
	foreach($partsArray as $partcatID => $selectedPartID) {
		$extraSQL .= ($extraSQL == "") ? " WHERE" : " OR";
		$extraSQL .= " id = '" . $partcatID . "'";
	}
	
	$sql = "SELECT id FROM `" . DBTABLEPREFIX . "partcats`" . $extraSQL . " ORDER BY sort_order ASC";
	$result = mysql_query($sql);	
		
	if ($result && mysql_num_rows($result) > 0) {				
		while ($row = mysql_fetch_array($result)) {
			// Push the values onto our temp arrays
			array_push($defaultPartCatIDs, $row['id']);
		}
		mysql_free_result($result);
	}
	
	// Get parts items
	foreach ($defaultPartCatIDs as $key => $partcatID) {
		$partID = $partsArray[$partcatID];
		
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "partcats` WHERE id ='" . $partcatID . "'";
		$result = mysql_query($sql);
			
		while ($row = mysql_fetch_array($result)) {			
			$sql2 = "SELECT item_num, name, weight, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "parts` WHERE id='" . $partID . "' AND UCASE(name)!='NONE'";
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
				$partsList = $_POST['part_id'];
				//print_r($_POST);
				$userID = "";
				$modelID = "";
				
				foreach ($partsList as $id => $systemPartsIDList) {	
					$totalWeight = 0;
					$totalPrice = 0;
					$isNewOrder = 0;
					
					// Get system model and userid of owner
					$sql = "SELECT user_id, model_id FROM `" . DBTABLEPREFIX . "orders` o, `" . DBTABLEPREFIX . "systems` s WHERE o.id = '" . $orderID . "' AND o.id = s.order_id";
					$result = mysql_query($sql);
	
					while ($row = mysql_fetch_array($result)) {
						$userID = $row['user_id'];
						$modelID = $row['model_id'];
					}
					mysql_free_result($result);
					
					// Get model items
					$sql = "SELECT base_weight, base_price, base_profit, discount, discount_percentage FROM `" . DBTABLEPREFIX . "models` WHERE id = '" . $modelID . "'";
					$result = mysql_query($sql);
	
					while ($row = mysql_fetch_array($result)) {
						$totalWeight = $row['base_weight'];
						$totalPrice = $row['base_price'] + $row['base_profit'];
						$totalDiscount = $row['discount'];
						$totalDiscountPercentage = $row['discount_percentage'];
						$totalDiscount = str_replace("-", "", $totalDiscount); // Remove negative sign
					}
					mysql_free_result($result);
				
					foreach ($systemPartsIDList as $systemPartsID => $partID) {							
						// Get parts items
						$sql = "SELECT id, item_num, name, weight, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "parts` WHERE id = '" . $partID . "'";
						$result = mysql_query($sql);
	
						while ($row = mysql_fetch_array($result)) {
							$totalWeight += $row['weight'];
							$totalPrice += $row['price'] + $row['shipping_costs'] + $row['profit'];
			
							// Update the system parts table
							$sql2 = "UPDATE `" . DBTABLEPREFIX . "systemparts` SET part_id = '" . $partID . "', part_name = '" . $row['name'] . "', weight = '" . $row['weight'] . "', price = '" . $row['price'] . "', shipping_costs = '" . $row['shipping_costs'] . "', profit = '" . $row['profit'] . "' WHERE id = '" . $systemPartsID . "'";
							$result2 = mysql_query($sql2);
						}
						mysql_free_result($result);
					}
	
					// Apply our discount percentage
					$totalDiscount = ($totalDiscountPercentage == 0 || $totalDiscountPercentage == "") ?  $totalDiscount : $totalDiscount + ($totalPrice * ($totalDiscountPercentage / 100));
						
					// Update this system's price
					$sql = "UPDATE `" . DBTABLEPREFIX . "systems` SET weight='" . $totalWeight . "', price='" . $totalPrice . "', discount='" . $totalDiscount . "' WHERE id='" . $id . "'";
					$result = mysql_query($sql);
				}
	
				// Update the order
				updateOrder($userID, $orderID, 0);
						
				//confirm
 				$page_content .= "Your order has been updated, and you are being redirected to the main page.
 									<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['ORDERS'] . "\">";
						
				unset($_POST['submit']);
			}
			else {		
				$page_content = "
							<form name=\"editOrderForm\" id=\"editOrderForm\" action=\"" . $menuvar['ORDERS'] . "&action=editorderparts&id=" . $orderID . "\" method=\"post\">
							<strong>Order # " . $orderID . "</strong>
							<br /><br />";	
							
					// Print out the systems info	
					$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "models` m ON s.model_id=m.id WHERE s.order_id = '$orderID' ORDER BY m.name";
					$result2 = mysql_query($sql2);
							
					$x = 1; //reset the variable we use for our row colors	

					if (mysql_num_rows($result2) == "0") {
						$page_content .= "\n						Unable to pull system info.";
					}
					else {	 // Print all our orders								
						while ($row2 = mysql_fetch_array($result2)) {	
							$modelDiscount = ($showDiscountColumn) ? "<td>" . formatCurrency($row2['discount']) . "</td>" : "";
							
							$page_content .= "
							<div id=\"" . $row2['id'] . "_holder\">	
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr id=\"" . $row2['id'] . "_row\" class=\"title2\">
									<td colspan=\"2\"> 
										<div class=\"floatRight\"> 
											<a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row2['id'] . "SystemSpinner', 'ajax.php?action=deletesystem&id=" . $row2['id'] . "', 'system', '" . $row2['id'] . "_holder');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete System\" /></a><span id=\"" . $row2['id'] . "SystemSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
										</div>
										System: " . $row2['id'] . " (" . $row2['name'] . ")
									</td>
								</tr>";
										
							// Spit out the parts for the system			
							$sql3 = "SELECT * FROM `" . DBTABLEPREFIX . "systemparts` s LEFT JOIN `" . DBTABLEPREFIX . "partcats` p ON p.id=s.partcat_id WHERE s.system_id = '" . $row2['id'] . "' ORDER BY p.sort_order ASC";
							$result3 = mysql_query($sql3);
							
							if (mysql_num_rows($result3) == "0") {
								$page_content .= "\n					<tr class=\"greenRow\">";
								$page_content .= "\n						<td colspan=\"2\">Unable to pull systemparts info.</td>";
								$page_content .= "\n					</tr>";	
							}
							else {					
								while ($row3 = mysql_fetch_array($result3)) {	
									$page_content .= "											
										<tr class=\"row" . $x . "\">
											<td>
												<strong>" . getPartcatList("x" . $row3['partcat_id'] . "x") . "</strong>
											</td>
											<td>
												<select name=\"part_id[" . $row2['id'] . "][" . $row3['id'] . "]\">";
									
					
									// Pull our other parts in this category for this model			
									$sql4 = "SELECT id, name, active FROM `" . DBTABLEPREFIX . "parts`  WHERE type LIKE '%x" . $row3['partcat_id'] . "x%' AND (models LIKE '%x" . $row2['model_id'] . "x%' OR models='') ORDER BY active DESC, sort ASC";
									$result4 = mysql_query($sql4);
									
									if (mysql_num_rows($result4) > 0) { 						
										while ($row4 = mysql_fetch_array($result4)) {
											$activeOrInactive = ($row4['active'] == "1") ? "" : " (Inactive)";
											//$activeOrInactive .= " " . $row3['part_id'] . " " . $row4['id'];
											
											$page_content .= "							
												<option value=\"" . $row4['id'] . "\"" . testSelected($row3['part_id'], $row4['id']) . ">" . $row4['name'] . $activeOrInactive . "</option>";
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