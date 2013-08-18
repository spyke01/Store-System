<? 
/***************************************************************************
 *                               graphit.php
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
 
	if ($_SESSION['user_level'] == ADMIN) {
		$selectedGraph = keepsafe($_REQUEST['selectedGraph']);
		$graphType = keepsafe($_REQUEST['graphType']);		
		$dates = keepsafe($_REQUEST['dates']);
		$graphs_start_datetimestamp = keepsafe($_REQUEST['graphs_start_datetimestamp']);
		$graphs_stop_datetimestamp = keepsafe($_REQUEST['graphs_stop_datetimestamp']);
		$selectedGraph = keepsafe($_REQUEST['selectedGraph']);
		$currentTime = time();
		$startDate = "";
		$endDate = "";
		$graphSuffix = "";
		
		if ($dates == "today") {
			$startDate = strtotime("today");
			$endDate = strtotime("+1 day");
			$graphSuffix = "Today";
		}
		elseif ($dates == "thisWeek") {
			$startDate = strtotime(date("Y").'W'.date('W')."0");
			$endDate = strtotime(date("Y").'W'.date('W')."7");
			$graphSuffix = "This Week";
		}
		elseif ($dates == "thisMonth") {
			$startDate = strtotime(makeMonth($currentTime) . " 1, " . makeYear($currentTime));
			$endDate = makeXMonthsFromCurrentMonthAsTimestamp(1);
			$graphSuffix = "This Month";
		}
		elseif ($dates == "thisYear") {
			$startDate = strtotime("Jan 1, " . makeYear($currentTime));
			$endDate = strtotime("Jan 1, " . (makeYear($currentTime) + 1));
			$graphSuffix = "This Year";
		}
		elseif ($dates == "allTime") {
			$graphSuffix = "All Time";
		}
		else {
			$startDate = strtotime($graphs_start_datetimestamp);
			$endDate = strtotime($graphs_stop_datetimestamp);
			$graphSuffix = "Between " . $graphs_start_datetimestamp . " and " . $graphs_stop_datetimestamp;
		}
	
		// Declare our basic variables that we will use
		$graphType = ($graphType == "") ? "column" : $graphType;
		$firstSeriesData = array();
		$secondSeriesData = array();
		$dataTitles = array();
		
		// Declare our graphclass object
		$graph = &new graphClass;
		
		//===================================================		
		// Fill our arrayData variable
		//===================================================		
		if ($selectedGraph == "numOfProductsSold") {
			// Add breadcrumb
			$page->addBreadCrumb("Number of Products Sold", "");
			
			// Data Titles
			$dataTitles[0] = "";
			
			// First Series
			$firstSeriesData[0] = getNumOfProductsSold($startDate, $endDate);
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph("", "", 0, 1);
			$graph->retitleGraph("Number of Products Sold - " . $graphSuffix, "", "Number Sold", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "dollarAmountOfProductsSold") {
			// Add breadcrumb
			$page->addBreadCrumb("Dollar Amount Sold", "");
			
			// Data Titles
			$dataTitles[0] = "";
			
			// First Series
			$firstSeriesData[0] = getTotalDollarAmountOfOrders($startDate, $endDate);
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Number of Systems Sold - " . $graphSuffix, "", "Dollar Amount Sold For", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "profitVsLoss") {
			// Add breadcrumb
			$page->addBreadCrumb("Profit vs Loss", "");
			
			// Data Titles
			$dataTitles[0] = "";
			
			// First Series
			$firstSeriesData[0] = getProfitVsLossOfOrders($startDate, $endDate);
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Profit vs Loss - " . $graphSuffix, "", "Profit or Loss", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "shippingCostCustomer") {
			// Add breadcrumb
			$page->addBreadCrumb("Shipping Cost (Customer)", "");
			
			// Data Titles
			$dataTitles[0] = "";
		
			// First Series
			$firstSeriesData[0] = getShippingCostForCustomerOfOrders($startDate, $endDate);
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Shipping Cost (Customer) - " . $graphSuffix, "", "Shipping Cost in Dollars", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		
		$page_content = $graph->buildGraph("graphHolder", $graphType);
		
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "You Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
	}
?>