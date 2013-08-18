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
		$chosenModels = $_REQUEST['chosenModels']; // This is kept safe after we parse the array
		$dates = keepsafe($_REQUEST['dates']);
		$graphs_start_datetimestamp = keepsafe($_REQUEST['graphs_start_datetimestamp']);
		$graphs_stop_datetimestamp = keepsafe($_REQUEST['graphs_stop_datetimestamp']);
		$selectedGraph = keepsafe($_REQUEST['selectedGraph']);
		$currentTime = time();
		$startDate = "";
		$endDate = "";
		$graphSuffix = "";
			
		// Make models list
		$modelsList = "";
		if(is_array($chosenModels)) {
			foreach ($chosenModels as $modelID) {
				$modelsList .= "x" . $modelID . "x ";
			}
		}
		else {
			$modelsList .= "x" . $chosenModels . "x ";
		}
		$modelsList = keepsafe(trim($modelsList));
		
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
		if ($selectedGraph == "numOfSystemsSold") {
			// Add breadcrumb
			$page->addBreadCrumb("Number of Systems Sold", "");
			
			if(is_array($chosenModels)) {
				$i = 0;
				foreach ($chosenModels as $modelID) {
					// Data Titles
					$dataTitles[$i] = getModelNameFromID($modelID);
				
					// First Series
					$firstSeriesData[$i] = getNumOfSystemsFromID($modelID, $startDate, $endDate);
				
					$i++;
				}
			}
			else {				
				// Data Titles
				$dataTitles[0] = getModelNameFromID($chosenModels);
			
				// First Series
				$firstSeriesData[0] = getNumOfSystemsFromModelID($chosenModels, $startDate, $endDate);
			}
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph("", "", 0, 1);
			$graph->retitleGraph("Number of Systems Sold - " . $graphSuffix, "System Name", "Number Sold", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "dollarAmountOfSystemsSold") {
			// Add breadcrumb
			$page->addBreadCrumb("Dollar Amount Sold", "");
			
			if(is_array($chosenModels)) {
				$i = 0;
				foreach ($chosenModels as $modelID) {
					// Data Titles
					$dataTitles[$i] = getModelNameFromID($modelID);
				
					// First Series
					$firstSeriesData[$i] = getTotalDollarAmountOfOrderFromModelID($modelID, $startDate, $endDate);
				
					$i++;
				}
			}
			else {				
				// Data Titles
				$dataTitles[0] = getModelNameFromID($chosenModels);
			
				// First Series
				$firstSeriesData[0] = getTotalDollarAmountOfOrderFromModelID($chosenModels, $startDate, $endDate);
			}
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Number of Systems Sold - " . $graphSuffix, "System Name", "Dollar Amount Sold For", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "profitVsLoss") {
			// Add breadcrumb
			$page->addBreadCrumb("Profit vs Loss", "");
			
			if(is_array($chosenModels)) {
				$i = 0;
				foreach ($chosenModels as $modelID) {
					// Data Titles
					$dataTitles[$i] = getModelNameFromID($modelID);
				
					// First Series
					$firstSeriesData[$i] = getProfitVsLossOfOrderFromModelID($modelID, $startDate, $endDate);
				
					$i++;
				}
			}
			else {				
				// Data Titles
				$dataTitles[0] = getModelNameFromID($chosenModels);
			
				// First Series
				$firstSeriesData[0] = getProfitVsLossOfOrderFromModelID($chosenModels, $startDate, $endDate);
			}
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Profit vs Loss - " . $graphSuffix, "System Name", "Profit or Loss", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "shippingCostCustomer") {
			// Add breadcrumb
			$page->addBreadCrumb("Shipping Cost (Customer)", "");
			
			if(is_array($chosenModels)) {
				$i = 0;
				foreach ($chosenModels as $modelID) {
					// Data Titles
					$dataTitles[$i] = getModelNameFromID($modelID);
				
					// First Series
					$firstSeriesData[$i] = getShippingCostForCustomerOfOrderFromModelID($modelID, $startDate, $endDate);
				
					$i++;
				}
			}
			else {				
				// Data Titles
				$dataTitles[0] = getModelNameFromID($chosenModels);
			
				// First Series
				$firstSeriesData[0] = getShippingCostForCustomerOfOrderFromModelID($chosenModels, $startDate, $endDate);
			}
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Shipping Cost (Customer) - " . $graphSuffix, "System Name", "Shipping Cost in Dollars", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		elseif ($selectedGraph == "testGraph") {			
			// Data Titles
			$dataTitles[0] = "Doughnuts";
			$dataTitles[1] = "Tires";
			$dataTitles[2] = "PCs";
			$dataTitles[3] = "Napkins";
			
			// First Series
			$firstSeriesData[0] = "12";
			$firstSeriesData[1] = "86";
			$firstSeriesData[2] = "32";
			$firstSeriesData[3] = "98";
			
			// Second Series
			$secondSeriesData[0] = "46";
			$secondSeriesData[1] = "2";
			$secondSeriesData[2] = "64";
			$secondSeriesData[3] = "90";
			
			// Set all graph related items
			$graph->resizeGraph(800, 800);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Test Graph", "Product", "Net Profit", $dataTitles, 2, "2008 Data", "2009 Data");
			$graph->addGraphData($firstSeriesData, $secondSeriesData);
		}
		elseif ($selectedGraph == "testGraph2") {			
			// Data Titles
			$dataTitles[0] = "Doughnuts";
			$dataTitles[1] = "Tires";
			$dataTitles[2] = "PCs";
			$dataTitles[3] = "Napkins";
			
			// First Series
			$firstSeriesTitle = "2008 Data";
			$firstSeriesData[0] = "12";
			$firstSeriesData[1] = "86";
			$firstSeriesData[2] = "32";
			$firstSeriesData[3] = "98";
			
			// Set all graph related items
			$graph->resizeGraph(400, 400);
			$graph->formatGraph(returnCurrencySymbol(), "", 2, 1);
			$graph->retitleGraph("Test Graph 2", "Product", "Net Profit", $dataTitles, 1, "", "");
			$graph->addGraphData($firstSeriesData, "");
		}
		
		$page_content = $graph->buildGraph("graphHolder", $graphType);
		
		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar("PageContent", "You Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
	}
?>