<? 
/***************************************************************************
 *                               coupons.php
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
		if ($actual_action == "editcoupons" && isset($actual_id)) { 
			// Add breadcrumb
			$page->addBreadCrumb("Edit Coupon", "");
			
			if(isset($_POST['name']))
			{
				$name = $_POST['name'];
				$code = $_POST['code'];
				$discount = $_POST['discount'];
				$discount_percentage = $_POST['discount_percentage'];
				
				$sql = "UPDATE `" . DBTABLEPREFIX . "coupons` SET name='" . $name . "', code='" . $code . "', discount='" . $discount . "', discount_percentage='" . $discount_percentage . "' WHERE id='" . $actual_id . "'";
			    $result = mysql_query($sql);
				
				if ($result) {
					$page_content = "<span class=\"center\">Your coupon has been updated, and you are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"1;url=" . $menuvar['COUPONS'] . "\">";
				}
				else {
					$page_content = "<span class=\"center\">There was an error while updating your coupon. You are being redirected to the main page.</span>
								<meta http-equiv=\"refresh\" content=\"5;url=" . $menuvar['COUPONS'] . "\">";						
				}
				
				unset($_POST['name']);
			}
			else{
				$sql = "SELECT * FROM `" . DBTABLEPREFIX . "coupons` WHERE id='" . $actual_id . "'";
				$result = mysql_query($sql);
					
				if (!$result || mysql_num_rows($result) == 0) {
					$page_content .= "There are no coupons in the database that match this ID.";
				}
				else {								
					$row = mysql_fetch_array($result);
					
					$page_content .= "\n
											<form action=\"" . $menuvar['COUPONS'] . "&action=editcoupons&id=" . $actual_id . "\" method=\"post\">
												<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
													<tr class=\"title1\">
														<td colspan=\"2\"><strong>Edit Coupon</strong></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Name: </strong></td>
														<td><input type=\"text\" name=\"name\" size=\"40\" value=\"" . $row['name'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Code: </strong></td>
														<td><input type=\"text\" name=\"code\" size=\"40\" value=\"" . $row['code'] . "\" /></td>
													</tr>
													<tr class=\"row1\">
														<td><strong>Discount (in Dollars): </strong></td>
														<td><input type=\"text\" name=\"discount\" size=\"40\" value=\"" . $row['discount'] . "\" /></td>
													</tr>
													<tr class=\"row2\">
														<td><strong>Discount (in Percents): </strong></td>
														<td><input type=\"text\" name=\"discount_percentage\" size=\"40\" value=\"" . $row['discount_percentage'] . "\" /></td>
													</tr>
												</table>
												<br />
												<div class=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"button\" /></div>
											</form>
											<br /><br />";
					mysql_free_result($result);
				}
			}			
		}			
		else {
			//==================================================
			// Print out our coupons table
			//==================================================
			$sql = "SELECT * FROM `" . DBTABLEPREFIX . "coupons` ORDER BY name ASC";
			$result = mysql_query($sql);
				
			$x = 1; //reset the variable we use for our row colors	
				
			$page_content = "
						<div id=\"updateMe\">
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"4\">
										<div class=\"floatRight\">
											<form name=\"newCouponForm\" action=\"" . $menuvar['COUPONS'] . "\" method=\"post\" onSubmit=\"ValidateForm(this); return false;\">
												<input type=\"text\" name=\"newCouponName\" />
												<input type=\"image\" src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/add.png\" />
											</form>
										</div>
										Coupons
									</td>
								</tr>							
								<tr class=\"title2\">
									<td><strong>Name</strong></td><td><strong>Code</strong></td><td><strong>Discount</strong></td><td></td>
								</tr>";
			$couponsids = array();
			if (!$result || mysql_num_rows($result) == 0) { // No couponss yet!
				$page_content .= "				
								<tr class=\"greenRow\">
									<td colspan=\"4\">There are no coupons in the database.</td>
								</tr>";	
			}
			else {	 // Print all our couponss								
				while ($row = mysql_fetch_array($result)) {
					
					$discount = ($row['discount'] > 0) ? "$" . $row['discount'] : "";
					$discount .= ($row['discount'] > 0 && $row['discount_percentage'] > 0) ? " + " : "";
					$discount .= ($row['discount_percentage'] > 0) ? $row['discount_percentage'] . "%" : "";
					
					$page_content .=	"					
								<tr id=\"" . $row['id'] . "_row\" class=\"row" . $x . "\">
									<td><div id=\"" . $row['id'] . "_name\">" . $row['name'] . "</div></td>
									<td><div id=\"" . $row['id'] . "_code\">" . $row['code'] . "</div></td>
									<td>" . $discount . "</td>
									<td>
										<span class=\"center\"><a href=\"" . $menuvar['COUPONS'] . "&action=editcoupons&id=" . $row['id'] . "\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/check.png\" alt=\"Edit\" /></a> &nbsp; <a style=\"cursor: pointer; cursor: hand;\" onclick=\"ajaxDeleteNotifier('" . $row['id'] . "couponsSpinner', 'ajax.php?action=deleteitem&table=coupons&id=" . $row['id'] . "', 'coupon', '" . $row['id'] . "_row');\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/delete.png\" alt=\"Delete coupon\" /></a><span id=\"" . $row['id'] . "couponsSpinner\" style=\"display: none;\"><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/indicator.gif\" alt=\"spinner\" /></span></span>
									</td>
								</tr>";
					$couponsids[$row['id']] = $row['name'];					
					$x = ($x==2) ? 1 : 2;
				}
				mysql_free_result($result);
			}
				
			
			$page_content .= "					
							</table>
							<script type=\"text/javascript\">";
			
			$x = 1; //reset the variable we use for our highlight colors
			foreach($couponsids as $key => $value) {
				$highlightColors .= ($x == 1) ? ",highlightcolor:'#CBD5DC',highlightendcolor:'#5194B6'" : ",highlightcolor:'#5194B6',highlightendcolor:'#CBD5DC'";
				$page_content .= "\n							new Ajax.InPlaceEditor('" . $key . "_name', 'ajax.php?action=updateitem&table=coupons&item=name&id=" . $key . "', {rows:1,cols:50" . $highlightColors . "});
														new Ajax.InPlaceEditor('" . $key . "_code', 'ajax.php?action=updateitem&table=coupons&item=code&id=" . $key . "', {rows:1,cols:50" . $highlightColors . "});";
				$x = ($x==2) ? 1 : 2;
			}
			
			$page_content .= "\n						
							</script>
						</div>
			
			<script language = \"Javascript\">
				
				function ValidateForm(theForm){
					var name=document.newCouponForm.newCouponName
					
					if ((name.value==null)||(name.value==\"\")){
						alert(\"Please enter the new coupons name.\")
						name.focus()
						return false
					}
					new Ajax.Updater('updateMe', 'ajax.php?action=postcoupons', {onComplete:function(){ new Effect.Highlight('newCoupon');},asynchronous:true, parameters:Form.serialize(theForm), evalScripts:true}); 
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