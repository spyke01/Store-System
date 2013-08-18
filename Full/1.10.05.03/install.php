<? 
/***************************************************************************
 *                               install.php
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
	ini_set('arg_separator.output','&amp;');
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	
	// Set up our installer
	define('INSTALLER_SCRIPT_NAME', 'Store System');
	define('INSTALLER_SCRIPT_DESC', 'A full fledged store system.');
	define('INSTALLER_SCRIPT_IS_PROFESSIONAL_VERSION', 1);
	define('INSTALLER_SCRIPT_DB_PREFIX', 'SS_');
	
	// Inlcude the needed files
	include_once ('includes/constants.php');
	if (substr(phpversion(), 0, 1) == 5) { include_once ('includes/php5/pageclass.php'); }
	else { include_once ('includes/php4/pageclass.php'); }

	// Instantiate our page class
	$page = &new pageClass;

	// Handle our variables
	$requested_step = $_GET['step'];

	$actual_step = ($requested_step == "" || !isset($requested_step)) ? 1 : keepsafe($requested_step);
	$page_content = "";
	$failed = 0;
	$totalfailure = 0;
	$failed = array();
	$failedsql = array();
	$currentdate = time();

	
	//========================================
	// Custom Functions for this Page
	//========================================
	function keepsafe($makesafe) {
		$makesafe=strip_tags($makesafe); // strip away any dangerous tags
		$makesafe=str_replace(" ","",$makesafe); // remove spaces from variables
		$makesafe=str_replace("%20","",$makesafe); // remove escaped spaces
		$makesafe = trim(preg_replace('/[^\x09\x0A\x0D\x20-\x7F]/e', '"&#".ord($0).";"', $makesafe)); //encodes all ascii items above #127
		$makesafe = stripslashes($makesafe);
		
		return $makesafe;
	}
	
	function keeptasafe($makesafe) {
		$makesafe=strip_tags($makesafe); // strip away any dangerous tags
		$makesafe = trim(preg_replace('/[^\x09\x0A\x0D\x20-\x7F]/e', '"&#".ord($0).";"', $makesafe)); //encodes all ascii items above #127
		$makesafe = stripslashes($makesafe);
		
		return $makesafe;
	}

	function checkresult($result, $sql, $table) {
		global $failed;
		global $failedsql;
		global $totalfailure;
		
		if (!$result || $result == "") {
			$failed[$table] = "failed";
			$failedsql[$table] = $sql;
			$totalfailure = 1;
		}  
		else {
			$failed[$table] = "succeeded";
			$failedsql[$table] = $sql;
		}	
	}
	
	//========================================
	// Build our Page
	//========================================
	switch ($actual_step) {
		case 1:
			$page->setTemplateVar("PageTitle", INSTALLER_SCRIPT_NAME . " Step 1 - Introduction");	
			
			// Print this page
			$page_content = "
					<h2>Welcome to the Fast Track Sites Script Installer</h2>
					Thank you for downloading the " . INSTALLER_SCRIPT_NAME . " this page will walk you through the setup procedure.
					<br /><br />
					" . INSTALLER_SCRIPT_DESC . "
					<br /><br />
					<h2><span class=\"iconText38px\"><img src=\"themes/installer/icons/paperAndPencil_38px.png\" alt=\"License Agreement\" /></span> <span class=\"iconText38px\">License Agreement</span></h2>
					By installing this application you are agreeing to all the terms and conditions stated in the <a href=\"http://www.fasttracksites.com/ftspl\">Fast Track Sites Program License</a>.
					<br /><br />";
					
			if (INSTALLER_SCRIPT_IS_PROFESSIONAL_VERSION) {
				$page_content .= "
					Please enter your registration information below, failure to do so can result in your application being disabled.
					<br /><br />
					<form id=\"licenseInformationForm\" action=\"install.php?step=2\" method=\"post\">
						<label for=\"serialNumber\">Serial Number</label> <input type=\"text\" name=\"serialNumber\" id=\"serialNumber\" class=\"required\" />
						<label for=\"registeredTo\">Registered To</label> <input type=\"text\" name=\"registeredTo\" id=\"registeredTo\" class=\"required\" />
						<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Next\" />
					</form>
					<script type=\"text/javascript\">
						var valid = new Validation('licenseInformationForm', {immediate : true, useTitles:true});
					</script>";
			}
			else {
				$page_content .= "
					<a href=\"install.php?step=2\" class=\"button\">I Agree</a>";			
			}
			break;
		case 2:
			$page->setTemplateVar("PageTitle", INSTALLER_SCRIPT_NAME . " Step 2 - Database Connection");	
			
			// Create our license file
			if (INSTALLER_SCRIPT_IS_PROFESSIONAL_VERSION) {
				$serialNumber = keepsafe($_POST['serialNumber']);
				$registeredTo = keeptasafe($_POST['registeredTo']);
			}
			else {
				$serialNumber = "Free Edition";
				$registeredTo = "Fast Track Sites";
			}
			
			$str = "<?PHP\n\n\$A_License = \"" . $serialNumber . "\";\n\$A_Licensed_To = \"" . $registeredTo . "\";\n\n?>";
		
			$fp=fopen("_license.php","w");
			$result = fwrite($fp,$str);
			fclose($fp);	
			
			// Print this page
			$page_content = "
					<h2>License File Results</h2>";
			
			if (!$result || $result == "") {
				$page_content .= "
					<span class=\"actionFailed\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/delete_20px.png\" alt=\"Action Failed\" /></span> <span class=\"iconText20px\">Unable to create license file.</span></span>";
			}
			else {
				$page_content .= "
					<span class=\"actionSucceeded\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/check_20px.png\" alt=\"Action Succeeded\" /></span> <span class=\"iconText20px\">Successfully created license file.</span></span>";
			}
			
			$page_content .= "
					<br /><br />
					<h2><span class=\"iconText38px\"><img src=\"themes/installer/icons/addDatabase_38px.png\" alt=\"Add Database\" /></span> <span class=\"iconText38px\">Configure Your Database Connection</span></h2>
					Please enter your database information below:
					<br /><br />
					<form id=\"databaseConnectionForm\" action=\"install.php?step=3\" method=\"post\">
						<label for=\"dbServer\">Server</label> <input type=\"text\" name=\"dbServer\" id=\"dbServer\" class=\"required\" />
						<label for=\"dbName\">Database Name</label> <input type=\"text\" name=\"dbName\" id=\"dbName\" class=\"required\" />
						<label for=\"dbUsername\">Username</label> <input type=\"text\" name=\"dbUsername\" id=\"dbUsername\" class=\"required\" />
						<label for=\"dbPassword\">Password</label> <input type=\"password\" name=\"dbPassword\" id=\"dbPassword\" class=\"required\" />
						<label for=\"dbTablePrefix\">Table Prefix</label> <input type=\"text\" name=\"dbTablePrefix\" id=\"dbTablePrefix\" class=\"required\" value=\"" . INSTALLER_SCRIPT_DB_PREFIX . "\" />
						<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Next\" />
					</form>
					<script type=\"text/javascript\">
						var valid = new Validation('databaseConnectionForm', {immediate : true, useTitles:true});
					</script>";
			break;
		case 3:
			$page->setTemplateVar("PageTitle", INSTALLER_SCRIPT_NAME . " Step 3 - Create database Tables");	
			
			// Create our database connection file
			$dbServer = keepsafe($_POST['dbServer']); 
			$dbName = keepsafe($_POST['dbName']); 
			$dbUsername = keepsafe($_POST['dbUsername']); 
			$dbPassword = keepsafe($_POST['dbPassword']); 
			$DBTABLEPREFIX = keepsafe($_POST['dbTablePrefix']); 
			
			$str = "<?PHP\n\n// Connect to the database\n\n\$server = \"" . $dbServer . "\";\n\$dbuser = \"" . $dbUsername . "\";\n\$dbpass = \"" . $dbPassword . "\";\n\$dbname = \"" . $dbName . "\";\ndefine('DBTABLEPREFIX', '" . DBTABLEPREFIX . "');\ndefine('USERSDBTABLEPREFIX', '" . DBTABLEPREFIX . "');\n\n\$connect = mysql_connect(\$server,\$dbuser,\$dbpass);\n\n//display error if connection fails\nif (\$connect==FALSE) {\n   print 'Unable to connect to database: '.mysql_error();\n   exit;\n}\n\nmysql_select_db(\$dbname); // select database\n\n?>";
		
			$fp=fopen("_db.php","w");
			$result = fwrite($fp,$str);
			fclose($fp);	
	
			// Print this page
			$page_content = "
					<h2>Database Connection Results</h2>";
			
			if (!$result || $result == "") {
				$page_content .= "
					<span class=\"actionFailed\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/delete_20px.png\" alt=\"Action Failed\" /></span> <span class=\"iconText20px\">Unable to create database connection file.</span></span>";
			}
			else {
				$page_content .= "
					<span class=\"actionSucceeded\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/check_20px.png\" alt=\"Action Succeeded\" /></span> <span class=\"iconText20px\">Successfully created database connection file.</span></span>";
			}
			
			$page_content .= "
					<br /><br />
					<h2><span class=\"iconText38px\"><img src=\"themes/installer/icons/table_38px.png\" alt=\"Create Tables\" /></span> <span class=\"iconText38px\">Create database Tables</span></h2>
					Press Next to create the database tables.
					<br /><br />
					<a href=\"install.php?step=4\" class=\"button\">Next</a>";
			break;
		case 4:
			$page->setTemplateVar("PageTitle", INSTALLER_SCRIPT_NAME . " Step 4 - Create Admin Account");	
			
			include('_db.php');
			
			// Create our Database Tables
			$sql = "CREATE TABLE `" . DBTABLEPREFIX . "addresses` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `order_id` mediumint(8) NOT NULL default '0',
				  `type` tinyint(1) NOT NULL default '0',
				  `first_name` varchar(50) NOT NULL default '',
				  `last_name` varchar(50) NOT NULL default '',
				  `company` varchar(100) NOT NULL default '',
				  `email_address` varchar(100) NOT NULL default '',
				  `street_1` varchar(100) NOT NULL default '',
				  `street_2` varchar(100) NOT NULL default '',
				  `city` varchar(50) NOT NULL default '',
				  `country` varchar(50) NOT NULL,
				  `state` varchar(50) NOT NULL,
				  `zip` varchar(15) NOT NULL default '0',
				  `day_phone` varchar(50) NOT NULL,
				  `day_phone_ext` varchar(25) NOT NULL default '',
				  `night_phone` varchar(50) NOT NULL,
				  `night_phone_ext` varchar(25) NOT NULL default '',
				  `fax` varchar(50) NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "addresses");
				    
			$sql = "CREATE TABLE `" . DBTABLEPREFIX . "config` (
				  `name` varchar(255) NOT NULL default '',
				  `value` text NOT NULL
				) ENGINE=MyISAM;";
			$result = mysql_query($sql);
			checkresult($result, $sql, "config");
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "coupons` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `name` varchar(250) NOT NULL default '',
				  `code` varchar(50) NOT NULL default '',
				  `discount` decimal(12,2) NOT NULL default '0.00',
				  `discount_percentage` tinyint(2) NOT NULL default '0',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "coupons");  
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "creditcards` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `user_id` mediumint(11) NOT NULL default '0',
				  `name_on_card` varchar(100) NOT NULL default '',
				  `card_type` varchar(50) NOT NULL default '',
				  `card_number` varchar(100) NOT NULL default '0',
				  `exp_month` mediumint(2) NOT NULL default '0',
				  `exp_year` mediumint(4) NOT NULL default '0',
				  `card_sid` varchar(8) NOT NULL default '0',
				  `bank_name` varchar(50) NOT NULL default '',
				  `bank_number` varchar(25) NOT NULL default '',
				  `trans_signature` varchar(50) NOT NULL,
				  `trans_result` varchar(20) NOT NULL,
				  `trans_code` varchar(20) NOT NULL,
				  `trans_timestamp` varchar(20) NOT NULL,
				  `trans_tried` smallint(5) NOT NULL default '0',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "creditcards");
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "dist` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `name` varchar(250) NOT NULL default '',
				  `phone_number` varchar(50) NOT NULL default '',
				  `website` varchar(200) NOT NULL default '',
				  `cust_num` varchar(100) NOT NULL default '',
				  `address` text NOT NULL,
				  `sales_rep` varchar(100) NOT NULL default '',
				  `sales_rep_ext` varchar(20) NOT NULL default '',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "dist");  
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "models` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `name` varchar(50) NOT NULL default '',
				  `description` text NOT NULL,
				  `base_price` decimal(12,2) NOT NULL default '0.00',
				  `base_weight` decimal(12,2) NOT NULL default '0.00',
				  `base_profit` decimal(12,2) NOT NULL default '0.00',
				  `discount` decimal(12,2) NOT NULL default '0.00',
				  `discount_percentage` tinyint(2) NOT NULL,
				  `discount_description` text NOT NULL,
				  `image_full` varchar(200) NOT NULL default '',
				  `image_thumb` varchar(200) NOT NULL default '',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "models");
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "orders` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `user_id` mediumint(8) NOT NULL default '0',
				  `tech_user_id` mediumint(8) NOT NULL,
				  `user_ip` varchar(50) NOT NULL,
				  `datetime` varchar(50) NOT NULL default '0',
				  `shipping` varchar(50) NOT NULL default '',
				  `shipping_price` decimal(12,2) NOT NULL default '0.00',
				  `tax` decimal(12,2) NOT NULL default '0.00',
				  `rush_fee` decimal(12,2) NOT NULL default '0.00',
				  `items_total` decimal(12,2) NOT NULL default '0.00',
				  `price` decimal(12,2) NOT NULL default '0.00',
				  `discount` decimal(12,2) NOT NULL default '0.00',
				  `coupon_name` varchar(250) NOT NULL,
				  `coupon_code` varchar(50) NOT NULL,
				  `coupon_discount` decimal(12,2) NOT NULL,
				  `coupon_discount_percentage` tinyint(2) NOT NULL,
				  `status` tinyint(2) NOT NULL default '0',
				  `tracking` varchar(100) NOT NULL default '',
				  `shipped_by` varchar(50) NOT NULL default '',
				  `date_shipped` varchar(50) NOT NULL default '',
				  `comments` varchar(250) NOT NULL default '',
				  `serials` text NOT NULL,
				  `os_key` varchar(50) NOT NULL default '',
				  `txn_id` varchar(250) NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "orders");
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "creditcards` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `order_id` mediumint(8) NOT NULL default '0',
				  `name_on_card` varchar(100) NOT NULL default '',
				  `card_type` varchar(50) NOT NULL default '',
				  `card_number` varchar(100) NOT NULL default '0',
				  `exp_month` mediumint(2) NOT NULL default '0',
				  `exp_year` mediumint(4) NOT NULL default '0',
				  `card_sid` varchar(8) NOT NULL default '0',
				  `bank_name` varchar(50) NOT NULL default '',
				  `bank_number` varchar(25) NOT NULL default '',
				  `trans_signature` varchar(50) NOT NULL,
				  `trans_result` varchar(20) NOT NULL,
				  `trans_code` varchar(20) NOT NULL,
				  `trans_timestamp` varchar(20) NOT NULL,
				  `trans_tried` smallint(5) NOT NULL,
				  `trans_verify_signature` varchar(10) NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "creditcards"); 
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "partcats` (
				  `sort_order` mediumint(8) NOT NULL default '0',
				  `id` mediumint(8) NOT NULL auto_increment,
				  `name` varchar(50) NOT NULL default '',
				  `description` text NOT NULL,
				  `list_type` mediumint(8) NOT NULL default '0',
				  `on_slider` tinyint(1) NOT NULL default '1',
				  `on_build_list` tinyint(1) NOT NULL,
				  `image` varchar(200) NOT NULL default '',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "partcats");
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "parts` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `name` varchar(250) NOT NULL default '',
				  `type` text NOT NULL,
				  `dist` mediumint(8) NOT NULL,
				  `item_num` varchar(50) NOT NULL default '',
				  `description` text NOT NULL,
				  `qty` mediumint(8) NOT NULL default '0',
				  `sort` mediumint(3) NOT NULL default '999',
				  `price` decimal(12,2) NOT NULL default '0.00',
				  `shipping_costs` decimal(12,2) NOT NULL default '0.00',
				  `profit` decimal(12,2) NOT NULL default '0.00',
				  `weight` decimal(12,2) NOT NULL default '0.00',
				  `models` text NOT NULL,
				  `default_on_models` text NOT NULL,
				  `image_full` varchar(250) NOT NULL default '',
				  `image_thumb` varchar(250) NOT NULL default '',
				  `active` smallint(1) NOT NULL default '1',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "parts");
		    
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "systems` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `order_id` mediumint(8) NOT NULL,
				  `model_id` mediumint(8) NOT NULL,
				  `qty` mediumint(8) NOT NULL default '0',
				  `weight` decimal(12,2) NOT NULL default '0.00',
				  `price` decimal(12,2) NOT NULL default '0.00',
				  `discount` decimal(12,2) NOT NULL default '0.00',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "systems");
			
		    $sql = "CREATE TABLE `" . DBTABLEPREFIX . "systemparts` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `system_id` mediumint(8) NOT NULL default '0',
				  `part_id` mediumint(8) NOT NULL default '0',
				  `partcat_id` mediumint(8) NOT NULL,
				  `part_name` varchar(50) NOT NULL default '',
				  `weight` decimal(12,2) NOT NULL default '0.00',
				  `price` decimal(12,2) NOT NULL default '0.00',
				  `shipping_costs` decimal(12,2) NOT NULL default '0.00',
				  `profit` decimal(12,2) NOT NULL default '0.00',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "systemparts");
	
			$sql = "CREATE TABLE `" . USERSDBTABLEPREFIX . "users` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `cat_id` mediumint(8) NOT NULL,
				  `active` tinyint(1) NOT NULL default '1',
				  `activation` varchar(255) NOT NULL default '',
				  `user_level` tinyint(4) NOT NULL default '0',
				  `tech` tinyint(1) NOT NULL default '0',
				  `preffered_client` tinyint(1) NOT NULL,
				  `on_email_list` tinyint(1) NOT NULL default '1',
				  `username` varchar(25) NOT NULL default '',
				  `password` varchar(32) NOT NULL default '',
				  `first_name` varchar(50) NOT NULL default '',
				  `last_name` varchar(50) NOT NULL default '',
				  `email_address` varchar(35) NOT NULL default '',
				  `website` varchar(100) NOT NULL default '',
				  `clms_title` varchar(50) NOT NULL,
				  `title` varchar(50) NOT NULL default '',
				  `company` varchar(50) NOT NULL,
				  `gender` varchar(15) NOT NULL default '',
				  `style` varchar(50) NOT NULL default 'default',
				  `country` varchar(20) NOT NULL default '',
				  `info` text NOT NULL,
				  `sig` text NOT NULL,
				  `attachsig` tinyint(1) NOT NULL default '1',
				  `aim` varchar(255) NOT NULL default '',
				  `yim` varchar(255) NOT NULL default '',
				  `msn` varchar(255) NOT NULL default '',
				  `birthday` int(10) NOT NULL default '0',
				  `avatar` varchar(100) NOT NULL default 'images/avatars/no_avatar.jpg',
				  `avatar_type` tinyint(1) NOT NULL default '0',
				  `last_login` int(11) NOT NULL default '0',
				  `posts` mediumint(8) NOT NULL default '0',
				  `signup_date` int(11) default NULL,
				  `notes` text NOT NULL,
				  `found_us_through` varchar(50) NOT NULL,
				  `language` varchar(5) NOT NULL default 'en',
				  `password_reset_code` varchar(50) NOT NULL default '',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
			$result = mysql_query($sql);
			checkresult($result, $sql, "users");
			
			$sql = "CREATE TABLE `" . DBTABLEPREFIX . "useraddresses` (
				  `id` mediumint(8) NOT NULL auto_increment,
				  `user_id` mediumint(11) NOT NULL default '0',
				  `type` tinyint(1) NOT NULL default '0',
				  `first_name` varchar(50) NOT NULL,
				  `last_name` varchar(50) NOT NULL,
				  `company` varchar(100) NOT NULL default '',
				  `street_1` varchar(100) NOT NULL default '',
				  `street_2` varchar(100) NOT NULL default '',
				  `city` varchar(50) NOT NULL default '',
				  `country` varchar(10) NOT NULL,
				  `state` varchar(50) NOT NULL,
				  `zip` varchar(15) NOT NULL default '0',
				  `day_phone` varchar(50) NOT NULL,
				  `day_phone_ext` varchar(25) NOT NULL default '',
				  `night_phone` varchar(50) NOT NULL,
				  `night_phone_ext` varchar(25) NOT NULL default '',
				  `fax` varchar(50) NOT NULL,
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=1;";
		    $result = mysql_query($sql);
		  	checkresult($result, $sql, "useraddresses");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_theme', 'default');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert1");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_cookie_name', 'ftsss');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert2");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_active', '1');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert4");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_inactive_message', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert5");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_store_name', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert6");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_zip_code', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert7");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_tax_state', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert8");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_tax_rate', '0.0825');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert9");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_rush_fee', '50');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert10");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_store_url', 'http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert11");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_terms', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert12");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_paypal_email', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert13");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_sales_email', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert14");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_address', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert15");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_phone_number', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert16");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_fax', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert17");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_google_checkout_email', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert18");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_google_checkout_id', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert19");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_paypal_active', '1');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert20");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_google_checkout_active', '0');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert21");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_credit_card_payment_active', '0');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert22");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_checkmowire_active', '1');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert23");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_thumbnail_rel_tag', 'lytebox');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert24");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_currency_type', '$');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert25");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_paypal_auth_token', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert26");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_use_https', '0');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert27");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_wire_transfer_instructions', '');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert28");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_logo_url', 'themes/newStyle/images/logo.png');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert29");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_slider_side', 'right');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert30");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_slider_padd_side', '15');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert31");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_slider_padd_top', '65');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert32");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_slider_padd_side_ie', '15');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert33");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_slider_padd_top_ie', '29');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert34");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_slider_active', '1');";
			$result = mysql_query($sql);
			checkresult($result, $sql, "configinsert35");
		
			// Print this page
			$page_content = "
					<h2>Insert Table Results</h2>";
			
			if ($totalfailure == 1) {
				$page_content .= "
					<span class=\"actionFailed\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/delete_20px.png\" alt=\"Action Failed\" /></span> <span class=\"iconText20px\">Unable to create database tables.</span></span>";
			}
			else {
				$page_content .= "
					<span class=\"actionSucceeded\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/check_20px.png\" alt=\"Action Succeeded\" /></span> <span class=\"iconText20px\">Successfully created database tables.</span></span>";
			}
			
			$page_content .= "
					<br /><br />
					<h2><span class=\"iconText38px\"><img src=\"themes/installer/icons/addUser_38px.png\" alt=\"Add User\" /></span> <span class=\"iconText38px\">Create Your Admin Account</span></h2>
					Please enter your admin user information below:
					<form id=\"adminAccountForm\" action=\"install.php?step=5\" method=\"post\">
						<label for=\"usrUsername\">Username</label> <input type=\"text\" name=\"usrUsername\" id=\"usrUsername\" class=\"required validate-alphanum\" />
						<label for=\"usrEmailAddress\">Email Address</label> <input type=\"text\" name=\"usrEmailAddress\" id=\"usrEmailAddress\" class=\"required validate-email\" />
						<label for=\"usrPassword\">Password</label> <input type=\"password\" name=\"usrPassword\" id=\"usrPassword\" class=\"required validate-password\" />
						<label for=\"usrConfirmPassword\">Confirm Password</label> <input type=\"password\" name=\"usrConfirmPassword\" id=\"usrConfirmPassword\" class=\"required validate-password-confirm\" />
						<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Next\" />
					</form>
					<script type=\"text/javascript\">
						var valid = new Validation('adminAccountForm', {immediate : true, useTitles:true});
						Validation.addAllThese([
								['validate-password', 'Your password must be at least 7 characters and cannot be your username, the word password, 1234567, or 0123456.', {
								minLength : 7,
								notOneOf : ['password','PASSWORD','1234567','0123456'],
								notEqualToField : 'usrUsername'
							}],
							['validate-password-confirm', 'Your passwords do not match.', {
								equalToField : 'usrPassword'
							}]
						]);
					</script>";
			break;
		case 5:
			$page->setTemplateVar("PageTitle", INSTALLER_SCRIPT_NAME . " Step 5 - Finish");	
			
			include('_db.php');
				
	    	// Create our admin account
			$usrUsername = keepsafe($_POST['usrUsername']); 
			$usrPassword = md5(keepsafe($_POST['usrPassword']));
			$usrEmailAddress = keepsafe($_POST['usrEmailAddress']);
		
	    	$sql = "INSERT INTO `" . USERSDBTABLEPREFIX . "users` (`username`, `password`, `email_address`, `signup_date`, `notes`, `user_level`) VALUES ('" . $usrUsername . "', '" . $usrPassword . "', '" . $usrEmailAddress . "', '" . time() . "', '', '1');";
	    	$result = mysql_query($sql);
		    checkresult($result, $sql, "AdminUser");
	
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "config` VALUES ('ftsss_admin_email', '$postemail');";
		    $result = mysql_query($sql);
		    checkresult($result, $sql, "configInsert");
		
			// Print this page
			$page_content = "
					<h2>Create Your Admin Account Results</h2>";
			
			if ($totalfailure == 1) {
				$page_content .= "
					<span class=\"actionFailed\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/delete_20px.png\" alt=\"Action Failed\" /></span> <span class=\"iconText20px\">Unable to create admin account.</span></span>";
			}
			else {
				$page_content .= "
					<span class=\"actionSucceeded\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/check_20px.png\" alt=\"Action Succeeded\" /></span> <span class=\"iconText20px\">Successfully created admin account.</span></span>";
			}
			
			$page_content .= "
					<h2>Finishing Up</h2>
					Installation is now complete, before using the system please make sure and delete this file (install.php) so that it cannot be reused by someone else.
					<br /><br />
					<a href=\"index.php\" class=\"button\">Finish</a>";
			break;	
	}
	
	// Send out the content
	$page->setTemplateVar("PageContent", $page_content);	
	
	include "themes/installer/template.php";
?>