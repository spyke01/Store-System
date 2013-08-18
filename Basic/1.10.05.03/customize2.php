<? 
/***************************************************************************
 *                               customize2.php
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
 	//==================================================
	// Handle the posted data from page one
	//==================================================
	if (isset($_POST['Parts'])) {
		$modelID = keepSafe($_POST['ModelID']);
		
		//Change These Items to Change Whats Displayed on The Second Customize Page
		$defaultParts = $_POST['Defaults2'];
		$currentPartNames = array();
		$defaultPartCatIDs = array();
		$defaultPartIDs = array();	
		$customizeLinkString = "";
		$customizeLinkString2 = "";
		
		$PartsList = ""; //This variable will hold our list of hidden fields for the parts from the last page
		$PartsPrice = ""; //This variable will hold the total of all of our parts
		
		foreach($_POST['Parts'] as $productCatID => $selectedPartID) {
			$productCatID = keepSafe($productCatID);
			$selectedPartID = keepSafe($selectedPartID);
			
			if(trim($selectedPartID) != "") {
				// Build back button url string
				$customizeLinkString .= $productCatID . "##" . $selectedPartID . "$$";
			
				$sql = "SELECT price, profit, shipping_costs FROM `" . DBTABLEPREFIX . "products` WHERE id='$selectedPartID'";
				$result = mysql_query($sql);
				
				if($row = mysql_fetch_array($result)) {
					// Create the hidden form fields to our page one parts
					$PartsList .= "\n<input type=\"hidden\" name=\"Parts[" . $productCatID . "]\" id=\"" . $productCatID . "\" value=\"" . $selectedPartID ."\">";
					
					// Calculate the parts cost from page one
					$PartsPrice += $row['price'] + $row['shipping_costs'] + $row['profit'];
					$currentPartNames[$productCatID] = $selectedPartID;
				}
			}
		}
		// Strip off last $$
		$customizeLinkString = urlencode(substr($customizeLinkString, 0, -2));
	
	//==================================================
	// Start building our page 
	//==================================================
		$defaultPats2Inputs = "";
		$priceMask = rand(600000,900000);
		$baseTotal = 0;
		$defaultPartCatIDs = array();
		$defaultPartIDs = array();
		
		// Get Model Info and begin to calculate our total cost
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` WHERE models_id ='" . $modelID . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$modelName = $row['models_name'];
				$modelImageFull = $row['models_image_full'];
				$modelImageThumb = $row['models_image_thumb'];
				$basePrice = $row['models_base_price'];
				$baseProfit = $row['models_base_profit'];
				$baseDiscount = $row['models_discount'];
				$baseDiscountPercentage = $row['models_discount_percentage'];
				$discountDescription = $row['models_discount_description'];
				$discountDescription = ($discountDescription == "") ? "None" : $discountDescription;
				$baseTotal = $basePrice + $baseProfit + $PartsPrice - $baseDiscount;
				$baseTotalWithoutDiscount = $basePrice + $baseProfit + $PartsPrice;
			}		
			mysql_free_result($result);
		}
		
		// Hide our discount on the slider if we don't have one
		$sliderPricehidden = ($baseDiscount > 0 || $baseDiscountPercentage > 0) ? "" : " style=\"display: none;\"";
		
		// Handle putting our productcats into the proper order since we cannot use SQL down the road to order things.
		$extraSQL = "";
		
		foreach($defaultParts as $productCatID => $selectedPartID) {
			$extraSQL .= ($extraSQL == "") ? " WHERE" : " OR";
			$extraSQL .= " id = '" . $productCatID . "'";
		}
		
		$sql = "SELECT id FROM `" . DBTABLEPREFIX . "productcats`" . $extraSQL . " ORDER BY sort_order ASC";
		$result = mysql_query($sql);	
		
		if ($result && mysql_num_rows($result) > 0) {				
			while ($row = mysql_fetch_array($result)) {
				// Push the values onto our temp arrays
				array_push($defaultPartCatIDs, $row['id']);
				array_push($defaultPartIDs, $defaultParts[$row['id']]);
			}
			mysql_free_result($result);
		}
		
		//print_r($defaultPartCatIDs);
		//print_r($defaultPartIDs);
		
		$JS_partIDArrays = "";
		$JS_partIDsAndUpdates = "";
		$page_mainContent = "";
		$page_sliderContent = "";
		
		//==================================================
		// Cycle through the parts list from page one and build our page vars
		//==================================================
		foreach ($currentPartNames as $productCatID => $selectedPartID) {
			$showPartName = 0;
			$partName = "";
			
			$sql = "SELECT on_slider FROM `" . DBTABLEPREFIX . "productcats` WHERE id = '" . $productCatID . "'";
			$result = mysql_query($sql);
				
			if($result && mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
					$showPartName = ($row['on_slider'] == "0") ? 0 : 1;
				}
				mysql_free_result($result);
			}
			
			// Pull part name since we aren't using JS to populate the field
			$sql = "SELECT name FROM `" . DBTABLEPREFIX . "products` WHERE id = '" . $selectedPartID. "' LIMIT 1";
			$result = mysql_query($sql);
				
			if($result && mysql_num_rows($result) > 0) {					
				while($row = mysql_fetch_array($result)) {					
					$partName = ($showPartName != 1) ? "" : $row['name'];
				}
				mysql_free_result($result);
			}
					
			$partName = ($showPartName != 1) ? "" : $partName;
			$page_sliderContent .= ($showPartName != 1) ? "" : "\n											<li>" . $partName . "</li>";		
		}
		
		//==================================================
		// Cycle through the parts list and build our page vars
		//==================================================
		foreach ($defaultParts as $productCatID => $selectedPartID) {
			$ProductCatsPartIDs = "";
			
			//==================================================
			// Build our JS items
			//==================================================
			$JS_partIDArrays .= "\n							Parts[" . $productCatID . "] = new Array(); ";		
		
			// Select only those components compatible with this model
			$sql = "SELECT id, price, shipping_costs, profit  FROM `" . DBTABLEPREFIX . "products` WHERE type LIKE '%x" . $productCatID . "x%' AND (models LIKE '%x" . $modelID . "x%' OR models='') AND active='1' ORDER BY sort";
			$result = mysql_query($sql);
				
			if($result && mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
					// Disguise the price with a mask that we will "mod" out later
					$overallprice = $priceMask * ($row['price'] + $row['shipping_costs'] + $row['profit']);
					
					$JS_partIDArrays .= "\n							Parts[" . $productCatID . "][" . $row['id'] . "] = " . $overallprice . ";";
					$ProductCatsPartIDs .= ($ProductCatsPartIDs == "") ? "" : ", ";
					$ProductCatsPartIDs .= "'" . $row['id'] . "'";
					
					$showPartName = 1;
					$sql2 = "SELECT on_slider FROM `" . DBTABLEPREFIX . "productcats` WHERE id = '" . $productCatID . "' LIMIT 1";
					$result2 = mysql_query($sql2);
				
					if($result2 && mysql_num_rows($result2) > 0) {
						while($row2 = mysql_fetch_array($result2)) {
							$showPartName = ($row2['on_slider'] == "0") ? 0 : 1;
						}
						mysql_free_result($result2);
					}
					
					$partName = ($showPartName != 1) ? "" : $row['name'];
					
					$JS_partIDArrays .= "\n							PartNames[" . $row['id'] . "] = '" . $partName . "'; ";
			
					//==================================================
					// Build our slider items at the same time to avoid unnecessary DB calls
					//==================================================
					$page_sliderContent .= ($showPartName != 1) ? "" : "											<li><span id=\"sliderSpan" . $productCatID . "\">" . $partName . "</span></li>";
				}
				mysql_free_result($result);
			} 
			
			$JS_partIDArrays .= "\n							PartIDs[" . $productCatID . "] = new Array(" . $ProductCatsPartIDs . ");";
		
			$JS_partIDsAndUpdates .= "\n
								id" . $productCatID . " = " . $selectedPartID . "; 
								startUpdate(" . $productCatID . ", " . $selectedPartID . "); ";
								
			//==================================================
			// Build our actual page items
			//==================================================
			// Pull the selected partid for this partcat
			$selectedPartID = $defaultPartIDs[$key];
			
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "productcats` WHERE id ='" . $productCatID . "'";
			$result = mysql_query($sql);
			
			if($result && mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_array($result)) {
					$description = ($row['description'] != "") ? $row['description'] . " - " : ""; 
				
					// Print an image of our partcat if available
					if ($row['image'] != "") {			
						$page_mainContent .= "
								<div class=\"customizeOptionsLeftPeice\">
									<img src=\"" . $row['image'] . "\" alt=\"" . $row['name'] . "\" rel=\"" . $ss_config['ftsss_thumbnail_rel_tag'] . "\" title=\"" . $row['name'] . "\" />
								</div>";
					}
					
					$page_mainContent .= "
							<div class=\"customizeOptionsRightPeice\">
								<input type=\"hidden\" name=\"slideStatus" . $productCatID . "\" id=\"slideStatus" . $productCatID . "\" value=\"1\" />
								<div class=\"customizeOptionsPartsName customizeOptionsWidth\">
									<!--<span class=\"floatRight\"><a href=\"\" onClick=\"ajaxShowHideSliderWithImg('" . $productCatID . "'); return false;\"><img id=\"slideImg" . $productCatID . "\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/collapseicon.jpg\" alt=\"\" border=\"0\"></a></span>-->
									" . $row['name'] . "
								</div>
								<div id=\"slideDiv" . $productCatID . "\">
									<div class=\"customizeOptionsPartsDescription\">" . $description . "<a href=\"javascript: more_info_win('" . $modelID . "', '" . $productCatID . "')\">More Info on this Category</a></div>
									<br /><br />
									<div class=\"customizePartRowHolder\">";			
			
					// Print the parts themselves
					$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "products` WHERE type LIKE '%x" . $productCatID . "x%' AND (models LIKE '%x" . $modelID . "x%' OR models='') AND active='1' ORDER BY sort";
					$result2 = mysql_query($sql2);
				
					if ($result2 && mysql_num_rows($result2) > 0) {
						while ($row2 = mysql_fetch_array($result2)) {
							$page_mainContent .= "
											<div id=\"boxSpan" . $row2['id'] ."\" onMouseOver=\"this.className='customizePartHover'\" onMouseOut=\"checkMouseOut()\"><label class=\"customizePartRadioLabel\"><input type=\"radio\" name=\"Parts[" . $productCatID . "]\" id=\"" . $productCatID . "\" class=\"customizePartRadioInput\" value=\"" . $row2['id'] . "\" onClick=\"startUpdate('" . $productCatID . "', " . $row2['id'] .");\"" . testChecked($row2['id'], $selectedPartID) . " /> " . $row2['name'] . " <span name=\"pd" . $row2['id'] . $productCatID . "\" id=\"pd" . $row2['id'] . $productCatID . "\" class=\"boldFont\"></span></label></div>";
						}
						mysql_free_result($result2);
					}
					
					$page_mainContent .= "
									</div>
								</div>
							</div>
							<br class=\"clear\" /><br class=\"clear\" />
							<hr class=\"customizeOptionsPartsDivider\" />
							<br class=\"clear\" /><br class=\"clear\" />";		
				}		
				mysql_free_result($result);
			}
		}
		
		//==================================================
		// Start printing our page 
		//==================================================
		$page_content = "
						<script type=\"text/javascript\">
							var currencySymbol = '" . returnCurrencySymbol() . "';
							var priceMask = " . $priceMask . ";
							var basePrice = " . $baseTotal . ";
							var baseDiscount = " . $baseDiscount . ";
							var baseDiscountPercentage = " . $baseDiscountPercentage . ";
							var Parts = new Array();
							var PartIDs = new Array();
							var PartNames = new Array();
							" . $JS_partIDArrays . "
							
							function init() {
								SetDiagramPosition();
								" . $JS_partIDsAndUpdates . "
								updatePrices();
							}				
						</script>
						
					<div id=\"customizeOptionsFormHolder\">
						<form name=\"customizeForm\" id=\"customizeForm\" action=\"" . $menuvar['OVERVIEW'] . "\" method=\"post\" name=\"customizeform\">
							<input type=\"hidden\" name=\"ModelID\" id=\"ModelID\" value=\"" . $modelID . "\">
							" . $PartsList . "
							<div id=\"modelImageHolder\">";
							
		if ($modelImageThumb != "") {			
			$page_content .= "
								<img src=\"" . $modelImageThumb . "\" alt=\"" . $modelName . "\" />";
		}			
				
		$page_content .= "		
							</div>
							<div id=\"modelNameHolder\">
								<span id=\"modelName\">" . $modelName . "</span><br />
								<span class=\"customizeprice\" id=\"PriceTop\" class=\"boldFont\"></span>						
							</div>
							<br class=\"clear\" /><br class=\"clear\" />
							<div id=\"customizeOptionsHeader\">Configure Your " . $modelName . "</div>
							<div id=\"customizeOptionsHolder\">
								" . $page_mainContent . "
								<div style=\"text-align: center;\">
									<span class=\"customizeprice\" id=\"PriceBottom\" class=\"boldFont\"></span><br />
									<input type=\"submit\" name=\"submit\" class=\"continueButton\" value=\"Continue\" />
								</div>
							</div>
							
							<!-- Slider Box Begin -->
							<div id=\"PriceBox_Layer\" style=\"position: absolute; width: 125px;\">
								<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"125px\">
									<tr class=\"priceboxheader\">
										<td>
											<div id=\"configurationHeader\">Current Configuration</div>
											<div id=\"configurationItemsHolder\">
												<ul id=\"configurationItems\">
													" . $page_sliderContent . "
												</ul>
											</div>
											<br /><br />
										</td>
									</tr>
									<tr class=\"priceboxheader\">
										<td>
											<strong>Your Current Total</strong>
										</td>
									</tr>
									<tr>
										<td>
											<span" . $sliderPricehidden . ">
												<strong>Base Total:</strong> <span class=\"customizeprice\" id=\"PriceSlideBase\"></span><br />
												<strong>Discount:</strong> <span class=\"customizeprice\" id=\"PriceSlideDiscount\">$" . number_format($baseDiscount + (($baseDiscountPercentage / 100) * $baseTotal)) . "</span><br />
											</span>
											<strong>Your Total:</strong> <span class=\"customizeprice\" id=\"PriceSlide\"></span><br />
											<br />
											<strong>Current Promotions</strong><br />
											" . $discountDescription . "
											<br /><br />
											<input type=\"submit\" class=\"continueButton\" value=\"Continue\" />
											</center>
										</td>
									</tr>
								</table>
							</div>							
							<!-- Slider Box End -->
						</form>
					</div>";
							
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou must provide the proper set of variables for this page to work");
	}
?>