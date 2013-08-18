<?php 
/***************************************************************************
 *                               payments.php
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
// Retuns the form items needed to process a credit card
//=========================================================
function returnCreditCardProcessorFormItems($orderID) {
	global $FTS_COUNTRIES;
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "addresses` WHERE order_id='$orderID' AND type='0'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);	
		mysql_free_result($result);
	}
	
	$country = ($row['country'] == "USA") ? $row['country'] : $FTS_COUNTRIES[$row['country']];
	
	$returnVar = "<input type=\"hidden\" name=\"billTo_firstName\" value=\"" . $row['first_name'] . "\">
				<input type=\"hidden\" name=\"billTo_lastName\" value=\"" . $row['last_name'] . "\">
				<input type=\"hidden\" name=\"billTo_street1\" value=\"" . $row['street_1'] . "\">
				<input type=\"hidden\" name=\"billTo_street2\" value=\"" . $row['street_2'] . "\">
				<input type=\"hidden\" name=\"billTo_city\" value=\"" . $row['city'] . "\">
				<input type=\"hidden\" name=\"billTo_state\" value=\"" . $row['state'] . "\">
				<input type=\"hidden\" name=\"billTo_postalCode\" value=\"" . $row['zip'] . "\">
				<input type=\"hidden\" name=\"billTo_country\" value=\"" . $country . "\">
				<input type=\"hidden\" name=\"billTo_email\" value=\"" . getEmailAddressFromOrderID($orderID) . "\">";
				
	return $returnVar;
}

?>