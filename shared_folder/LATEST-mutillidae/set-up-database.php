<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="./styles/global-styles.css" />
	</head>
	<body>
		<div>&nbsp;</div>
		<div class="page-title">Setting up the database...</div><br /><br />
		<span style="text-align: center;">
			<div class="label">If you see no error messages, it should be done.</div>
			<div>&nbsp;</div>
			<div class="label"><a href="index.php">Continue back to the frontpage.</a></div>
		</span>
		<br />
		<script>
			try{
				window.sessionStorage.clear();
				window.localStorage.clear();
			}catch(e){
				alert("Error clearing HTML 5 Local and Session Storage" + e.toString());
			};
		</script>
		<div class="database-success-message">HTML 5 Local and Session Storage cleared unless error popped-up already.</div>
<?php

//initialize custom error handler
require_once 'classes/CustomErrorHandler.php';
if (!isset($CustomErrorHandler)){
	$CustomErrorHandler = 
	new CustomErrorHandler("owasp-esapi-php/src/", 0);
}// end if

require_once 'classes/MySQLHandler.php';
$MySQLHandler = new MySQLHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
$lErrorDetected = FALSE;

function format($pMessage, $pLevel ) {
	switch ($pLevel){
		case "I": $lStyle = "database-informative-message";break;
		case "S": $lStyle = "database-success-message";break;
		case "F": $lStyle = "database-failture-message";break;
	}// end switch
	
	return "<div class=\"".$lStyle."\">" . $pMessage . "</div>";
}// end function

