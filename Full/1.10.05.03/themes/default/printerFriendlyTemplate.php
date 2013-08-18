<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
	<head>
		<title>Fast Track Sites Store System - <? $page->printTemplateVar("PageTitle");  ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="en-us" />
		<!--Stylesheets Begin-->
			<link rel="stylesheet" type="text/css" href="themes/general.css" />
			<link rel="stylesheet" type="text/css" href="themes/lytebox.css" />
			<link rel="stylesheet" type="text/css" href="themes/<?= $ss_config['ftsss_theme']; ?>/printerFriendly.css" />
			<!--[if lt IE 7]>
				<style>
				</style>
			<![endif]-->			
		<!--Stylesheets End-->
		<!--Javascripts Begin-->
			<script type="text/javascript" src="javascripts/scriptaculous1.8.2.js"></script>	
			<script type="text/javascript" src="javascripts/validation.js"></script>	
			<script type="text/javascript" src="javascripts/functions.js"></script>
			<script type="text/javascript" src="javascripts/storefunctions.js"></script>
			<script type="text/javascript" src="javascripts/pricebox.js"></script>
			<script type="text/javascript" src="javascripts/lytebox.js"></script>
			<?
			$onloadFeature = "";
			
			if ($actual_page_id == "customize" || $actual_page_id == "customize2") {	
				$onloadFeature = " onload=\"init();\"";			
			}
			?>
		<!--Javascripts End-->
	</head>
	<body<?= $onloadFeature ?>>
		<img src="images/logo.png" alt="" />
		<br /><br />
		<? $page->printTemplateVar('PageContent'); ?>
		<br /><br />				
		<div>
			<div style="float: right; padding-right: 5px;">
				Powered By: <a href="http://www.fasttracksites.com">Fast Track Sites Store System</a>
			</div>
			Copyright &copy; 2009 Fast Track Sites
		</div>
	</body>
</html>
