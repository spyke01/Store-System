<? 
/***************************************************************************
 *                               products.php
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
	if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
		if ($actual_action == "editproducts" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Product", "");
			
			if(isset($_POST['name'])) {
				//print_r($_POST);
				$name = $_POST['name'];
				$type = $_POST['type'];
				$dist = $_POST['dist'];
				$item_num = $_POST['item_num'];
				$description = $_POST['description'];
				$qty = $_POST['qty'];
				$price = $_POST['price'];
				$shipping_costs = $_POST['shipping_costs'];
				$profit = $_POST['profit'];
				$weight = $_POST['weight'];
				$image_full = $_POST['image_full'];
				$image_thumb = $_POST['image_thumb'];
				$active = ($_POST['active'] == "") ? 0 : 1;				
				
				// Make productcats list
				$productcatsList = "";
				if(is_array($type)) {
					foreach ($type as $productcatID) {
						$productcatsList .= "x" . $productcatID . "x ";
					}
				}
				else {
					$productcatsList .= "x" . $type . "x ";
				}		
				$productcatsList = trim($productcatsList);
				
				// Strips out xx because this means that there was no id sent
				$productcatsList = str_replace("xx", "", $productcatsList);
				
				// Strips out double blanks
				$productcatsList = str_replace("  ", "", $productcatsList);
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "products` SET name='" . $name . "', type = '" . $productcatsList . "', dist = '" . $dist . "', item_num = '" . $item_num . "', description='" . $description . "', qty = '" . $qty . "', price = '" . $price . "', shipping_costs = '" . $shipping_costs . "', profit = '" . $profit . "', weight = '" . $weight . "', image_full='" . $image_full . "', image_thumb='" . $image_thumb . "', active = '" . $active . "' WHERE id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your product has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['PRODUCTS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your product. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['PRODUCTS'] . "\">";						
				}
				//echo $sql;
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "products` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['PRODUCTS'] . "&action=editproducts&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Product</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"30%\"><strong>Name: </strong></td>
														<td><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"title2\">
														<td><strong>Selected Product Category</strong></td>
														<td><strong>Available Product Categories</strong></td>
													</tr>
													<tr class=\"row1\">
														<td>
															<select name=\"type[]\" multiple=\"multiple\" cols=\"10\">";
															
															// Print the select box containing the currently selected models for this product
															$productcatsList = split(" ", str_replace("x", "", $row['type']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($productcatsList as $productcatID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id='" . $productcatID . "'" : " OR id='" . $productcatID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "productcats`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if ($result2 && mysql_num_rows($result2) != "0") {	 // Print all our products							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
					$page_content .= "\n							
															</select>
														</td>
														<td>
															<select name=\"products_avail_types\" multiple=\"multiple\" size=\"10\">";
															
															// Print the select box containing the currently selected models for this product
															$productcatsList = split(" ", str_replace("x", "", $row['type']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($productcatsList as $productcatID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id!='" . $productcatID . "'" : " AND id!='" . $productcatID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "productcats`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if ($result2 && mysql_num_rows($result2) != "0") {	 // Print all our products							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
					$page_content .= "\n							
															</select>
														</td>
													</tr>
													<tr class=\"row2\">
														<td colspan=\"2\" class=\"center\">
															<input type=\"button\" name=\"btnMoveLeftProductCat\" onClick=\"moveModels('products_avail_types', 'type[]'); return false;\" value=\"<\" />
															<input type=\"button\" name=\"btnMoveRightProductCat\" onClick=\"moveModels('type[]', 'products_avail_types'); return false;\" value=\">\" />
														</td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Distributor: </strong></td>
														<td>
															" . createDropdown("distributors", "dist", $row['dist'], "") . "
														</td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Item Number: </strong></td>
														<td><input type=\"text\" name=\"item_num\" size=\"40\" value=\"" . $row['item_num'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Description: </strong></td>
														<td><textarea name=\"description\" cols=\"45\" rows=\"5\">" . $row['description'] . "</textarea></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Quantity: </strong></td>
														<td><input type=\"text\" name=\"qty\" size=\"40\" value=\"" . $row['qty'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Price: </strong></td>
														<td><input type=\"text\" name=\"price\" size=\"40\" value=\"" . $row['price'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Shipping Costs: </strong></td>
														<td><input type=\"text\" name=\"shipping_costs\" size=\"40\" value=\"" . $row['shipping_costs'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Profit: </strong></td>
														<td><input type=\"text\" name=\"profit\" size=\"40\" value=\"" . $row['profit'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Weight: </strong></td>
														<td><input type=\"text\" name=\"weight\" size=\"40\" value=\"" . $row['weight'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Image: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"image_full\" size=\"40\" value=\"" . $row['image_full'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Thumbnail Image: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"image_thumb\" size=\"40\" value=\"" . $row['image_thumb'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Active: </strong></td>
														<td><input name=\"active\" type=\"checkbox\" value=\"1\"". testChecked($row['active'], ACTIVE) . " /></td>
													</tr>
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" onClick=\"selectAllItems('type[]'); selectAllItems('models[]'); selectAllItems('default_on_models[]');\" /></div>
											</form>
											<br /><br />";
				}
				else { $page_content .= "No such ID was found in the database!"; }
			}			
		}			
		elseif ($actual_action == "addmultiproducts") {
			// Add breadcrumb
			$page->addBreadCrumb("Add Multiple Products", "");
			
			// Change this number to increase or decrease the number of rows show on the page
			$numOfProductRows = 10;
			
			if(isset($_POST['name'])) {				
				for ($i = 0; $i < $numOfProductRows; $i++) {
					$name = keeptasafe($_POST['name'][$i]);
					$type = $_POST['type'][$i];
					$dist = keepsafe($_POST['dist'][$i]);
					$item_num = keeptasafe($_POST['item_num'][$i]);
					$description = keeptasafe($_POST['description'][$i]);
					$price = keepsafe($_POST['price'][$i]);
					$shipping_costs = keepsafe($_POST['shipping_costs'][$i]);
					$profit = keepsafe($_POST['profit'][$i]);
					$active = ($_POST['active'][$i] == "") ? 0 : 1;	
					
					// Make productcats list
					$productcatsList = "";
					if(is_array($type)) {
						foreach ($type as $productcatID) {
							$productcatsList .= "x" . $productcatID . "x ";
						}
					}
					else {
						$productcatsList .= "x" . $type . "x ";
					}		
					$productcatsList = trim($productcatsList);
				
					// Strips out xx because this means that there was no id sent
					$productcatsList = str_replace("xx", "", $productcatsList);
				
					// Strips out double blanks
					$productcatsList = str_replace("  ", "", $productcatsList);
					
					// Only insert rows that have a product name
					if ($name != "") {
						$sql = "INSERT INTO `" . DBTABLEPREFIX . "products` (name, type, dist, item_num, sort, price, shipping_costs, profit, active) VALUES ('" . $name . "', '" . $productcatsList . "', '" . $dist . "', '" . $item_num . "', '" . $sort . "', '" . $price . "', '" . $shipping_costs . "', '" . $profit . "', '" . trim($modelsList) . "', '" . trim($defaultOnModelsList) . "', '" . $active . "')";
				    	$result = mysql_query($sql);
						//echo $sql . "<br />";
					}
				}
				unset($_POST['name']);
				
			    // confirm
 				$page_content .= "Your products has been updated, and you are being redirected to the main page.
 									<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['PRODUCTS'] . "\">";
			}
			else{
					
					$page_content .= "\n
											<form action=\"" . $menuvar['PRODUCTS'] . "&action=addmultiproducts\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"13\"><strong>Add New Product(s)</strong></td>
													</tr>
													<tr class=\"title2\">
														<td><strong>Name: </strong></td>
														<td><strong>Selected Product Category</strong></td>
														<td><strong>Available Product Categories</strong></td>
														<td><strong>Distributor: </strong></td>
														<td><strong>Item Number: </strong></td>
														<td><strong>Price: </strong></td>
														<td><strong>Shipping Costs: </strong></td>
														<td><strong>Profit: </strong></td>
														<td><strong>Active: </strong></td>
													</tr>";
													
					$x = 1;
					$onSubmitSelectCode = "";
					
					for ($i = 0; $i < $numOfProductRows; $i++) {
						$page_content .= "\n							
													<tr class=\"row" . $x . "\">
														<td><input type=\"text\" name=\"name[". $i ."]\" size=\"40\" /></td>
														<td>
															<select name=\"type[". $i ."][]\" multiple=\"multiple\" cols=\"10\">
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveLeftProductCat\" onClick=\"moveModels('products_avail_types[". $i ."]', 'type[". $i ."][]'); return false;\" value=\"<\" />
														</td>
														<td>
															<select name=\"products_avail_types[". $i ."]\" multiple=\"multiple\" size=\"10\">";
															
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "productcats` ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if ($result2 && mysql_num_rows($result2) != "0") {	 // Print all our products							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
						$page_content .= "\n							
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveRightProductCat\" onClick=\"moveModels('type[". $i ."][]', 'products_avail_types[". $i ."]'); return false;\" value=\">\" />
														</td>
														<td>
															" . createDropdown("distributors", "dist[". $i ."]", $row['dist'], "") . "
														</td>
														<td><input type=\"text\" name=\"item_num[". $i ."]\" size=\"40\" /></td>
														<td><input type=\"text\" name=\"price[". $i ."]\" size=\"40\" /></td>
														<td><input type=\"text\" name=\"shipping_costs[". $i ."]\" size=\"40\" /></td>
														<td><input type=\"text\" name=\"profit[". $i ."]\" size=\"40\" /></td>
														<td><input name=\"active[". $i ."]\" type=\"checkbox\" value=\"1\" /></td>
													</tr>";
													
						$onSubmitSelectCode .= " selectAllItems('type[". $i ."][]');";
						$x = ($x == 1) ? 2 : 1;
					}
					$page_content .= "\n							
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" onClick=\"" . $onSubmitSelectCode . "\" /></div>
											</form>
											<br /><br />";
			}			
		}			
		else {
			//==================================================
			// Print out our products table
			//==================================================
				
			$page_content = "
						<div id=\"updateMe\">" . printProductsTable(-1) . "</div>
				<script language = \"Javascript\">
				
				function ValidateForm(theForm){
					var name=document.newProductsForm.newproductsname
					
					if ((name.value==null)||(name.value==\"\")){
						alert(\"Please enter the new product\'s name.\")
						name.focus()
						return false
					}
					new Ajax.Updater('updateMe', 'ajax.php?action=postproducts', {onComplete:function(){ new Effect.Highlight('newProducts');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
					name.value = '';
					return false;
				 }
				</script>";	
		}
		
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
	}
?>