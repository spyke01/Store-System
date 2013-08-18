<? 
/***************************************************************************
 *                               models.php
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
		if ($actual_action == "editmodel" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Model", "");
			
			if(isset($_POST['name']))
			{
				$name = $_POST['name'];
				$description = $_POST['description'];
				$base_price = $_POST['base_price'];
				$base_weight = $_POST['base_weight'];
				$base_profit = $_POST['base_profit'];
				$discount = $_POST['discount'];
				$discount_percentage = $_POST['discount_percentage'];
				$discount_description = $_POST['discount_description'];
				$image_full = $_POST['image_full'];
				$image_thumb = $_POST['image_thumb'];
				$drivers = $_POST['drivers'];
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "models` SET name='" . $name . "', description='" . $description . "', base_price='" . $base_price . "', base_weight='" . $base_weight . "', base_profit='" . $base_profit . "', discount='" . $discount . "', discount_percentage='" . $discount_percentage . "', discount_description='" . $discount_description . "', image_full='" . $image_full . "', image_thumb='" . $image_thumb . "' WHERE id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your model has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['MODELS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your model. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['MODELS'] . "\">";						
				}
				
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['MODELS'] . "&action=editmodel&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Model</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Name: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Description: </strong></td>
														<td width=\"80\"><textarea name=\"description\" cols=\"45\" rows=\"5\">" . $row['description'] . "</textarea></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Base Weight: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"base_weight\" size=\"40\" value=\"" . $row['base_weight'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Base Price: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"base_price\" size=\"40\" value=\"" . $row['base_price'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Base Profit: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"base_profit\" size=\"40\" value=\"" . $row['base_profit'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Discount (In Dollars): </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"discount\" size=\"40\" value=\"" . $row['discount'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Discount (In Percents): </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"discount_percentage\" size=\"40\" value=\"" . $row['discount_percentage'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Discount Description: </strong></td>
														<td width=\"80\"><textarea name=\"discount_description\" cols=\"45\" rows=\"5\">" . $row['discount_description'] . "</textarea></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Image: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"image_full\" size=\"40\" value=\"" . $row['image_full'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Thumbnail Image: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"image_thumb\" size=\"40\" value=\"" . $row['image_thumb'] . "\" /></td>
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
			// Print out our models table
			//==================================================
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "models` ORDER BY name ASC";
			$result = mysql_query($sql);
				
			$x = 1; //reset the variable we use for our row colors	
				
			$page_content = "
						<div id=\"updateMe\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"4\">
										<div class=\"floatRight\">
											<form name=\"newModelForm\" action=\"" . $menuvar['MODELS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newmodelname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Models
									</td>
								</tr>							
								<tr class=\"title2\">
									<td></td><td><strong>Name</strong></td><td><strong>Description</strong></td><td></td>
								</tr>";
			$modelids = array();
			if (!$result || mysql_num_rows($result) == 0) { // No models yet!
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"4\">There are no models in the database.</td>
														</tr>";	
			}
			else {	 // Print all our models								
				while ($row = mysql_fetch_array($result)) {
					
					$page_content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><img src=\"" . $row['image_thumb'] . "\" alt=\"\" /></td>
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . nl2br($row['description']) . "</td>
											<td>
												<span class=\"center\"><a href=\"" . $menuvar['MODELS'] . "&action=editmodel&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "ModelSpinner', 'ajax.php?action=deleteitem&table=models&id=" . $row['id'] . "', 'model', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Model\" /></a><span id=\"" . $row['id'] . "ModelSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
											</td>
										</tr>";
					$modelids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$page_content .=		"					</table>";
			$page_content .= "\n						<script type=\"text/javascript\">";
			
			$x = 1; //reset the variable we use for our highlight colors
			foreach($modelids as $key => $value) {
				$page_content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=models&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=models&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updatemodel&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=models&item=name&id=" . $key . "'});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$page_content .= "\n						</script>";	
			$page_content .=		"				</div>";
			
			$page_content .= "\n	<script language = \"Javascript\">
				
				function ValidateForm(theForm){
					var name=document.newModelForm.newmodelname
					
					if ((name.value==null)||(name.value==\"\")){
						alert(\"Please enter the new model\'s name.\")
						name.focus()
						return false
					}
					new Ajax.Updater('updateMe', 'ajax.php?action=postmodel', {onComplete:function(){ new Effect.Highlight('newModel');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
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