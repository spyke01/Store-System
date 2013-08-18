<?
/***************************************************************************
 *                               confirmOrder.php
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
 	include("includes/HOP.php");
	
	// Get our payment method
	$orderPrice = "";
	$extraRows = "";
	$card_number = "";
	$card_sid = "";
	$name_on_card = "";
	$card_type = "";
	$card_number = "";
	$exp_month = "";
	$exp_year = "";
	$card_sid = "";
	$bank_name = "";
	$bank_number = "";
	$authorized = 0;
	
	// If our user isnt an admin or a mode then they arent allowed to access other user's credit cards
	$extraSQL = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? "" : " AND user_id='" . $_SESSION['userid'] . "'";

	// Get Order values
	$sql = "SELECT price, user_id FROM `" . DBTABLEPREFIX . "orders` WHERE id='" . $actual_id . "'" . $extraSQL . " LIMIT 1";
	$result = mysql_query($sql);					

	if (mysql_num_rows($result) > 0) {							
		while ($row = mysql_fetch_array($result)) {
			$authorized = 1;
			$orderPrice = $row['price'];
			$orderUser = $row['user_id'];
		}
		mysql_free_result($result);
	}
	
	if ($authorized == 1) {
		// Get card number in the database in case the user didnt change it
		//$orderUser = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $orderUser : $_SESSION['userid'];
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "creditcards` WHERE order_id='" . $actual_id . "';";
		$result = mysql_query($sql);
					
		if (mysql_num_rows($result) != 0) {
			while ($row = mysql_fetch_array($result)) {
				$card_number = $row['card_number'];
				$card_sid = $row['card_sid'];
				$name_on_card = $row['name_on_card'];
				$card_type = $row['card_type'];
				$card_number = $row['card_number'];
				$exp_month = $row['exp_month'];
				$exp_year = $row['exp_year'];
				$card_sid = $row['card_sid'];
				$bank_name = $row['bank_name'];
				$bank_number = $row['bank_number'];
			}
		}
		mysql_free_result($result);

		// Print our page
		$page_content .= "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Retry Credit Card</td>
								</tr>
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>Order Number</strong></td>
									<td class=\"row1\">" . $actual_id . "</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Order Total</strong></td>
									<td class=\"row1\">" . formatCurrency($orderPrice) . "</td>
								</tr>
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>		
								<tr>
									<td class=\"row1\" colspan=\"2\">
										<strong>The following card will be charged:</strong><br /><br />
										" . $name_on_card . "<br />" . 
										printCreditCardType($card_type) . "<br />" . 
										maskCCNumber($card_number) . "<br />" . 
										maskCCSID($card_sid) . "<br />" . 
										printCreditCardMonth($exp_month) . "/" . printCreditCardYear($exp_year) . "<br /><br />" . 
										$bank_name . "<br />" . 
										$bank_number . "<br /><br />
										<em>If you need to change the credit card on file for this order, please contact us.</em>
									</td>
								</tr>
								<tr><td class=\"title2\" colspan=\"2\">&nbsp;</td></tr>
							</table>
							<br /><br />
							<form action=\"https://orderpage.ic3.com/hop/ProcessOrder.do\" method=\"post\">
								" . returnSignature3($orderPrice, "usd", "sale") . "
								" . returnCreditCardProcessorFormItems($actual_id) . "
			 					<input type=\"hidden\" name=\"card_cardType\" value=\"" . $FTS_CARDS_PROCESSOR[$card_type] . "\" />
			 					<input type=\"hidden\" name=\"card_accountNumber\" value=\"" . $card_number . "\" />
			 					<input type=\"hidden\" name=\"card_cvNumber\" value=\"" . $card_sid . "\" />
			 					<input type=\"hidden\" name=\"card_expirationMonth\" value=\"" . paddString($exp_month, 2, "0", "L") . "\" />
			 					<input type=\"hidden\" name=\"card_expirationYear\" value=\"" . printCreditCardYear($exp_year) . "\" />
			 					<input type=\"hidden\" name=\"comments\" value=\"" . $ss_config['ftsss_store_name'] . " Order #" . $actual_id . "\" />
								<input type=\"submit\" name=\"submit\" class=\"confirmOrderButton\" value=\"Confirm Order\" />
							</form>
							<br /><br />" . returnInvoice($actual_id, $_SESSION['userid']);
	}
	else {
		$page_content .= "You are not authorized to access this page for this order.";
	}
	// Print to page					
	$page->setTemplateVar("PageContent", $page_content);
?>