<?php 
/***************************************************************************
 *                               graphs.php
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
// Gets the number of systems sold based on a date range and id
//=========================================================
function getNumOfProductsSold($startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetimestamp >= '" . $startDatetimestamp . "' AND o.datetimestamp < '" . $stopDatetimestamp . "'";
	$sql = "SELECT COUNT(op.id) AS numSold FROM `" . DBTABLEPREFIX . "orders_products` op LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = op.order_id WHERE o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
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
function getTotalDollarAmountOfOrders($startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetimestamp >= '" . $startDatetimestamp . "' AND o.datetimestamp < '" . $stopDatetimestamp . "'";
	$sql = "SELECT SUM(o.price) AS totalSold FROM `" . DBTABLEPREFIX . "orders_products` op LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = op.order_id WHERE o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
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
function getProfitVsLossOfOrders($startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetimestamp >= '" . $startDatetimestamp . "' AND o.datetimestamp < '" . $stopDatetimestamp . "'";
	$sql = "SELECT SUM(o.items_total) AS totalSold, SUM(po.purchaseprice) AS totalCost FROM `" . DBTABLEPREFIX . "orders_products` op LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = op.order_id LEFT JOIN `" . DBTABLEPREFIX . "purchaseorders` po ON po.purchaseorders_order_id = o.id WHERE o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
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
function getShippingCostForCustomerOfOrders($startDatetimestamp, $stopDatetimestamp) {
	$extraSQL = "";
	$extraSQL = ($startDate == "" || $stopDate == "") ? "" : " AND o.datetimestamp >= '" . $startDatetimestamp . "' AND o.datetimestamp < '" . $stopDatetimestamp . "'";
	$sql = "SELECT SUM(o.shipping_price) AS totalCost FROM `" . DBTABLEPREFIX . "orders_products` op LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON o.id = op.order_id LEFT JOIN `" . DBTABLEPREFIX . "purchaseorders` po ON po.purchaseorders_order_id = o.id WHERE o.status = '" . STATUS_ORDER_SHIPPED . "'" . $extraSQL;
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

?>