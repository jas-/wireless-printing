<?php

/*
 * Marriott Library Wireless Printing Portal
 *
 * Last update: 11.12.2012
 * Questions? Suggestions? jason.gerfen@utah.edu
 */

/* set the default path */
define('__SITE', realpath(dirname(__FILE__)));

/* array of classes to include */
$includes = array('printer', 'io');

/* loop and include all, erroring if we can't */
foreach($includes as $value) {
    if (!file_exists(__SITE.'/classes/class.'.$value.'.php')) {
    	exit(__SITE.'/classes/class.'.$value.'.php does not exist');
    }
    include __SITE.'/classes/class.'.$value.'.php';
}

/* 'upload_max_filesize' perform calculations
 * PHP zero's super-globals when upload_max_filesize & post_max_size
 * are met
 */
if (!printer::_calculations()) {
	$r = printer::genErr(printer::fErr(3));
}

/* if post recieved do it */
if (!empty($_POST)) {

	$r = printer::_main($_POST, $_FILES);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width" />
	<title>Wireless Printing - Marriott Library - The University of Utah</title>
	<meta name="robots" content="index,follow">
	<link href="css/main.css" rel="stylesheet" type="text/css">
	<link href="css/tabs.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.12.custom-dev.css">
	<link rel="stylesheet" type="text/css" href="css/default-dev.css">
	<script type="text/javascript" src="scripts/tabs.js">//</script>
	<script type="text/javascript" src="scripts/jquery-1.4.4.min.js">//</script>
	<script type="text/javascript" src="scripts/jquery-ui-1.8.12.custom.min.js">//</script>
	<script type="text/javascript" src="scripts/highlight.js">//</script>
	<script type="text/javascript" src="scripts/MLhome.js">//</script>
	<!--[if gte IE 6]>
	<link href="css/ie6.css" rel="stylesheet" type="text/css"/>
	<![endif]-->
	<script type="text/javascript" src="scripts/directedit.js">//</script>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
		$j(document).ready(function(){
			$j('#locations').accordion({active:-1,autoHeight:false,clearStyle:true});
			$j('#wjml_printers, #rou_printers, #bsh_printers, #duplex').buttonset();
			$j('#showInfo').bind('click', function(){
				$j('#info').toggle('slow');
			});
			/* This will be added by the end of next week to support multiple print job uploads
			$j('#addfile').click(function(){
			$j('#primary').clone().removeAttr('id').appendTo($j('#primary').parent());*/
		});
	</script>
	<style type="text/css">
		hidden {
			display: none;
		}
		#footer a#de {
			border-bottom-width: 0px;
			text-decoration:none;
		}
	</style>