try{
	echo format("Attempting to connect to MySQL server on host " . MySQLHandler::$mMySQLDatabaseHost . " with user name " . MySQLHandler::$mMySQLDatabaseUsername,"I");
	$MySQLHandler->openDatabaseConnection();
	echo format("Connected to MySQL server at " . MySQLHandler::$mMySQLDatabaseHost . " as " . MySQLHandler::$mMySQLDatabaseUsername,"I");
		
	try{
		echo format("Preparing to drop database " . MySQLHandler::$mMySQLDatabaseName,"I");
		$lQueryString = "DROP DATABASE IF EXISTS " . MySQLHandler::$mMySQLDatabaseName;
		$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
		if (!$lQueryResult) {
			$lErrorDetected = TRUE;
			echo format("Was not able to drop database " . MySQLHandler::$mMySQLDatabaseName,"F");
		}else{
			echo format("Executed query 'DROP DATABASE IF EXISTS' for database " . MySQLHandler::$mMySQLDatabaseName . " with result ".$lQueryResult,"S");
		}// end if
	}catch(Exception $e){
		//DO NOTHING. THIS IS HERE DUE TO A MYSQL BUG THAT HAS NOT BEEN PATCHED YET.
	}//end try
	
	echo format("Preparing to create database " . MySQLHandler::$mMySQLDatabaseName,"I");
	$lQueryString = "CREATE DATABASE " . MySQLHandler::$mMySQLDatabaseName;
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
		echo format("Was not able to create database " . MySQLHandler::$mMySQLDatabaseName,"F");
	}else{
		echo format("Executed query 'CREATE DATABASE' for database " . MySQLHandler::$mMySQLDatabaseName . " with result ".$lQueryResult,"S");
	}// end if
	
	echo format("Switching to use database " . MySQLHandler::$mMySQLDatabaseName,"I");
	$lQueryString = "USE " . MySQLHandler::$mMySQLDatabaseName;
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
		echo format("Was not able to use database " . MySQLHandler::$mMySQLDatabaseName,"F");
	}else{
		echo format("Executed query 'USE DATABASE' " . MySQLHandler::$mMySQLDatabaseName . " with result ".$lQueryResult,"I");
	}// end if
			
	$lQueryString = 'CREATE TABLE blogs_table( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'blogger_name TEXT, '.
	         'comment TEXT, '.
			 'date DATETIME, '.
			 'PRIMARY KEY(cid))';	
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if
	
	$lQueryString = 'CREATE TABLE accounts( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'username TEXT, '.
	         'password TEXT, '.
			 'mysignature TEXT, '.
			 'is_admin VARCHAR(5),'.
			 'PRIMARY KEY(cid))';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if
	
	$lQueryString = 'CREATE TABLE hitlog( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'hostname TEXT, '.
	         'ip TEXT, '.
			 'browser TEXT, '.
			 'referer TEXT, '.
			 'date DATETIME, '.
			 'PRIMARY KEY(cid))';		 
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if
	
	$lQueryString = "INSERT INTO accounts (username, password, mysignature, is_admin) VALUES
		('admin', 'adminpass', 'Monkey!', 'TRUE'),
		('adrian', 'somepassword', 'Zombie Films Rock!', 'TRUE'),
		('john', 'monkey', 'I like the smell of confunk', 'FALSE'),
		('jeremy', 'password', 'd1373 1337 speak', 'FALSE'),
		('bryce', 'password', 'I Love SANS', 'FALSE'),
		('samurai', 'samurai', 'Carving Fools', 'FALSE'),
		('jim', 'password', 'Jim Rome is Burning', 'FALSE'),
		('bobby', 'password', 'Hank is my dad', 'FALSE'),
		('simba', 'password', 'I am a super-cat', 'FALSE'),
		('dreveil', 'password', 'Preparation H', 'FALSE'),
		('scotty', 'password', 'Scotty Do', 'FALSE'),
		('cal', 'password', 'Go Wildcats', 'FALSE'),
		('john', 'password', 'Do the Duggie!', 'FALSE'),
		('kevin', '42', 'Doug Adams rocks', 'FALSE'),
		('dave', 'set', 'Bet on S.E.T. FTW', 'FALSE'),
		('ed', 'pentest', 'Commandline KungFu anyone?', 'FALSE')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString ="INSERT INTO `blogs_table` (`cid`, `blogger_name`, `comment`, `date`) VALUES
		(1, 'adrian', 'Well, I''ve been working on this for a bit. Welcome to my crappy blog software. :)', '2009-03-01 22:26:12'),
		(2, 'adrian', 'Looks like I got a lot more work to do. Fun, Fun, Fun!!!', '2009-03-01 22:26:54'),
		(3, 'anonymous', 'An anonymous blog? Huh? ', '2009-03-01 22:27:11'),
		(4, 'ed', 'I love me some Netcat!!!', '2009-03-01 22:27:48'),
		(5, 'john', 'Listen to Pauldotcom!', '2009-03-01 22:29:04'),
		(6, 'jeremy', 'Why give users the ability to get to the unfiltered Internet? It''s just asking for trouble. ', '2009-03-01 22:29:49'),
		(7, 'john', 'Chocolate is GOOD!!!', '2009-03-01 22:30:06'),
		(8, 'admin', 'Fear me, for I am ROOT!', '2009-03-01 22:31:13'),
		(9, 'dave', 'Social Engineering is woot-tastic', '2009-03-01 22:31:13'),
		(10, 'kevin', 'Read more Douglas Adams', '2009-03-01 22:31:13'),
		(11, 'kevin', 'You should take SANS SEC542', '2009-03-01 22:31:13'),
		(12, 'asprox', 'Fear me, for I am asprox!', '2009-03-01 22:31:13')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = 'CREATE TABLE credit_cards( '.
			 'ccid INT NOT NULL AUTO_INCREMENT, '.
	         'ccnumber TEXT, '.
	         'ccv TEXT, '.
			 'expiration DATE, '.
			 'PRIMARY KEY(ccid))';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if

	$lQueryString ="INSERT INTO `credit_cards` (`ccid`, `ccnumber`, `ccv`, `expiration`) VALUES
		(1, '4444111122223333', '745', '2012-03-01 10:01:12'),
		(2, '7746536337776330', '722', '2015-04-01 07:00:12'),
		(3, '8242325748474749', '461', '2016-03-01 11:55:12'),
		(4, '7725653200487633', '230', '2017-06-01 04:33:12'),
		(5, '1234567812345678', '627', '2018-11-01 13:31:13')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString = 
			'CREATE TABLE pen_test_tools('.
			'tool_id INT NOT NULL AUTO_INCREMENT, '.
	        'tool_name TEXT, '.
	        'phase_to_use TEXT, '.
			'tool_type TEXT, '.
			'comment TEXT, '.
			'PRIMARY KEY(tool_id))';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if

	$lQueryString ="INSERT INTO `pen_test_tools` (`tool_id`, `tool_name`, `phase_to_use`, `tool_type`, `comment`) VALUES
		(1, 'WebSecurify', 'Discovery', 'Scanner', 'Can capture screenshots automatically'),
		(2, 'Grendel-Scan', 'Discovery', 'Scanner', 'Has interactive-mode. Lots plug-ins. Includes Nikto. May not spider JS menus well.'),
		(3, 'Skipfish', 'Discovery', 'Scanner', 'Agressive. Fast. Uses wordlists to brute force directories.'),
		(4, 'w3af', 'Discovery', 'Scanner', 'GUI simple to use. Can call sqlmap. Allows scan packages to be saved in profiles. Provides evasion, discovery, brute force, vulneraility assessment (audit), exploitation, pattern matching (grep).'),
		(5, 'Burp-Suite', 'Discovery', 'Scanner', 'GUI simple to use. Provides highly configurable manual scan assistence with productivity enhancements.'),
		(6, 'Netsparker Community Edition', 'Discovery', 'Scanner', 'Excellent spider abilities and reporting. GUI driven. Runs on Windows. Good at SQLi and XSS detection. From Mavituna Security. Professional version available for purchase.'),
		(7, 'NeXpose', 'Discovery', 'Scanner', 'GUI driven. Runs on Windows. From Rapid7. Professional version available for purchase. Updates automatically. Requires large amounts of memory.'),
		(8, 'Hailstorm', 'Discovery', 'Scanner', 'From Cenzic. Professional version requires dedicated staff, multiple dediciated servers, professional pen-tester to analyze results, and very large license fee. Extensive scanning ability. Very large vulnerability database. Highly configurable. Excellent reporting. Can scan entire networks of web applications. Extremely expensive. Requires large amounts of memory.'),
		(9, 'Tamper Data', 'Discovery', 'Interception Proxy', 'Firefox add-on. Easy to use. Tampers with POST parameters and HTTP Headers. Does not tamper with URL query parameters. Requires manual browsing.'),		
		(10, 'DirBuster', 'Discovery', 'Fuzzer', 'OWASP tool. Fuzzes directory names to brute force directories.'),
		(11, 'SQL Inject Me', 'Discovery', 'Fuzzer', 'Firefox add-on. Attempts common strings which elicit XSS responses. Not compatible with Firefox 8.0.'),
		(12, 'XSS Me', 'Discovery', 'Fuzzer', 'Firefox add-on. Attempts common strings which elicit responses from databases when SQL injection is present. Not compatible with Firefox 8.0.'),
		(13, 'GreaseMonkey', 'Discovery', 'Browser Manipulation Tool', 'Firefox add-on. Allows the user to inject JavaScripts and change page.'),
		(14, 'NSLookup', 'Reconnaissance', 'DNS Server Query Tool', 'DNS query tool can query DNS name or reverse lookup on IP. Set debug for better output. Premiere tool on Windows but Linux perfers Dig. DNS traffic generally over UDP 53 unless response long then over TCP 53. Online version combined with anonymous proxy or TOR network may be prefered for stealth.'),
		(15, 'Whois', 'Reconnaissance', 'Domain name lookup service', 'Whois is available in Linux naitvely and Windows as a Sysinternals download plus online. Whois can lookup the registrar of a domain and the IP block associated. An online version is http://network-tools.com/'),
		(16, 'Dig', 'Reconnaissance', 'DNS Server Query Tool', 'The Domain Information Groper is prefered on Linux over NSLookup and provides more information natively. NSLookup must be in debug mode to give similar output. DIG can perform zone transfers if the DNS server allows transfers.'),
		(17, 'Fierce Domain Scanner', 'Reconnaissance', 'DNS Server Query Tool', 'Powerful DNS scan tool. FDS is a Perl program which scans and reverse scans a domain plus scans IPs within the same block to look for neighoring machines. Available in the Samurai and Backtrack distributions plus http://ha.ckers.org/fierce/'),
		(18, 'host', 'Reconnaissance', 'DNS Server Query Tool', 'A simple DNS lookup tool included with BIND. The tool is a friendly and capible command line tool with excellent documentation. Does not posess the automation of FDS.'),
		(19, 'zaproxy', 'Reconnaissance', 'Interception Proxy', 'OWASP Zed Attack Proxy. An interception proxy that can also passively or actively scan applications as well as perform brute-forcing. Similar to Burp-Suite without the disadvantage of requiring a costly license.'),
		(20, 'Google intitle', 'Discovery', 'Search Engine','intitle and site directives allow directory discovery. GHDB available to provide hints. See Hackers for Charity site.')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString = 
			'CREATE TABLE captured_data('.
				'data_id INT NOT NULL AUTO_INCREMENT, '.
				'ip_address TEXT, '.
				'hostname TEXT, '.
				'port TEXT, '.
				'user_agent_string TEXT, '.
				'referrer TEXT, '.
				'data TEXT, '.
			 	'capture_date DATETIME, '.
				'PRIMARY KEY(data_id)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if

	$lQueryString = 
			'CREATE TABLE page_hints('.
				'page_name VARCHAR(64) NOT NULL, '.
				'hint_key INT, '.
				'hint TEXT, '.
				'PRIMARY KEY(page_name, hint_key)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if
	
	$lQueryString = 
			'CREATE TABLE page_help('.
				'page_name VARCHAR(64) NOT NULL, '.
				'help_text_key INT, '.
				'order_preference INT, '.
				'PRIMARY KEY(page_name, help_text_key)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if

	$lQueryString ="INSERT INTO `page_help` (`page_name`, `help_text_key`, `order_preference`) VALUES
			('home.php', 0, 1),
			('home.php', 1, 1),
			('home.php', 2, 1),
			('home.php', 3, 1),
			('home.php', 4, 1),
			('home.php', 5, 1),
			('home.php', 6, 1),
			('home.php', 7, 0),
			('home.php', 9, 1),
			('home.php', 24, 1),
			('add-to-your-blog.php', 8, 0),
			('add-to-your-blog.php', 10, 1),
			('add-to-your-blog.php', 11, 1),
			('add-to-your-blog.php', 12, 1),
			('add-to-your-blog.php', 13, 1),
			('add-to-your-blog.php', 14, 1),
			('arbitrary-file-inclusion.php', 11, 1),
			('arbitrary-file-inclusion.php', 12, 1),
			('arbitrary-file-inclusion.php', 15, 0),
			('arbitrary-file-inclusion.php', 16, 1),
			('arbitrary-file-inclusion.php', 17, 1),
			('browser-info.php', 11, 1),
			('browser-info.php', 12, 1),
			('browser-info.php', 18, 1),
			('capture-data.php', 11, 1),
			('capture-data.php', 12, 1),
			('captured-data.php', 10, 1),
			('captured-data.php', 11, 1),
			('captured-data.php', 12, 1),
			('credits.php', 19, 1),
			('directory-browsing.php', 9, 1),
			('dns-lookup.php', 11, 1),
			('dns-lookup.php', 12, 1),
			('dns-lookup.php', 20, 1),
			('document-viewer.php', 11, 1),
			('document-viewer.php', 12, 1),
			('document-viewer.php', 21, 1),
			('framing.php', 22, 1),
			('html5-storage.php', 23, 1),
			('login.php', 10, 1),
			('login.php', 11, 1),
			('login.php', 12, 1),
			('login.php', 13, 1),
			('login.php', 25, 1),
			('password-generator.php', 18, 1),
			('pen-test-tool-lookup.php', 26, 1),
			('pen-test-tool-lookup-ajax.php', 26, 1),
			('phpinfo.php', 27, 1),
			('phpinfo.php', 28, 1),
			('phpinfo.php', 29, 1),
			('register.php', 10, 1),
			('register.php', 11, 1),
			('register.php', 12, 1),
			('register.php', 14, 1),
			('register.php', 30, 1),
			('rene-magritte.php', 22, 1),
			('robots.txt.php', 29, 1),
			('repeater.php', 31, 1),
			('repeater.php', 32, 1),
			('secret-administrative-pages.php', 6, 1),
			('secret-administrative-pages.php', 27, 1),
			('secret-administrative-pages.php', 28, 1),
			('secret-administrative-pages.php', 29, 1),
			('set-background-color.php', 33, 1),
			('show-log.php', 11, 1),
			('show-log.php', 12, 1),
			('show-log.php', 34, 1),
			('site-footer-xss-discussion.php', 11, 1),
			('site-footer-xss-discussion.php', 12, 1),
			('source-viewer.php', 11, 1),
			('source-viewer.php', 12, 1),
			('source-viewer.php', 15, 1),
			('source-viewer.php', 16, 1),
			('ssl-misconfiguration.php', 1, 1),
			('text-file-viewer.php', 11, 1),
			('text-file-viewer.php', 12, 1),
			('text-file-viewer.php', 15, 1),
			('text-file-viewer.php', 16, 1),
			('text-file-viewer.php', 35, 1),
			('user-info.php', 10, 1),
			('user-info.php', 11, 1),
			('user-info.php', 12, 1),
			('user-poll.php', 11, 1),
			('user-poll.php', 12, 1),
			('user-poll.php', 14, 1),
			('user-poll.php', 30, 1),
			('user-poll.php', 37, 1),
			('view-someones-blog.php', 11, 1),
			('view-someones-blog.php', 12, 1),
			('view-user-privilege-level.php', 38, 1),
			('xml-validator.php', 11, 1),
			('xml-validator.php', 12, 1),
			('xml-validator.php', 15, 1),
			('xml-validator.php', 36, 1)
			;";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = 
			'CREATE TABLE help_texts('.
				'help_text_key INT, '.
				'help_text TEXT, '.
				'PRIMARY KEY(help_text_key)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if

	$lQueryString ="INSERT INTO `help_texts` (`help_text_key`, `help_text`) VALUES
			(0, 'The index page has several global vulnerabilities.'),
			(1, 'SSLStrip can be used to downgrade the connection when the Enforce SSL button is selected.'),
			(2, 'Output fields such as the logged-in username, signature, and the footer are vulnerable to cross-site scripting.'),
			(3, 'The hints cookie and other cookies can be hacked to login as another user and gain admin access.'),
			(4, 'Cookies are missing the HTTPOnly attribute and may be accessed via cross-site scripting.'),
			(5, 'Check HTML comments for database credentials.'),
			(6, 'The \"page\" input parameter is vulnerable to insecure direct object reference. Fuzzing the parameter with administrative page names or system file paths is likely to yield results.'),
			(7, 'This is the home page. Its primary purpose is to provide a starting page for the user and provide instructions. There are no known vulnerabilties on the home.php page.'),
			(8, '<span class=\"label\">Stored Cross-Site Scripting</span>: Attempt to inject cross-site scripts which will be stored in the backend database. When a user visits this page, the cross-site scripts will be fetched from the database, incorporated into the HTML generated, and sent to the user browser. The user browser will execute the JavaScript. One option is to inject a cross-site script which sends the user to the capture-data.php page. You can view captured data on the captured-data.php page.'),
			(9, '<span class=\"label\">Directory Browsing</span>: The entire site is vulnerable to directory browsing. Looking at the robots.txt file can provide hints of interesting directories.'),
			(10, '<span class=\"label\">SQL Injection</span>: Attempt to inject special database characters or SQL timing attacks into page parameters. Database errors, page defacement, or noticable delays in response may indicate SQL injection flaws. This page is vulnerable.'),
			(11, '<span class=\"label\">Reflected Cross-Site Scripting:</span> This page is vulnerable to reflected cross-site scripting because the input is not encoded prior to be used as output. Determine which input field contributes output here and inject scripts. Try to redirect the user to the capture-data.php page which records cookies and other parameters. Visit the captured-data.php page to view captured data.'),
			(12, '<span class=\"label\">HTML Injection</span>: It is possible to inject your own HTML into this page because the input is not encoded prior to be used as output. Determine which input field contributes output here and inject HTML, CSS, and/or JavaScripts in order to alter the client-side code of this page.'),
			(13, '<span class=\"label\">JavaScript Validation Bypass</span>: Set the page to at least security level 1 to activate the javascript validation. JavaScript validation can always be bypassed. Use a client-proxy like Burp-Suite to capture the request after it has left the browser. You can alter the request at that time. Also, JavaScript can be disabled.'),
			(14, '<span class=\"label\">Cross Site Request Forgery</span>: This page is vulnerable to cross-site request forgery. There are a few steps to prepare a cross-site script to carry out the cross-site request forgery. Begin by filling out the form capturing the legitimate request. Inject a stored or reflected cross-site script anywhere on the site that will cause the browser to submit a copy of the legitimate request to the server. The server will process the request as if the user had filled out the form themselves.'),
			(15, '<span class=\"label\">System File Compromise</span>: It is possible to access system files by injecting input parameters with the pathnames of system files. The web application will fetch the system files instead of application files. The system files may be displayed and/or included in page output. Remember web applications are usually served from a system directory like /var/www or C:XAMPP. You may need to move up directories.'),
			(16, '<span class=\"label\">Insecure Direct Object Reference</span>: This page refers directly to resources by there real name or identifier making it possible to modify the name/ID to access other resources. Determine what resources are fetched. Provide the name or ID of a different resource. Resources can be filenames, record identifiers or others.'),
			(17, '<span class=\"label\">Server Side Include</span>: It is possible to make the application include application files in this page that are not intended. These files may even come from other sites.'),
			(18, '<span class=\"label\">JavaScript Injection</span>: This page uses at least some of the input from the user to generate JavaScript code. Usually in these cases the user input is used to create either a JavaScript string or JSON object. Attempt to inject input which when incorporated with the page will form a syntactically correct JavaScript statement. This will allow the injection to execute in the context of the browser.'),
			(19, '<span class=\"label\">Unvalidated Redirects and Forwards</span>: This page refers directly to dynamic URLs. If the user clicks on one of the link, the URL embedded is passed to a page which performs redirection. Try to over-write one of the intended pages beind passed to redirect the user to an arbitrary page. Give the poisoned link you create to a freind and see if they are redirected to a site of your choosing.'),
			(20, '<span class=\"label\">Operating System Command Injection</span>:  Command injection may occur when a web application passes user input in part or in whole to the operating system for execution. This page incorporates user input into a larger statement that is submitted to an operating system shell for execution. Try to determine the operating system in use. Enter characters that are reserved in shells; especially characters used to concatenate commands.'),
			(21, '<span class=\"label\">HTTP Parameter Pollution</span>: If multiple parameters with the same name are sent in a request, different application servers will react differently. PHP takes only one of the parameters but not neccesarily the parameters intended by the developer. By duplicating parameters with a value of your choosing and placing that parameters before and-or after the pages native parameters, you can influence the pages behavior. Note that ASP and Java application servers act different.'),
			(22, '<span class=\"label\">Click-jacking</span>: By placing an invisible overlay over top of a legitimate page, a malicious agent can hijack a users mouse clicks. To overlay the vulnerable page, the malicious agent will host the victim page inside a full page frame with no borders.'),
			(23, '<span class=\"label\">Document Object Model (DOM) Injection</span>: User input is incorporated into the document object model (DOM) of the page itself. This allows a user to inject HTML which will be incorporated into the source code of the page. The browser will execute this new code immediately.'),
			(24, 'The UID cookie is used in an SQL query allowing SQL injection via a cookie value.'),
			(25, '<span class=\"label\">Authentication Bypass</span>: Authentication bypass can be achieved by either hacking the UID cookie or by SQL injecting the login.'),
			(26, '<span class=\"label\">JavaScript Object Notation (JSON) Injection</span>: This page uses JSON to pass data which is later parsed and incorporated into the page. Because the output is not properly encoded, it is possible to carefully craft an injection which will add extra data into the JSON without breaking the JSON syntax. This extra data will be executed by the browser once the data is incorporated into the page.'),
			(27, '<span class=\"label\">Platform Path Disclosure</span>: Internal system paths are disclosed by this page under certain conditions.'),
			(28, '<span class=\"label\">Application Path Disclosure</span>: Application file paths are disclosed by this page under certain conditions.'),
			(29, '<span class=\"label\">Information Disclosure</span>: This page gives away internal system information, configuration information, paths, filenames, or other private information.'),
			(30, '<span class=\"label\">Method Tampering</span>: Because the page does not specify that the input parameters must be posted, it is possible to submit input parameters via a post or a get. This is a second order vulnerability allowing other vulnerabilities to be exploited easier.'),
			(31, '<span class=\"label\">Parameter Addition</span>: If extra parameters are submitted, the page will include them in output. A parameter can be added containing scripts which will be executed when loaded in the users browser.'),
			(32, '<span class=\"label\">Buffer Overflow</span>: If very long input is submitted, it is possible to exhaust the available space alloted on the heap.'),
			(33, '<span class=\"label\">Cascading Style Sheet Injection</span>: CSS styles can be used to interpret and execute JavaScript. If styles can be injected, it is possible to inject a style with embedded JavaScript which will be executed when loaded into the users browser.'),
			(34, '<span class=\"label\">Denial of Service</span>: This page allows denial of service. DOS can be performed by exhausting system resource(s) such as filling up disk drives or consuming available network bandwidth.'),
			(35, '<span class=\"label\">Phishing/Remote File Inclusion</span>: Due to defects allowing arbitrary web pages to be loaded into this pages frames, phishing and malware downloads are possible.'),
			(36, '<span class=\"label\">XML External Entity Injection Attack</span>: This page parses XML which the user can influence. If external entities embedded in the XML contain system file directives, it is possible to cause the page to load system files and include the contents in the XML output.'),
			(37, '<span class=\"label\">Parameter Pollution</span>: If multiple parameters are submitted with the same name, the server will process either the first, last, or all of the duplicates. This can be used to forge requests and in some cases bypass WAF filters. How the server processes the duplicates will vary according to the brand of server.'),
			(38, '<span class=\"label\">Cipher Block Chaining (CBC) Bit Flipping Attack</span>: This page is vulnerable to CBC bit flipping attack.');";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
		
	$lQueryString = 
			'CREATE TABLE balloon_tips('.
				'tip_key VARCHAR(64) NOT NULL, '.
				'hint_level INT, '.
				'tip TEXT, '.
				'PRIMARY KEY(tip_key, hint_level)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE TABLE' with result ".$lQueryResult,"S");
	}// end if

	$lQueryString ="INSERT INTO `balloon_tips` (`tip_key`, `hint_level`, `tip`) VALUES
			('ParameterPollutionInjectionPoint', 0, 'User input is not evaluated for duplicate parameters'),
			('ParameterPollutionInjectionPoint', 1, 'If user input contains the same variable more than once, the system will only accept one of the values. This can be used to trick the system into accepting a correct value and a mallicious value but only counting the mallicious value.'),
			('ParameterPollutionInjectionPoint', 2, 'Send two copies of the same parameter. Note carefully if the system uses the first, second, or both values. Some systems will concatenate the values together. If the system uses the first value, inject the value you want the system to count first.'),
			('CSSInjectionPoint', 0, 'User input is incorporated into the style sheet returned from the server'),
			('CSSInjectionPoint', 1, 'User input is incorporated into the style sheet returned from the server without being properly encoded. This allows an attacker to inject cross-site scripts or HTML into the input and break out of the style-sheet context. Arbitrary JavaScript and HTML can be injected.'),
			('CSSInjectionPoint', 2, 'Locate the input parameter that is incorporated into the style sheet. Determine what chracters are needed to properly complete the style so it is sytactically correct. Inject this closing statement along with a JavaScript or HTML to be executed.'),
			('JSONInjectionPoint', 0, 'User input is incorporated into the JSON returned from the server'),
			('JSONInjectionPoint', 1, 'User input is incorporated into the JSON returned from the server without being properly encoded. This allows an attacker to inject JSON into the input and break out of the JSON context. Arbitrary JavaScript can be injected.'),
			('JSONInjectionPoint', 2, 'Locate the input parameter that is incorporated into the JSON. Determine what chracters are needed to properly complete the JSON so it is sytactically correct. Inject this closing statement along with a JavaScript to be executed.'),
			('DOMXSSExecutionPoint', 0, 'This location contains dynamic output modified by the DOM'),
			('DOMXSSExecutionPoint', 1, 'Lack of output encoding controls often result in cross-site scripting when user input is incorporated into the DOM'),
			('DOMXSSExecutionPoint', 2, 'This output is vulnerable to cross-site scripting because user-input is incorporated into the DOM without properly encoding the user input first. Determine which input field contributes output here and inject HTML or scripts'),
			('ArbitraryRedirectionPoint', 0, 'Arbitrary redirection is a type of insecure direct object reference'),
			('ArbitraryRedirectionPoint', 1, 'See if a URL can be injected in place of the intended URL'),
			('ArbitraryRedirectionPoint', 2, 'Try injecting a URL into the parameter which contains the page to which the site thinks the user should be redirected to. It may be neccesary to use a complete URL including the protocol.'),
			('SQLInjectionPoint', 0, 'SQL Injection may occur on any page interacting with a database'),
			('SQLInjectionPoint', 1, 'Try injecting single-quotes and other special control characters'),
			('SQLInjectionPoint', 2, 'Try injecting single-quotes and other special control characters to produce an error if possible. Note any queries in the error to assist in injecting a complete query. Try using SQLMAP to inject queries.'),
			('CookieTamperingAffectedArea', 0, 'Cookies may store system state information'),
			('CookieTamperingAffectedArea', 1, 'Inspect the value of the cookies with a Firefox add-on like Cookie-Manager or a non-transparent proxy like Burp or Zap'),
			('CookieTamperingAffectedArea', 2, 'Change the value of the cookies to see what affect is produced on the site. Also watch how the values of the cookies change after using different site features.'),
			('JavaScriptInjectionPoint', 0, 'This location does not use JavaScript string encoding'),
			('JavaScriptInjectionPoint', 1, 'This location is vulnerable to JavaScript string injection. The first step is to determine which parameter is output here'),
			('JavaScriptInjectionPoint', 2, 'Locate the input parameter that is output to this location and inject raw JavaScript commands. Use the view-source to see if the syntax of the injection is correct'),
			('LocalFileInclusionVulnerability', 0, 'Perhaps a file other than the one intended could be included in this page'),
			('LocalFileInclusionVulnerability', 1, 'This page is vulnerable a local file inclusion vulnerability because it does not strongly validate that only explicitly named-pages are allowed.'),
			('LocalFileInclusionVulnerability', 2, 'Identify the input parameter that accepts the filename to be included then change that parameter to a system file such as /etc/passwd or C:\\boot.ini'),
			('HTMLandXSSandSQLInjectionPoint', 0, 'Inputs are usually a good place to start testing for cross-site scripting, HTML injection and SQL injection'),
			('HTMLandXSSandSQLInjectionPoint', 1, 'This input is vulnerable to multiple types of injection including cross-site scripting, HTML injection and SQL injection'),
			('HTMLandXSSandSQLInjectionPoint', 2, 'To get started with cross-site scripting and HTML injection, inject a JavaScript or HTML code then view-source on the resulting page to see if the script syntax is correct. For SQL injection, start by injecting a single-quote to produce an error.'),
			('HTMLandXSSInjectionPoint', 0, 'Inputs are usually a good place to start testing for cross-site scripting and HTML injection'),
			('HTMLandXSSInjectionPoint', 1, 'This input is vulnerable to multiple types of injection including cross-site scripting and HTML injection'),
			('HTMLandXSSInjectionPoint', 2, 'To get started with cross-site scripting and HTML injection, inject a JavaScript or HTML code then view-source on the resulting page to see if the script syntax is correct.'),
			('BufferOverflowInjectionPoint', 0, 'Inputs are usually a good place to start testing for buffer overflows.'),
			('BufferOverflowInjectionPoint', 1, 'This input is vulnerable to overflowing a memory buffer. Given this is an interpreted web application, the buffer is just a variable rather than a stack- or heap- overflow.'),
			('BufferOverflowInjectionPoint', 2, 'To trigger a buffer overflow, cause the system to store a large number of characters in a string variable or inject a large number that overflows the data type assigned. PHP documentation states that 134,217,728 characters can be held in a string including the null terminator. String buffer overflows using str_repeat() are tricky because if the number of characters to repeat is too large, PHP sees the number as NaN and wont throw an overflow error.'),
			('OSCommandInjectionPoint', 0, 'Inputs are usually a good place to start testing for command injection'),
			('OSCommandInjectionPoint', 1, 'This input is vulnerable to multiple types of injection'),
			('OSCommandInjectionPoint', 2, 'This input is vulnerable to command injection plus may provide an injection point for reflected cross-site scripting. Try stating with \"127.0.0.1 && dir\".'),
			('XSRFVulnerabilityArea', 0, 'HTML forms are vulnerable to cross-site request forgery by default although sensitive forms may be protected'),
			('XSRFVulnerabilityArea', 1, 'This form is vulnerable to cross-site request forgery. Knowing the form action and inputs is the first step.'),
			('XSRFVulnerabilityArea', 2, 'Use this form to commit cross-site request forgery. Capture a legitimate request in Burp/Zap then create a cross-site script that sends the equivilent request when a user executes the cross-site script.'),
			('ReflectedXSSExecutionPoint', 0, 'This location contains dynamic output'),
			('ReflectedXSSExecutionPoint', 1, 'Lack of output encoding controls often result in cross-site scripting'),
			('ReflectedXSSExecutionPoint', 2, 'This output is vulnerable to cross-site scripting. Determine which input field contributes output here and inject scripts'),
			('HTMLEventReflectedXSSExecutionPoint', 0, 'This location contains dynamic output'),
			('HTMLEventReflectedXSSExecutionPoint', 1, 'Lack of output encoding controls often result in cross-site scripting; in this case via HTML Event injection.'),
			('HTMLEventReflectedXSSExecutionPoint', 2, 'This output is vulnerable to cross-site scripting because the input is not encoded prior to be used as a value in an HTML event. Determine which input field contributes output here and inject scripts.')
			;";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = "
	CREATE PROCEDURE getBestCollegeBasketballTeam ()
	BEGIN
		SELECT 'Kentucky Wildcats';
	END;
	";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE PROCEDURE' with result ".$lQueryResult,"S");
	}// end if
	
	$lQueryString = "
		CREATE PROCEDURE authenticateUserAndReturnProfile (p_username text, p_password text)
		BEGIN
			SELECT  accounts.cid, 
		          accounts.username, 
		          accounts.password, 
		          accounts.mysignature
		  FROM accounts
		    WHERE accounts.username = p_username
		      AND accounts.password = p_password;
		END;
	";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE PROCEDURE' with result ".$lQueryResult,"S");
	}// end if
	
	$lQueryString = "
		CREATE PROCEDURE insertBlogEntry (
		  pBloggerName text,
		  pComment text
		)
		BEGIN
		
		  INSERT INTO blogs_table(
		    blogger_name, 
		    comment, 
		    date
		   )VALUES(
		    pBloggerName, 
		    pComment, 
		    now()
		  );
		
		END;
	";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo format("Executed query 'CREATE PROCEDURE' with result ".$lQueryResult,"S");
	}// end if
	
	$MySQLHandler->closeDatabaseConnection();

} catch (Exception $e) {
	$lErrorDetected = TRUE;
	echo $CustomErrorHandler->FormatError($e, $lQueryString);
}// end try

// if no errors were detected, send the user back to the page that requested the database be reset.
//We use JS instead of HTTP Location header so that HTML5 clearing JS above will run
if(!$lErrorDetected){
	/*If the user came from the database error page but we do not have database errors anymore,
	 * send them to the home page.
	 */
	$lRedirectLocation = str_ireplace("database-offline.php", "index.php", $_SERVER["HTTP_REFERER"]);
	echo "<script>if(confirm(\"No PHP or MySQL errors were detected when resetting the database.\\n\\nClick OK to proceed to ".$lRedirectLocation." or Cancel to stay on this page.\")){document.location=\"".$lRedirectLocation."\"};</script>";
	//header("Location: ".$_SERVER["HTTP_REFERER"], true, 302);
}// end if

$CustomErrorHandler = null;
?>

	</body>
</html>