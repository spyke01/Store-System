<? 
/***************************************************************************
 *                               overview.php
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
	if (isset($_POST['Parts'])) {
		$postedParts = $_POST['Parts'];
		$postedModelID = keepSafe($_POST['ModelID']);
		$defaultPartCatIDs = array();
		
		// Get Model Info
		$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` WHERE models_id ='" . $postedModelID . "' LIMIT 1";
		$result = mysql_query($sql);
		
		while ($row = mysql_fetch_array($result)) {
			$modelName = $row['models_name'];
			$modelImageFull = $row['models_image_full'];
			$modelImageThumb = $row['models_image_thumb'];
			$basePrice = $row['models_base_price'];
			$baseProfit = $row['models_base_profit'];
			$baseDiscount = $row['models_discount'];
			$baseDiscountPercentage = $row['models_discount_percentage'];
			$baseTotal = $basePrice + $baseProfit - $baseDiscount;
			$baseTotalWithoutDiscount = $basePrice + $baseProfit;
		}		
		mysql_free_result($result);
		
		$overallprice = $baseTotal;

		//=======================================================
		// Create form items that we use more than once
		//=======================================================
		// Create hidden form fields for our chosen parts
		$hiddenFormFields = "";
		foreach ($postedParts as $productCatID => $selectedPartID) {
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "products` WHERE id = '" . $selectedPartID . "' AND active='1'";
			$result = mysql_query($sql);
				
			while($row = mysql_fetch_array($result)) {
				$overallprice += $row['price'] + $row['shipping_costs'] + $row['profit'];
				$hiddenFormFields .= "			
							<input type=\"hidden\" name=\"Parts[" . $productCatID . "]\" value=\"" . $row['id'] . "\">";
			} 
			mysql_free_result($result);
		}	

		//=======================================================
		// Print emailer form
		//=======================================================
		if ($modelImageThumb != "") {			
			$page_content .= "
					<div class=\"center\">
						<img src=\"" . $modelImageThumb . "\" alt=\"" . $modelName . "\" />
						<br />
					</div>";
		}	
		
		$page_content .= "			
						<form name=\"emailForm\" id=\"emailForm\" action=\"\" method=\"post\" onsubmit=\"return false;\">
							<input type=\"hidden\" name=\"ModelID\" id=\"ModelID\" value=\"" . $postedModelID . "\">
							" . $hiddenFormFields . "
							<div name=\"updateMe\" id=\"updateMe\">
								Email this page to: <input type=\"text\" name=\"email\" class=\"required validate-email\" />
								<input type=\"submit\" class=\"emailSpecsButton\" name=\"submit\" value=\"Send\" />
							</div>
						</form>
						<script language = \"Javascript\">
							var valid = new Validation('emailForm', {immediate : true, useTitles:true, onFormValidate : ValidateForm});
				
							function ValidateForm(result, form) {
								if (result) {
									new Ajax.Updater('updateMe', 'ajax.php?action=sendEmailofParts', {asynchronous:true, parameters:Form.serialize('emailForm'), evalScripts:true});				
								}
								return false;
			 				}
						</script>";
		
		//=======================================================
		// Print summary and add to cart form
		//=======================================================
		$page_content .= "
						</div>
						<br class=\"clear\" /><br class=\"clear\" />
						<form action=\"" . $menuvar['CART'] . "\" method=\"post\" name=\"customizeform\">
							<input type=\"hidden\" name=\"ModelID\" id=\"ModelID\" value=\"" . $postedModelID . "\">
							" . $hiddenFormFields . "
							<strong>Model Name: </strong>" . $modelName . "<br /><br />";
		
		// Handle putting our productcats into the proper order since we cannot use SQL down the road to order things.
		$extraSQL = "";
		
		foreach($postedParts as $productCatID => $selectedPartID) {
			$extraSQL .= ($extraSQL == "") ? " WHERE" : " OR";
			$extraSQL .= " id = '" . $productCatID . "'";
		}
		
		$sql = "SELECT id FROM `" . DBTABLEPREFIX . "productcats`" . $extraSQL . " ORDER BY sort_order ASC";
		$result = mysql_query($sql);	
		
		if ($result && mysql_num_rows($result) > 0) {				
			while ($row = mysql_fetch_array($result)) {
				// Push the values onto our temp arrays
				array_push($defaultPartCatIDs, $row['id']);
			}
			mysql_free_result($result);
		}
		
		// Now that the array is in order we can take and print our parts and their categories, we also strip out any items that are called none
		foreach ($defaultPartCatIDs as $key => $productCatID) {
			$productCatID = keepSafe($productCatID);
			$selectedPartID = keepSafe($postedParts[$productCatID]);
			$partName = getProductNameFromID($selectedPartID, 0);
			
			$page_content .= ($partName != "") ? "							
								<strong>" . getProductCatNameFromID($productCatID) . ": </strong>" . $partName . "<br />";
		}
		
		$page_content .= "
							<br class=\"clear\" /><br class=\"clear\" />
							<div style=\"text-align: center;\">
								<span class=\"customizeprice\" id=\"PriceBottom\" class=\"boldFont\">" . formatCurrency($overallprice - ($overallprice * ($baseDiscountPercentage / 100))) . "</span><br />
								<input type=\"submit\" name=\"submit\" class=\"continueButton\" value=\"Continue\" />
							</div>
						</form>";
							
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou must provide the proper set of variables for this page to work");
	}
?>