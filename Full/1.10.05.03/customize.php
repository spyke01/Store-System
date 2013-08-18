<? 
/***************************************************************************
 *                               customize.php
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
	// If we are editing an order we need to set the system 
	// accordingly. When editing an order we do not know if the 
	// part was originally a page two item or not so we handle 
	// all items on one page and send the result to the cart to modify the order 
	//==================================================
 	if ($actual_action == "editSystem" && isset($actual_id)) {
		$nextPage = $menuvar['CART'] . "&action=updateSystem&id=" . $actual_id;
		
		// Get system parts and partcats so that we can rebuild our customize page
		$sql = "SELECT sp.part_id, sp.partcat_id, s.model_id FROM `" . DBTABLEPREFIX . "systems` s LEFT JOIN `" . DBTABLEPREFIX . "systemparts` sp ON sp.system_id=s.id WHERE s.id = '" . $actual_id . "'";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			$defaultParts = array();
			
			while ($row = mysql_fetch_array($result)) {	
				$defaultParts[$row['partcat_id']] = $row['part_id'];
				$modelID = $row['model_id'];
			}
			mysql_free_result($result);
		}				
	}
	//==================================================
	// If we are returning from the second page by using 
	// the back button on the page (not the toolbar one) 
	// then we need to repopulate our arrays using the posted items 
	//==================================================
 	elseif ($actual_action == "backFromPage2") {
		$nextPage = $menuvar['CUSTOMIZE2'];
		
		// Build our parts from our URL strings		
		$defaultParts = keepSafe($_GET['Defaults']);
		$defaultParts2 = keepSafe($_GET['Defaults2']);
		$modelID = keepSafe($_GET['ModelID']);
		$defaultPartsTemp = array();
		$defaultParts2Temp = array();
		
		// URL strings look like this
		// Defaults=catid1##partid1$$catid2##partid2
		// we need to split these strings into usable arrays
		$defaultPartsSplit = explode("$$", $defaultParts);
		$defaultParts2Split = explode("$$", $defaultParts2);
		
		// Now that our array is split into categories we need to make a split again and get the catid and partid and then populate our new arrays
		foreach ($defaultPartsSplit as $key => $chunk) {
			$splitChunk = explode("##", $chunk);
			$defaultPartsTemp[$splitChunk[0]] = $splitChunk[1];
		}
		foreach ($defaultParts2Split as $key => $chunk) {
			$splitChunk = explode("##", $chunk);
			$defaultParts2Temp[$splitChunk[0]] = $splitChunk[1];
		}
		
		// Replace the original arrays with our new ones
		$defaultParts = $defaultPartsTemp;
		$defaultParts2 = $defaultParts2Temp;
	}
	//==================================================
	// Otherwise we just populate our arrays 
	//==================================================
	else {
		$nextPage = $menuvar['CUSTOMIZE2'];
		$defaultParts = $_POST['Defaults'];
		$defaultParts2 = $_POST['Defaults2'];
		$modelID = keepSafe($_POST['ModelID']);
	}
	
	// If there are no page 2 parts then go to the overview page next
	$nextPage = (is_array($defaultParts2)) ? $menuvar['CUSTOMIZE2'] : $menuvar['OVERVIEW'];
	
	//==================================================
	// Start building our page 
	//==================================================
	if (isset($defaultParts)) {
		$defaultPats2Inputs = "";
		$priceMask = rand(600000,900000);
		$baseTotal = 0;
		$defaultPartCatIDs = array();
		$defaultPartIDs = array();
		
		// Get Model Info and begin to calculate our total cost
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` WHERE id ='" . $modelID . "' LIMIT 1";
		$result = mysql_query($sql);
		
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				$modelName = $row['name'];
				$modelImageFull = $row['image_full'];
				$modelImageThumb = $row['image_thumb'];
				$basePrice = $row['base_price'];
				$baseProfit = $row['base_profit'];
				$baseDiscount = $row['discount'];
				$baseDiscountPercentage = $row['discount_percentage'];
				$discountDescription = $row['discount_description'];
				$discountDescription = ($discountDescription == "") ? "None" : $discountDescription;
				$baseTotal = $basePrice + $baseProfit - $baseDiscount;
				$baseTotalWithoutDiscount = $basePrice + $baseProfit;
			}		
			mysql_free_result($result);
		}
		
		// Hide our discount on the slider if we don't have one
		$sliderPricehidden = ($baseDiscount > 0 || $baseDiscountPercentage > 0) ? "" : " style=\"display: none;\"";
		
		// Hide our slider if we have chosen to
		$sliderhidden = ($ss_config['ftsss_slider_active'] == 1) ? "" : " style=\"display: none;\"";
		
		// Create the hidden form fields to our page two parts
		foreach($defaultParts2 as $partcatID => $selectedPartID) {
			$defaultPats2Inputs .= "\n
								<input type=\"hidden\" name=\"Defaults2[" . $partcatID . "]\" value=\"" . $selectedPartID . "\">";
		}
		
		// Handle putting our partcats into the proper order since we cannot use SQL down the road to order things.
		$extraSQL = "";
		
		foreach($defaultParts as $partcatID => $selectedPartID) {
			$extraSQL .= ($extraSQL == "") ? " WHERE" : " OR";
			$extraSQL .= " id = '" . $partcatID . "'";
		}
		
		$sql = "SELECT id FROM `" . DBTABLEPREFIX . "partcats`" . $extraSQL . " ORDER BY sort_order ASC";
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
		// Cycle through the parts list and build our page vars
		//==================================================
		foreach ($defaultParts as $partcatID => $selectedPartID) {
			$PartCatsPartIDs = "";
			
			//==================================================
			// Build our JS items
			//==================================================
			$JS_partIDArrays .= "\n							Parts[" . $partcatID . "] = new Array(); ";		
		
			// Select only those components compatible with this model
			$sql = "SELECT id, name, price, shipping_costs, profit  FROM `" . DBTABLEPREFIX . "parts` WHERE type LIKE '%x" . $partcatID . "x%' AND (models LIKE '%x" . $modelID . "x%' OR models='') AND active='1' ORDER BY sort";
			$result = mysql_query($sql);
				
			if($result && mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
					// Disguise the price with a mask that we will "mod" out later
					$overallprice = $priceMask * ($row['price'] + $row['shipping_costs'] + $row['profit']);
					
					$JS_partIDArrays .= "\n							Parts[" . $partcatID . "][" . $row['id'] . "] = " . $overallprice . ";";
					$PartCatsPartIDs .= ($PartCatsPartIDs == "") ? "" : ", ";
					$PartCatsPartIDs .= "'" . $row['id'] . "'";
					
					$showPartName = 1;
					$sql2 = "SELECT on_slider FROM `" . DBTABLEPREFIX . "partcats` WHERE id = '" . $partcatID . "' LIMIT 1";
					$result2 = mysql_query($sql2);
					
					if($result2 && mysql_num_rows($result2) > 0) {
						while($row2 = mysql_fetch_array($result2)) {
							$showPartName = ($row2['on_slider'] == 0) ? 0 : 1;
						}
						mysql_free_result($result2);
					}
					
					$partName = ($showPartName != 1) ? "" : $row['name'];
					
					$JS_partIDArrays .= "\n							PartNames[" . $row['id'] . "] = '" . $partName . "'; ";
			
					//==================================================
					// Build our slider items at the same time to avoid unnecessary DB calls
					//==================================================
					$page_sliderContent .= ($showPartName != 1 || $row['id'] != $selectedPartID) ? "" : "											<li><span id=\"sliderSpan" . $partcatID . "\">" . handleQuotes($partName) . "</span></li>";
				}
				mysql_free_result($result);
			} 
			
			$JS_partIDArrays .= "\n							PartIDs[" . $partcatID . "] = new Array(" . $PartCatsPartIDs . ");";
		
			$JS_partIDsAndUpdates .= "\n
								id" . $partcatID . " = " . $selectedPartID . "; 
								startUpdate(" . $partcatID . ", " . $selectedPartID . "); ";
								
			//==================================================
			// Build our actual page items
			//==================================================
			// Pull the selected partid for this partcat
			$selectedPartID = $defaultPartIDs[$key];
			
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "partcats` WHERE id ='" . $partcatID . "'";
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
								<input type=\"hidden\" name=\"slideStatus" . $partcatID . "\" id=\"slideStatus" . $partcatID . "\" value=\"1\" />
								<div class=\"customizeOptionsPartsName customizeOptionsWidth\">
									<!--<span class=\"floatRight\"><a href=\"\" onClick=\"ajaxShowHideSliderWithImg('" . $partcatID . "'); return false;\"><img id=\"slideImg" . $partcatID . "\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/collapseicon.jpg\" alt=\"\" border=\"0\"></a></span>-->
									" . $row['name'] . "
								</div>
								<div id=\"slideDiv" . $partcatID . "\">
									<div class=\"customizeOptionsPartsDescription\">" . $description . "<a href=\"javascript: more_info_win('" . $modelID . "', '" . $partcatID . "')\">More Info on this Category</a></div>
									<br /><br />
									<div class=\"customizePartRowHolder\">";			
			
					// Print the parts themselves
					$sql2 = "SELECT * FROM `" . DBTABLEPREFIX . "parts` WHERE type LIKE '%x" . $partcatID . "x%' AND (models LIKE '%x" . $modelID . "x%' OR models='') AND active='1' ORDER BY sort";
					$result2 = mysql_query($sql2);
				
					if ($result2 && mysql_num_rows($result2) > 0) {
						while ($row2 = mysql_fetch_array($result2)) {
							$page_mainContent .= "
											<div id=\"boxSpan" . $row2['id'] ."\" onMouseOver=\"this.className='customizePartHover'\" onMouseOut=\"checkMouseOut()\"><label class=\"customizePartRadioLabel\"><input type=\"radio\" name=\"Parts[" . $partcatID . "]\" id=\"" . $partcatID . "\" class=\"customizePartRadioInput\" value=\"" . $row2['id'] . "\" onClick=\"startUpdate('" . $partcatID . "', " . $row2['id'] .");\"" . testChecked($row2['id'], $selectedPartID) . " /> " . $row2['name'] . " <span name=\"pd" . $row2['id'] . $partcatID . "\" id=\"pd" . $row2['id'] . $partcatID . "\" class=\"boldFont\"></span></label></div>";
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
								SetDiagramPosition('" . $ss_config['ftsss_slider_side'] . "', " . $ss_config['ftsss_slider_padd_side'] . ", " . $ss_config['ftsss_slider_padd_top'] . ", " . $ss_config['ftsss_slider_padd_side_ie'] . ", " . $ss_config['ftsss_slider_padd_top_ie'] . ");
								" . $JS_partIDsAndUpdates . "
								updatePrices();
							}				
						</script>
						
					<div id=\"customizeOptionsFormHolder\">
						<form name=\"customizeForm\" id=\"customizeForm\" action=\"" . $nextPage . "\" method=\"post\" name=\"customizeform\">
							<input type=\"hidden\" name=\"ModelID\" id=\"ModelID\" value=\"" . $modelID . "\">
							" . $defaultPats2Inputs . "
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
							
							<!-- Slider Box Begin -->
							<div id=\"PriceBox_Layer\"" . $sliderhidden . " style=\"position: absolute; width: 200px;\">
								<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"200px\">
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
							</div>
						</form>
					</div>";
							
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou must provide the proper set of variables for this page to work");
	}
?>