<? 
/***************************************************************************
 *                               users.php
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
	// Handle editing, adding, and deleting of users
	//==================================================	
	if ($actual_action == "newuser") {
		// Add breadcrumb
		$page->addBreadCrumb("Add User", "");
			
		if (isset($_POST['submit'])) {
			if ($_POST['password'] == $_POST['password2']) {
				$password = md5(keepsafe($_POST['password']));
								
				$sql = "INSERT INTO `" . USERSDBTABLEPREFIX . "users` (`password`, `email_address`, `user_level`, `first_name`, `last_name`, `website`) VALUES ('" . $password . "', '" . keepsafe($_POST['emailaddress']) . "', '" . keepsafe($_POST['userlevel']) . "', '" . keeptasafe($_POST['firstname']) . "', '" . keeptasafe($_POST['lastname']) . "', '" . keeptasafe($_POST['website']) . "')";
				$result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your new user has been added, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['USERS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while creating your new user. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";						
				}
			}
			else {
				$page_content = "<span class=\"center\">The passwords you supplied do not match. You are being redirected to the main page.</span>
							<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "\">";			
			}
		}
		else {
			$page_content .= "
						<form name=\"newuserform\" action=\"" . $menuvar['USERS'] . "&amp;action=newuser\" method=\"post\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Add A New User</td>
								</tr>
								<tr class=\"row1\">
									<td><strong>First Name:</strong></td><td><input name=\"firstname\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Last Name:</strong></td><td><input name=\"lastname\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>Email Address:</strong></td><td><input name=\"emailaddress\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Password:</strong></td><td><input name=\"password\" type=\"password\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>Confirm Password:</strong></td><td><input name=\"password2\" type=\"password\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Website:</strong></td><td><input name=\"website\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>User Level:</strong></td><td>
										" . createDropdown("userlevel", "userlevel", "", "") . "
									</td>
								</tr>
							</table>									
							<br />
							<span class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Add User\" /></span>
						</form>";
		}
	}	
	elseif ($actual_action == "edituser" && isset($actual_id)) {
		// Add breadcrumb
		$page->addBreadCrumb("Edit User", "");
			
		if (isset($_POST['submit'])) {
			$poston_email_list = keepsafe($_POST['on_email_list']);
			$poston_email_list = ($poston_email_list != 1) ? 0 : 1;
			
			// Update our users account	
			if ($_POST['password'] != "") {
				if ($_POST['password'] == $_POST['password2']) {
					$password = md5($_POST['password']);								

					$sql = "UPDATE `" . USERSDBTABLEPREFIX . "users` SET password = '" . $password . "', company = '" . keeptasafe($_POST['company']) . "', email_address = '" . keepsafe($_POST['emailaddress']) . "', user_level = '" . keepsafe($_POST['userlevel']) . "', first_name = '" . keeptasafe($_POST['firstname']) . "', last_name = '" . keeptasafe($_POST['lastname']) . "', website = '" . keeptasafe($_POST['website']) . "', notes = '" . keeptasafe($_POST['notes']) . "', `on_email_list`='" . $poston_email_list . "' WHERE id = '" . $actual_id . "'";
				}
				else {
					$page_content = "<span class=\"center\">The passwords you supplied do not match. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "&action=edituser&id=" . $actual_id . "\">";			
				}
			}
			else {
				$sql = "UPDATE `" . USERSDBTABLEPREFIX . "users` SET company = '" . keeptasafe($_POST['company']) . "', email_address = '" . keepsafe($_POST['emailaddress']) . "', user_level = '" . keepsafe($_POST['userlevel']) . "', first_name = '" . keeptasafe($_POST['firstname']) . "', last_name = '" . keeptasafe($_POST['lastname']) . "', website = '" . keeptasafe($_POST['website']) . "', notes = '" . keeptasafe($_POST['notes']) . "', `on_email_list`='" . $poston_email_list . "' WHERE id = '" . $actual_id . "'";
				}
			$result = mysql_query($sql);
			
			if ($result) {
				$page_content = "<span class=\"center\">Your user's details have been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['USERS'] . "&action=edituser&id=" . $actual_id . "\">";
			}
			else {
				$page_content = "<span class=\"center\">There was an error while updating your user's details. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "&action=edituser&id=" . $actual_id . "\">";						
			}
		}
		else {
			$sql = "SELECT * FROM `" . USERSDBTABLEPREFIX . "users` WHERE id = '" . $actual_id . "' LIMIT 1";
			$result = mysql_query($sql);
			
			if ($result && mysql_num_rows($result) == 0) {
				$page_content = "<span class=\"center\">There was an error while accessing the user's details you are trying to update. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['USERS'] . "&action=edituser&id=" . $actual_id . "\">";	
			}
			else {
				$row = mysql_fetch_array($result);
				
				$page_content .= "
							<form name=\"editUsersForm\" id=\"editUsersForm\" action=\"" . $menuvar['USERS'] . "&action=edituser&id=" . $actual_id . "\" method=\"post\">
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\" colspan=\"2\">Edit User's Details</td>
									</tr>
									<tr class=\"row1\">
										<td><strong>First Name:</strong></td><td><input name=\"firstname\" type=\"text\" size=\"60\" value=\"$row[first_name]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>Last Name:</strong></td><td><input name=\"lastname\" type=\"text\" size=\"60\" value=\"$row[last_name]\" /></td>
									</tr>
									<tr class=\"row1\">
										<td><strong>Email Address:</strong></td><td><input name=\"emailaddress\" type=\"text\" size=\"60\" value=\"$row[email_address]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>New Password:</strong></td><td><input name=\"password\" type=\"password\" size=\"60\" /></td>
									</tr>
									<tr class=\"row1\">
										<td><strong>Confirm Password:</strong></td><td><input name=\"password2\" type=\"password\" size=\"60\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>Company:</strong></td><td><input name=\"company\" type=\"text\" size=\"60\" value=\"$row[company]\" /></td>
									</tr>	
									<tr class=\"row1\">
										<td><strong>Website:</strong></td><td><input name=\"website\" type=\"text\" size=\"60\" value=\"$row[website]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td><strong>User Level:</strong></td><td>
											" . createDropdown("userlevel", "userlevel", $row['user_level'], "") . "
										</td>
									</tr>
									<tr class=\"row1\">
										<td><strong>Notes:</strong></td><td>
											<textarea name=\"notes\" cols=\"50\" rows=\"10\">" . $row['notes'] . "</textarea>
										</td>
									</tr>
									<tr class=\"row2\"> 
										<td>Place on Email List?</td>
										<td><input name=\"on_email_list\" type=\"checkbox\" value=\"1\"" . testChecked(1, $row['on_email_list']) . " /></td>
									</tr>
								</table>									
								<br /><br />
									<br /><br />";
						
			//==================================================
			// Print out our users credit card info
			//==================================================
			$page_content .= "
									<div id=\"userCreditCardHolder\">
									" . getUserPanelUserCreditCard($actual_id) . "
									</div>
									<br /><br />";
								
						
			//==================================================
			// Print out our adress tables
			//==================================================
			$page_content .= "
									<div id=\"userBillAddressHolder\">
									" . getUserPanelUserAddress($actual_id, BILL_ADDRESS) . "
									</div>
									<br /><br />
									<div id=\"userShipAddressHolder\">
									" . getUserPanelUserAddress($actual_id, SHIP_ADDRESS) . "
									</div>
								<br /><br />
								<span class=\"center\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Update User's Details\" /></span>
							</form>
										<script language = \"Javascript\">
											var valid = new Validation('editUsersForm', {immediate : true, useTitles:true});
										</script>";							
			}			
		}
	}
	else {
		if ($actual_action == "deleteuser") {
			$sql = "DELETE FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
		}		
		
		//==================================================
		// Print out our users table
		//==================================================
		$currentTimestamp = time();
		$todayTimestamp = strtotime(gmdate('Y-m-d', $currentTimestamp + (3600 * '-7.00')));
		$tomorrowTimestamp = strtotime(gmdate('Y-m-d', strtotime("+1 day") + (3600 * '-7.00')));
		
		$extraSQL = " WHERE 1";
		$extraSQL .= (isset($_POST['search_username']) && $_POST['search_username'] != "") ? " AND username LIKE '%" . $_POST['search_username'] . "%'" : "";
		$extraSQL .= (isset($_POST['search_email_address']) && $_POST['search_email_address'] != "") ? " AND email_address LIKE '%" . $_POST['search_email_address'] . "%'" : "";
		$extraSQL .= (isset($_POST['search_first_name']) && $_POST['search_first_name'] != "") ? " AND first_name LIKE '%" . $_POST['search_first_name'] . "%'" : "";
		$extraSQL .= (isset($_POST['search_last_name']) && $_POST['search_last_name'] != "") ? " AND last_name LIKE '%" . $_POST['search_last_name'] . "%'" : "";
		$extraSQL .= ($actual_action == "viewTodaysUsers") ? " AND signup_date > '" . $todayTimestamp . "' AND signup_date < '" . $tomorrowTimestamp . "'" : "";
		
		
		$sql = "SELECT * FROM `" . USERSDBTABLEPREFIX . "users`" . $extraSQL . " ORDER BY signup_date DESC";
		$result = mysql_query($sql);
		
		$x = 1; //reset the variable we use for our row colors	
		
		// Allow admins to view only users who registered today or all users
		if ($actual_action == "viewTodaysUsers") {
			// Add breadcrumb
			$page->addBreadCrumb("View Users Who Registered Today", "");
			
			$viewUsersLink = "<a href=\"" . $menuvar['USERS'] . "&action=viewAllUsers\">View All Users</a><br />";
		}
		else {
			$viewUsersLink = "<a href=\"" . $menuvar['USERS'] . "&action=viewTodaysUsers\">View Users Who Registered Today</a><br />";
		}		
		
		$page_content = "						
						<form name=\"searchUsersForm\" id=\"searchUsersForm\" action=\"" . $menuvar['USERS'] . "\" method=\"post\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Search Users</td>
								</tr>	
								<tr>
									<td class=\"title2\" colspan=\"2\">Choose any or all of the following to search by.</td>
								</tr>	
								<tr class=\"row1\">
									<td><strong>Username: </strong></td>
									<td><input type=\"text\" name=\"search_username\" size=\"40\" value=\"" . $_POST['search_username'] . "\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Email Address: </strong></td>
									<td><input type=\"text\" name=\"search_email_address\" size=\"40\" value=\"" . $_POST['search_email_address'] . "\" /></td>
								</tr>
								<tr class=\"row1\">
									<td><strong>First name: </strong></td>
									<td><input type=\"text\" name=\"search_first_name\" size=\"40\" value=\"" . $_POST['search_first_name'] . "\" /></td>
								</tr>
								<tr class=\"row2\">
									<td><strong>Last name: </strong></td>
									<td><input type=\"text\" name=\"search_last_name\" size=\"40\" value=\"" . $_POST['search_last_name'] . "\" /></td>
								</tr>
							</table>
							<br />
							<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Search!\" />
						</form>
						<br /><br />
						" . $viewUsersLink . "
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"5\">
									<div class=\"floatRight\">
										<a href=\"" . $menuvar['USERS'] . "&amp;action=newuser\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" alt=\"Add a new user\" /></a>
									</div>
									Current Users (" . mysql_num_rows($result) . ")
								</td>
							</tr>							
							<tr class=\"title2\">
								<td><strong>Email Address</strong></td><td><strong>Full Name</strong></td><td><strong>Signup Date</strong></td><td><strong>User Level</strong></td><td></td>
							</tr>";
							
		while ($row = mysql_fetch_array($result)) {
			
			$page_content .= "
								<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
									<td>" . $row['email_address'] . "</td>
									<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
									<td>" . makeDate($row['signup_date']) . "</td>
									<td>" . getUserlevelFromID($row['id']) . "</td>
									<td>
										<span class=\"center\"><a href=\"" . $menuvar['USERS'] . "&amp;action=edituser&amp;id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit User Details\" /></a> <a href=\"" . $menuvar['USERS'] . "&amp;action=deleteuser&amp;id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete User\" /></a></span>
									</td>
								</tr>";
			$x = ($x==2) ? 1 : 2;
		}
		mysql_free_result($result);
		
	
		$page_content .=		"</table>";
	}
	$page->setTemplateVar("PageContent", $page_content);
}
else {
	$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>