</head>
<body class="home">
<div id="wrapper">
	<div id="innerwrapper">
		<div id="header">
			<div id="skip"><a href="#contenttop">skip to main content</a></div>
			 <div id="ulogo"><a href="http://www.utah.edu/"><img src="images/marriottHeader-U.gif" alt="The University of Utah"></a><a href="http://www.lib.utah.edu/index.php"><img src="images/marriottHeader-title.gif" alt="J. Willard Marriott Library, The University of Utah"></a></div>
			 <div id="unav">
				<ul>
					<li class="firstitem"><a href="http://www.lib.utah.edu/info/az.php">A - Z</a></li>
				 	<li><a href="http://search.library.utah.edu/">library catalog</a></li>
				 	<li><a href="http://www.lib.utah.edu/info/library-maps.php">floor maps</a></li>
				 	<li><a href="http://www.lib.utah.edu/info/hours.php">hours</a></li>
				</ul>
				<div id="search">
					<form action="http://search.utah.edu:8765/custom/query.html" method="get">
						<input name="qpurl" type="hidden" value="url:www.lib.utah.edu/" />
						<input name="qpname" type="hidden" value="Marriott Library" />
						<label for="qt">Search:</label>
						<input id="qt" maxlength="50" name="qt" size="20" type="text" />
						<input class="searchBtn" alt="Search" name="image2" src="images/trans.png" type="image" />
					</form>
				</div>
			</div>
		</div>
		<div id="horizNav">
			<div id="navContainer">
				<ul id="topnav">
					<li class="nav_home"><a class="nav_item" href="http://www.lib.utah.edu/index.php"><span>Home</span></a></li>
					<li class="nav_research"><a class="nav_item" href="http://www.lib.utah.edu/research/index.php"><span>Research Tools</span></a></li>
					<li class="nav_services"><a class="nav_item" href="http://www.lib.utah.edu/services/index.php"><span>Services</span></a></li>
					<li class="nav_collections"><a class="nav_item" href="http://www.lib.utah.edu/collections/index.php"><span>Collections</span></a> </li>
					<li class="nav_info"><a class="nav_item" href="http://www.lib.utah.edu/info/index.php"><span>About the Library</span></a></li>
					<li class="nav_help"><a class="nav_item" href="http://www.lib.utah.edu/help/index.php"><span>Get Help</span></a></li>
				</ul>
			</div>
		</div>
		<div id="main">
			<div id="breadcrumbs" style="margin-left: 0pt;">
				<ul>
					<li class="firstitem"> <a href="http://www.lib.utah.edu/"> Home </a> </li>
					<li> <a href="http://www.lib.utah.edu/services/"> Services </a> </li>
					<li> <a href="http://www.lib.utah.edu/services/copy-print.php"> Copy and Printing</a> </li>
					<li> Wireless Printing </li></ul>
			</div>
			<div id="sub_3ColRightCol">
				<div id="rtColContact">
					<h2>Contact</h2>
					<ul>
						<li class="contact-dept"> <a href="http://www.lib.utah.edu/services/knowledge-commons/">Knowledge Commons</a> </li>
						<li class="contact-phone">801-581-6273</li>
					</ul>
				</div>
				<div id="rtColReg1"></div>
				<div id="rtColReg2"></div>
			</div>
			<div class="banner">
				<p>&nbsp;</p>
			</div>
			<div id="wideOpenRtCol">
				<h1>Wireless Printing</h1>
				<p>This page allows patrons to send PDFs from their wireless devices to the Marriott Library and Student Computing Labs print queues. For other file types, you can <a href="javascript:void(0)" id="showInfo" name="showInfo">convert your file to a PDF</a> first.</p>
				<div id="info" style="display: none">
					<p><strong>Mac OS X:</strong> File &gt; Print &gt; PDF (bottom right) &gt; Save as PDF</p>
					<p><strong>Windows:</strong> Windows does not have built in PDF printing, but there are a few options:
					<blockquote>
						<strong>Office 2010:</strong> File &gt; Save & Send &gt; Create PDF/XPS Document<br />
						<strong>Office 2007:</strong> <a href="http://www.microsoft.com/download/en/details.aspx?id=7">Install add-in</a>; File &gt; Save or Publish to PDF or XPS<br />
						<strong>OpenOffice.org:</strong> File &gt; Export to PDF<br />
						<strong>Other:</strong> Install a free PDF printer such as <a href="http://download.cnet.com/BullZip-PDF-Printer/3000-13455_4-85827.html">Bullzip</a> or <a href="http://download.cnet.com/PDF24-Creator/3000-18497_4-10569740.html">PDF24</a>; File > Print > select PDF printer</p>
					</blockquote>
				</p>
			</div>
			<?php
				if (isset($r)) {
					if (is_array($r)) {
						foreach($r as $v) {
							if ((!empty($v)) && ($v !== '')) {
								echo $v;
							}
						}
					} else {
						echo $r;
					}
				}
			?>
			<form name="print" action="index.php" method="post" enctype="multipart/form-data">
				<p><strong>1. Enter uNID:</strong> <input type="text" maxlength="64" alt="Enter uNID" id="unid" name="unid" placeholder="u0123456" required="required" /></p>
				<p><strong>2. Select printer</strong></p>
				<div id="locations">
					<h3 class="wjml_printers"><p class="middle">J. Willard Marriott Library</p></h3>
					<div id="wjml_printers">
						<div class="col1">
							<p><input type="radio" alt="Knowledge Commons B&amp;W" id="kc-1" name="printer" value="kc-1" /><label for="kc-1">Knowledge Commons B&amp;W</label></p>
							<p><input type="radio" alt="Knowledge Commons Color" id="kc-color" name="printer" value="kc-color" /><label for="kc-color">Knowledge Commons Color</label></p>
							<p><input type="radio" alt="Level 1 East B&amp;W" id="pub-1" name="printer" value="pub-1" /><label for="pub-1">Level 1 East B&amp;W</label></p>
						</div>
						<div class="col2">
							<p><input type="radio" alt="Level 2 East B&amp;W" id="pub-2" name="printer" value="pub-2" /><label for="pub-2">Level 2 East B&amp;W</label></p>
							<p><input type="radio" alt="Fine Arts &amp; Architecture Color" id="fa-1" name="printer" value="fa-1" /><label for="fa-1">Fine Arts &amp; Architecture Color</label></p>
							<p><input type="radio" alt="Digital Scholarship Lab B&amp;W" id="st-1" name="printer" value="st-1" /><label for="st-1">Digital Scholarship Lab B&amp;W</label></p>
						</div>
					</div>
					<h3 class="rou_printers"><p class="middle">A. Ray Olpin University Union</p></h3>
					<div id="rou_printers">
						<p><input type="radio" alt="Union Lab B&amp;W" id="un-1" name="printer" value="un-1" /><label for="un-1">Union Lab B&amp;W</label></p>
						<p><input type="radio" alt="Union Lab Color" id="un-color" name="printer" value="un-color" /><label for="un-color">Union Lab Color</label></p>
					</div>
					<h3 class="bsh_printers"><p class="middle">Benchmark Plaza &amp; Sage Point Housing</p></h3>
					<div id="bsh_printers">
						<p><input type="radio" alt="Benchmark Lab B&amp;W" id="ben-1" name="printer" value="ben-1" /><label for="ben-1">Benchmark Lab B&amp;W</label></p>
						<p><input type="radio" alt="Benchmark Lab Color" id="bencolor" name="printer" value="bencolor" /><label for="bencolor">Benchmark Lab Color</label></p>
						<p><input type="radio" alt="Sage Point Lab B&amp;W" id="sage-1" name="printer" value="sage-1" /><label for="sage-1">Sage Point Lab B&amp;W</label></p>
					</div>
				</div>
				<p id="duplex">
					<strong>3. Two-sided printing?</strong> <input type="radio" alt="Duplex On" id="on" name="duplex" value="true" /><label for="on">On</label>
					<input type="radio" alt="Duplex Off" id="off" name="duplex" value="false" checked /><label for="off">Off</label>
				</p>
				<!--a href="javascript:void(0)" id="addfile" name="addfile"><img src="images/icons/icon-add.png" alt="Add another file" /></a-->
				<div id="primary">
					<p><strong>4. Select PDF:</strong> <input type="file" id="files" name="files[]" /></p>
				</div>
				<div id="send">
					<p><input type="submit" id="submit" value="Print file" alt="Print file(s)" /></p>
				</div>
			</form>
			<p class="clear"></p>
		</div>
	</div>
	<div id="footer">
		<ul>
			<li class="firstitem"><a href="http://www.lib.utah.edu/index.php">J. Willard Marriott Library</a>, 295 S 1500 E SLC, UT 84112-0860</li>
			<li>801.581.8558 • Fax 801.585.3464</li>
		</ul>
		<ul>
			<li class="firstitem">© <a href="http://www.utah.edu/portal/site/uuhome/">The University of Utah</a></li>
			<li><a href="http://support.scl.utah.edu/index.php?departmentid=14&amp;_m=tickets&amp;_a=submit&amp;step=1&amp;subject=Question%20Regarding%20Streaming%20Site">Contact Us</a></li>
			<li><a href="http://www.utah.edu/disclaimer" target="_blank">DISCLAIMER</a></li>
			<li><a href="http://www.utah.edu/privacy" target="_blank">PRIVACY</a></li>
			<li><a rel="nofollow" href="https://www.library.utah.edu/sites/intranet/default.aspx">Staff Intranet</a></li>
		</ul>
	</div>
</div>
</body>
</html>
