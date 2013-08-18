<?
/***************************************************************************
 *                               login.php
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
define('IN_LOGIN', 1); //let the header file know were here to stay Hey! Hey! Hey! 

$current_time = time();

//========================================
// Login Function for registering session
//========================================
if (isset($_POST['password'])) {
	// strip away any dangerous tags
	$email_address = keepsafe($_POST['email_address']);
	$password = keepsafe($_POST['password']);
	
	if(isset($email_address) && isset($password)){		
		// Convert password to md5 hash
		$password = md5($password);
	
		// check if the user info validates the db
		$sql = "SELECT * FROM `" . USERSDBTABLEPREFIX . "users` WHERE email_address='" . $email_address . "' AND password='" . $password . "' LIMIT 1";
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
				if ($actual_action == "updateOrder" && isset($actual_id)) { 
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
				elseif ($actual_redirect_to == "troubletickets") { 
					header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['TROUBLETICKETS']));
				}
				else {
					header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['LOGIN']));			
			 	}
				$content = "You are now logged in as " . $_SESSION['username'] . ". <br /><div class=\"center\"><a href=\"" . $menuvar['LOGOUT'] . "\">Logout</a></div>"; 
			}
			mysql_free_result($result);
		} 
		else {
			$content = "You could not be logged in! Either the username and password do not match or you have not validated your membership!<br />
		Please try again!<br /><a href=\"" . $menuvar['HOME'] . "\">Home</a>.";
		}
	}
	else { $content = "Please enter ALL of the information! <br />"; }
}

//========================================
// If we got here check and see if they 
// are logged in, if not print login page
//========================================
else{

	if (isset($_SESSION['email_address'])) {
		$content = "
			You are logged in as " . $_SESSION['username'] . ", and are being redirected to the main page. 
			<br /><div class=\"center\"><a href=\"" . $menuvar['LOGOUT'] . "\">Logout</a></div>";
			
		// Update the order in the DB to this Userid
			if ($actual_action == "updateOrder" && isset($actual_id)) { 
				$_SESSION['orderid'] = $actual_id;
				$sql2 = "UPDATE `" . DBTABLEPREFIX . "orders` SET user_id = '" . $_SESSION['userid'] . "' WHERE oders_id='" . $_SESSION['orderid'] . "'";
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
			if ($_SESSION['user_level'] == ADMIN) {
				header("Location: " . returnHttpLinks("http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/" . $menuvar['HOME']));	
			}		
		 }
 
	}
	else { 
		$content = "
			<form name=\"loginForm\" id=\"loginForm\" action=\"" . $menuvar['LOGIN'] . "&action=" . $actual_action . "&id=" . $actual_id . "&redirect_to=" . $actual_redirect_to . "\" method=\"post\">
				<div style=\"padding-left: 100px;\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"contentBox half\">
						<tr>
							<td class=\"title1\" colspan=\"2\">User Login</td>
						</tr>
						<tr>
							<td width=\"32%\" class=\"title2\">Email Address: </td>
							<td width=\"68%\" class=\"row1\"><input type=\"text\" name=\"email_address\" class=\"required\" title=\"Enter your email address. This is a required field\" size=\"20\" maxlength=\"40\" value=\"\" /></td>
						</tr>
						<tr>
							<td width=\"32%\" class=\"title2\">Password: </td>
							<td width=\"68%\" class=\"row1\"><input type=\"password\" name=\"password\" class=\"required\" title=\"Enter your password. This is a required field\" size=\"20\" maxlength=\"25\" /></td>
						</tr>
						<tr>
							<td width=\"100%\" colspan=\"2\" class=\"row1\">
								<input type=\"checkbox\" class=\"check\" name=\"autologin\" border=\"0\" value=\"ON\" checked /> Stay logged in <a href=\"" . $menuvar['RESETPASS'] . "\">Forgot Password?</a>
							</td>
						</tr>
					</table>
					<br /><br />
					<input type=\"submit\" class=\"loginButton\" name=\"login\" value=\"Login\" />
				</div>
			</form>
			<script type=\"text/javascript\">
				var valid = new Validation('loginForm', {immediate : true, useTitles:true});
			</script>
			<br /><br />
			<strong>-OR-</strong>
			<br /><br />
			<form name=\"newUserForm\" id=\"newUserForm\" action=\"" . $menuvar['REGISTER'] . "&action=newUser&action2=" . $actual_action . "&id=" . $actual_id . "&redirect_to=" . $actual_redirect_to . "\" method=\"post\">
				<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\">
					<tr>
						<td colspan=\"2\" class=\"title1\">Create a New Account</td>
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
			<script type=\"text/javascript\">
				var valid2 = new Validation('newUserForm', {immediate : true, useTitles:true});
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


	}
}
unset($_POST['password']); //weve finished registering the session variables le them pass so they dont get reregistered

$page->setTemplateVar('PageContent', $content);	

?>