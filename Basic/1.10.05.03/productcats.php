<? 
/***************************************************************************
 *                               productcats.php
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
		if ($actual_action == "editproductcats" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Product Category", "");
			if(isset($_POST['name']))
			{
				$name = keeptasafe($_POST['name']);
				$description = $_POST['description'];
				$image = $_POST['image'];
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "productcats` SET name='" . $name . "', description='" . $description . "', image='" . $image . "' WHERE id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your product category has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['PRODUCTCATS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your product category. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['PRODUCTCATS'] . "\">";						
				}
				
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "productcats` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['PRODUCTCATS'] . "&action=editproductcats&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Product Category</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"30%\"><strong>Name: </strong></td>
														<td><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Description: </strong></td>
														<td><textarea name=\"description\" cols=\"45\" rows=\"5\">" . $row['description'] . "</textarea></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Image: </strong></td>
														<td><input type=\"text\" name=\"image\" size=\"40\" value=\"" . $row['image'] . "\" /></td>
													</tr>
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" /></div>
											</form>
											<br /><br />";
				}
				else { $page_content .= "No such ID was found in the database!"; }
			}			
		}			
		else {
			//==================================================
			// Print out our productcats table
			//==================================================
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "productcats` ORDER BY sort_order ASC";
			$result = mysql_query($sql);
				
			$x = 1; //reset the variable we use for our row colors	
				
			$page_content = "
						<div id=\"updateMe\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"5\">
										<div class=\"floatRight\">
											<form name=\"newProductcatsForm\" action=\"" . $menuvar['PRODUCTCATS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newproductcatsname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Part Categories
									</td>
								</tr>							
								<tr class=\"title2\">
									<td></td><td><strong>Name</strong></td><td><strong>Description</strong></td><td></td>
								</tr>";
			$productcatsids = array();
			if ($result && mysql_num_rows($result) == 0) { // No productcats yet!
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"4\">There are no productcats in the database.</td>
													</tr>";	
			}
			else {	 // Print all our productcats								
				while ($row = mysql_fetch_array($result)) {
					
					$page_content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><img src=\"" . $row['image'] . "\" alt=\"\" /></td>
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td><div id=\"" . $row['id'] . "_description\">" . bbcode($row['description']) . "</div></td>
											<td width=\"5%\">
												<span class=\"center\"><a href=\"" . $menuvar['PRODUCTCATS'] . "&action=editproductcats&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "ProductcatsSpinner', 'ajax.php?action=deleteitem&table=productcats&id=" . $row['id'] . "', 'productcats', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Productcats\" /></a><span id=\"" . $row['id'] . "ProductcatsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
											</td>
										</tr>";
					$productcatsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$page_content .= "					
									</table>
									<script type=\"text/javascript\">";
			
			$x = 1; //reset the variable we use for our highlight colors
			foreach($productcatsids as $key => $value) {
				$page_content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=productcats&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=productcats&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=productcats&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=productcats&item=name&id=" . $key . "'});";
				$page_content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_description', 'ajax.php?action=updateitem&table=productcats&item=description&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=productcats&item=description&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_description', 'ajax.php?action=updateitem&table=productcats&item=description&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=productcats&item=description&id=" . $key . "'});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$page_content .= "
									</script>
								</div>
				<script language = \"Javascript\">				
				function ValidateForm(theForm){
					var name=document.newProductcatsForm.newproductcatsname
					
					if ((name.value==null)||(name.value==\"\")){
						alert(\"Please enter the new product category\'s name.\")
						name.focus()
						return false
					}
					new Ajax.Updater('updateMe', 'ajax.php?action=postproductcats', {onComplete:function(){ new Effect.Highlight('newProductcats');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
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