<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
	<head>
		<title>Fast Track Sites Store System - <? $page->printTemplateVar("PageTitle");  ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="en-us" />
		<!--Stylesheets Begin-->
			<link rel="stylesheet" type="text/css" href="themes/general.css" />
			<link rel="stylesheet" type="text/css" href="themes/lytebox.css" />
			<link rel="stylesheet" type="text/css" href="themes/<?= $ss_config['ftsss_theme']; ?>/main.css" />
			<!--[if lt IE 7]>
				<style>
				</style>
			<![endif]-->			
		<!--Stylesheets End-->
		<!--Javascripts Begin-->
			<script type="text/javascript" src="javascripts/scriptaculous1.8.2.js"></script>
			<script type="text/javascript" src="javascripts/validation.js"></script>	
			<script type="text/javascript" src="javascripts/functions.js"></script>
			<script type="text/javascript" src="javascripts/lytebox.js"></script>
			<script type="text/javascript" src="javascripts/FusionCharts.js"></script>
			<script type="text/javascript" src="javascripts/date-functions.js"></script>
			<script type="text/javascript" src="javascripts/datechooser.js"></script>
			<?
			$onloadFeature = "";
			
			if ($actual_page_id == "customize" || $actual_page_id == "customize2") {	
				$onloadFeature = " onload=\"init();\"";			
			}
			?>
		<!--Javascripts End-->
	</head>
	<body<?= $onloadFeature ?>>
		<div id="container">
			<div id="page">
				<div id="header">
					<? $page->printMenu("top", "ul", "", "", "nav", "", ""); ?>
				</div>				
				<div id="left-col">
					<? $page->printSidebar("sidenav", ""); ?>
				</div>
				<div id="right-col">
					<div id="content">
						<? $page->printBreadCrumbs("div", "&nbsp;>>&nbsp;", "", "breadCrumbs", ""); ?>
						<br /><br />
						<? $page->printTemplateVar('PageContent'); ?>	
					</div>
				</div>
				
				<div id="footer">
					<div style="float: right; padding-right: 5px;">
						Powered By: <a href="http://www.fasttracksites.com">Fast Track Sites Store System</a>
					</div>
					Copyright &copy; 2009 Fast Track Sites
				</div>
			</div>
		</div>
	</body>
</html>
