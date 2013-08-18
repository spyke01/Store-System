<? 
/***************************************************************************
 *                               productfeatures.php
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
				
		// Delete all ingredients that are blank
		$sql = "DELETE FROM `" . DBTABLEPREFIX . "products_features_values` WHERE price='' AND name=''";
		$result = mysql_query($sql);
		
		if ($actual_action == "editproductfeatures" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Product Features", "");
			
			if(isset($_POST['name'])) {
				//print_r($_POST);
				$errors = 0;
				
				// Handle basic productfeature
				$name = keeptasafe($_POST['name']);
				$product_id = keeptasafe($_POST['product_id']);
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "products_features` SET name='" . $name . "', product_id='" . $product_id . "' WHERE id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				$errors += ($result) ? 0 : 1;
				//echo $sql . "<br />";
				
				// Handle ingredients
				$productFeaturePrices = $_POST['productFeatureSetPrice'];
				$productFeatureNames = $_POST['productFeatureSetName'];
				
				// Delete all ingredients for this productfeature
				$sql = "DELETE FROM `" . DBTABLEPREFIX . "products_features_values` WHERE feature_id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				
				// Add the ingredients back in
				for ($i = 0; $i < count($productFeaturePrices); $i++) {
					$sql = "INSERT INTO `" . DBTABLEPREFIX . "products_features_values` (`feature_id`, `price`, `name`) VALUES ('" . $actual_id . "', '" . keeptasafe($productFeaturePrices[$i]) . "', '" . keeptasafe($productFeatureNames[$i]) . "')";
			    	$result = mysql_query($sql);
					$errors += ($result) ? 0 : 1;
					//echo $sql . "<br />";
				}
				
				
				if ($errors == 0) {
					$page_content = "<span class=\"center\">Your product feature has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['PRODUCTFEATURES'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your product feature. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['PRODUCTFEATURES'] . "\">";						
				}
				
				
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "products_features` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if ($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "
											<form action=\"" . $menuvar['PRODUCTFEATURES'] . "&action=editproductfeatures&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Product Features</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"30%\"><strong>Name: </strong></td>
														<td><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Product: </strong></td>
														<td>
															" . createDropdown("products", "product_id", $row['product_id'], "") . "
														</td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Options: </strong></td>
														<td>
															<table class=\"productFeaturesTable\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
																<thead><tr><th>Name</th><th>Price</th></tr></thead>
																<tbody id=\"productFeatures\"></tbody>
															</table>
															<br /><br />
															<script type=\"text/javascript\">
																// Set our basic settings
																configureProductFeaturesSettings('0', 'themes/" . $ss_config['ftsss_theme'] . "/icons/add.png', 'themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png');
																
																// Add our ingredients";
					
						$sql2 = "SELECT price, name FROM `" . DBTABLEPREFIX . "products_features_values` WHERE feature_id='" . $actual_id . "' ORDER BY id ASC";
						$result2 = mysql_query($sql2);
					
						if ($result2 && mysql_num_rows($result2) > 0) {
							while ($row2 = mysql_fetch_array($result2)) {
								$page_content .= "\n																addProductFeatureSet('" . $row2['name'] . "', '" . $row2['price'] . "');";
							}
							mysql_free_result($result2);
						}
					
					$page_content .= "
																// Add a blank ingredient set
																addProductFeatureSet('', '');
															</script>
														</td>
													</tr>
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" onClick=\"selectAllItems('productfeatures_type[]'); selectAllItems('productfeatures_models[]'); selectAllItems('productfeatures_default_on_models[]');\" /></div>
											</form>
											<br /><br />";
											
					mysql_free_result($result);
				}
				else { $page_content .= "No such ID was found in the database!"; }
			}			
		}
		else {
			//==================================================
			// Print out our productfeatures table
			//==================================================
				
			$page_content = "
						<div id=\"updateMe\">" . printProductFeaturesTable() . "</div>
				<script type=\"text/javascript\">
					function ValidateForm(theForm){
						var name=theForm.newproductfeaturesname;
					
						if ((name.value == null) || (name.value == '')) {
							alert('Please enter the new product feature\'s name.');
							name.focus();
							return false;
						}
						new Ajax.Updater('updateMe', 'ajax.php?action=postproductfeatures', {onComplete:function(){ new Effect.Highlight('newProductFeatures');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
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