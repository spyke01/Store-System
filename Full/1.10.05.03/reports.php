<? 
/***************************************************************************
 *                               reports.php
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
		//==================================================
		// Set our variables
		//==================================================
		$x = 1; //reset the variable we use for our row colors
		// Create an array to hold our reports so that it is easier to modify the page
		$reportsArray = array(
							array('Generate Email List', $menuvar['REPORTS_GENERATEEMAILLIST'], 'Generate a list of email addresses for users who have agreed to be on our mailing list.'),
							array('Profit VS Cost', $menuvar['REPORTS_PROFITVSCOST'], 'Get profit and cost for each part and model in an order.'),
							//array('', $menuvar[''], ''),
						);
		
		//==================================================
		// Print out our reports table
		//==================================================
		$page_content = "
							<table class=\"contentBox\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Reports</td>
								</tr>
								<tr class=\"title2\">
									<td>Name</td><td>Description</td>
								</tr>";
								
		// Cycle through our array and print the reports
		foreach ($reportsArray as $key => $value) {
			$page_content .= "
								<tr class=\"row" . $x  . "\">
									<td><a href=\"" . $value[1] . "\">" . $value[0] . "</a></td>
									<td>" . $value[2] . "</td>
								</tr>";
			$x = ($x == 2) ? 1 : 2;
		}
		$page_content .= "
							</table>";
		
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
	}
?>