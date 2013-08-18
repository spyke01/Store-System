<?php 
/***************************************************************************
 *                               users.php
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
// Gets a username from a userid
//=========================================================
function getUsernameFromID($userID) {
	$sql = "SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['username'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets a username from a userid
//=========================================================
function getUsersNameByOrderID($orderID) {
	$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $orderID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$sql2 = "SELECT first_name, last_name FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $row['user_id'] . "'";
			$result2 = mysql_query($sql2);
	
			if ($result2 && mysql_num_rows($result2) > 0) {
				while ($row2 = mysql_fetch_array($result2)) {
					if ($row2['last_name'] == "" && $row2['first_name'] == "") {
						// Pull from billing info
						$sql3 = "SELECT first_name, last_name FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='" . $orderID . "' AND type = '0'";
						$result3 = mysql_query($sql3);
	
						if (mysql_num_rows($result3) > 0) {
							while ($row3 = mysql_fetch_array($result3)) {
								return $row3['last_name'] . ", " . $row3['first_name'];
							}
							mysql_free_result($result3);
						}						
					}
					else {
						return $row2['last_name'] . ", " . $row2['first_name'];
					}					
				}
				mysql_free_result($result2);
			}
		}
		mysql_free_result($result);
	}
}

//=========================================================
// Gets a userid from a userid
//=========================================================
function getUserIDByOrderID($orderID) {
	$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $orderID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['user_id'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets a user's userlevel from a userid
//=========================================================
function getUserlevelFromID($userID) {
	$level = "";
	
	$sql = "SELECT user_level FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$level = ($row['user_level'] == ADMIN) ? "Administrator" : "Moderator";
			$level = ($row['user_level'] == USER) ? "User" : $level;
			$level = ($row['user_level'] == BANNED) ? "Banned" : $level;
		}	
		mysql_free_result($result);
	}
	
	return $level;
}

//=========================================================
// Gets an email address from a userid
//=========================================================
function getEmailAddressFromID($userID) {
	$sql = "SELECT email_address FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['email_address'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets an email address from a orderid
//=========================================================
function getEmailAddressFromOrderID($orderID) {
	$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $orderID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return getEmailAddressFromID($row['user_id']);
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Returns basic info on a tech(username, name, phone # & ext)
//=========================================================
function getTechUserInfoFromID($userID) {
	$block = "";
	
	$sql = "SELECT u.email_address, u.first_name, u.last_name, ua.day_phone, ua.day_phone_ext FROM `" . USERSDBTABLEPREFIX . "users` u, `" . DBTABLEPREFIX . "useraddresses` ua WHERE u.id='$userID' AND u.id = ua.user_id AND type = '0'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$block = "<strong>Sales Rep:</strong> " . " " . $row['first_name'] . " " . $row['last_name'] . " - ext. " . $row['day_phone_ext'] . "<br /><strong>Sales Email:</strong> " . $row['email_address'];
		}
		mysql_free_result($result);
	}
	
	return $block;
}

//=========================================================
// Gets an address from an userid
//=========================================================
function getUserAddress($type, $userID) {
	$sql = "SELECT u.first_name, u.last_name, ua.* FROM `" . DBTABLEPREFIX . "useraddresses` ua, `" . USERSDBTABLEPREFIX . "users` u WHERE ua.user_id='$userID' AND ua.user_id=u.id AND ua.type='$type'";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$addressLine2 = (trim($row['street_2']) != "") ? $row['street_2'] . "<br />" : "";
		$companyName = (trim($row['company']) != "") ? $row['company'] . "<br />" : "";
		$adressVar = $companyName . 
					$row['first_name'] . " " . $row['last_name'] . "<br />" .
					$row['street_1'] . "<br />" .
					$addressLine2 .
					$row['city'] . ", " . $row['state'] . " " . $row['country'] . " " . $row['zip'] . "<br /><br />" .
					//"<strong>Email Address: </strong>" . $row['email_address'] . "<br />" .
					"<strong>Primary Phone Number: </strong>" . $row['day_phone'] . "<br />" .
					"<strong>Secondary Phone Number: </strong>" . $row['night_phone'] . "<br />" .
					"<strong>Fax: </strong>" . $row['fax'] . "<br />";
		return $adressVar;
	}
	
	mysql_free_result($result);
}

//=========================================================
// Gets an address from the DB
//=========================================================
function getUserPanelUserAddress($userID, $type) {
	global $_SESSION, $FTS_COUNTRIES, $FTS_STATES;
	$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $userID : $_SESSION['userid'];
	
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $userID . "' AND type='" . keepsafe($type) . "' LIMIT 1";
	$result = mysql_query($sql);
	
	$row = mysql_fetch_array($result);
	
	$tableTitle = ($type == BILL_ADDRESS) ? "Billing Information" : "Shipping Information";
	$adressVar = "
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">
									<span class=\"floatRight\"><a href=\"ajax.php?action=showUserPanelUserAddressEdit&id=" . $userID . "\" rel=\"lyteframe\" title=\"Edit Bill/Ship Information\" rev=\"width: 700px; height: 500px; scrolling: yes;\">Edit Bill/Ship Information</a></span>
									" . $tableTitle . "
								</td>
							</tr>		
							<tr>
								<td class=\"title2\" style=\"width: 30%\"><strong>First Name</strong></td>
								<td class=\"row1\">" . $row['first_name'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\" style=\"width: 30%\"><strong>Last Name</strong></td>
								<td class=\"row1\">" . $row['last_name'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\" style=\"width: 30%\"><strong>Company Name</strong></td>
								<td class=\"row1\">" . $row['company'] . "</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>Address</strong></td>
								<td class=\"row1\">
									" . $row['street_1'] . "<br />
									" . $row['street_2'] . "
								</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>City</strong></td>
								<td class=\"row1\">" . $row['city'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Country</strong></td>
								<td class=\"row1\">" . $FTS_COUNTRIES[$row['country']] . "</td>
							</tr>	
							<tr id=\"billStateRow\"" . $billShowStates . ">
								<td class=\"title2\"><strong>State / Province</strong></td>
								<td class=\"row1\">" . $FTS_STATES[$row['state']] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Postal Code</strong></td>
								<td class=\"row1\">" . $row['zip'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Primary Phone Number</strong></td>
								<td class=\"row1\">" . $row['day_phone'] . " ext. " . $row['day_phone_ext'] . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Secondary Phone Number</strong></td>
								<td class=\"row1\">" . $row['night_phone'] . " ext. " . $row['night_phone_ext'] . "</td>
							</tr>
							<tr>
								<td class=\"title2\"><strong>Fax</strong></td>
								<td class=\"row1\">" . $row['fax'] . "</td>
							</tr>	
						</table>";
		return $adressVar;
	
	mysql_free_result($result);
}

//=========================================================
// Gets an user's CC info from the DB
//=========================================================
function getUserPanelUserCreditCard($userID) {
	global $_SESSION;
	$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $userID : $_SESSION['userid'];
	
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $userID . "' LIMIT 1";
	$result = mysql_query($sql);
	
	$row = mysql_fetch_array($result);
	
	$adressVar = "
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">
									<span class=\"floatRight\">
										<a href=\"ajax.php?action=showCreditCardEdit&id=" . $userID . "\" rel=\"lyteframe\" title=\"Edit Credit Card Information\" rev=\"width: 700px; height: 500px; scrolling: yes;\">Edit Credit Card Information</a> &nbsp;|&nbsp;
										<a href=\"\" title=\"Delete Credit Card Information\" onClick=\"deleteCreditCardInfo(); return false;\">Delete Credit Card Information</a>
									</span>
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
								<td class=\"row1\">" . maskCCNumber($row['card_number']) . "</td>
							</tr>	
							<tr>
								<td class=\"title2\"><strong>Security ID on the Back of the Card</strong></td>
								<td class=\"row1\">" . maskCCSID($row['card_sid']) . "</td>
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

?>