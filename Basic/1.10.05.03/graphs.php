<? 
/***************************************************************************
 *                               graphs.php
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
	if ($_SESSION['user_level'] == DISTRIBUTOR || $_SESSION['user_level'] == ADMIN) {
				
			$page_content .= "
						<form name=\"showGraphForm\" id=\"showGraphForm\" action=\"" . $menuvar['GRAPHIT'] . "\" method=\"post\">
							<h3>1. Choose Date Range</h3>
							<select name=\"dates\">
								<option value=\"today\">Today</option>
								<option value=\"thisWeek\">This Week</option>
								<option value=\"thisMonth\">This Month</option>
								<option value=\"thisYear\">This Year</option>
								<option value=\"allTime\">Alltime</option>
								<option value=\"custom\">Custom Date Range</option>
							</select>							
							<br /><br />
							<strong>Start Date:</strong> 
							<input type=\"text\" name=\"graphs_start_datetimestamp\" id=\"graphs_start_datetimestamp\" size=\"20\" value=\"" . makeShortDate(time()) . "\" /><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/calendar.gif\" onclick=\"showChooser(this, 'graphs_start_datetimestamp', 'graphs_start_datetimestampChooser', " . makeCurrentYear(time()) . ", " . makeXYearsFromCurrentYear(time(), 20) . ", Date.patterns.ShortDatePattern, false);\" /><div id=\"graphs_start_datetimestampChooser\" class=\"dateChooser select-free\" style=\"display: none; visibility: hidden; width: 160px;\"></div>
							<br />
							<strong>Stop Date:</strong> 
							<input type=\"text\" name=\"graphs_stop_datetimestamp\" id=\"graphs_stop_datetimestamp\" size=\"20\" value=\"" . makeShortDate(time()) . "\" /><img src=\"themes/" . $ss_config['ftsss_theme'] . "/icons/calendar.gif\" onclick=\"showChooser(this, 'graphs_stop_datetimestamp', 'graphs_stop_datetimestampChooser', " . makeCurrentYear(time()) . ", " . makeXYearsFromCurrentYear(time(), 20) . ", Date.patterns.ShortDatePattern, false);\" /><div id=\"graphs_stop_datetimestampChooser\" class=\"dateChooser select-free\" style=\"display: none; visibility: hidden; width: 160px;\"></div>
							<br /><br />
							
							<h3>2. Choose Graph</h3>
							<select name=\"selectedGraph\">
								<option value=\"numOfProductsSold\">Number of Products Sold</option>
								<option value=\"dollarAmountOfProductsSold\">" . $FTS_CURRENCIES[returnCurrencySymbol()] . " Amount Sold</option>
								<option value=\"profitVsLoss\">Profit vs Loss</option>
								<option value=\"shippingCostCustomer\">Shipping Cost (Customer)</option>
							</select>
							<br /><br />
							
							<h3>3. Choose Graph Type</h3>
							<select name=\"graphType\">
								<option value=\"area2d\">Area (2D)</option>
								<option value=\"bar2d\">Bar (2D)</option>
								<option value=\"column\">Column</option>
								<option value=\"column2d\">Column (2D)</option>
								<option value=\"doughnut2d\">Doughnut (2D)</option>
								<option value=\"funnel\">Funnel</option>
								<option value=\"line\">Line</option>
								<option value=\"pie\">Pie</option>
								<option value=\"pie2d\">Pie (2D)</option>
							</select>
							<br /><br />
							
							<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Show Graph!\" onClick=\"selectAllItems('chosenModels[]');\" />
						</form>";
		
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
	}
?>