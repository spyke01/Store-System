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
			<link rel="stylesheet" type="text/css" href="themes/<?= $ss_config['ftsss_theme']; ?>/main.css" />
			<link rel="stylesheet" type="text/css" href="themes/<?= $ss_config['ftsss_theme']; ?>/style.css" />
		<!--Stylesheets End-->
		<!--Javascripts Begin-->
			<script type="text/javascript" src="javascripts/scriptaculous1.8.2.js"></script>	
			<script type="text/javascript" src="javascripts/validation.js"></script>	
			<script type="text/javascript" src="javascripts/functions.js"></script>
			<script type="text/javascript" src="javascripts/lytebox.js"></script>
		<!--Javascripts End-->
	</head>
	<body>
		<div id="container">
			<div id="page">
				<div id="header">
					<img src="images/logo.gif" alt="Fast Track Sites Logo" />
				</div>				
				<? $page->printTemplateVar('PageContent'); ?>
				<br clas="clear" /><br clas="clear" />
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
