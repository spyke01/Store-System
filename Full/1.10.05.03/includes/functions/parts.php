<?php 
/***************************************************************************
 *                               parts.php
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
// Gets a part's name from an id
//=========================================================
function getPartNameFromID($partID, $includeItemsCalledNone = 0) {
	$extraSQL = ($includeItemsCalledNone == 0) ? " AND UCASE(name)!='NONE'" : "";
	
	$sql = "SELECT name FROM `" . DBTABLEPREFIX . "parts` WHERE id='" . $partID . "'" . $extraSQL;
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['name'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Check if this item should be selected
//=========================================================
function printPartsTable($modelID = "-1") {
	global $menuvar, $ss_config;
	$actual_id = parseurl($_GET['id']);
	
			$x = 1; //reset the variable we use for our row colors	
			$sqlParams = ($modelID != "" && $modelID >= 0) ? " AND (models LIKE '%x" . $modelID . "x%' OR p.models = '')" : "";
			$sqlParams2 = ($modelID != "" && $modelID >= 0) ? " AND (p.models LIKE '%x" . $modelID . "x%' OR p.models = '')" : "";
			
			$content = "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"7\">
										<div class=\"floatRight\">
											<form name=\"newPartsForm\" id=\"newPartsForm\" action=\"" . $menuvar['PARTS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newpartsname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
												<a href=\"" . $menuvar['PARTS'] . "&action=addmultiparts\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add2.png\" /></a>
											</form>
										</div>
										Parts
									</td>
								</tr>							
								<tr class=\"title2\">
									<td><strong>Model</strong></td>
									<td class=\"row1\" colspan=\"6\">
										<form name=\"searchPartsForm\" id=\"searchPartsForm\" action=\"\" method=\"post\" onSubmit=\"ajaxModelPicker(); return false;\">
											" . createDropdown("models", "model", $actual_id, "") . "<input type=\"submit\" name=\"submit\" value=\"Go\" /><span id=\"modelPickerSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span>
										</form>
									</td>
								</tr>							
								<tr class=\"title2\">
									<td colspan=\"7\"><strong>Parts Not Assigned to Any Category</strong></td>
								</tr>						
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Categories</strong></td><td><strong>Models</strong></td><td><strong>Price</strong></td><td><strong>Shipping Costs</strong></td><td><strong>Profit</strong></td><td style=\"width: 5%;\"></td>
								</tr>";
			// get parts that have no type					
			$sql = "SELECT id, name, type, models, price, shipping_costs, profit FROM `" . DBTABLEPREFIX . "parts` WHERE type = ''" . $sqlParams . " ORDER BY sort";
			$result = mysql_query($sql);
			//echo $sql;
			
			$partsids = array();
			if ($result && mysql_num_rows($result) > 0) {						
				while ($row = mysql_fetch_array($result)) {
							
					$content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . getPartcatList($row['products_type']) . "</td>
											<td>" . getModelList($row['models']) . "</td>
											<td><div id=\"" . $row['id'] . "_price\">" . formatCurrency($row['price']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_shipping_costs\">" . formatCurrency($row['shipping_costs']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_profit\">" . formatCurrency($row['profit']) . "</div></td>
											<td>
												<center><a href=\"" . $menuvar['PARTS'] . "&action=editparts&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "PartsSpinner', 'ajax.php?action=deleteitem&table=parts&id=" . $row['id'] . "', 'parts', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Parts\" /></a><span id=\"" . $row['id'] . "PartsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></center>
											</td>
										</tr>";
					$partsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
			
			$catName = ($modelID >= 0) ? " on " . getModelNameFromID($modelID) : "";
			
			$content .= "
								<tr class=\"title2\">
									<td colspan=\"7\"><strong>Parts Available" . $catName . "</strong></td>
								</tr>						
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Categories</strong></td><td><strong>Models</strong></td><td><strong>Price</strong></td><td><strong>Shipping Costs</strong></td><td><strong>Profit</strong></td><td style=\"width: 5%;\"></td>
								</tr>";
			
			// get parts that have a type
			$sql = "SELECT Distinct p.id, p.name, p.type, p.models, p.price, p.shipping_costs, p.profit, pc.name, p.sort FROM `" . DBTABLEPREFIX . "partcats` pc, `" . DBTABLEPREFIX . "parts` p WHERE (p.type LIKE CONCAT('%x', pc.id, 'x%'))" . $sqlParams2 . " ORDER BY pc.name, p.sort";
			$result = mysql_query($sql);
			//echo $sql;
			if ($result && mysql_num_rows($result) > 0) {						
				while ($row = mysql_fetch_array($result)) {
					$content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . getPartcatList($row['type']) . "</td>
											<td>" . getModelList($row['models']) . "</td>
											<td><div id=\"" . $row['id'] . "_price\">" . formatCurrency($row['price']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_shipping_costs\">" . formatCurrency($row['shipping_costs']) . "</div></td>
											<td><div id=\"" . $row['id'] . "_profit\">" . formatCurrency($row['profit']) . "</div></td>
											<td>
												<center><a href=\"" . $menuvar['PARTS'] . "&action=editparts&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "PartsSpinner', 'ajax.php?action=deleteitem&table=parts&id=" . $row['id'] . "', 'parts', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Parts\" /></a><span id=\"" . $row['id'] . "PartsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></center>
											</td>
										</tr>";
										
					$partsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$content .= "
									</table>
									<script type=\"text/javascript\">";
			
			// Generate the AJAX code for inPlaceEditors for our main table
			$x = 1; //reset the variable we use for our highlight colors
			foreach($partsids as $key => $value) {
				$highlightColors = ($x == 1) ? "highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6'" : "highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC'";
				$content .= "
															new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=parts&item=name&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=parts&item=name&id=" . $key . "'});
															new Ajax.InPlaceEditor('" . $key . "_price', 'ajax.php?action=updateitem&table=parts&item=price&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=parts&item=price&id=" . $key . "'});
															new Ajax.InPlaceEditor('" . $key . "_shipping_costs', 'ajax.php?action=updateitem&table=parts&item=shipping_costs&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=parts&item=shipping_costs&id=" . $key . "'});
															new Ajax.InPlaceEditor('" . $key . "_profit', 'ajax.php?action=updateitem&table=parts&item=profit&id=" . $key . "', {rows:1,cols:50," . $highlightColors . ",loadTextURL:'ajax.php?action=getitem&table=parts&item=profit&id=" . $key . "'});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$content .= "
									</script>";
			
			return $content;
}

?>