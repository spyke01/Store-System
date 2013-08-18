<?php 
/***************************************************************************
 *                               productcats.php
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
// Gets a partcat's name from an id
//=========================================================
function getProductCatNameFromID($productCatID) {
	$sql = "SELECT name FROM `" . DBTABLEPREFIX . "productcats` WHERE id='" . $productCatID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['name'];
		}	
		mysql_free_result($result);
	}
}
 
//=========================================================
// Gets a partcat's description from an id
//=========================================================
function getProductCatDescriptionFromID($productCatID) {
	$sql = "SELECT description FROM `" . DBTABLEPREFIX . "productcats` WHERE id='" . $productCatID . "'";
	$result = mysql_query($sql);
	
	if ($result && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_array($result)) {
			return $row['description'];
		}	
		mysql_free_result($result);
	}
}

//=========================================================
// Gets a list of part categories based on a type string
//=========================================================
function getProductCatList($typeString) {
	$productcatsList = split(" ", str_replace("x", "", $typeString));
	$sqlParams = "";
	$listVar = "";
	$firstRound = 1;
	
	foreach ($productcatsList as $productCatID) {																
		$sqlParams .= ($firstRound == 1) ? " WHERE id='" . $productCatID . "'" : " OR id='" . $productCatID . "'";
		$firstRound = 0;
	}

	$sql = "SELECT id, name FROM `" . DBTABLEPREFIX . "productcats`" . $sqlParams . "";
	$result = mysql_query($sql);
	$firstRound = 1;
	
	while ($row = mysql_fetch_array($result)) {
		$listVar .= ($firstRound == 1) ? $row['name'] : "<br />" . $row['name'];
		$firstRound = 0;
	}
	return $listVar;
	
	mysql_free_result($result);
}

//=========================================================
// Prints the product blocks for a certain category
//=========================================================
function getNumberOfProductsInCat($productCatID) {
	$totalRows = 0;
	
	$extraSQL = ($productCatID != "") ? " WHERE active = '1'" : "";
	$extraSQL .= ($productCatID != "") ? " AND type LIKE '%x" . $productCatID . "x%'" : "";
	$sql = "SELECT COUNT(id) AS totalRows FROM `" . DBTABLEPREFIX . "products`" . $extraSQL . "";
	$result = mysql_query($sql);
			
	if ($result && mysql_num_rows($result) > 0) {						
		while ($row = mysql_fetch_array($result)) {
			$totalRows = $row['totalRows'];
		}
		mysql_free_result($result);
	}
	
	return $totalRows;
}

//=========================================================
// Prints the product blocks for a certain category
//=========================================================
function printProductCatsProducts($productCatID) {
	global $menuvar, $ss_config, $actual_page;
	$content = "";
	$thumbnailHTML = "";
	
	// Figure out if we are using pagination due to the number of products in this category
	$numOfProductsInCat = getNumberOfProductsInCat($productCatID);
	$usePagination = ($numOfProductsInCat <= $ss_config['ftsss_products_per_page']) ? 0 : 1;
	
	// Handle our pagination SQL building function
	if ($usePagination == 0) {
		$startAt = 0;
		$stopAt = $numOfProductsInCat;
	}
	else {
		// Make sure to take care of 0 values
		$actual_page = ($actual_page == 0) ? 1 : $actual_page;
		$usePagination = ($numOfProductsInCat <= $ss_config['ftsss_products_per_page']) ? 0 : 1;
		$totalPages = ($totalPages == 0) ? 1 : $totalPages;	
		
		// Determine which record to start and stop at based on page number
		$totalPages = $numOfProductsInCat / $ss_config['ftsss_products_per_page'];
				
		// Decimal places signify that another $page is needed
		$totalPages = ($totalPages > stripChange($totalPages)) ? stripChange($totalPages) + 1 : $totalPages;
		//echo $totalPages . " " . stripChange($totalPages, 0);
		
		// Calculate our starting row
		$startAt = ($actual_page == 1) ? 0 : $ss_config['ftsss_products_per_page'] * ($actual_page - 1);
				
		// Calculate our ending row
		$stopAt = ($actual_page == "" || $actual_page == 1) ? $ss_config['ftsss_products_per_page'] : $startAt + $ss_config['ftsss_products_per_page'];
					
		// Don't loop through a number that is greater than the total number of rows
		$stopAt = ($stopAt > $numOfProductsInCat) ? $numOfProductsInCat : $stopAt;
	}
	//echo $actual_page . " " . $usePagination . " " . $totalPages . " " . $startAt . " " . $stopAt . "<br />";
	
	$extraSQL = ($productCatID != "") ? " WHERE active = '1'" : "";
	$extraSQL .= ($productCatID != "") ? " AND type LIKE '%x" . $productCatID . "x%'" : "";
	$sql = "SELECT id FROM `" . DBTABLEPREFIX . "products`" . $extraSQL . " LIMIT " . $startAt . ", " . $stopAt;
	$result = mysql_query($sql);
	//echo $sql . "<br />";
			
	if ($result && mysql_num_rows($result) > 0) {						
		while ($row = mysql_fetch_array($result)) {
			$content .= printProductBlock($productCatID, $row['id']);
		}
		mysql_free_result($result);
	}
	
	$content .= "
				<br class=\"clear\" /><br class=\"clear\" />";
				
	$content .= ($usePagination == 1) ? generatePagination($menuvar['VIEWPRODUCTCATEGORY'] . "&id=" . $actual_id, $actual_page, $totalPages) : "";
	
	return $content;
}

?>