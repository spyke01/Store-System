<?
/***************************************************************************
 *                               register.php
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

if ($actual_action == "newUser") {
	//=====================================================
	// Define variables from post data
	//=====================================================	
	$postfirst_name = $_POST['first_name'];
	$postlast_name = $_POST['last_name'];
	$postemail_address = $_POST['email_address'];
	$postpassword = $_POST['password1'];
	$poston_email_list = $_POST['on_email_list'];
	$current_time = time();	
	
	//=====================================================
	// Strip dangerous tags
	//=====================================================	
	$postpassword = keepsafe($postpassword);
	$postfirst_name = keepsafe($postfirst_name);
	$postlast_name = keepsafe($postlast_name);
	$postemail_address = keepsafe($postemail_address);
	$poston_email_list = keepsafe($poston_email_list);
	$poston_email_list = ($poston_email_list != 1) ? 0 : 1;
	
	$sql_email_check = mysql_query("SELECT email_address FROM `" . USERSDBTABLEPREFIX . "users` WHERE email_address='" . $postemail_address . "'");
	 
	$email_check = mysql_num_rows($sql_email_check);
	$username_check = mysql_num_rows($sql_username_check);
	 
	if($email_check > 0){
		$content .= $T_FIX_ERRORS . "<br />";
		if($email_check > 0){
			$content .= "This email address has already been used.<br />";
			unset($postemail_address);
		}
	}
	else {
		//=====================================================
		// Everything has passed both error checks that we 
		// have done. It's time to create the account!
		//=====================================================
	
		$db_password = md5($postpassword);
		$activationCode = md5($postpassword . $current_time);
		
		// generate SQL.
		$sql = "INSERT INTO `" . USERSDBTABLEPREFIX . "users` (first_name, last_name, email_address, password, on_email_list, signup_date) VALUES('" . $postfirst_name . "', '" . $postlast_name . "', '" . $postemail_address . "', '" . $db_password . "', '" . $poston_email_list . "', '" . $current_time . "')";
		$result = mysql_query($sql);
		$userID = mysql_insert_id();
		
		if(!$result){
			$content .= "There was an error while creating your account, please try again.";
		}
		else {
			// Notify user of success
			/*
			$content .= "Welcome $postfirst_name $postlast_name,<br />
							Thank You for registering<br />
							<br />
							You can now login with the following information<br />
							<br />
							Email Address: $postemail_address<br />
							Password: $postpassword<br /><br />
							
							<a href=\"" . $menuvar['LOGIN'] . "&redirect_to=" . $actual_redirect_to . "\">Click Here to Login.</a>
							
							<style>
								#formHolder {
									display: none;
								}
							</style>";
			
			*/
			
		}	
		//session_destroy();
		
		// log the user in
		$sql = "SELECT * FROM `" . USERSDBTABLEPREFIX . "users` WHERE id='" . $userID . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result) > 0){
			while($row = mysql_fetch_array($result)){			
				if (isset($_POST['autologin'])) {
					$cookiename = $ss_config['ftsss_cookie_name'];
					setcookie($cookiename, $row['id'] . "-" . $row['password'], time()+2592000 ); //set cookie for 1 month
				}
										
				// Register some session variables!
				$_SESSION['STATUS'] = "true";
				$_SESSION['userid'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['epassword'] = $row['password'];
				$_SESSION['email_address'] = $row['email_address'];
				$_SESSION['first_name'] = $first_name;
				$_SESSION['last_name'] = $last_name;
				$_SESSION['website'] = $row['website'];
				$_SESSION['user_level'] = $row['user_level'];
				
				
				// Update the order in the DB to this Userid
				if ($actual_action2 == "updateOrder" && isset($actual_id)) {
					$_SESSION['orderid'] = $actual_id; 
					$sql2 = "UPDATE `" . DBTABLEPREFIX . "orders` SET user_id = '" . $_SESSION['userid'] . "' WHERE id='" . $_SESSION['orderid'] . "'";
					$result2 = mysql_query($sql2);
				}
			
				// Take redirect into account
				if ($actual_redirect_to == "cart") { 
					header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['CART']));
				}
				elseif ($actual_redirect_to == "userpanel") { 
					header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['USERPANEL']));
				}
				elseif ($actual_redirect_to == "checkout") { 
					header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['CHECKOUT']));
				}
				else {
					//header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['REGISTER']));			
			 	}
				$content = "You are now logged in as</span> " . $_SESSION['email_address'] . ". <br /><span class=\"center\"><a href='" . $menuvar['LOGOUT'] . "'>Logout</a></span>"; 
			}
			mysql_free_result($result);
		} 
	}
	unset($_POST['submit']);
}
	$content .= " 
	<div id=\"formHolder\">
		<form id=\"newUserForm\" name=\"newUserForm\" method=\"post\" action=\"" . $menuvar['REGISTER'] . "&action=newUser\">
			<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\">
				<tr class=\"title1\">
					<td colspan=\"2\">Create a New Account</td>
				</tr>
				<tr> 
					<td class=\"title2\">Email Address</td>
					<td class=\"row1\"><div id=\"emailaddressCheckerHolder\" class=\"floatRight\"><a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">[Check]</a></div><input name=\"email_address\" type=\"text\" size=\"60\" id=\"email_address\" class=\"required validate-email\" value=\"" . keeptasafe($_POST['email_address']) . "\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">Password</td>
					<td class=\"row1\"><input name=\"password1\" type=\"password\" size=\"60\" id=\"password1\" class=\"required validate-password\" value=\"\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">Confirm Password</td>
					<td class=\"row1\"><input name=\"password2\" type=\"password\" size=\"60\" id=\"password2\" class=\"required validate-password-confirm\" value=\"\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">First Name</td>
					<td class=\"row1\"><input name=\"first_name\" type=\"text\" size=\"60\" id=\"first_name\" class=\"required validate-alpha\" value=\"" . keeptasafe($_POST['first_name']) . "\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">Last name</td>
					<td class=\"row1\"><input name=\"last_name\" type=\"text\" size=\"60\" id=\"last_name\" class=\"required validate-alpha\" value=\"" . keeptasafe($_POST['last_name']) . "\" /></td>
				</tr>
				<tr> 
					<td class=\"title2\">Would you like to receive emails from us?</td>
					<td class=\"row1\"><input name=\"on_email_list\" type=\"checkbox\" value=\"1\" /></td>
				</tr>
			</table>
			<br /><br />
			<div class=\"center\"><input type=\"submit\" class=\"createAccountButton\" name=\"submit\" value=\"Create Account\" /></div>
		</form>
	</div>
	<script type=\"text/javascript\">
		var valid = new Validation('newUserForm', {immediate : true, useTitles:true});
						Validation.addAllThese([
							['validate-password', 'Your password must be more than 6 characters and not be \'password\' or the same as your username.', {
								minLength : 7,
								notOneOf : ['password','PASSWORD','1234567','0123456'],
								notEqualToField : 'username'
							}],
							['validate-password-confirm', 'Your passwords do not match, please re-enter them.', {
								equalToField : 'password1'
							}]
						]);
	</script>";	

$page->setTemplateVar("PageContent", $content);
?>