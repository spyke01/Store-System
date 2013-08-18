<?
/***************************************************************************
 *                               resetpass.php
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

if ($actual_action == "sendResetPasswordEmail") {
	//=====================================================
	// Define variables from post data
	//=====================================================	
	$postemail_address = keepsafe($_POST['email_address']);
	
	$sql = "SELECT id FROM `" . USERSDBTABLEPREFIX . "users` WHERE email_address='" . $postemail_address . "'";
	$result = mysql_query($sql);
	 
	if($result && mysql_num_rows($result) > 0){
		// There was a match
		while ($row = mysql_fetch_array($result)) {
			$code = md5($row['id'] . $postemail_address);
			
			$sql = "UPDATE `" . USERSDBTABLEPREFIX . "users` SET password_reset_code='" . $code . "' WHERE id='" . $row['id'] . "'";
			$result = mysql_query($sql);
			
			$message = "Please follow this link to reset your password.<br /><br /> <a href=\"" . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['RESETPASS'] . "&action=resetPassword&userid=" . $row['id'] . "&code=" . $code) . "\">" . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['RESETPASS'] . "&action=resetPassword&userid=" . $row['id'] . "&code=" . $code) . "</a>";
			
			emailMessage($postemail_address, "Password Reset Link", $message);
		}
		mysql_free_result($result);
		
		$content .= "An email has been sent to the email address provided, simply follow that link to reset your password.";
	}
	else {
		$content .= "Your email address was not found in the database, please go back and try again. ";
	}
}
elseif ($actual_action == "resetPassword") {
	//=====================================================
	// Define variables from post data
	//=====================================================	
	$postuserid = keepsafe($_GET['userid']);
	$postcode = keepsafe($_GET['code']);
	
	$sql = "SELECT id FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $postuserid . "' AND password_reset_code='" . $postcode . "'";
	$result = mysql_query($sql);
	//echo $sql;
	 
	if($result && mysql_num_rows($result) > 0){
		// There was a match
		if (isset($_POST['password']) && isset($_POST['password2']) && $_POST['password'] == $_POST['password2']) {
			
			$sql = "UPDATE `" . USERSDBTABLEPREFIX . "users` SET password='" . md5($_POST['password']) . "' WHERE id='" . $postuserid . "' AND password_reset_code='" . $postcode . "'";
			$result = mysql_query($sql);
			
			$content .= "Your password has been reset, <a href=\"" . $menuvar['LOGIN'] . "\">click here to login</a>.";
			
			unset($_POST['password']);
			unset($_POST['password2']);
		}
		else {
			$content .= "
				<form name=\"resetPasswordForm\" method=\"post\" action=\"" . $menuvar['RESETPASS'] . "&action=resetPassword&code=" . $postcode . "&userid=" . $postuserid . "\">
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\">
						<tr class=\"title1\">
							<td colspan=\"2\">Reset Password</td>
						</tr>
						<tr> 
							<td class=\"title2\">New Password</td>
							<td class=\"row1\"><input name=\"password\" id=\"password\" size=\"60\" /></td>
						</tr>
						<tr> 
							<td class=\"title2\">Confirm New Password</td>
							<td class=\"row1\"><input name=\"password2\" id=\"password2\" size=\"60\" /></td>
						</tr>
					</table>
					<br /><br />
					<div class=\"center\"><input type=\"submit\" class=\"button\" name=\"submit\" value=\"Reset My Password!\" /></div>
				</form>";
		}
	}
	else {
		$content .= "Please use the link in your email to reset you password, if you continue to experience issues please contact the webmaster.";
	}
}
else {
	$content .= "
		<form name=\"resetPasswordForm\" method=\"post\" action=\"" . $menuvar['RESETPASS'] . "&action=sendResetPasswordEmail\">
			<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\">
				<tr class=\"title1\">
					<td colspan=\"2\">Reset Password</td>
				</tr>
				<tr> 
					<td class=\"title2\">Email Address</td>
					<td class=\"row1\"><input name=\"email_address\" type=\"text\" size=\"60\" /></td>
				</tr>
			</table>
			<br /><br />
			<div class=\"center\"><input type=\"submit\" class=\"button\" name=\"submit\" value=\"Reset My Password!\" /></div>
		</form>";	
}

$page->setTemplateVar("PageContent", $content);
?>