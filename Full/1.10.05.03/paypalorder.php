<?
/***************************************************************************
 *                               paypalorder.php
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
	include 'includes/header.php';
	$page->setTemplateVar("PageTitle", 'Order Submited');

	// We want to show all of our menus by default
	$page->setTemplateVar("uOLm_active", ACTIVE);
	$page->setTemplateVar("aOLm_active", ACTIVE);

	// Get the current theme
	$themeDir = $ss_config['ftsss_theme'];

	// Get our order status to see if IPN has succeeded
	$processingResultText = (returnOrderStatusByID($_SESSION['orderid']) == STATUS_PAYMENT_PROCESSED) ? "<span style=\"color: green;\">Your account has been successfully charged.</span>" : "<span style=\"color: red;\">Your account does not have sufficient or an error has occurred. Please contact a representative to verify.</span>";

	// Print our payment confirmation, status, and invoice
	$page_content .= "
							Order <strong>#" . $_SESSION['orderid'] . "</strong> has been entered into our system. " . $processingResultText . "
							<br /><br />
							To view a printer friendly version of this invoice, <a href=\"" . $menuvar['VIEWINVOICE'] . "&id=" . $_SESSION['orderid'] . "&style=printerFriendly\" target=\"_blank\">please click here.</a><br />";
		
	$page_content .= returnInvoice($_SESSION['orderid'], $_SESSION['userid']);

	// Print to page					
	$page->setTemplateVar("PageContent", $page_content);
	version_functions("no");
	if (isset($actual_style) && $actual_style == "printerFriendly") { include "themes/" . $themeDir . "/printerFriendlyTemplate.php"; }
	else { include "themes/" . $themeDir . "/template.php"; }
?>