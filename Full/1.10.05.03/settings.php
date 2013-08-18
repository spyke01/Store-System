<? 
/***************************************************************************
 *                               settings.php
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

if ($_SESSION['user_level'] == ADMIN) {	
	//==================================================
	// Update our DB
	//==================================================
	if (isset($_POST['submit'])) {
		foreach($_POST as $name => $value) {
			if ($name != "submit"){			
				if ($name == "ftsss_active" || $name == "ftsss_paypal_active" || $name == "ftsss_google_checkout_active" || $name == "ftsss_credit_card_payment_active" || $name == "ftsss_checkmowire_active") {
					$value = ($value == "") ? 0 : 1;
				}
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '" . $value . "' WHERE name = '" . $name . "'";
				$result = mysql_query($sql);
				//echo $sql . "<br />";
			}
		}
		
		// Handle checkboxes, unchecked boxes are not posted so we check for this and mark them in the DB as such
		if (!isset($_POST['ftsss_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_active'";
			$result = mysql_query($sql);
		}
		if (!isset($_POST['ftsss_use_https'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_use_https'";
			$result = mysql_query($sql);
		}
		if (!isset($_POST['ftsss_paypal_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_paypal_active'";
			$result = mysql_query($sql);
		}
		if (!isset($_POST['ftsss_google_checkout_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_google_checkout_active'";
			$result = mysql_query($sql);
		}
		if (!isset($_POST['ftsss_credit_card_payment_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_credit_card_payment_active'";
			$result = mysql_query($sql);
		}
		if (!isset($_POST['ftsss_checkmowire_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_checkmowire_active'";
			$result = mysql_query($sql);
		}
		if (!isset($_POST['ftsss_slider_active'])) {
			$sql = "UPDATE `" . DBTABLEPREFIX . "config` SET value = '0' WHERE name = 'ftsss_slider_active'";
			$result = mysql_query($sql);
		}
		
		unset($_POST['submit']);
	}
	
	//==================================================
	// Print out our settings table
	//==================================================
	$sql = "SELECT * FROM `" . DBTABLEPREFIX . "config`";
	$result = mysql_query($sql);
	
	// This is used to let us get the actual items and not just name and value
	while ($row = mysql_fetch_array($result)) {
		extract($row);
		$name = $row['name'];
		$value = $row['value'];
		$current_config[$name] = $value;
	}	
	extract($current_config);
		
	// Give our template the values
	$content = "<form action=\"" . $menuvar['SETTINGS'] . "\" method=\"post\" target=\"_top\">
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
						<tr><td class=\"title1\" colspan=\"2\">Basic Settings</td></tr>
						<tr class=\"row1\">
							<td><strong>Website Name: </strong></td>
							<td>
								<input name=\"ftsss_store_name\" type=\"text\" size=\"60\" value=\"" . $ftsss_store_name . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Logo URL: </strong></td>
							<td>
								<input name=\"ftsss_logo_url\" type=\"text\" size=\"60\" value=\"" . $ftsss_logo_url . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Admin Email: </strong></td>
							<td>
								<input name=\"ftsss_admin_email\" type=\"text\" size=\"60\" value=\"" . $ftsss_admin_email . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Sales Email: </strong></td>
							<td>
								<input name=\"ftsss_sales_email\" type=\"text\" size=\"60\" value=\"" . $ftsss_sales_email . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Use HTTPS: </strong></td>
							<td>
								<input name=\"ftsss_use_https\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_use_https, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Active: </strong></td>
							<td>
								<input name=\"ftsss_active\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_active, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Inactive Message:</strong></td>
							<td>
								<textarea name=\"ftsss_inactive_msg\" cols=\"45\" rows=\"10\">" . $ftsss_inactive_msg . "</textarea>
							</td>
						</tr>
						<tr><td class=\"title1\" colspan=\"2\">Company Information</td></tr>
						<tr class=\"row1\">
							<td><strong>Address:</strong></td>
							<td>
								<textarea name=\"ftsss_address\" cols=\"45\" rows=\"10\">" . $ftsss_address . "</textarea>
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Phone Number: </strong></td>
							<td>
								<input name=\"ftsss_phone_number\" type=\"text\" size=\"60\" value=\"" . $ftsss_phone_number . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Fax: </strong></td>
							<td>
								<input name=\"ftsss_fax\" type=\"text\" size=\"60\" value=\"" . $ftsss_fax . "\" />
							</td>
						</tr>
						<tr><td class=\"title1\" colspan=\"2\">Store Settings</td></tr>
						<tr class=\"row1\">
							<td><strong>Rush Fee: </strong></td>
							<td>
								<input name=\"ftsss_rush_fee\" type=\"text\" size=\"60\" value=\"" . $ftsss_rush_fee . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Terms and Conditions:</strong></td>
							<td>
								<textarea name=\"ftsss_terms\" cols=\"45\" rows=\"10\">" . $ftsss_terms . "</textarea>
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Wire Transfer Instructions:</strong></td>
							<td>
								<textarea name=\"ftsss_wire_transfer_instructions\" cols=\"45\" rows=\"10\">" . $ftsss_wire_transfer_instructions . "</textarea>
							</td>
						</tr>
						<tr><td class=\"title1\" colspan=\"2\">Tax and Shipping Options</td></tr>
						<tr class=\"row1\">
							<td><strong>State to Collect Taxes In: </strong></td>
							<td>
								" . createDropdown("states", "ftsss_tax_state", $ftsss_tax_state, "") . "
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Tax Rate: </strong></td>
							<td>
								<input name=\"ftsss_tax_rate\" type=\"text\" size=\"60\" value=\"" . $ftsss_tax_rate . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Zip Code(for calculating shipping costs): </strong></td>
							<td>
								<input name=\"ftsss_zip_code\" type=\"text\" size=\"60\" value=\"" . $ftsss_zip_code . "\" />
							</td>
						</tr>
						<tr><td class=\"title1\" colspan=\"2\">Payment Options</td></tr>
						<tr class=\"row1\">
							<td><strong>Paypal Active: </strong></td>
							<td>
								<input name=\"ftsss_paypal_active\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_paypal_active, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Google Checkout Active: </strong></td>
							<td>
								<input name=\"ftsss_google_checkout_active\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_google_checkout_active, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Credit Card Payment Active: </strong></td>
							<td>
								<input name=\"ftsss_credit_card_payment_active\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_credit_card_payment_active, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Check / Money Order / Wire Transfer Payment Active: </strong></td>
							<td>
								<input name=\"ftsss_checkmowire_active\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_checkmowire_active, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>PayPal Email: </strong></td>
							<td>
								<input name=\"ftsss_paypal_email\" type=\"text\" size=\"60\" value=\"" . $ftsss_paypal_email . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>PayPal Authorzation Token (for PayPal Callback): </strong></td>
							<td>
								<input name=\"ftsss_paypal_auth_token\" type=\"text\" size=\"60\" value=\"" . $ftsss_paypal_auth_token . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Google Checkout Merchant ID: </strong></td>
							<td>
								<input name=\"ftsss_google_checkout_id\" type=\"text\" size=\"60\" value=\"" . $ftsss_google_checkout_id . "\" />
							</td>
						</tr>
						<tr><td class=\"title1\" colspan=\"2\">Customize Page(s) Slider Settings</td></tr>
						<tr class=\"row1\">
							<td><strong>Slider Active: </strong></td>
							<td>
								<input name=\"ftsss_slider_active\" type=\"checkbox\" value=\"1\"". testChecked($ftsss_slider_active, ACTIVE) . " />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Side of Page: </strong></td>
							<td>
								" . createDropdown("sliderSide", "ftsss_slider_side", $ftsss_slider_side, "") . "
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Padding from Side: </strong></td>
							<td>
								<input name=\"ftsss_slider_padd_side\" type=\"text\" size=\"60\" value=\"" . $ftsss_slider_padd_side . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Padding from Top: </strong></td>
							<td>
								<input name=\"ftsss_slider_padd_top\" type=\"text\" size=\"60\" value=\"" . $ftsss_slider_padd_top . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Padding from Side (IE Specific): </strong></td>
							<td>
								<input name=\"ftsss_slider_padd_side_ie\" type=\"text\" size=\"60\" value=\"" . $ftsss_slider_padd_side_ie . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Padding from Top (IE Specific): </strong></td>
							<td>
								<input name=\"ftsss_slider_padd_top_ie\" type=\"text\" size=\"60\" value=\"" . $ftsss_slider_padd_top_ie . "\" />
							</td>
						</tr>
						<tr><td class=\"title1\" colspan=\"2\">Advanced Settings</td></tr>
						<tr class=\"row1\">
							<td><strong>Store URL: </strong></td>
							<td>
								<input name=\"ftsss_store_url\" type=\"text\" size=\"60\" value=\"" . $ftsss_store_url . "\" />
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>Cookie Name: </strong></td>
							<td>
								<input name=\"ftsss_cookie_name\" type=\"text\" size=\"60\" value=\"" . $ftsss_cookie_name . "\" />
							</td>
						</tr>
						<tr class=\"row1\">
							<td><strong>Lightbox Style Script for Images: </strong></td>
							<td>
								" . createDropdown("lightboxscript", "ftsss_thumbnail_rel_tag", $ftsss_thumbnail_rel_tag, "") . "
							</td>
						</tr>
						<tr class=\"row2\">
							<td><strong>System Currency: </strong></td>
							<td>
								" . createDropdown("currencies", "ftsss_currency_type", $ftsss_currency_type, "") . "
							</td>
						</tr>
					</table>
					<br />
					<span class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Update Settings\" /></span>
				</form>";

	$page->setTemplateVar('PageContent', $content);
}
else {
	$page->setTemplateVar('PageContent', "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>