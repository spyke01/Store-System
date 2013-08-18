<?
/***************************************************************************
 *                               checkpayment.php
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

	//Notify the user of the new order	
	$sql = "SELECT email_address, first_name, last_name FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $_SESSION['userid'] . "' LIMIT 1";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			$emailMessageText = "To our valued customer " . $row['first_name'] . " " . $row['last_name'] . ",<br />
						Thank you for placing an order with " . $ss_config['ftsss_store_name'] . ". Your order will be processed as soon as your payment is recieved. You can always check on your order status by logging into your userpanel at: <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a>.
						<br /><br />
						Sincerely,
						<br />
						The " . $ss_config['ftsss_store_name'] . " Team<br /><br />" . returnInvoice($_SESSION['orderid'], $_SESSION['userid']);
			$emailSubject = $ss_config['ftsss_store_name'] . " Order #" . $_SESSION['orderid'];
			
			$success = emailMessage($row['email_address'], $emailSubject, $emailMessageText);
			//echo "Submitted User Email - " . $emailSubject . " - " . $emailMessage . "<br />";
		}
		mysql_free_result($result);
	}
	
	//Notify the company of the new order
	$emailSubject = "New Order #" . $_SESSION['orderid'];
	$emailMessageText = "<strong>Order #:</strong> " . $_SESSION['orderid'] . "<br /><br />
					A new order has been submitted, you can view it on the orders page by signing into <a href=\"" . $ss_config['ftsss_store_url'] . "\">" . $ss_config['ftsss_store_name'] . "</a>";
	$success = emailMessage($ss_config['ftsss_sales_email'], $emailSubject, $emailMessageText);
	//echo "Submitted Admin Email - " . $emailSubject . " - " . $emailMessage . "<br />";
		
	// Update Order Status and Info					
	changeOrderStatus($_SESSION['orderid'], STATUS_CHECK_PAYMENT);	
	
	$page_content .= "
						Order <strong>#" . $_SESSION['orderid'] . "</strong> has been entered into our system and your payment is awaiting arrival to be verified and processed in Accounting.<br /><br />
						For payment by check or money order please mail the respetive document to the following address:
						<br /><br />
						<strong>" . $ss_config['ftsss_store_name'] . "<br />
						" . nl2br($ss_config['ftsss_address']) . "</strong>
						<br /><br />
						The faster we recieve your payment, the faster we can process it. Our representatives are standing by to finish your order. Thank you for your order, and we look foreward to doing business with you again in the near future.
						<br /><br />
						To view a printer friendly version of this invoice, <a href=\"" . $menuvar['VIEWINVOICE'] . "&id=" . $_SESSION['orderid'] . "&style=printerFriendly\" target=\"_blank\">please click here.</a><br />";

	$page_content .= returnInvoice($_SESSION['orderid'], $_SESSION['userid']);	
	
	// Kill the orderid
	$_SESSION['orderid'] = "";
	
	// Print to page					
	$page->setTemplateVar("PageContent", $page_content);
?>