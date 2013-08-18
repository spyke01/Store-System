<? 
/***************************************************************************
 *                               parts.php
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
		if ($actual_action == "editparts" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Part", "");
			
			if(isset($_POST['name'])) {
				//print_r($_POST);
				$name = $_POST['name'];
				$type = $_POST['type'];
				$dist = $_POST['dist'];
				$item_num = $_POST['item_num'];
				$description = $_POST['description'];
				$qty = $_POST['qty'];
				$sort = $_POST['sort'];
				$price = $_POST['price'];
				$shipping_costs = $_POST['shipping_costs'];
				$profit = $_POST['profit'];
				$weight = $_POST['weight'];
				$image_full = $_POST['image_full'];
				$image_thumb = $_POST['image_thumb'];
				$models = $_POST['models'];
				$default_on_models = $_POST['default_on_models'];
				$active = ($_POST['active'] == "") ? 0 : 1;				
				
				// Make partcats list
				$partcatsList = "";
				if(is_array($type)) {
					foreach ($type as $partcatID) {
						$partcatsList .= "x" . $partcatID . "x ";
					}
				}
				else {
					$partcatsList .= "x" . $type . "x ";
				}		
				$partcatsList = trim($partcatsList);
				
				// Make models list
				$modelsList = "";
				if(is_array($models)) {
					foreach ($models as $modelID) {
						$modelsList .= "x" . $modelID . "x ";
					}
				}
				else {
					$modelsList .= "x" . $models . "x ";
				}
				$modelsList = trim($modelsList);
				
				// Make default on models list
				$defaultOnModelsList = "";
				if(is_array($default_on_models)) {
					foreach ($default_on_models as $modelID) {
						$defaultOnModelsList .= "x" . $modelID . "x ";
					}
				}
				else {
					$defaultOnModelsList .= "x" . $models . "x ";
				}
				$defaultOnModelsList = trim($defaultOnModelsList);
				
				// Strips out xx because this means that there was no id sent
				$partcatsList = str_replace("xx", "", $partcatsList);
				$modelsList = str_replace("xx", "", $modelsList);
				$defaultOnModelsList = str_replace("xx", "", $defaultOnModelsList);
				
				// Strips out double blanks
				$partcatsList = str_replace("  ", "", $partcatsList);
				$modelsList = str_replace("  ", "", $modelsList);
				$defaultOnModelsList = str_replace("  ", "", $defaultOnModelsList);
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "parts` SET name='" . $name . "', type = '" . $partcatsList . "', dist = '" . $dist . "', item_num = '" . $item_num . "', description='" . $description . "', qty = '" . $qty . "', sort = '" . $sort . "', price = '" . $price . "', shipping_costs = '" . $shipping_costs . "', profit = '" . $profit . "', weight = '" . $weight . "', image_full='" . $image_full . "', image_thumb='" . $image_thumb . "', models = '" . trim($modelsList) . "', default_on_models = '" . trim($defaultOnModelsList) . "', active = '" . $active . "' WHERE id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your part has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['PARTS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your part. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['PARTS'] . "\">";						
				}
				//echo $sql;
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "parts` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['PARTS'] . "&action=editparts&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Part</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"30%\"><strong>Name: </strong></td>
														<td><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"title2\">
														<td><strong>Selected Part Category</strong></td>
														<td><strong>Available Part Categories</strong></td>
													</tr>
													<tr class=\"row1\">
														<td>
															<select name=\"type[]\" multiple=\"multiple\" cols=\"10\">";
															
															// Print the select box containing the currently selected models for this part
															$partcatsList = split(" ", str_replace("x", "", $row['type']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($partcatsList as $partcatID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id='" . $partcatID . "'" : " OR id='" . $partcatID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "partcats`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
					$page_content .= "\n							
															</select>
														</td>
														<td>
															<select name=\"avail_types\" multiple=\"multiple\" size=\"10\">";
															
															// Print the select box containing the currently selected models for this part
															$partcatsList = split(" ", str_replace("x", "", $row['type']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($partcatsList as $partcatID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id!='" . $partcatID . "'" : " AND id!='" . $partcatID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "partcats`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
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
															<input type=\"button\" name=\"btnMoveLeftPartCat\" onClick=\"moveModels('avail_types', 'type[]'); return false;\" value=\"<\" />
															<input type=\"button\" name=\"btnMoveRightPartCat\" onClick=\"moveModels('type[]', 'avail_types'); return false;\" value=\">\" />
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
														<td><strong>Sort Order: </strong></td>
														<td><input type=\"text\" name=\"sort\" size=\"40\" value=\"" . $row['sort'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Price: </strong></td>
														<td><input type=\"text\" name=\"price\" size=\"40\" value=\"" . $row['price'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Shipping Costs: </strong></td>
														<td><input type=\"text\" name=\"shipping_costs\" size=\"40\" value=\"" . $row['shipping_costs'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Profit: </strong></td>
														<td><input type=\"text\" name=\"profit\" size=\"40\" value=\"" . $row['profit'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Weight: </strong></td>
														<td><input type=\"text\" name=\"weight\" size=\"40\" value=\"" . $row['weight'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Image: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"image_full\" size=\"40\" value=\"" . $row['image_full'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Thumbnail Image: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"image_thumb\" size=\"40\" value=\"" . $row['image_thumb'] . "\" /></td>
													</tr>
													<tr class=\"title2\">
														<td><strong>Selected Models</strong></td>
														<td><strong>Available Models</strong></td>
													</tr>
													<tr class=\"row1\">
														<td>
															<select name=\"models[]\" multiple=\"multiple\" cols=\"10\">";
															
															// Print the select box containing the currently selected models for this part
															$modelList = split(" ", str_replace("x", "", $row['models']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($modelList as $modelID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id='" . $modelID . "'" : " OR id='" . $modelID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "models`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
					$page_content .= "\n							
															</select>
														</td>
														<td>
															<select name=\"avail_models\" multiple=\"multiple\" size=\"10\">";
															
															// Print the select box containing the currently selected models for this part
															$modelList = split(" ", str_replace("x", "", $row['models']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($modelList as $modelID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id!='" . $modelID . "'" : " AND id!='" . $modelID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "models`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
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
															<input type=\"button\" name=\"btnMoveLeft\" onClick=\"moveModels('avail_models', 'models[]'); return false;\" value=\"<\" />
															<input type=\"button\" name=\"btnMoveRight\" onClick=\"moveModels('models[]', 'avail_models'); return false;\" value=\">\" />
														</td>
													</tr>
													<tr class=\"title2\">
														<td><strong>Default Part on these Models</strong></td>
														<td><strong>Available Models</strong></td>
													</tr>
													<tr class=\"row1\">
														<td>
															<select name=\"default_on_models[]\" multiple=\"multiple\" cols=\"10\">";
															
															// Print the select box containing the currently selected models for this part
															$modelList = split(" ", str_replace("x", "", $row['default_on_models']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($modelList as $modelID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id='". $modelID . "'" : " OR id='". $modelID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "models`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
					$page_content .= "\n							
															</select>
														</td>
														<td>
															<select name=\"default_on_avail_models\" multiple=\"multiple\" size=\"10\">";
															
															// Print the select box containing the currently selected models for this part
															$modelList = split(" ", str_replace("x", "", $row['default_on_models']));
															$sqlParams = "";
															$firstRound = 1;
															foreach ($modelList as $modelID) {																
																$sqlParams .= ($firstRound == 1) ? " WHERE id!='". $modelID . "'" : " AND id!='". $modelID . "'";
																$firstRound = 0;
															}
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "models`" . $sqlParams . " ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
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
															<input type=\"button\" name=\"btnMoveLeft\" onClick=\"moveModels('default_on_avail_models', 'default_on_models[]'); return false;\" value=\"<\" />
															<input type=\"button\" name=\"btnMoveRight\" onClick=\"moveModels('default_on_models[]', 'default_on_avail_models'); return false;\" value=\">\" />
														</td>
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
		elseif ($actual_action == "addmultiparts") {
			// Add breadcrumb
			$page->addBreadCrumb("Add Multiple Parts", "");
			
			// Change this number to increase or decrease the number of rows show on the page
			$numOfPartRows = 10;
			
			if(isset($_POST['name'])) {				
				for ($i = 0; $i < $numOfPartRows; $i++) {
					$name = $_POST['name'][$i];
					$type = $_POST['type'][$i];
					$dist = $_POST['dist'][$i];
					$item_num = $_POST['item_num'][$i];
					$description = $_POST['description'][$i];
					$price = $_POST['price'][$i];
					$shipping_costs = $_POST['shipping_costs'][$i];
					$profit = $_POST['profit'][$i];
					$models = $_POST['models'][$i];
					$default_on_models = $_POST['default_on_models'][$i];
					$active = ($_POST['active'][$i] == "") ? 0 : 1;	
					
					// Make partcats list
					$partcatsList = "";
					if(is_array($type)) {
						foreach ($type as $partcatID) {
							$partcatsList .= "x" . $partcatID . "x ";
						}
					}
					else {
						$partcatsList .= "x" . $type . "x ";
					}		
					$partcatsList = trim($partcatsList);
					
					// Make models list
					$modelsList = "";
					if(is_array($models)) {
						foreach ($models as $modelID) {
							$modelsList .= "x" . $modelID . "x ";
						}
					}
					else {
						$modelsList .= "x" . $models . "x ";
					}
					$modelsList = trim($modelsList);
					
					// Make default on models list
					$defaultOnModelsList = "";
					if(is_array($default_on_models)) {
						foreach ($default_on_models as $modelID) {
							$defaultOnModelsList .= "x" . $modelID . "x ";
						}
					}
					else {
						$defaultOnModelsList .= "x" . $models . "x ";
					}
					$defaultOnModelsList = trim($defaultOnModelsList);
				
					// Strips out xx because this means that there was no id sent
					$partcatsList = str_replace("xx", "", $partcatsList);
					$modelsList = str_replace("xx", "", $modelsList);
					$defaultOnModelsList = str_replace("xx", "", $defaultOnModelsList);
				
					// Strips out double blanks
					$partcatsList = str_replace("  ", "", $partcatsList);
					$modelsList = str_replace("  ", "", $modelsList);
					$defaultOnModelsList = str_replace("  ", "", $defaultOnModelsList);
					
					// Only insert rows that have a part name
					if ($name != "") {
						$sql = "INSERT INTO `" . DBTABLEPREFIX . "parts` (name, type, dist, item_num, sort, price, shipping_costs, profit, models, default_on_models, active) VALUES ('" . $name . "', '" . $partcatsList . "', '" . $dist . "', '" . $item_num . "', '" . $sort . "', '" . $price . "', '" . $shipping_costs . "', '" . $profit . "', '" . trim($modelsList) . "', '" . trim($defaultOnModelsList) . "', '" . $active . "')";
				    	$result = mysql_query($sql);
						//echo $sql . "<br />";
					}
				}
				unset($_POST['name']);
				
			    // confirm
 				$page_content .= "Your parts has been updated, and you are being redirected to the main page.
 									<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['PARTS'] . "\">";
			}
			else{
					$onClickCode .= "";
					
					$page_content .= "\n
											<form action=\"" . $menuvar['PARTS'] . "&action=addmultiparts\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"13\"><strong>Add New Part(s)</strong></td>
													</tr>
													<tr class=\"title2\">
														<td><strong>Name: </strong></td>
														<td><strong>Selected Part Category</strong></td>
														<td><strong>Available Part Categories</strong></td>
														<td><strong>Distributor: </strong></td>
														<td><strong>Item Number: </strong></td>
														<td><strong>Price: </strong></td>
														<td><strong>Shipping Costs: </strong></td>
														<td><strong>Profit: </strong></td>
														<td><strong>Selected Models</strong></td>
														<td><strong>Available Models</strong></td>
														<td><strong>Default Part on these Models</strong></td>
														<td><strong>Available Models</strong></td>
														<td><strong>Active: </strong></td>
													</tr>";
					$x = 1;
					for ($i = 0; $i < $numOfPartRows; $i++) {
						$page_content .= "\n							
													<tr class=\"row" . $x . "\">
														<td><input type=\"text\" name=\"name[". $i ."]\" size=\"40\" /></td>
														<td>
															<select name=\"type[". $i ."][]\" multiple=\"multiple\" cols=\"10\">
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveLeftPartCat\" onClick=\"moveModels('avail_types[". $i ."]', 'type[". $i ."][]'); return false;\" value=\"<\" />
														</td>
														<td>
															<select name=\"avail_types[". $i ."]\" multiple=\"multiple\" size=\"10\">";
															
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "partcats` ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
						$page_content .= "\n							
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveRightPartCat\" onClick=\"moveModels('type[". $i ."][]', 'avail_types[". $i ."]'); return false;\" value=\">\" />
														</td>
														<td>
															" . createDropdown("distributors", "dist[". $i ."]", $row['dist'], "") . "
														</td>
														<td><input type=\"text\" name=\"item_num[". $i ."]\" size=\"40\" /></td>
														<td><input type=\"text\" name=\"price[". $i ."]\" size=\"40\" /></td>
														<td><input type=\"text\" name=\"shipping_costs[". $i ."]\" size=\"40\" /></td>
														<td><input type=\"text\" name=\"profit[". $i ."]\" size=\"40\" /></td>
														<td>
															<select name=\"models[". $i ."][]\" multiple=\"multiple\" cols=\"10\">
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveLeft\" onClick=\"moveModels('avail_models[". $i ."]', 'models[". $i ."][]'); return false;\" value=\"<\" />
														</td>
														<td>
															<select name=\"avail_models[". $i ."]\" multiple=\"multiple\" size=\"10\">";
															
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "models` ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
						$page_content .= "\n							
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveRight\" onClick=\"moveModels('models[". $i ."][]', 'avail_models[". $i ."]'); return false;\" value=\">\" />
														</td>
														<td>
															<select name=\"default_on_models[". $i ."][]\" multiple=\"multiple\" cols=\"10\">
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveLeft\" onClick=\"moveModels('default_on_avail_models[". $i ."]', 'default_on_models[". $i ."][]'); return false;\" value=\"<\" />
														</td>
														<td>
															<select name=\"default_on_avail_models[". $i ."]\" multiple=\"multiple\" size=\"10\">";
															
															$sql2 = "SELECT id, name FROM `" . DBTABLEPREFIX . "models` ORDER BY name";
															$result2 = mysql_query($sql2);
															
															if (mysql_num_rows($result2) != "0") {	 // Print all our parts							
																while ($row2 = mysql_fetch_array($result2)) {
																	$page_content .= "\n																<option value=\"" . $row2['id'] . "\">" . $row2['name'] . "</option>";
																}	
															}
															mysql_free_result($result2);
						$page_content .= "\n							
															</select>
															<br />
															<input type=\"button\" name=\"btnMoveRight\" onClick=\"moveModels('default_on_models[". $i ."][]', 'default_on_avail_models[". $i ."]'); return false;\" value=\">\" />
														</td>
														<td><input name=\"active[". $i ."]\" type=\"checkbox\" value=\"1\" /></td>
													</tr>";
						$x = ($x == 1) ? 2 : 1;
						$onClickCode .= "selectAllItems('type[". $i ."][]'); selectAllItems('models[". $i ."][]'); selectAllItems('default_on_models[". $i ."][]');";
					}
					$page_content .= "\n							
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" onClick=\"" . $onClickCode . "\" /></div>
											</form>
											<br /><br />";
			}			
		}			
		else {
			//==================================================
			// Print out our parts table
			//==================================================
				
			$page_content = "
						<div id=\"updateMe\">" . printPartsTable(-1) . "</div>
				<script language = \"Javascript\">
				
				function ValidateForm(theForm){
					var name=document.newPartsForm.newpartsname
					
					if ((name.value==null)||(name.value==\"\")){
						alert(\"Please enter the new part\'s name.\")
						name.focus()
						return false
					}
					new Ajax.Updater('updateMe', 'ajax.php?action=postparts', {onComplete:function(){ new Effect.Highlight('newParts');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
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