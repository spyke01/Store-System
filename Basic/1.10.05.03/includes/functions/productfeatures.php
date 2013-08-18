<?php 
/***************************************************************************
 *                               productfeatures.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton - Fast Track Sites
 *   email                : sales@fasttacksites.com
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
 
//=========================================================
// Gets a product feature's name from an id
//=========================================================
function getProductFeatureNameFromID($productFeatureID) {
	$sql = "SELECT name FROM `" . DBTABLEPREFIX . "products_features` WHERE id='" . $productFeatureID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['name'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Print the ProductFeatures Table
//=========================================================
function printProductFeaturesTable() {
	global $menuvar, $ss_config;
	$productFeaturesids = array();
	
			$x = 1; //reset the variable we use for our row colors	
			
			$content = "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"7\">
										<div class=\"floatRight\">
											<form name=\"newProductFeaturesForm\" id=\"newProductFeaturesForm\" action=\"" . $menuvar['PRODUCTFEATURES'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newproductfeaturesname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Product Features
									</td>
								</tr>
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Product</strong></td><td style=\"width: 5%;\"></td>
								</tr>";
			
			// get productFeatures that have a type
			$sql = "SELECT pf.id, pf.name, p.name AS productName FROM `" . DBTABLEPREFIX . "products_features` pf LEFT JOIN `" . DBTABLEPREFIX . "products` p ON pf.product_id = p.id ORDER BY p.name, pf.name";
			$result = mysql_query($sql);
			//echo $sql;
			
			if ($result && mysql_num_rows($result) > 0) {						
				while ($row = mysql_fetch_array($result)) {
					$content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . $row['productName'] . "</td>
											<td>
												<center><a href=\"" . $menuvar['PRODUCTFEATURES'] . "&action=editproductfeatures&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "ProductFeaturesSpinner', 'ajax.php?action=deleteitem&table=product_features&id=" . $row['id'] . "', 'Product Feature', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete ProductFeatures\" /></a><span id=\"" . $row['id'] . "ProductFeaturesSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></center>
											</td>
										</tr>";
										
					$productFeaturesids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
			
			$content .= "
									</table>
									<script type=\"text/javascript\">";
			
			// Generate the AJAX code for inPlaceEditors for our main table
			$x = 1; //reset the variable we use for our highlight colors
			foreach($productFeaturesids as $key => $value) {
				$highlightColors = ($x == 1) ? "highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6'" : "highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC'";
				$content .= "
															new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=product_features&item=name&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=product_features&item=name&id=" . $key . "'});";
				
				$x = ($x==2) ? 1 : 2;
			}
			
			$content .= "
									</script>";
			
			return $content;
}

//=========================================================
// Print the ProductFeatures Table
//=========================================================
function printProductFeaturesFromProductID($productID) {
	global $menuvar, $ss_config;
	$productFeaturesids = array();
			
	// get productFeatures that have a type
	$sql = "SELECT id, name FROM `" . DBTABLEPREFIX . "products_features` WHERE product_id = '" . $productID . "' ORDER BY name";
	$result = mysql_query($sql);
	//echo $sql;
			
	if ($result && mysql_num_rows($result) > 0) {						
		while ($row = mysql_fetch_array($result)) {
			$content .=	"					
										<label for=\"productFeatures" . $row['id'] . "\">" . $row['name'] . "</label>
										<select name=\"productFeatures[" . $row['id'] . "]\" id=\"productFeatures" . $row['id'] . "\">
											<option value=\"\">--Select One--</option>";
			
			// get productFeatures that have a type
			$sql2 = "SELECT id, price, name FROM `" . DBTABLEPREFIX . "products_features_values` WHERE feature_id='" . $row['id'] . "' ORDER BY id ASC";
			$result2 = mysql_query($sql2);
			//echo $sql2;
			
			if ($result2 && mysql_num_rows($result2) > 0) {						
				while ($row2 = mysql_fetch_array($result2)) {
					$content .=	"					<option value=\"" . $row2['id'] . "\">" . $row2['name'] . " (" . formatCurrency($row2['price']) . ")</option>";
				}
			}
			mysql_free_result($result2);
											
			$content .=	"					
										</select>
										<br />";
		}
	}
	mysql_free_result($result);
			
	return $content;
}

?>