<? 
/***************************************************************************
 *                               ajax.php
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
	include 'includes/header.php';
	
	$actual_id = parseurl($_GET['id']);
	$actual_action = parseurl($_GET['action']);
	$actual_value = parseurl($_GET['value']);
	$actual_type = parseurl($_GET['type']);
	$actual_showInactiveParts = parseurl($_GET['showInactiveParts']);
	
	//================================================
	// Main updater and get functions
	//================================================
	// Update an item in a DB table
	if ($actual_action == "updateitem") {
		$item = parseurl($_GET['item']);
		$table = parseurl($_GET['table']);
		$tableabrev = ($table == "categories") ? "cat" : $table;
		$updateto = ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") ? strtotime(keeptasafe($_REQUEST['value'])) : keeptasafe($_REQUEST['value']);
		
		$sql = "UPDATE `" . DBTABLEPREFIX . $table . "` SET " . $tableabrev . "_" . $item ." = '$updateto' WHERE " . $tableabrev . "_id = '" . $actual_id . "'";
		
		// Only admins or Mods should be able to get whatever they want things
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			$result = mysql_query($sql);
			echo stripslashes($updateto);	
		}		
		else {
			// Run checks to verify access rights
		 	$authorized = 0;
			
			if ($table == "users" && $item == "language") { $authorized = 1; }
			if ($table == "systems" && $item == "qty") { $authorized = 1; }
			
			if ($authorized) {
				$result = mysql_query($sql);
				echo stripslashes($updateto);
			}
		}			
	}
	// Get an item from a DB table
	elseif ($actual_action == "getitem") {
		$item = parseurl($_GET['item']);
		$table = parseurl($_GET['table']);
		$tableabrev = ($table == "categories") ? "cat" : $table;
		$sqlrow = $tableabrev . "_" . $item;
		
		$sql = "SELECT " . $sqlrow . " FROM `" . DBTABLEPREFIX . $table . "` WHERE " . $tableabrev . "_id = '" . $actual_id . "'";
		$result = mysql_query($sql);
		
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			if ($table == "systems") {
				//Verify the user owns this order
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders` o, `" . DBTABLEPREFIX . "orders_products` op WHERE o.id = op.order_id AND op.id = '" . $actual_id . "'";
				$result2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($result2);
				
				if ($result2 && mysql_num_rows($result2) > 0) {
					// Check has passed, now make sure they can only see what we say they can
					if ($item == "total_price") {
						echo formatCurrency($row2['qty'] * ($row2['price'] - $row2['discount']));
					}
					else {
						echo $row[$sqlrow];
					}
				}
				mysql_free_result($result2);
			}
			if ($table == "invoices") {
				//Verify the user owns this order
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "invoices_systemparts` WHERE invoices_orders_products_invoice_id = '" . $actual_id . "'";
				$result2 = mysql_query($sql2);
				$invoiceSubTotal = 0;
				
				if ($result2 && mysql_num_rows($result2) > 0) {
					// Check has passed, now make sure they can only see what we say they can
					if ($item == "items_total") {
						while ($row2 = mysql_fetch_array($result2)) {
							$invoiceSubTotal += $row2['invoices_qty'] * ($row2['invoices_price'] + $row2['invoices_profit'] - $row2['invoices_discount']);
						}
						echo formatCurrency($invoiceSubTotal);
					}
					else {
						$row2 = mysql_fetch_array($result2);
						echo $row[$sqlrow];
					}
				}
				mysql_free_result($result2);
			}
			else {
				if ($item == "datetimestamp" || $item == "date_ordered" || $item == "date_shipped") { 
					$result =  (trim($row[$sqlrow]) != "") ? @gmdate('m/d/Y h:i A', $row[$sqlrow] + (3600 * '-5.00')) : ""; 
					echo $result;
				}
				elseif ($item == "items_total" || $item == "total_cost" || $item == "tax" || $item == "price") { 
					echo formatCurrency($row[$sqlrow]);
				}
				else { echo bbcode($row[$sqlrow]); }	
			}
		}
		else {
			if ($table == "orders") {
				//Verify the user owns this order
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $actual_id . "' AND user_id = '" . $_SESSION['userid'] . "'";
				$result2 = mysql_query($sql2);

				if ($result2 && mysql_num_rows($result2) > 0) {
					// Check has passed, now make sure they can only see what we say they can
					if ($item == "items_total" || $item == "tax" || $item == "price") {
						echo formatCurrency($row[$sqlrow]);
					}
					mysql_free_result($result2);
				}
			}
			if ($table == "orders_products") {
				//Verify the user owns this order
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders` o, `" . DBTABLEPREFIX . "orders_products` op WHERE o.id = op.order_id AND op.id = '" . $actual_id . "' AND o.user_id = '" . $_SESSION['userid'] . "'";
				$result2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($result2);
				
				if ($result2 && mysql_num_rows($result2) > 0) {
					// Check has passed, now make sure they can only see what we say they can
					if ($item == "qty") {
						echo $row[$sqlrow];
					}
					if ($item == "total_price") {
						echo formatCurrency($row2['qty'] * ($row2['price'] - $row2['discount']));
					}
					if ($item == "total_cost") {
						echo formatCurrency($row2['total_cost']);
					}
					mysql_free_result($result2);
				}
			}
		}
	}	
	// Delete a row from a DB table
	elseif ($actual_action == "deleteitem") {
		$table = parseurl($_GET['table']);
		
		// Do these before killing the table
		if ($table == "orders_products") {
			//Verify the user owns this order unless they are an admin
			$extraSQL = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? "" : " AND o.user_id = '" . $_SESSION['userid'] . "'";
			
			$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders` o, `" . DBTABLEPREFIX . "orders_products` op WHERE o.id = op.order_id AND op.id = '" . $actual_id . "'" . $extraSQL;
			$result2 = mysql_query($sql2);
			//echo $sql2 . "<br />";
				
			if ($result2 && mysql_num_rows($result2) > 0) {
				// Kill order product
				$sql = "DELETE FROM `" . DBTABLEPREFIX . "orders_products` WHERE id = '" . $actual_id . "'";
				$result = mysql_query($sql);
				//echo $sql . "<br />";
				
				// Kill order product features
				$sql = "DELETE FROM `" . DBTABLEPREFIX . "orders_products_features` WHERE product_id = '" . $actual_id . "'";
				$result = mysql_query($sql);
				//echo $sql . "<br />";
			
				mysql_free_result($result2);
			}
			updateOrder($_SESSION['userid'], $tempOrderID);
		}
		
		// Kill the chosen row in the chosen DB
		$sql = "DELETE FROM `" . DBTABLEPREFIX . $table . "` WHERE " . $table . "_id = '" . $actual_id . "'";
		$result = mysql_query($sql);
		
		// The tables below have foreign keys that need to be killed as well
		if ($table == "orders") {
			// Get a list of systems for this order, then cycle through and kill them
			$sql = "SELECT id FROM `" . DBTABLEPREFIX . "orders_products` WHERE order_id = '" . $actual_id . "'";
			$result = mysql_query($sql);
			
			while ($row = mysql_fetch_array($result)) {
				// Kill order product
				$sql2 = "DELETE FROM `" . DBTABLEPREFIX . "orders_products` WHERE id = '" . $row['id'] . "'";
				// Only admins or Mods should delete things
				if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
					$result2 = mysql_query($sql2);
				}
			
				// Kill order product features
				$sql2 = "DELETE FROM `" . DBTABLEPREFIX . "orders_products_features` WHERE product_id = '" . $row['id'] . "'";
				// Only admins or Mods should delete things
				if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
					$result2 = mysql_query($sql2);
				}
				mysql_free_result($result2);
			}
		}
	}
	
	//================================================
	// Update our distributors in the database
	//================================================
	// Post a dist
	elseif ($actual_action == "postdist") {
		$name = keeptasafe($_POST['newdistname']);	
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "dist` (`name`) VALUES ('" . $name . "')";
		$result = mysql_query($sql);

		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "dist` ORDER BY name ASC";
		$result = mysql_query($sql);
		
		$content = "";
		
		$x = 1; //reset the variable we use for our row colors	
			
		$content = "
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
						<tr>
							<td class=\"title1\" colspan=\"3\">
								<div class=\"floatRight\">
									<form name=\"newDistForm\" action=\"" . $PHP_SELF . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
										<input type=\"text\" name=\"newdistname\" />
										<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
									</form>
								</div>
								Distributors
							</td>
						</tr>							
						<tr class=\"title2\">
							<td><strong>Name</strong></td><td><strong>Phone Number</strong></td><td></td>
						</tr>";
		$distids = array();
		if (!$result || mysql_num_rows($result) == "0") { // No dists yet!
			$content .= "\n					<tr class=\"greenRow\">
												<td colspan=\"3\">There are no distributors in the database.</td>
											</tr>";	
		}
		else {	 // Print all our dists								
			while ($row = mysql_fetch_array($result)) {
				
				$content .=	"					
									<tr id=\"" . $row['id'] . "\" class=\"row" . $x . "\">
										<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
										<td>" . $row['phone_number'] . "</td>
										<td>
											<span class=\"center\"><a href=\"" . $menuvar['DISTRIBUTORS'] . "&action=editdist&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "DistSpinner', 'ajax.php?action=deleteitem&table=dist&id=" . $row['id'] . "', 'distributor', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Distributor\" /></a><span id=\"" . $row['id'] . "DistSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
										</td>
									</tr>";
				$distids[$row['id']] = $row['name'];					
				$x = ($x==2) ? 1 : 2;
			}
		}
		mysql_free_result($result);
			
		
		$content .=		"					</table>";
		$content .= "\n						<script type=\"text/javascript\">";
		
		$x = 1; //reset the variable we use for our highlight colors
		foreach($distids as $key => $value) {
			$content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=dist&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=dist&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updatedist&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=dist&item=name&id=" . $key . "'});";
			$x = ($x==2) ? 1 : 2;
		}
		
		$content .= "\n						</script>";	
		
		echo $content;
	}
	
	//================================================
	// Update our models in the database
	//================================================
	// Post a model
	elseif ($actual_action == "postmodel") {
		$name = keeptasafe($_POST['newmodelname']);	
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "models` (`models_name`) VALUES ('" . $name . "')";
		$result = mysql_query($sql);

		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` ORDER BY models_name ASC";
		$result = mysql_query($sql);
		
		$content = "";
		
		$x = 1; //reset the variable we use for our row colors	
			
		$content = "
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"4\">
										<div class=\"floatRight\">
											<form name=\"newModelForm\" action=\"" . $menuvar['MODELS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newmodelname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Models
									</td>
								</tr>							
								<tr class=\"title2\">
									<td></td><td><strong>Name</strong></td><td><strong>Description</strong></td><td></td>
								</tr>";
		$modelids = array();
		if (!$result || mysql_num_rows($result) == "0") { // No models yet!
			$content .= "\n					<tr class=\"greenRow\">
											<td colspan=\"4\">There are no models in the database.</td>
										</tr>";	
		}
		else {	 // Print all our models								
			while ($row = mysql_fetch_array($result)) {
				
				$content .=	"					
									<tr id=\"" . $row['models_id'] . "_row\" class=\"row" . $x . "\">
										<td><img src=\"" . $row['models_image_thumb'] . "\" alt=\"\" /></td>
										<td><div id=\"" . $row['models_id'] . "_text\">" . $row['models_name'] . "</div></td>
										<td>" . nl2br($row['models_description']) . "</td>
										<td>
											<span class=\"center\"><a href=\"" . $menuvar['MODELS'] . "&action=editmodel&id=" . $row['models_id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['models_id'] . "ModelSpinner', 'ajax.php?action=deleteitem&table=models&id=" . $row['models_id'] . "', 'model', '" . $row['models_id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Model\" /></a><span id=\"" . $row['models_id'] . "ModelSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
										</td>
									</tr>";
				$modelids[$row['models_id']] = $row['models_name'];					
				$x = ($x==2) ? 1 : 2;
			}
		}
		mysql_free_result($result);
			
		
		$content .=		"					</table>";
		$content .= "\n						<script type=\"text/javascript\">";
		
		$x = 1; //reset the variable we use for our highlight colors
		foreach($modelids as $key => $value) {
			$content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=models&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=models&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updatemodel&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=models&item=name&id=" . $key . "'});";
			$x = ($x==2) ? 1 : 2;
		}
			
		$content .= "\n						</script>";	
		
		echo $content;
	}
	
	//================================================
	// Update our coupons in the database
	//================================================
	// Post a coupon
	elseif ($actual_action == "postcoupons") {
		$name = keeptasafe($_POST['newCouponName']);	
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "coupons` (`name`) VALUES ('" . $name . "')";
		$result = mysql_query($sql);

		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "coupons` ORDER BY name ASC";
		$result = mysql_query($sql);
		
		$content = "";
		
		$x = 1; //reset the variable we use for our row colors	
			
		$content = "
					<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"4\">
										<div class=\"floatRight\">
											<form name=\"newCouponForm\" action=\"" . $menuvar['COUPONS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newCouponName\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Coupons
									</td>
								</tr>							
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Code</strong></td><td><strong>Discount</strong></td><td></td>
								</tr>";
			$couponsids = array();
			if (!$result || mysql_num_rows($result) == "0") { // No couponss yet!
				$content .= "\n					<tr class=\"greenRow\">
													<td colspan=\"4\">There are no coupons in the database.</td>
												</tr>";	
			}
			else {	 // Print all our couponss								
				while ($row = mysql_fetch_array($result)) {
					
					$content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_name\">$row[name]</div></td>
											<td><div id=\"" . $row['id'] . "_code\">" . $row['code'] . "</div></td>
											<td>" . $discount . "</td>
											<td>
												<span class=\"center\"><a href=\"" . $menuvar['COUPONS'] . "&action=editcoupons&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "couponsSpinner', 'ajax.php?action=deleteitem&table=coupons&id=" . $row['id'] . "', 'coupon', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete coupon\" /></a><span id=\"" . $row['id'] . "couponsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
											</td>
										</tr>";
					$couponsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$content .=		"					</table>";
			$content .= "\n						<script type=\"text/javascript\">";
			
			$x = 1; //reset the variable we use for our highlight colors
			foreach($couponsids as $key => $value) {
				$highlightColors .= ($x == 1) ? ",highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6'" : ",highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC'";
				$content .= "\n							new Ajax.InPlaceEditor('" . $key . "_name', 'ajax.php?action=updateitem&table=coupons&item=name&id=" . $key . "', {rows:1,cols:50" . $highlightColors . "});";
				$content .= "\n							new Ajax.InPlaceEditor('" . $key . "_code', 'ajax.php?action=updateitem&table=coupons&item=code&id=" . $key . "', {rows:1,cols:50" . $highlightColors . "});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$content .= "\n						</script>";	
		
		echo $content;
	}
	
	//================================================
	// Update our product categorys in the database
	//================================================
	// Post a partcat
	elseif ($actual_action == "postproductcats") {
		$name = keeptasafe($_POST['newproductcatsname']);	
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "productcats` (`name`) VALUES ('" . $name . "')";
		$result = mysql_query($sql);

		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "productcats` ORDER BY name ASC";
		$result = mysql_query($sql);
		
		$content = "";
		
		$x = 1; //reset the variable we use for our row colors	
			
		$content = "
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"4\">
									<div class=\"floatRight\">
										<form name=\"newProductcatsForm\" action=\"" . $menuvar['PRODUCTCATS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
											<input type=\"text\" name=\"newproductcatsname\" />
											<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
										</form>
									</div>
									Part Categories
								</td>
							</tr>							
							<tr class=\"title2\">
								<td></td><td><strong>Name</strong></td><td><strong>Description</strong></td><td></td>
							</tr>";
		$productcatsids = array();
		if (!$result || mysql_num_rows($result) == "0") { // No productcats yet!
			$content .= "\n					<tr class=\"greenRow\">
												<td colspan=\"4\">There are no productcats in the database.</td>
											</tr>";	
		}
		else {	 // Print all our productcats								
			while ($row = mysql_fetch_array($result)) {
				
				$content .=	"					
									<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
										<td><img src=\"" . $row['image'] . "\" alt=\"\" /></td>
										<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
										<td>" . nl2br($row['description']) . "</td>
										<td>
											<span class=\"center\"><a href=\"" . $menuvar['PRODUCTCATS'] . "&action=editproductcats&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "ProductcatsSpinner', 'ajax.php?action=deleteitem&table=productcats&id=" . $row['id'] . "', 'productcats', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Productcats\" /></a><span id=\"" . $row['id'] . "ProductcatsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
										</td>
									</tr>";
				$productcatsids[$row['id']] = $row['name'];					
				$x = ($x==2) ? 1 : 2;
			}
		}
		mysql_free_result($result);
				
			
		$content .=		"					</table>";
		$content .= "\n						<script type=\"text/javascript\">";
		
		$x = 1; //reset the variable we use for our highlight colors
		foreach($productcatsids as $key => $value) {
			$content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=productcats&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=productcats&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateproductcats&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=productcats&item=name&id=" . $key . "'});";
			$x = ($x==2) ? 1 : 2;
		}
		
		$content .= "\n						</script>";	
		
		echo $content;
	}
	
	//================================================
	// Add a new part feature to the database
	//================================================
	elseif ($actual_action == "postproductfeatures") {
		$name = keeptasafe($_POST['newproductfeaturesname']);	
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "products_features` (`name`) VALUES ('" . $name . "')";
		$result = mysql_query($sql);
		
		$content = printProductFeaturesTable();	
		
		echo $content;
	}
	
	//================================================
	// Add a new part to the database
	//================================================
	elseif ($actual_action == "postproducts") {
		$name = keeptasafe($_POST['newproductsname']);	
		
		$sql = "INSERT INTO `" . DBTABLEPREFIX . "products` (`name`) VALUES ('" . $name . "')";
		$result = mysql_query($sql);

		$content = printProductsTable(-1);	
		
		echo $content;
	}
	
	//================================================
	// Select parts for a certain model from the database
	//================================================
	elseif ($actual_action == "searchparts") {	
		$actual_id = ($actual_id == "" || !is_numeric($actual_id)) ? "-1" : $actual_id;	
		echo printPartsTable($actual_id);
	}
	
	//================================================
	// Print out our list of parts for a model
	//================================================
	elseif ($actual_action == "listModelParts") {
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "productcats` ORDER BY sort_order ASC";
		$result = mysql_query($sql);
		
		$content = "";
		
		$x = 1; //reset the variable we use for our row colors	
			
		$content = "
					<form name=\"frmCodeGenParts\" action=\"\" method=\"post\" onSubmit=\"return false;\">
						<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">Choose Your Parts</td>
							</tr>";
		$partsids = array();
		if (!$result || mysql_num_rows($result) == "0") { // No parts yet!
			$content .= "\n					<tr class=\"greenRow\">
												<td colspan=\"2\">There are no parts in the database for this model.</td>
											</tr>";	
		}
		else {	 // Print all our parts								
			while ($row = mysql_fetch_array($result)) {										
				$showInactivePartsSQL = ($actual_showInactiveParts == "1") ? "" : " AND active = '1'";	
				$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "products` WHERE type LIKE '%x" . $row['id'] . "x%' AND (models LIKE '%x" . $actual_id . "x%' OR models = '')" . $showInactivePartsSQL;
				$result2 = mysql_query($sql2);
				
				if ($result2 && mysql_num_rows($result2) > 0) {
					$content .= "\n										
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td>" . $row['name'] . "</td>
											<td>
												<select name=\"parts[" . $row['id'] . "]\">
													<option value=\"\">--Select One--</option>";							
					while ($row2 = mysql_fetch_array($result2)) {
						
						$content .= "\n											<option value=\"" . $row2['id'] . "\"" . testSelectedDefaultOnModels($actual_id, $row2['default_on_models']) . ">" . $row2['name'] . "</option>";
					}
					$content .= "\n				</select>
												<input type=\"checkbox\" name=\"page2part[" . $row['id'] . "]\" value=\"1\" /> Page 2 Part
											</td>
										</tr>";
					$x = ($x==2) ? 1 : 2;
				}
				mysql_free_result($result2);				
				
								
			}
		}
		mysql_free_result($result);
					
		$content .=		"					</table>
											<br /><br />
											<input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" onClick=\"new Ajax.Updater('codeContainer', 'ajax.php?action=genCode&id=" . $actual_id . "', {asynchronous:true, parameters:Form.serialize(frmCodeGenParts), evalScripts:true});\" />
										</form>";
		
		echo $content;
	}
	
	//================================================
	// Print out code for customize buttons
	//================================================
	elseif ($actual_action == "genCode") {
		$productsArray = $_POST['parts'];
		$page2partArray = $_POST['page2part'];
		
		$content = "
					<textarea name=\"code\" cols=\"80\" rows=\"15\">
<form name=\"ftsssCustomizeForm\" action=\"" . $ss_config['ftsss_store_url'] . "/" . $menuvar['CUSTOMIZE'] . "\" method=\"post\">
	<input type=\"hidden\" name=\"ModelID\" value=\"" . $actual_id . "\" />\n";
		
		foreach ($productsArray as $productCatID => $selectedPartID) {
			$pagenumber = ($page2partArray[$productCatID] == "1") ? "2" : "";
			if ($selectedPartID != "") {
				$content .= "\n	<input type=\"hidden\" name=\"Defaults" . $pagenumber . "[" . $productCatID . "]\" value=\"" . $selectedPartID . "\" />";	
			}
		}
		
		$content .= "	
	<span class=\"center\"><input type=\"image\" name=\"orderButton\" value=\"1\" class=\"imagebutton\" src=\"configureb.jpg\" style=\"border: 0px;\" /></span>
</form>
					</textarea>";
		
		echo $content;
	}
	
	//================================================
	// Print out Profit vs Cost table
	//================================================
	elseif ($actual_action == "calculateProfitVsCost") {		
		$content = "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"4\">Profit vs Cost - " . $actual_id . "</td>
								</tr>";
			
		$x = 1;
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders_products` WHERE order_id = '" . $actual_id . "' ORDER BY name";
		$result = mysql_query($sql);
		
				
		$content .= "\n	
								<tr>
									<td class=\"title2\"><strong>Part name</strong></td><td class=\"title2\"><strong>Price</strong></td><td class=\"title2\"><strong>Shipping Costs</strong></td><td class=\"title2\"><strong>Profit</strong></td>
								</tr>";
								
								
		if (!$result || mysql_num_rows($result) == 0) { // No parts yet!
			$content .= "
										<tr class=\"greenRow\">
											<td colspan=\"4\">There are no products in the database for this order.</td>
										</tr>";	
		}
		else {	 // Print all our parts							
			while ($row = mysql_fetch_array($result)) {
						
				$content .= "\n									
										<tr class=\"row" . $x . "\">
											<td>" . $row['name'] . "</td><td>" . formatCurrency($row['price']) . "</td><td>" . formatCurrency($row['shipping_costs']) . "</td><td>" . formatCurrency($row['profit']) . "</td>
										</tr>";
										
				$totalPartsPrice += $row['price'];
				$totalPartsShippingCosts += $row['shipping_costs'];
				$totalPartsProfit += $row['profit'];
				$x = ($x==2) ? 1 : 2;
			}
			mysql_free_result($result);
		}
					
		$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "orders` WHERE id = '" . $actual_id . "'";
		$result2 = mysql_query($sql2);
		
		while ($row2 = mysql_fetch_array($result2)) {
			$orderPrice = $row2['items_total'];
			$orderDiscount = $row2['discount'];
		}
		mysql_free_result($result2);
		
			$content .= "				
									<tr>
										<td class=\"title1\" colspan=\"4\">Profit vs Cost Analysis</td>
									</tr>
									<tr>
										<td class=\"title2\"><strong>Cost of Parts: </strong></td><td class=\"row1\" colspan=\"3\">" . formatCurrency(($totalPartsPrice + $totalPartsShippingCosts)) . "</td>
									</tr>							
									<tr>
										<td class=\"title2\"><strong>Profit on Parts: </strong></td><td class=\"row2\" colspan=\"3\">" . formatCurrency($totalPartsProfit) . "</td>
									</tr>					
									<tr>
										<td class=\"title2\"><strong>Total Profit: </strong></td><td class=\"row1\" colspan=\"3\">" . formatCurrency($totalPartsProfit) . "</td>
									</tr>							
									<tr>
										<td class=\"title2\"><strong>Cost of Order: </strong></td><td class=\"row2\" colspan=\"3\">" . formatCurrency($orderPrice) . "</td>
									</tr>							
									<tr>
										<td class=\"title2\"><strong>Order Discount: </strong></td><td class=\"row2\" colspan=\"3\">" . formatCurrency($orderDiscount) . "</td>
									</tr>								
									<tr>
										<td class=\"title2\"><strong>Total Profit on Order: </strong></td><td class=\"row2\" colspan=\"3\">" . formatCurrency(($orderPrice - $orderDiscount - $totalPartsPrice - $totalPartsShippingCosts))  . "</td>
									</tr>	
								</table>";
		
		echo $content;
	}
	
	//================================================
	// Outputs the form to the lytebox popup
	//================================================
	if ($actual_action == "showCreditCardEdit") {	
		$content = "";
		
		$content .= "
					<form id=\"userEditForm\" class=\"plasmaForm\" action=\"\" method=\"post\" onSubmit=\"return false;\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Credit Card Information</td>
								</tr>";
		// Pull user's billing address and rip it out of the array
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $userID . "'";
		$result2 = mysql_query($sql2);
					
		if ($result2 && mysql_num_rows($result2) > 0) {
			while ($row2 = mysql_fetch_array($result2)) {	
				extract($row2);	
			}
		}			
		$content .= "					
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>Name as it Appears on Card</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"name_on_card\" id=\"name_on_card\" value=\"" . $name_on_card . "\" size=\"60\" /></td>
								</tr>				
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>Card Type</strong></td>
									<td class=\"row1\">" . createDropdown("ccType", "card_type", $card_type, "") . "</td>
								</tr>			
								<tr>
									<td class=\"title2\" style=\"width: 200px;\"><strong>Card Number</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"card_number\" id=\"card_number\" value=\"" . maskCCNumber($card_number) . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Security ID on the Back of the Card</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"card_sid\" id=\"card_sid\" value=\"" . maskCCSID($card_sid) . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Expiration Date</strong></td>
									<td class=\"row1\">" . createDropdown("ccExpMonth", "exp_month", $exp_month, "") . createDropdown("ccExpYear", "exp_year", $exp_year, "") . "</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Bank Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"bank_name\" id=\"bank_name\" size=\"60\" value=\"" . $bank_name . "\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Bank phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"bank_number\" id=\"bank_number\" size=\"60\" value=\"" . $bank_number . "\" /></td>
								</tr>
							</table>
						<br />
						<input type=\"button\" name=\"submit\" value=\"Save Settings\" onClick=\"ajaxSubmitCreditCardEdit(document.forms[0], '1', '" . $userID . "');\" /> 
					</form>
					<br />
					<span id=\"creditCardEditSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span><div id=\"updateMe\"></div>";
		
		$page->setTemplateVar('PageContent', $content);
		
		include "themes/default/popUpTemplate.php";
	}

	//================================================
	// Updates the DB using the values from the 
	// lytebox popup form 
	//================================================
	if ($actual_action == "submitCreditCardEdit") {	
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		$postname_on_card = keeptasafe($_POST['name_on_card']);
		$postcard_type = keeptasafe($_POST['card_type']);
		$postcard_number = keeptasafe($_POST['card_number']);
		$postexp_month = keepsafe($_POST['exp_month']);
		$postexp_year = keepsafe($_POST['exp_year']);
		$postcard_sid = keepsafe($_POST['card_sid']);
		$postbank_name = keeptasafe($_POST['bank_name']);
		$postbank_number = keeptasafe($_POST['bank_number']);
				
		// Update Credit Card
		$updateCardNumber = (!stristr($postcard_number, '*')) ? ", card_number='" . $postcard_number . "'" : ""; // If a user didn't change the masked value then don't change the value in the DB
		$updateCardNumber .= (!stristr($postcard_sid, '*')) ? ", card_sid='" . $postcard_sid . "'" : ""; // If a user didn't change the masked value then don't change the value in the DB
				
		$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "creditcards` WHERE user_id='" . $userID . "';";
		$result = mysql_query($sql);
				
		if ($result && mysql_num_rows($result) == 0) {
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "creditcards` (`user_id`) VALUES ('" . $userID . "');";
			$result = mysql_query($sql);
		}
		$sql = "UPDATE `" . DBTABLEPREFIX . "creditcards` SET `name_on_card`='" . $postname_on_card . "', `card_type`='" . $postcard_type . "', `exp_month`='" . $postexp_month . "', `exp_year`='" . $postexp_year . "', `bank_name`='" . $postbank_name . "', `bank_number`='" . $postbank_number . "'" . $updateCardNumber . " WHERE `user_id`='" . $userID . "';";
		$result = mysql_query($sql);
		
		if ($result) { echo "<span class=\"result-success\">Changes Saved. You may now close this popup.</span>"; }
		else { echo "<span class=\"result-failure\">Your changes could not be saved. Please try again.</span>"; }
	}
	
	//================================================
	// Remove credit card details
	//================================================
	elseif ($actual_action == "deleteCreditCardInfo") {		
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "creditcards` WHERE `user_id`='" . $userID . "';";
		$result = mysql_query($sql);
	}
	
	//================================================
	// Echo a Credit Card
	//================================================
	elseif ($actual_action == "updateCreditCardHolder") {		
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		echo getUserPanelUserCreditCard($userID);
	}
	
	//================================================
	// Outputs the form to the lytebox popup for the userpanel
	//================================================
	if ($actual_action == "showUserPanelUserAddressEdit") {	
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		$content = "";
		
		$content .= "
					<form id=\"userEditForm\" class=\"plasmaForm\" action=\"\" method=\"post\" onSubmit=\"return false;\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Billing Information</td>
								</tr>";
		// Pull user's billing address and rip it out of the array
		$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $userID . "' AND type='" . BILL_ADDRESS . "'";
		$result2 = mysql_query($sql2);
					
		if ($result2 && mysql_num_rows($result2) > 0) {
			while ($row2 = mysql_fetch_array($result2)) {	
				extract($row2);	
			}
		}			
		$billShowStates = ($country != "USA") ? " style=\"display: none;\"" : "";
		$billShowStates2 = ($country != "USA") ? "" : " style=\"display: none;\"";
				
		$content .= "				
								<tr>
									<td class=\"title2\"><strong>First Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_first_name\" id=\"Bill_first_name\" value=\"" . $first_name . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Last Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_last_name\" id=\"Bill_last_name\" value=\"" . $last_name . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Company Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_company\" id=\"Bill_company\" value=\"" . $company . "\" size=\"60\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Address</strong></td>
									<td class=\"row1\">
										<input type=\"text\" class=\"required\" name=\"Bill_street_1\" id=\"Bill_street_1\" size=\"60\" value=\"" . $street_1 . "\" /><br />
										<input type=\"text\" name=\"Bill_street_2\" id=\"Bill_street_2\" size=\"60\" value=\"" . $street_2 . "\" />
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>City</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_city\" id=\"Bill_city\" value=\"" . $city . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Country</strong></td>
									<td class=\"row1\">
										" . createDropdown("countries", "Bill_country", $country, "showStateDropBox(this, 'bill')") . "
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>State / Province</strong></td>
									<td class=\"row1\">
										<span id=\"billStateRow\"" . $billShowStates . ">
											" . createDropdown("states", "Bill_state", $state, "") . "
										</span>
										<span id=\"billStateRow2\"" . $billShowStates2 . "><input type=\"text\" name=\"Bill_state2\" id=\"Bill_state2\" value=\"" . $state . "\" size=\"60\" /></span>
									</td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Postal Code</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Bill_zip\" id=\"Bill_zip\" value=\"" . $zip . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Primary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_day_phone\" id=\"Bill_day_phone\" value=\"" . $day_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Bill_day_phone_ext\" id=\"Bill_day_phone_ext\" value=\"" . $day_phone_ext . "\" size=\"6\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Secondary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_night_phone\" id=\"Bill_night_phone\" value=\"" . $night_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Bill_night_phone_ext\" id=\"Bill_night_phone_ext\" value=\"" . $night_phone_ext . "\" size=\"6\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Fax</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Bill_fax\" id=\"Bill_fax\" value=\"" . $fax . "\" size=\"60\" /></td>
								</tr>	
								</table>
								<br /><br />
								
								<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">
										<div class=\"floatRight\">
											<input type=\"checkbox\" name=\"sameAsBilling\" value=\"1\" onClick=\"sameAsBillingCheck(this);\" /> Same as Billing
										</div>										
										Shipping Information
									</td>
								</tr>";
								
		// Pull user's billing address and rip it out of the array
		$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $userID . "' AND type='" . SHIP_ADDRESS . "'";
		$result2 = mysql_query($sql2);
					
		if ($result2 && mysql_num_rows($result2) > 0) {
			while ($row2 = mysql_fetch_array($result2)) {	
				extract($row2);	
			}
		}			
		$shipShowStates = ($country != "USA") ? " style=\"display: none;\"" : "";
		$shipShowStates2 = ($country != "USA") ? "" : " style=\"display: none;\"";
				
		$content .= "			
								<tr>
									<td class=\"title2\"><strong>First Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_first_name\" id=\"Ship_first_name\" value=\"" . $first_name . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Last Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_last_name\" id=\"Ship_last_name\" value=\"" . $last_name . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Company Name</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_company\" id=\"Ship_company\" value=\"" . $company . "\" size=\"60\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Address</strong></td>
									<td class=\"row1\">
										<input type=\"text\" class=\"required\" name=\"Ship_street_1\" id=\"Ship_street_1\" size=\"60\" value=\"" . $street_1 . "\" /><br />
										<input type=\"text\" name=\"Ship_street_2\" id=\"Ship_street_2\" size=\"60\" value=\"" . $street_2 . "\" />
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>City</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_city\" id=\"Ship_city\" value=\"" . $city . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Country</strong></td>
									<td class=\"row1\">
										" . createDropdown("countries", "Ship_country", $country, "showStateDropBox(this, 'ship')") . "
									</td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>State / Province</strong></td>
									<td class=\"row1\">
										<span id=\"shipStateRow\"" . $shipShowStates . ">
											" . createDropdown("states", "Ship_state", $state, "") . "
										</span>
										<span id=\"shipStateRow2\"" . $shipShowStates2 . "><input type=\"text\" name=\"Ship_state2\" id=\"Ship_state2\" value=\"" . $state . "\" size=\"60\" /></span>
									</td>
								</tr>		
								<tr>
									<td class=\"title2\"><strong>Postal Code</strong></td>
									<td class=\"row1\"><input type=\"text\" class=\"required\" name=\"Ship_zip\" id=\"Ship_zip\" value=\"" . $zip . "\" size=\"60\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Primary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_day_phone\" id=\"Ship_day_phone\" value=\"" . $day_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Ship_day_phone_ext\" id=\"Ship_day_phone_ext\" value=\"" . $day_phone_ext . "\" size=\"6\" /></td>
								</tr>	
								<tr>
									<td class=\"title2\"><strong>Secondary Phone Number</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_night_phone\" id=\"Ship_night_phone\" value=\"" . $night_phone . "\" size=\"60\" /> ext. <input type=\"text\" name=\"Ship_night_phone_ext\" id=\"Ship_night_phone_ext\" value=\"" . $night_phone_ext . "\" size=\"6\" /></td>
								</tr>
								<tr>
									<td class=\"title2\"><strong>Fax</strong></td>
									<td class=\"row1\"><input type=\"text\" name=\"Ship_fax\" id=\"Ship_fax\" value=\"" . $fax . "\" size=\"60\" /></td>
								</tr>
							</table>
						<br />
						<input type=\"button\" name=\"submit\" value=\"Save Settings\" onClick=\"ajaxSubmitUserPanelUserAddressEdit(document.forms[0], '1', '" . $userID . "');\" /> 
					</form>
					<br />
					<span id=\"userPanelUserAddressEditSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span><div id=\"updateMe\"></div>";
		
		$page->setTemplateVar('PageContent', $content);
		
		include "themes/default/popUpTemplate.php";
	}

	//================================================
	// Updates the DB using the values from the 
	// lytebox popup form 
	//================================================
	if ($actual_action == "submitUserPanelUserAddressEdit") {	
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		$Ship_first_name = keeptasafe($_POST['Ship_first_name']);
		$Ship_last_name = keeptasafe($_POST['Ship_last_name']);
		$Ship_company = keeptasafe($_POST['Ship_company']);				
		$Ship_street_1 = keeptasafe($_POST['Ship_street_1']);
		$Ship_street_2 = keeptasafe($_POST['Ship_street_2']);
		$Ship_city = keeptasafe($_POST['Ship_city']);
		$Ship_country = keeptasafe($_POST['Ship_country']);
		$Ship_state = keeptasafe($_POST['Ship_state']);
		$Ship_state2 = keeptasafe($_POST['Ship_state2']);
		$Ship_state = ($Ship_country == "USA") ? $Ship_state : $Ship_state2;
		$Ship_zip = keeptasafe($_POST['Ship_zip']);
		$Ship_day_phone = keeptasafe($_POST['Ship_day_phone']);
		$Ship_day_phone_ext = keeptasafe($_POST['Ship_day_phone_ext']);
		$Ship_night_phone = keeptasafe($_POST['Ship_night_phone']);
		$Ship_night_phone_ext = keeptasafe($_POST['Ship_night_phone_ext']);
		$Ship_fax = keeptasafe($_POST['Ship_fax']);
		
		$Bill_first_name = keeptasafe($_POST['Bill_first_name']);
		$Bill_last_name = keeptasafe($_POST['Bill_last_name']);
		$Bill_company = keeptasafe($_POST['Bill_company']);
		$Bill_street_1 = keeptasafe($_POST['Bill_street_1']);
		$Bill_street_2 = keeptasafe($_POST['Bill_street_2']);
		$Bill_city = keeptasafe($_POST['Bill_city']);
		$Bill_country = keeptasafe($_POST['Bill_country']);
		$Bill_state = keeptasafe($_POST['Bill_state']);
		$Bill_state2 = keeptasafe($_POST['Bill_state2']);
		$Bill_state = ($Bill_country == "USA") ? $Bill_state : $Bill_state2;
		$Bill_zip = keeptasafe($_POST['Bill_zip']);
		$Bill_day_phone = keeptasafe($_POST['Bill_day_phone']);
		$Bill_day_phone_ext = keeptasafe($_POST['Bill_day_phone_ext']);
		$Bill_night_phone = keeptasafe($_POST['Bill_night_phone']);
		$Bill_night_phone_ext = keeptasafe($_POST['Bill_night_phone_ext']);
		$Bill_fax = keeptasafe($_POST['Bill_fax']);
				
		// Update our Billing Address
		$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $userID . "' AND `type`='0';";
		$result = mysql_query($sql);
				
		if ($result && mysql_num_rows($result) == 0) {
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "useraddresses` (`user_id`, `type`) VALUES ('" . $userID . "', '0');";
			$result = mysql_query($sql);
		}
		$sql = "UPDATE `" . DBTABLEPREFIX . "useraddresses` SET `first_name`='" . $Bill_first_name . "', `last_name`='" . $Bill_last_name . "', `company`='" . $Bill_company . "', `street_1`='" . $Bill_street_1 . "', `street_2`='" . $Bill_street_2 . "', `city`='" . $Bill_city . "', `country`='" . $Bill_country . "', `state`='" . $Bill_state . "', `zip`='" . $Bill_zip . "', `day_phone`='" . $Bill_day_phone . "', `day_phone_ext`='" . $Bill_day_phone_ext . "', `night_phone`='" . $Bill_night_phone . "', `night_phone_ext`='" . $Bill_night_phone_ext . "', `fax`='" . $Bill_fax . "' WHERE `user_id`='" . $userID . "' AND `type`='0';";
		$result = mysql_query($sql);
		
		// Update our Shipping Address
		$sql = "SELECT user_id FROM `" . DBTABLEPREFIX . "useraddresses` WHERE user_id='" . $userID . "' AND `type`='1';";
		$result = mysql_query($sql);
				
		if ($result && mysql_num_rows($result) == 0) {
			$sql = "INSERT INTO `" . DBTABLEPREFIX . "useraddresses` (`user_id`, `type`) VALUES ('" . $userID . "', '1');";
			$result = mysql_query($sql);
		}
		$sql = "UPDATE `" . DBTABLEPREFIX . "useraddresses` SET `first_name`='" . $Ship_first_name . "', `last_name`='" . $Ship_last_name . "', `company`='" . $Ship_company . "', `street_1`='" . $Ship_street_1 . "', `street_2`='" . $Ship_street_2 . "', `city`='" . $Ship_city . "', `country`='" . $Ship_country . "', `state`='" . $Ship_state . "', `zip`='" . $Ship_zip . "', `day_phone`='" . $Ship_day_phone . "', `day_phone_ext`='" . $Ship_day_phone_ext . "', `night_phone`='" . $Ship_night_phone . "', `night_phone_ext`='" . $Ship_night_phone_ext . "', `fax`='" . $Ship_fax . "' WHERE `user_id`='" . $userID . "' AND `type`='1';";
		$result = mysql_query($sql);
		
		if ($result) { echo "<span class=\"result-success\">Changes Saved. You may now close this popup.</span>"; }
		else { echo "<span class=\"result-failure\">Your changes could not be saved. Please try again.</span>"; }
	}
	
	//================================================
	// Echo a User Address
	//================================================
	elseif ($actual_action == "updateUserPanelUserAddressHolder") {		
		$userID = ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) ? $actual_id : $_SESSION['userid'];
		echo getUserPanelUserAddress($userID, $actual_type);
	}
	
	//================================================
	// Outputs the form to the lytebox popup
	//================================================
	if ($actual_action == "showCreateUserEdit") {	
		$content = "";
		
		$content .= "
						<form id=\"createUserForm\" class=\"plasmaForm\" action=\"" . $menuvar['SETTINGS'] . "\" method=\"post\" onSubmit=\"return false;\">
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
							<script type=\"text/javascript\">
								var valid = new Validation('createUserForm', {immediate : true, useTitles:true});
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
							</script>
						<br />
						<input type=\"button\" name=\"submit\" value=\"Create User\" onClick=\"ajaxSubmitCreateUser(document.forms[0], '" . $actual_id . "', '1');\" /> 
					</form>
					<br />
					<span id=\"createUserFormSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span><div id=\"updateMe\"></div>";
		
		$page->setTemplateVar('PageContent', $content);
		
		include "themes/default/popUpTemplate.php";
	}

	//================================================
	// Updates the DB using the values from the 
	// lytebox popup form 
	//================================================
	if ($actual_action == "submitCreateUser") {	
		$current_time = time();	
		$postpassword = keepsafe($_POST['password1']);
		$postfirst_name = keepsafe($_POST['first_name']);
		$postlast_name = keepsafe($_POST['last_name']);
		$postemail_address = keepsafe($_POST['email_address']);
		$poston_email_list = keepsafe($_POST['on_email_list']);
		$poston_email_list = ($poston_email_list != 1) ? 0 : 1;
		
		$sql_email_check = mysql_query("SELECT email_address FROM `" . USERSDBTABLEPREFIX . "users` WHERE email_address='$postemail_address'");
		 
		$email_check = mysql_num_rows($sql_email_check);
		 
		if($email_check > 0){
			$content .= $T_FIX_ERRORS . "<br />";
			$content .= "This email address has already been used.<br />";
			echo "<span class=\"result-failure\">" . $content . "</span>";
		}
		else {
			//=====================================================
			// Everything has passed both error checks that we 
			// have done. It's time to create the account!
			//=====================================================
		
			$db_password = md5($postpassword);
			
			// generate SQL.
			$sql = "INSERT INTO `" . USERSDBTABLEPREFIX . "users` (first_name, last_name, email_address, password, on_email_list, signup_date) VALUES('" . $postfirst_name . "', '" . $postlast_name . "', '" . $postemail_address . "', '" . $db_password . "', '" . $poston_email_list . "', '" . $current_time . "')";
			$result = mysql_query($sql);
		
			if ($result) { echo "<span class=\"result-success\">User was created. You may now close this popup.</span>"; }
			else { echo "<span class=\"result-failure\">Your User could not be created. Please try again.</span>"; }
			
		}
	}
	
	//================================================
	// Send an email of the parts list
	//================================================
	elseif ($actual_action == "sendEmailofParts") {		
		emailOrder($_POST['Parts'], $_POST['ModelID'], $_POST['email']);
	}
	
	//================================================
	// Re-calculates all of the order numbers
	//================================================
	elseif ($actual_action == "reCalculateOrderPrices") {	
		updateOrder($_SESSION['userid'], $actual_id, 0);
	}
	
	//================================================
	// RDeletes a system from an order
	//================================================
	elseif ($actual_action == "deletesystem") {	
		$userid = "";
		$orderid = "";
				
		// Get the orderid and userid before we delete the system and its parts
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "orders_products` op LEFT JOIN `" . DBTABLEPREFIX . "orders` o ON op.order_id = o.id WHERE `op.id` = '" . $actual_id . "' LIMIT 1;";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$userid = $row['user_id'];
				$orderid = $row['id'];
			}
			mysql_free_result($result);		
		}
		
		// Delete the system parts
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "orders_products` WHERE `orders_products_system_id` = '" . $actual_id . "';";
		$result = mysql_query($sql);
		
		// Delete the system
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "orders_products` WHERE `id` = '" . $actual_id . "';";
		$result = mysql_query($sql);
		
		// Update the order totals
		updateOrder($userid, $orderid, 0);
	}
	
	//================================================
	// Calculate Shipping Fee
	//================================================
	elseif ($actual_action == "calculateShipping") {	
		$shippingChoiceID = parseurl($_GET['shippingChoiceID']);			
	
		$ShipTotal = calculateShippingCost($_SESSION['orderid'], $_SESSION['userid'], $shippingChoiceID);
		
		echo formatCurrency($ShipTotal);
	}
	
	//================================================
	// Calculate Shipping Fee based on Zip Code
	//================================================
	elseif ($actual_action == "calculateShippingFromZipCode") {	
		$zipCode = parseurl($_GET['zipCode']);
		
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "useraddresses` WHERE `user_id` = '" . $_SESSION['userid'] . "' AND `type` = '1';";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			// Update Shipping Address zip code
			$sql2 = "UPDATE `" . DBTABLEPREFIX . "useraddresses` SET `zip` = '" . $zipCode . "' WHERE `user_id` = '" . $_SESSION['userid'] . "' AND `type` = '1';";
			$result2 = mysql_query($sql2);
			
			mysql_free_result($result);
		}
		else {
			// Insert a Shipping Address zip code
			$sql2 = "INSERT INTO `" . DBTABLEPREFIX . "useraddresses` (`user_id`, `type`, `zip`)  VALUES ('" . $_SESSION['userid'] . "', '1', '" . $zipCode . "');";
			$result2 = mysql_query($sql2);
		}
	}
	
	//================================================
	// Calculate Rush Fee
	//================================================
	elseif ($actual_action == "calculateRushFee") {		
		$rushFee = parseurl($_GET['processingChoiceID']);
		
		//Update the rush fee in the DB
		$sql = "UPDATE `" . DBTABLEPREFIX . "orders` SET rush_fee='" . $rushFee . "' WHERE id='" . $_SESSION['orderid'] . "'";
		$result = mysql_query($sql);
		
		//Update the order totals
		updateOrder($_SESSION['userid'], $_SESSION['orderid'], 0);
		
		echo formatCurrency($rushFee);
	}
	
	//================================================
	// Adds coupon to an order
	//================================================
	elseif ($actual_action == "addCoupon") {
		// Pull coupon discounts
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "coupons` WHERE code='" . $actual_value . "' LIMIT 1";
		$result = mysql_query($sql);
		
		while ($row = mysql_fetch_array($result)) {
			//Update the order in the db
			$sql2 = "UPDATE `" . DBTABLEPREFIX . "orders` SET coupon_name = '" . $row['name'] . "', coupon_code = '" . $row['code'] . "', coupon_discount = '" . $row['discount'] . "', coupon_discount_percentage = '" . $row['discount_percentage'] . "' WHERE id='" . $actual_id . "'";
			$result2 = mysql_query($sql2);
		}
		mysql_free_result($result);
		
		// Update the order totals
		updateOrder($_SESSION['userid'], $actual_id, 0);
		
		echo "-" . formatCurrency(getOrderCouponDiscount($actual_id));
	}
	
	//================================================
	// Lets our user know if theircoupon is in the DB
	//================================================
	elseif ($actual_action == "couponExists") {
		// Pull coupon discounts
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "coupons` WHERE code='" . $actual_value . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			echo "<span style=\"color: green;\">Your coupon has been added.</span>";
		}
		else {
			echo "<span style=\"color: red;\">We could not find your coupon, please try adding another one.</span>";
		}
		mysql_free_result($result);
	}
	
	//================================================
	// Re-calculates all of the invoices numbers
	//================================================
	elseif ($actual_action == "reCalculateInvoicePrices") {	
		updateInvoice($_SESSION['userid'], $actual_id, 0);
	}
	
	//================================================
	// Calculate Shipping Fee for Invoice if they use 
	// the dropdown
	//================================================
	elseif ($actual_action == "calculateInvoiceShipping") {	
		$shippingChoiceID = parseurl($_GET['shippingChoiceID']);			
	
		$ShipTotal = calculateInvoiceShippingCost($_SESSION['invoiceid'], $_SESSION['userid'], $shippingChoiceID);
		
		echo "<input name=\"shippingCost\" id=\"shippingCost\" type=\"text\" size=\"10\" value=\"" . $ShipTotal . "\" />";
	}
	
	//================================================
	// Calculate Shipping Fee for Invoice if they enter 
	// a custom cost
	//================================================
	elseif ($actual_action == "calculateInvoiceShippingCost") {	
		$shippingCost = parseurl($_GET['shippingCost']);			
	
		$sql = "UPDATE `" . DBTABLEPREFIX . "invoices` SET invoices_shipping_price='" . $shippingCost . "', invoices_price = (" . $shippingCost . " + invoices_items_total + invoices_tax - invoices_discount) WHERE invoices_id='" . $actual_id . "'";
		$result = mysql_query($sql);
	}
	
	//================================================
	// Calculate Rush Fee for Invoice
	//================================================
	elseif ($actual_action == "calculateInvoiceRushFee") {		
		$rushFee = parseurl($_GET['processingChoiceID']);
		$extraSQL = ($rushFee > 0) ? ", invoices_price = (invoices_shipping_price + invoices_items_total + invoices_tax - invoices_discount + " . $rushFee . ")" : ", invoices_price = (invoices_shipping_price + invoices_items_total + invoices_tax - invoices_discount)";
		//Update the order
		$sql = "UPDATE `" . DBTABLEPREFIX . "invoices` SET invoices_rush_fee='" . $rushFee . "'" . $extraSQL . " WHERE invoices_id='" . $actual_id . "'";
		$result = mysql_query($sql);
		
		echo formatCurrency($rushFee);
	}
	
	//================================================
	// Prints our the info on credit card SIDs
	//================================================
	elseif ($actual_action == "showCreditCardSIDInfo") {	
		echo "
			<strong>Security ID on the Back of the Card</strong><br />
			<img src=\"images/CCSID.jpg\" alt=\"\" /><br/><br/>
			<strong>What is a Credit Card Security ID?</strong><br/>
			A Credit Card Security ID is a 3 to 4 digit number located on the back of your credit or debit card. This number is needed to help verify your credit card. Please refer to the image above to help locate the code on your card.
			<br /><br />
			<strong>Bank Name</strong><br />
			This is the bank that issued your card and should be listed on the card.
			<br /><br />
			<strong>Bank Phone Number</strong><br />
			This is the customer service phone number listed on your card generally on the reverse side.";
	}
	
	//================================================
	// Echo's nothing so that a div or span gets cleared
	//================================================
	elseif ($actual_action == "clearIt") {	
		echo "";
	}
	
	//================================================
	// Echo's a spinner
	//================================================
	elseif ($actual_action == "showSpinner") {	
		echo "<img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" />";
	}

	//================================================
	// Checks to see if a username is already in use
	//================================================
	if ($actual_action == "checkusername") {	
		$sql_username_check = mysql_query("SELECT username FROM `" . USERSDBTABLEPREFIX . "users` WHERE username='" . $actual_value . "'");
	
		if (mysql_num_rows($sql_username_check) > 0 && trim($actual_value) != "") {
			echo "<a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">[In Use]</a>";
		}
		else {
			echo "<a style=\"cursor: pointer; cursor: hand; color: green;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">[Available]</a>";
		}
	}
	
	//================================================
	// Checks to see if an email address is already in use
	//================================================
	elseif ($actual_action == "checkemailaddress") {	
		$sql_email_address_check = mysql_query("SELECT email_address FROM `" . USERSDBTABLEPREFIX . "users` WHERE email_address='" . $actual_value . "'");
	
		if (mysql_num_rows($sql_email_address_check) > 0 && trim($actual_value) != "") {
			echo "<a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">[In Use]</a>";
		}
		else {
			echo "<a style=\"cursor: pointer; cursor: hand; color: green;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">[Available]</a>";
		}
	}
	
	else {
		// Do Nothing
	}

?>
