<? 
/***************************************************************************
 *                               update.php
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
	define('UPDATER_SCRIPT_NAME', 'Store System');
	
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
			$page->setTemplateVar("PageTitle", UPDATER_SCRIPT_NAME . " Step 1 - Update Database Connection");	
			
			// Print this page
			$page_content = "
					<h2>Welcome to the Fast Track Sites Script Updater</h2>
					Thank you for downloading the " . UPDATER_SCRIPT_NAME . " this page will walk you through the update procedure.
					<br /><br />
					<h2><span class=\"iconText38px\"><img src=\"themes/installer/icons/addDatabase_38px.png\" alt=\"Add Database\" /></span> <span class=\"iconText38px\">Update Your Database Connection</span></h2>
					Press Next to update the database connection.
					<br /><br />
					<a href=\"update.php?step=2\" class=\"button\">Next</a>";
			break;
		case 2:
			$page->setTemplateVar("PageTitle", INSTALLER_SCRIPT_NAME . " Step 2 - Update Database Tables");	
			
			include('_db.php');
			
			$str = "<?PHP\n\n// Connect to the database\n\n\$server = \"" . $server . "\";\n\$dbuser = \"" . $dbuser . "\";\n\$dbpass = \"" . $dbpass . "\";\n\$dbname = \"" . $dbname . "\";\ndefine('DBTABLEPREFIX', '" . $DBTABLEPREFIX . "');\ndefine('USERSDBTABLEPREFIX', '" . $USERSDBTABLEPREFIX . "');\n\n\$connect = mysql_connect(\$server,\$dbuser,\$dbpass);\n\n//display error if connection fails\nif (\$connect==FALSE) {\n   print 'Unable to connect to database: '.mysql_error();\n   exit;\n}\n\nmysql_select_db(\$dbname); // select database\n\n?>";
		
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
					<h2><span class=\"iconText38px\"><img src=\"themes/installer/icons/table_38px.png\" alt=\"Create Tables\" /></span> <span class=\"iconText38px\">Update Database Tables</span></h2>
					Press Next to update the database tables.
					<br /><br />
					<a href=\"update.php?step=3\" class=\"button\">Next</a>";
			break;
		case 3:
			$page->setTemplateVar("PageTitle", UPDATER_SCRIPT_NAME . " Step 3 - Finish");	
			
			include('_db.php');
			
			// Update our Database Tables			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "addresses` 
					CHANGE `addresses_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `addresses_order_id` `order_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `addresses_type` `type` TINYINT( 1 ) NOT NULL DEFAULT '0',
					CHANGE `addresses_first_name` `first_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_last_name` `last_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_company` `company` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_email_address` `email_address` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_street_1` `street_1` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_street_2` `street_2` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_city` `city` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_country` `country` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_state` `state` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_zip` `zip` MEDIUMINT( 15 ) NOT NULL DEFAULT '0',
					CHANGE `addresses_day_phone` `day_phone` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_day_phone_ext` `day_phone_ext` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_night_phone` `night_phone` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_night_phone_ext` `night_phone_ext` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `addresses_fax` `fax` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL";
			$result = mysql_query($sql);
			checkresult($result, $sql, "addresses");	
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "config` 
					CHANGE `config_name` `name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `config_value` `value` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ";
			$result = mysql_query($sql);
			checkresult($result, $sql, "config");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "coupons` 
					CHANGE `coupons_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `coupons_name` `name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `coupons_code` `code` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `coupons_discount` `discount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `coupons_discount_percentage` `discount_percentage` TINYINT( 2 ) NOT NULL DEFAULT '0'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "coupons");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "creditcards` 
					CHANGE `creditcards_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `creditcards_user_id` `user_id` MEDIUMINT( 11 ) NOT NULL DEFAULT '0',
					CHANGE `creditcards_name_on_card` `name_on_card` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_card_type` `card_type` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_card_number` `card_number` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '0',
					CHANGE `creditcards_exp_month` `exp_month` MEDIUMINT( 2 ) NOT NULL DEFAULT '0',
					CHANGE `creditcards_exp_year` `exp_year` MEDIUMINT( 4 ) NOT NULL DEFAULT '0',
					CHANGE `creditcards_card_sid` `card_sid` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `creditcards_bank_name` `bank_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_bank_number` `bank_number` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_trans_signature` `trans_signature` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_trans_result` `trans_result` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_trans_code` `trans_code` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_trans_timestamp` `trans_timestamp` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `creditcards_trans_tried` `trans_tried` SMALLINT( 5 ) NOT NULL DEFAULT '0'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "creditcards");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "dist` 
					CHANGE `dist_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `dist_name` `name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `dist_phone_number` `phone_number` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `dist_website` `website` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `dist_cust_num` `cust_num` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `dist_address` `address` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `dist_sales_rep` `sales_rep` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `dist_sales_rep_ext` `sales_rep_ext` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ";
			$result = mysql_query($sql);
			checkresult($result, $sql, "dist");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "models` 
					CHANGE `models_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `models_name` `name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `models_description` `description` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `models_base_price` `base_price` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `models_base_weight` `base_weight` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `models_base_profit` `base_profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `models_discount` `discount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `models_discount_percentage` `discount_percentage` TINYINT( 2 ) NOT NULL DEFAULT '0',
					CHANGE `models_discount_description` `discount_description` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `models_image_full` `image_full` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `models_image_thumb` `image_thumb` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ";
			$result = mysql_query($sql);
			checkresult($result, $sql, "models");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "orders` 
					CHANGE `orders_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `orders_user_id` `user_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `orders_tech_user_id` `tech_user_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `orders_user_ip` `user_ip` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_datetime` `datetime` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '0',
					CHANGE `orders_shipping` `shipping` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_shipping_price` `shipping_price` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_tax` `tax` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_rush_fee` `rush_fee` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_items_total` `items_total` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_price` `price` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_discount` `discount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_coupon_name` `coupon_name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_coupon_code` `coupon_code` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_coupon_discount` `coupon_discount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `orders_coupon_discount_percentage` `coupon_discount_percentage` TINYINT( 2 ) NOT NULL DEFAULT '0',
					CHANGE `orders_status` `status` TINYINT( 1 ) NOT NULL DEFAULT '0',
					CHANGE `orders_tracking` `tracking` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_shipped_by` `shipped_by` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_date_shipped` `date_shipped` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_comments` `comments` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_serials` `serials` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_os_key` `os_key` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL
					ADD `txn_id` varchar(250) NOT NULL AFTER `comments`";
			$result = mysql_query($sql);
			checkresult($result, $sql, "orders");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "orders_creditcards` 
					CHANGE `orders_creditcards_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `orders_creditcards_order_id` `order_id` MEDIUMINT( 11 ) NOT NULL DEFAULT '0',
					CHANGE `orders_creditcards_name_on_card` `name_on_card` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_card_type` `card_type` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_card_number` `card_number` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL DEFAULT '0',
					CHANGE `orders_creditcards_exp_month` `exp_month` MEDIUMINT( 2 ) NOT NULL DEFAULT '0',
					CHANGE `orders_creditcards_exp_year` `exp_year` MEDIUMINT( 4 ) NOT NULL DEFAULT '0',
					CHANGE `orders_creditcards_card_sid` `card_sid` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `orders_creditcards_bank_name` `bank_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_bank_number` `bank_number` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_trans_signature` `trans_signature` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_trans_result` `trans_result` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_trans_code` `trans_code` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_trans_timestamp` `trans_timestamp` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `orders_creditcards_trans_tried` `trans_tried` SMALLINT( 5 ) NOT NULL DEFAULT '0'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "orders_creditcards");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "partcats` 
					CHANGE `partcats_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `partcats_name` `name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `partcats_description` `description` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `partcats_list_type` `list_type` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `partcats_on_slider` `on_slider` TINYINT( 1 ) NOT NULL DEFAULT '0',
					CHANGE `partcats_on_build_list` `on_build_list` TINYINT( 1 ) NOT NULL DEFAULT '0',
					CHANGE `partcats_image` `image` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `partcats_sort_order` `sort_order` MEDIUMINT( 8 ) NOT NULL DEFAULT '0'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "partcats");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "parts` 
					CHANGE `parts_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `parts_name` `name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_type` `type` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_dist` `dist` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `parts_item_num` `item_num` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_description` `description` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_qty` `qty` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `parts_sort` `sort` MEDIUMINT( 3 ) NOT NULL DEFAULT '999',
					CHANGE `parts_price` `price` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `parts_shipping_costs` `shipping_costs` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `parts_profit` `profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `parts_weight` `weight` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `parts_models` `models` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_default_on_models` `default_on_models` TEXT CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_image_full` `image_full` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_image_thumb` `image_thumb` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `parts_active` `active` SMALLINT( 1 ) NOT NULL DEFAULT '1'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "parts");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "systems` 
					CHANGE `systems_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `systems_order_id` `order_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `systems_model_id` `model_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `systems_qty` `qty` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `systems_weight` `weight` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `systems_price` `price` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `systems_discount` `discount` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "systems");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "systemparts` 
					CHANGE `systemparts_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `systemparts_system_id` `system_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `systemparts_part_id` `part_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `systemparts_part_cat_id` `part_cat_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `systemparts_part_name` `part_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `systemparts_weight` `weight` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `systemparts_price` `price` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `systemparts_shipping_costs` `shipping_costs` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00',
					CHANGE `systemparts_profit` `profit` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00'";
			$result = mysql_query($sql);
			checkresult($result, $sql, "systemparts");
			
			$sql = "ALTER TABLE `" . USERSDBTABLEPREFIX . "users` 
					CHANGE `users_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `users_username` `username` VARCHAR( 255 ) NOT NULL ,
					CHANGE `users_password` `password` VARCHAR( 255 ) NOT NULL ,
					CHANGE `users_first_name` `first_name` VARCHAR( 50 ) NOT NULL ,
					CHANGE `users_last_name` `last_name` VARCHAR( 50 ) NOT NULL ,
					CHANGE `users_email_address` `email_address` VARCHAR( 255 ) NOT NULL ,
					CHANGE `users_website` `website` VARCHAR( 255 ) NOT NULL ,
					CHANGE `users_signup_date` `signup_date` INT( 11 ) NULL DEFAULT NULL ,
					CHANGE `users_notes` `notes` TEXT NOT NULL ,
					CHANGE `users_user_level` `user_level` TINYINT( 1 ) NOT NULL DEFAULT '0',
					CHANGE `users_active` `active` TINYINT( 1 ) NOT NULL DEFAULT '1',
					CHANGE `users_language` `language` VARCHAR( 5 ) NOT NULL DEFAULT 'en';";
			$result = mysql_query($sql);
			checkresult($result, $sql, "users");
			
			$sql = "ALTER TABLE `" . DBTABLEPREFIX . "useraddresses` 
					CHANGE `useraddresses_id` `id` MEDIUMINT( 8 ) NOT NULL AUTO_INCREMENT ,
					CHANGE `useraddresses_user_id` `user_id` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
					CHANGE `useraddresses_type` `type` TINYINT( 1 ) NOT NULL DEFAULT '0',
					CHANGE `useraddresses_first_name` `first_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_last_name` `last_name` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_company` `company` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_street_1` `street_1` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_street_2` `street_2` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_city` `city` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_country` `country` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_state` `state` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_zip` `zip` MEDIUMINT( 15 ) NOT NULL DEFAULT '0',
					CHANGE `useraddresses_day_phone` `day_phone` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_day_phone_ext` `day_phone_ext` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_night_phone` `night_phone` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_night_phone_ext` `night_phone_ext` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL ,
					CHANGE `useraddresses_fax` `fax` VARCHAR( 25 ) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL";
			$result = mysql_query($sql);
			checkresult($result, $sql, "useraddresses");
		
			// Print this page
			$page_content = "
					<h2>Update Database Tables Results</h2>";
			
			if ($totalfailure == 1) {
				$page_content .= "
					<span class=\"actionFailed\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/delete_20px.png\" alt=\"Action Failed\" /></span> <span class=\"iconText20px\">Unable to update database tables.</span></span>";
			}
			else {
				$page_content .= "
					<span class=\"actionSucceeded\"><span class=\"iconText20px\"><img src=\"themes/installer/icons/check_20px.png\" alt=\"Action Succeeded\" /></span> <span class=\"iconText20px\">Successfully updated database tables.</span></span>";
			}
			
			$page_content .= "
					<br /><br />
					<h2>Finishing Up</h2>
					Update is now complete, before using the system please make sure and delete this file (update.php) so that it cannot be reused by someone else.
					<br /><br />
					<a href=\"index.php\" class=\"button\">Finish</a>";
			break;	
	}
	
	// Send out the content
	$page->setTemplateVar("PageContent", $page_content);	
	
	include "themes/installer/updatertemplate.php";
?>