<? 
/***************************************************************************
 *                               distributors.php
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
		if ($actual_action == "editdist" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Distributor", "");
			
			if(isset($_POST['name']))
			{
				$name = $_POST['name'];
				$phone_number = $_POST['phone_number'];
				$website = $_POST['website'];
				$cust_num = $_POST['cust_num'];
				$address = $_POST['address'];
				$sales_rep = $_POST['sales_rep'];
				$sales_rep_ext = $_POST['sales_rep_ext'];
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "dist` SET name='" . $name . "', phone_number='" . $phone_number . "', website='" . $website . "', cust_num='" . $cust_num . "', address='" . $address . "', sales_rep='" . $sales_rep . "', sales_rep_ext='" . $sales_rep_ext . "' WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your distributor has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['DISTRIBUTORS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your distributor. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['DISTRIBUTORS'] . "\">";						
				}
				
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "dist` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if($result && mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['DISTRIBUTORS'] . "&action=editdist&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Distributor</strong></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Name: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Phone Number: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"phone_number\" size=\"40\" value=\"" . $row['phone_number'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Website: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"website\" size=\"40\" value=\"" . $row['website'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Customer Number: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"cust_num\" size=\"40\" value=\"" . $row['cust_num'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Address: </strong></td>
														<td width=\"80\"><textarea name=\"address\" cols=\"45\" rows=\"5\">" . $row['address'] . "</textarea></td>
													</tr>
													<tr class=\"row2\">
														<td width=\"80\"><strong>Sales Rep: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"sales_rep\" size=\"40\" value=\"" . $row['sales_rep'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td width=\"80\"><strong>Sales Rep Ext: </strong></td>
														<td width=\"80\"><input type=\"text\" name=\"sales_rep_ext\" size=\"40\" value=\"" . $row['sales_rep_ext'] . "\" /></td>
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
			// Print out our distributors table
			//==================================================
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "dist` ORDER BY name ASC";
			$result = mysql_query($sql);
				
			$x = 1; //reset the variable we use for our row colors	
				
			$page_content = "
						<div id=\"updateMe\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"3\">
										<div class=\"floatRight\">
											<form name=\"newDistForm\" action=\"" . $menuvar['DISTRIBUTORS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newdistname\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Distributors
									</td>
								</tr>							
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Phone Number</strong></td><td></td>
								</tr>";
			$distids = array();
			if (!$result || mysql_num_rows($result) == "0") { // No dists yet!
				$page_content .= "\n					<tr class=\"greenRow\">
															<td colspan=\"3\">There are no distributors in the database.</td>
														</tr>";	
			}
			else {	 // Print all our dists								
				while ($row = mysql_fetch_array($result)) {
					
					$page_content .=	"					
										<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
											<td><div id=\"" . $row['id'] . "_text\">" . $row['name'] . "</div></td>
											<td>" . $row['phone_number'] . "</td>
											<td>
												<span class=\"center\"><a href=\"" . $menuvar['DISTRIBUTORS'] . "&action=editdist&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "DistSpinner', 'ajax.php?action=deleteitem&table=dist&id=" . $row['id'] . "', 'distributor', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete Distributor\" /></a><span id=\"" . $row['id'] . "DistSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
											</td>
										</tr>";
					$distids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
			}
			mysql_free_result($result);
				
			
			$page_content .= "					
									</table>
									<script type=\"text/javascript\">";
			
			$x = 1; //reset the variable we use for our highlight colors
			foreach($distids as $key => $value) {
				$page_content .= ($x == 1) ? "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updateitem&table=dist&item=name&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6',loadTextURL:'ajax.php?action=getitem&table=dist&item=name&id=" . $key . "'});" : "\n							new Ajax.InPlaceEditor('" . $key . "_text', 'ajax.php?action=updatedist&id=" . $key . "', {rows:1,cols:50,highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC',loadTextURL:'ajax.php?action=getitem&table=dist&item=name&id=" . $key . "'});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$page_content .= "
									</script>
								</div>
			<script language = \"Javascript\">
				
				function ValidateForm(theForm){
					var name=document.newDistForm.newdistname
					
					if ((name.value==null)||(name.value==\"\")){
						alert(\"Please enter the new distributors name.\")
						name.focus()
						return false
					}
					new Ajax.Updater('updateMe', 'ajax.php?action=postdist', {onComplete:function(){ new Effect.Highlight('newDist');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
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