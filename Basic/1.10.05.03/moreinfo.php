<?
/***************************************************************************
 *                               moreinfo.php
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
	include 'includes/header.php';
	
	$actual_id = keepsafe($_GET['id']);
	$page_content = "";
	
	// Make sure we have sent a ModelID over to this page
	if ($actual_id != "") {
		$sql = "SELECT models_name, models_image_thumb FROM `" . DBTABLEPREFIX . "models` WHERE models_id='" . $actual_id . "'";
		$result = mysql_query($sql);
		
		// Print out our Models image and name
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				if ($row['models_image_thumb'] != "") {			
					$page_content .= "
									<img src=\"" . $row['models_image_thumb'] . "\" alt=\"" . $row['models_name'] . "\" />	
									<br />";
				}
				$page_content .= "<h2>" . $row['models_name'] . "</h2><br />";
			}
			mysql_free_result($result);
		}
		
		$sql = "SELECT p.id, p.name, p.type, p.models, p.description, pc.name, pc.id, p.sort FROM `" . DBTABLEPREFIX . "productcats` pc, `" . DBTABLEPREFIX . "products` p WHERE SUBSTRING(p.type, 2, LENGTH(pc.id)) = pc.id AND p.models LIKE '%x" . $actual_id . "x%' ORDER BY pc.name, p.sort";
		$result = mysql_query($sql);
		$firstRound = 1;
		$lastType = "";
		
		// Print out our Parts, their names and descriptions
		if ($result && mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result)) {
				if ($row['id'] != $lastType) {
					$page_content .= "
									<a name=\"" . $row['id'] . "\"></a>
									<h3>" . $row['name'] . "</h3><br />";
				}
			
				$page_content .= "<strong>" . $row['name'] . "</strong><br />" . $row['description'] . "<br /><br />";
			
				$lastType = $row['id'];
			}
			mysql_free_result($result);
		}
	}
	
	$page->setTemplateVar("PageContent", $page_content);
	
	// Use the mini page template for this popup
	include "themes/" . $ss_config['ftsss_theme'] . "/minitemplate.php";
?>