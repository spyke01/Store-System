<?php 
/***************************************************************************
 *                               products.php
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
// Gets a product's name from an id
//=========================================================
function getProductNameFromID($productID, $includeItemsCalledNone = 0) {
	$extraSQL = ($includeItemsCalledNone == 0) ? " AND UCASE(name)!='NONE'" : "";
	
	$sql = "SELECT name FROM `" . DBTABLEPREFIX . "products` WHERE id='" . $productID . "'" . $extraSQL;
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['name'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Prints a product block based on a productID
//=========================================================
function printProductBlock($productCatID, $productID) {
	global $menuvar, $ss_config;
	$content = "";
	$thumbnailHTML = "";
	
	$sql = "SELECT name, price, shipping_costs, profit, image_full, image_thumb FROM `" . DBTABLEPREFIX . "products` WHERE id = '" . $productID . "' LIMIT 1";
	$result = mysql_query($sql);
			
	if ($result && mysql_num_rows($result) > 0) {						
		while ($row = mysql_fetch_array($result)) {
			$thumbnailHTML .= ($row['image_thumb'] != "") ? "<img src=\"" . $row['image_thumb'] . "\" alt=\"" . $row['name'] . "\" />" : "<img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/noImage.png\" alt=\"" . $row['name'] . "\" />";
			
			$content .= "
							<div class=\"productBlock\">
								<form name=\"addToCartForm\" action=\"" . $menuvar['CART'] . "&action=addToCart&productCatID=" . $productCatID . "&id=" . $productID . "\" method=\"post\">
									<p class=\"productImage\"><a href=\"" . $menuvar['VIEWPRODUCT'] . "&productCatID=" . $productCatID . "&id=" . $productID . "\">" . $thumbnailHTML . "</a></p>
									<p class=\"productName\"><a href=\"" . $menuvar['VIEWPRODUCT'] . "&productCatID=" . $productCatID . "&id=" . $productID . "\">" . $row['name'] . "</a></p>
									<p class=\"productPrice\">" . formatCurrency($row['price'] + $row['shipping_costs'] + $row['profit']) . "</p>
									<br /><br />
									" . printProductFeaturesFromProductID($productID) . "
									<br />
									<span class=\"productAddToCart\"><input type=\"submit\" name=\"submit\" value=\"Add to Cart\" /></span>
								</form>
							</div>";
		}
	mysql_free_result($result);
	}
	
	return $content;
}

//=========================================================
// Prints a product block based on a productID
//=========================================================
function printViewProductBlock($productCatID, $productID) {
	global $menuvar, $ss_config;
	$content = "";
	$thumbnailHTML = "";
	
	$sql = "SELECT name, description, qty, price, shipping_costs, profit, image_full, image_thumb FROM `" . DBTABLEPREFIX . "products` WHERE id = '" . $productID . "' LIMIT 1";
	$result = mysql_query($sql);
			
	if ($result && mysql_num_rows($result) > 0) {						
		while ($row = mysql_fetch_array($result)) {
			$thumbnailHTML .= ($row['image_full'] != "") ? "<a href=\"" . $row['image_full'] . "\" title=\"" . $row['name'] . "\" rel=\"" . $ss_config['ftsss_thumbnail_rel_tag'] . "\">" : "";
			$thumbnailHTML .= ($row['image_thumb'] != "") ? "<img src=\"" . $row['image_thumb'] . "\" alt=\"" . $row['name'] . "\" />" : "<img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/noImage.png\" alt=\"" . $row['name'] . "\" />";
			$thumbnailHTML .= ($row['image_full'] != "") ? "</a>" : "";
		
			$content .= "
							<div class=\"viewProductBlock\">
								<div class=\"productImage\">" . $thumbnailHTML . "</div>
								<div class=\"productInformation\">
									<form name=\"addToCartForm\" action=\"" . $menuvar['CART'] . "&action=addToCart&productCatID=" . $productCatID . "&id=" . $productID . "\" method=\"post\">
										<p class=\"productName\">" . $row['name'] . "</p>
										<p class=\"productDescription\">" . $row['description'] . "</p>
										<p class=\"productQty\">Number in Stock: " . $row['qty'] . "</p>
										<p class=\"productPrice\">" . formatCurrency($row['price'] + $row['shipping_costs'] + $row['profit']) . "</p>
										<br /><br />
										" . printProductFeaturesFromProductID($productID) . "
										<br />
										<span class=\"productAddToCart\"><input type=\"submit\" name=\"submit\" value=\"Add to Cart\" /></span>
									</form>
								</div>
							</div>";
		}
	mysql_free_result($result);
	}
	
	return $content;
}

//=========================================================
// Check if this item should be selected
//=========================================================
function printProductsTable($productCatID = "-1") {
	global $menuvar, $ss_config;
	$actual_id = parseurl($_GET['id']);
	
			$x = 1; //reset the variable we use for our row colors	
			$sqlParams = ($productCatID != "" && $productCatID >= 0) ? " AND (type LIKE '%x" . $productCatID . "x%' OR p.type = '')" : "";
			$sqlParams2 = ($productCatID != "" && $productCatID >= 0) ? " AND (p.type LIKE '%x" . $productCatID . "x%' OR p.type = '')" : "";
			
			$content = "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"7\">
										<div class=\"floatRight\">
											<form name=\"newProductsForm\" id=\"newProductsForm\" action=\"" . $menuvar['PRODUCTS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newproductsname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
												<a href=\"" . $menuvar['PRODUCTS'] . "&action=addmultiproducts\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add2.png\" /></a>
											</form>
										</div>
										Products
									</td>
								</tr>							
								<tr class=\"title2\">
									<td><strong>Category</strong></td>
									<td class=\"row1\" colspan=\"6\">
										<form name=\"searchProductsForm\" id=\"searchProductsForm\" action=\"\" method=\"post\" onSubmit=\"ajaxModelPicker(); return false;\">
											" . createDropdown("", "model", $actual_id, "") . "<input type=\"submit\" name=\"submit\" value=\"Go\" /><span id=\"modelPickerSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
										</form>
									</td>
								</tr>							
								<tr class=\"title2\">
									<td colspan=\"7\"><strong>Products Not Assigned to Any Category</strong></td>
								</tr>						
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Categories</strong></td><td><strong>Price</strong></td><td><strong>Shipping Costs</strong></td><td><strong>Profit</strong></td><td style=\"width: 5%;\"></td>
								</tr>";
			// get products that have no type					
			$sql = "SELECT id, name, type, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "products` WHERE type = '' ORDER BY sort";
			$result = mysql_query($sql);
			
			$productsids = array();
			if ($result && mysql_num_rows($result) > 0) {						
				while ($row = mysql_fetch_array($result)) {
							
					$content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . getProductCatList($row['type']) . "</td>
											<td><div id=\"" . $row['id'] . "_price\">" . formatCurrency($row['price']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_shipping_costs\">" . formatCurrency($row['shipping_costs']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_profit\">" . formatCurrency($row['profit']) . "</div></td>
											<td>
												<center><a href=\"" . $menuvar['PRODUCTS'] . "&action=editproducts&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "ProductsSpinner', 'ajax.php?action=deleteitem&table=products&id=" . $row['id'] . "', 'products', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Products\" /></a><span id=\"" . $row['id'] . "ProductsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></center>
											</td>
										</tr>";
					$productsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
			
			$catName = ($productCatID >= 0) ? " on " . getModelNameFromID($productCatID) : "";
			
			$content .= "
								<tr class=\"title2\">
									<td colspan=\"7\"><strong>Products Available" . $catName . "</strong></td>
								</tr>						
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Categories</strong></td><td><strong>Price</strong></td><td><strong>Shipping Costs</strong></td><td><strong>Profit</strong></td><td style=\"width: 5%;\"></td>
								</tr>";
			
			// get products that have a type
			$sql = "SELECT Distinct p.id, p.name, p.type, p.price, p.shipping_costs, p.profit, pc.name, p.sort FROM `" . DBTABLEPREFIX . "productcats` pc, `" . DBTABLEPREFIX . "products` p WHERE (p.type LIKE CONCAT('%x', pc.id, 'x%'))" . $sqlParams2 . " ORDER BY pc.name, p.sort";
			$result = mysql_query($sql);
			//echo $sql;
			if ($result && mysql_num_rows($result) > 0) {						
				while ($row = mysql_fetch_array($result)) {
					$content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . getProductCatList($row['type']) . "</td>
											<td><div id=\"" . $row['id'] . "_price\">" . formatCurrency($row['price']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_shipping_costs\">" . formatCurrency($row['shipping_costs']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_profit\">" . formatCurrency($row['profit']) . "</div></td>
											<td>
												<center><a href=\"" . $menuvar['PRODUCTS'] . "&action=editproducts&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "ProductsSpinner', 'ajax.php?action=deleteitem&table=products&id=" . $row['id'] . "', 'products', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Products\" /></a><span id=\"" . $row['id'] . "ProductsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></center>
											</td>
										</tr>";
										
					$productsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$content .= "
									</table>
									<script type=\"text/javascript\">";
			
			// Generate the AJAX code for inPlaceEditors for our main table
			$x = 1; //reset the variable we use for our highlight colors
			foreach($productsids as $key => $value) {
				$highlightColors = ($x == 1) ? "highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6'" : "highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC'";
				$content .= "
															new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=products&item=name&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=products&item=name&id=" . $key . "'});
															new Ajax.InPlaceEditor('" . $key . "_price', 'ajax.php?action=updateitem&table=products&item=price&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=products&item=price&id=" . $key . "'});
															new Ajax.InPlaceEditor('" . $key . "_shipping_costs', 'ajax.php?action=updateitem&table=products&item=shipping_costs&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=products&item=shipping_costs&id=" . $key . "'});
															new Ajax.InPlaceEditor('" . $key . "_profit', 'ajax.php?action=updateitem&table=products&item=profit&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=products&item=profit&id=" . $key . "'});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$content .= "
									</script>";
			
			return $content;
}

?>