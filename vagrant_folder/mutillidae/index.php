<?php

	/* ------------------------------------------
	 * Constants used in application
	 * ------------------------------------------ */
	include_once './includes/constants.php';

	/* ------------------------------------------------------
	 * INCLUDE CLASS DEFINITION PRIOR TO INITIALIZING SESSION
	 * ------------------------------------------------------ */
	require_once 'classes/MySQLHandler.php';
	require_once 'owasp-esapi-php/src/ESAPI.php';
	require_once 'classes/CustomErrorHandler.php';
	require_once 'classes/LogHandler.php';
	require_once 'classes/BubbleHintHandler.php';	
	
    /* ------------------------------------------
     * INITIALIZE SESSION
     * ------------------------------------------ */
	//initialize session
    if (strlen(session_id()) == 0){
    	session_start();
    }// end if

    // ----------------------------------------
	// initialize security level to "insecure" 
	// ----------------------------------------
    if (!isset($_SESSION['security-level'])){
    	$_SESSION['security-level'] = '0';
    }// end if
    
	// user is logged out by default
    if (!isset($_SESSION["loggedin"])){
	    $_SESSION['loggedin'] = 'False';
	    $_SESSION['logged_in_user'] = '';
	    $_SESSION['logged_in_usersignature'] = '';	    	
    }// end if    
    
    if (!isset($_SESSION["UserOKWithDatabaseFailure"])) {
    	$_SESSION["UserOKWithDatabaseFailure"] = "FALSE";
    }// end if
    
    // ----------------------------------------
	// initialize showhints session and cookie 
	// ----------------------------------------
	/* This code is here to create a simulated vulnerability. Some
	 * sites put authorication and status tokens in cookies instead
	 * of the session. This is a mistake. The user controls the 
	 * cookies entirely.
	*/    
	if (isset($_COOKIE["showhints"])){
		$l_showhints = $_COOKIE["showhints"];
	}else{
		$l_showhints = 0;

		/*
		 * If in secure mode, we want the cookie to be protected
		 * with HTTPOnly flag. There is some irony here. In secure code,
		 * we are to ignore authorization cookies, so we are protecting
		 * a cookie we know we are going to ignore. But the point is to
		 * provide an example to developers of proper coding techniques.
		 */
	   	switch ($_SESSION["security-level"]){
	   		case "0": // This code is insecure
	   		case "1": // This code is insecure
	   			$lProtectCookies = FALSE;
	   		break;
			
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
	   			$lProtectCookies = TRUE;
	   		break;
	   	}// end switch		
		
		if ($lProtectCookies){
			setcookie('showhints', $l_showhints.";HTTPOnly");
		}else {
			setcookie('showhints', $l_showhints);
		}// end if $lProtectCookies
	}// end if (isset($_COOKIE["showhints"])){

	if (!isset($_SESSION["showhints"]) || ($_SESSION["showhints"] != $l_showhints)){
		// make session = cookie
		$_SESSION["showhints"] = $l_showhints;
		switch ($l_showhints){
			case 0: $_SESSION["hints-enabled"] = "Disabled (".$l_showhints." - I try harder)"; break;
			case 1: $_SESSION["hints-enabled"] = "Enabled (".$l_showhints." - 5cr1pt K1dd1e)"; break;
			case 2: $_SESSION["hints-enabled"] = "Enabled (".$l_showhints." - Noob)"; break;
		}// end switch
	}//end if
	
	/* ------------------------------------------
	 * initialize OWASP ESAPI for PHP
	 * ------------------------------------------ */
	/*
	if (!is_object($_SESSION["Objects"]["ESAPIHandler"])){
		$_SESSION["Objects"]["ESAPIHandler"] = new ESAPI('owasp-esapi-php/src/ESAPI.xml');
		$_SESSION["Objects"]["ESAPIEncoder"] = $_SESSION["Objects"]["ESAPIHandler"]->getEncoder();
		$_SESSION["Objects"]["ESAPIRandomizer"] = $_SESSION["Objects"]["ESAPIHandler"]->getRandomizer();
	}// end if
	
	// Set up an alias by reference so object can be referenced in memory without copying
	$ESAPI = &$_SESSION["Objects"]["ESAPIHandler"];
	$Encoder = &$_SESSION["Objects"]["ESAPIEncoder"];
	$ESAPIRandomizer = &$_SESSION["Objects"]["ESAPIRandomizer"];
	*/
	$ESAPI = new ESAPI('owasp-esapi-php/src/ESAPI.xml');
	$Encoder = $ESAPI->getEncoder();
	$ESAPIRandomizer = $ESAPI->getRandomizer();

	/* ------------------------------------------
 	* Test for database availability
 	* ------------------------------------------ */

	function handleError($errno, $errstr, $errfile, $errline, array $errcontext){
		restore_error_handler();
		restore_exception_handler();
		header("Location: database-offline.php", true, 302);
		exit();
	}

	function handleException($exception){
		restore_error_handler();
		restore_exception_handler();
		header("Location: database-offline.php", true, 302);
		exit();
	}// end function
	
	if ($_SESSION["UserOKWithDatabaseFailure"] == "FALSE") {
		set_error_handler('handleError', E_ALL);
		set_exception_handler('handleException');
	    MySQLHandler::databaseAvailable();
		restore_error_handler();
		restore_exception_handler();
	}//end if

	/* ------------------------------------------
	 * initialize custom error handler
	 * ------------------------------------------ */
	/*
	if (!is_object($_SESSION["Objects"]["CustomErrorHandler"])){
		$_SESSION["Objects"]["CustomErrorHandler"] = new CustomErrorHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if
	
	$CustomErrorHandler = &$_SESSION["Objects"]["CustomErrorHandler"];
	*/
	$CustomErrorHandler = new CustomErrorHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	
	/* ------------------------------------------
 	* initialize log handler
 	* ------------------------------------------ */
	/*
	if (!is_object($_SESSION["Objects"]["LogHandler"])){
		$_SESSION["Objects"]["LogHandler"] = new LogHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if
	
	$LogHandler = &$_SESSION["Objects"]["LogHandler"];
	*/
	$LogHandler = new LogHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);	
		
	/* ------------------------------------------
 	* initialize MySQL handler
 	* ------------------------------------------ */
	/*
	if (!is_object($_SESSION["Objects"]["MySQLHandler"])){
		$_SESSION["Objects"]["MySQLHandler"] = new MySQLHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if
	
	$MySQLHandler = &$_SESSION["Objects"]["MySQLHandler"];
	*/
	$MySQLHandler = new MySQLHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	$MySQLHandler->connectToDefaultDatabase();
	
	/* ------------------------------------------
 	* initialize balloon-hint handler
 	* ------------------------------------------ */
	/*
   	if (!is_object($_SESSION["Objects"]["BubbleHintHandler"])){
		$_SESSION["Objects"]["BubbleHintHandler"] = new BubbleHintHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if
	
	// Set up an alias by reference so object can be referenced in memory without copying
	$BubbleHintHandler = &$_SESSION["Objects"]["BubbleHintHandler"];
	*/
	$BubbleHintHandler = new BubbleHintHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	
	if ($_SESSION["showhints"] != $BubbleHintHandler->getHintLevel()){
		$BubbleHintHandler->setHintLevel($_SESSION["showhints"]);
	}//end if
	
	/* ------------------------------------------
     * PROCESS REQUESTS
     * ------------------------------------------ */
	if (isset($_GET["do"])){
    	include ("process-commands.php");
    }// end if
    
    // detect and process login attempt
    if (isset($_POST["login-php-submit-button"])){
    	include ("process-login-attempt.php");
    }// end if
	/* ------------------------------------------
     * END PROCESS REQUESTS
     * ------------------------------------------ */	
    
	/* ------------------------------------------
     * REACT TO CLIENT SIDE CHANGES
     * ------------------------------------------ */
	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
			/* Use the clients authorization token which is stored in
			 * the cookie (in this case). Placing authorization tokens
			 * on the client is fairly ridiculous.
			 * 
			 * Known Vulnerabilities: SQL Injection, Authorization Bypass, Session Fixation,
			 * 	Lack of custom error page, Application Exception
			 */
   			if (isset($_COOKIE['uid'])){
 
				try{
					$lQueryString = "SELECT * FROM accounts WHERE cid='" . $_COOKIE['uid'] . "'";
					$lQueryResult = $MySQLHandler->executeQuery($lQueryString);

				    // Switch to whatever cookie the user sent to simulate sites
				    // that use client-side authorization tokens. Auth information
				    // should never be in cookies.
				    if ($lQueryResult->num_rows > 0) {
					    $row = $lQueryResult->fetch_object();
						$_SESSION['loggedin'] = 'True';
						$_SESSION['uid'] = $row->cid;
						$_SESSION['logged_in_user'] = $row->username;
						$_SESSION['logged_in_usersignature'] = $row->mysignature;
						$_SESSION['is_admin'] = $row->is_admin;
   						header('Logged-In-User: '.$_SESSION['logged_in_user'], true);
			    	}// end if ($result->num_rows > 0)
				    
				} catch (Exception $e) {
			   		echo $CustomErrorHandler->FormatError($e, $lQueryString); 
			   	}// end try
   			}else{
	   			/* 
	   			 * Output the user's login name into a custom header 
	   			 * 
	   			 * Known Vulnerability: Potential HTTP Response Splitting
	   			 * (PHP defends itself against HTTP response splitting by
	   			 * filtering "new line" characters)
	   			 */
   				header('Logged-In-User: '.$_SESSION['logged_in_user'], true);
   			}// end if

   		break;
	    
   		case "2":
   		case "3":
   		case "4":
   		case "5": // This code is fairly secure
  			/* If we are secure, then we do not rely on any client input
  			 * to make authorization decisions. Authorization tokens should
  			 * never be stored on the client. We use the SESSION in secure mode.
  			 * 
  			 * Also, when we create an HTTP header, we encode any output to 
  			 * prevent response splitting. The critical chars in response splitting
  			 * are CR-LF. Dont fall for filtering. Just encode it all.  			
  			 */
   			header('Logged-In-User: '.$Encoder->encodeForHTML($_SESSION['logged_in_user']), TRUE);
   		break;
   	}// end switch
	/* ------------------------------------------
     * END REACT TO CLIENT SIDE CHANGES
     * ------------------------------------------ */

   	/* ------------------------------------------
    * ANTI-CLICK-JACKING (Modern Browsers)
    * ------------------------------------------ */
	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   			$lIncludeFrameBustingJavaScript = FALSE;
   		break;
   		case "1":
			$lIncludeFrameBustingJavaScript = TRUE;
   		break;

   		case "2":
   		case "3":
   		case "4":
   		case "5": // This code is fairly secure
  			/* To prevent click-jacking and IE6 key-log-via-framing issue
  			 * we can instruct the browser that it should not frame our site. 
  			 * Unfortuneately only the latest browsers support this option.
  			 * There are javascript frame-buster options that work reasonably well
  			 * although the arms race continues. Use the x-frame-options as the
  			 * primary defense and include the javascript frame-buster to help
  			 * with older browsers.
  			 */
   			header('X-FRAME-OPTIONS: DENY', TRUE);
   			$lIncludeFrameBustingJavaScript = TRUE;
   		break;
   	}// end switch
	/* ------------------------------------------
    * END ANTI-CLICK-JACKING (Modern Browsers)
    * ------------------------------------------ */
	 
	 /* ------------------------------------------
     * CACHE CONTROL
     * ------------------------------------------ */
	try{
		/*
		 * This section detects if the header_remove() function should
		 * be supported. PHP 5.3 first includes this function.
		 */
		$l_header_remove_supported = FALSE;
		$l_phpversion = explode(".", phpversion());
		$l_phpmajorversion = (int)$l_phpversion[0];
		$l_phpminorversion = (int)$l_phpversion[1];
		if (($l_phpmajorversion >= 5 && $l_phpminorversion >= 3) || $l_phpmajorversion > 5){
			$l_header_remove_supported = TRUE;
		}else{
			$l_header_remove_supported = FALSE;
		}// end if
	} catch (Exception $e) {
   		//Bummer: Not sure if we have support
		$l_header_remove_supported = FALSE;
   	}// end try

	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
			try{
				/*
				 * This section is the cache-control. This only works in PHP 5.3
				 * and higher due to the header_remove function becoming
				 * available at that time.
				 */
				if ($l_header_remove_supported){
					/* Try to get rid of expires, last-modified, Pragma,
					 * cache control header, HTTP/1.1 and cookie cache control
					 * that would be created if the user
					 * enabled security level 5. 
					 */
					header_remove("Expires");
					header_remove("Last-Modified");
					header_remove("Cache-Control");
					header_remove("Pragma");			
				}else{
					/* Try to get rid of expires, last-modified, Pragma,
					 * cache control header, HTTP/1.1 and cookie cache control
					 * that would be created if the user
					 * enabled security level 5. 
					 */
					 /*This line causes severe issues with the toggle security and toggle hints. 
						DO NOT uncomment until a patch is found.
						header("Expires: Mon, 26 Jul 2050 05:00:00 GMT", TRUE);
					*/
					header("Last-Modified: Mon, 26 Jul 2050 05:00:00 GMT", TRUE);
					header('Cache-Control: public', TRUE);
					header("Pragma: public", TRUE);
				}// end if
			} catch (Exception $e) {
		   		//Bummer: The cahce-control exercise are not working
		   	}// end try
   		break;
	    		
   		case "2":
   		case "3":
   		case "4":
   		case "5": // This code is fairly secure
   			/*
   			 * Forms caching:
   			 * In insecure mode, we do nothing (as is often the case with insecure mode)
   			 * In secure mode, we set the proper caching directives to help prevent client side caching
   			 * 
   			 * When the browser caches the information asset just walked out the door. HTML 5 combined
   			 * with naive developers is going to make this problem much worse. HTML 5 includes advanced
   			 * cookies called "offline" storage or "DOM" storage. This is going to be a nightmare
   			 * for enterprises to protect their data from leakage.
   			 */
			// Expires: past date tells browser that file is out of date
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", TRUE);
						
			// Always modified - Tells browser that new content available
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT", TRUE);
			
			// HTTP/1.1 and cookie cache control
			header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, no-cache="set-cookie"', TRUE);
						
			// HTTP/1.0 cache-control
			header("Pragma: no-cache", TRUE);

			try{
				/*
				 * Remove x-powered-by header and server header for security. 
				 * Server is hard to get rid of without modifying the Apache config because Apache
				 * adds the header after the PHP has already been rendered and sent to Apache,
				 * but atleast we discussed it here.
				 */
				if ($l_header_remove_supported){
					header_remove("X-Powered-By");
					header_remove("Server");			
				}else{
					/* Try to get rid of expires, last-modified, Pragma,
					 * cache control header, HTTP/1.1 and cookie cache control
					 * that would be created if the user
					 * enabled security level 5. Server is often over-ridden 
					 * by Apache no matter what we do. Change Apache config to fix.
					 */
					header("X-Powered-By: Ming Industries Draconian Power Ring", TRUE);
					header("Server: Kentucky Wildcat Baskeball", TRUE);
				}// end if
			} catch (Exception $e) {
		   		//Bummer: The server blabbermouth defense is not working
		   	}// end try
   		break;
   	}// end switch		
	/* ------------------------------------------
     * END CACHE CONTROL
     * ------------------------------------------ */

	/* ------------------------------------------
     * HTML\JAVASCRIPT COMMENTS
     * ------------------------------------------ */
   	/* In general, HTML and JavaScript comments are to 
   	 * be avoided. Commenting is an excellent practice,
   	 * but the comments should be kept on the server 
   	 * where they belong. To accomplish this, simply
   	 * use the frameworks comment tags rather than
   	 * the HTML and JavaScript style comments.
   	 */
	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1":
			echo '
			<!-- I think the database password is set to blank or perhaps samurai.
			It depends on whether you installed this web app from irongeeks site or
			are using it inside Kevin Johnsons Samurai web testing framework. 
			It is ok to put the password in HTML comments because no user will ever see 
			this comment. I remember that security instructor saying we should use the 
			framework comment symbols (ASP.NET, JAVA, PHP, Etc.) 
			rather than HTML comments, but we all know those 
			security instructors are just making all this up. -->'; 
   		break;
	    
   		case "2":
   		case "3":
   		case "4":
   		case "5": // This code is fairly secure
   			/*
   			 * Note: Notice these are PHP comments rather than client side comments.
   			 * I think the database password is set to blank or perhaps samurai.
   			 */
   		break;
   	}// end switch
	/* ------------------------------------------
     * END HTML\JAVASCRIPT COMMENTS
     * ------------------------------------------ */
	
	/* ------------------------------------------
     * DISPLAY PAGE
     * ------------------------------------------ */

   	/* ------------------------------------------
    * "PAGE" VARIABLE INJECTION
    * ------------------------------------------ */
   	$lPage = "home.php";
	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
		    // Get the value of the "page" URL query parameter
		    if (isset($_REQUEST["page"])) {
		    	$lPage = $_REQUEST["page"];
		    }// end if
   		break;
	    		
   		case "2":
   		case "3":
   		case "4":
   		case "5": // This code is fairly secure
  			/* To prevent page injection, we start with the basic priciple
  			 * of "DENY ALL". We decide to allow only the characters abosolutely
  			 * neccesary to spell the Mutillidae file names. This requires 
  			 * alpha, hyphen, and period.
  			 */
		    // Get the value of the "page" URL query parameter without accepting POST.
		    if (isset($_GET["page"])) {
		    	$lPage = $_GET["page"];
		    }// end if
   			
   			$lPageIsAllowed = (preg_match("/^[a-zA-Z0-9\.\-]+$/", $lPage) == 1);    			
   			if (!$lPageIsAllowed){
		    	$lPage = "page-not-found.php";
   			}// end if
   		break;
   	}// end switch
	/* ------------------------------------------
    * END "PAGE" VARIABLE INJECTION
    * ------------------------------------------ */

	/* ------------------------------------------
     * SIMULATE "SECRET" PAGES
     * ------------------------------------------ */
	switch ($lPage){
   		case "secret.php":
   		case "admin.php":
   		case "_adm.php":
   		case "_admin.php":
   		case "root.php":
   		case "administrator.php":
   		case "auth.php":
   		case "hidden.php":
   		case "console.php":
   		case "conf.php":
   		case "_private.php":
   		case "private.php":
   		case "access.php":
   		case "control.php":
   		case "control-panel.php":

   			switch ($_SESSION["security-level"]){
		   		case "0": // This code is insecure
		   		case "1": // This code is insecure
	    			$lPage="phpinfo.php";
		   		break;
		
		   		case "2":
		   		case "3":
		   		case "4":
		   		case "5": // This code is fairly secure
		  			/* To prevent unauthorized access, we start with the basic priciple
		  			 * of "DENY ALL". 
		  			 */
		   			$lUserAuthorized = FALSE; 
		   			if(isset($_SESSION['is_admin'])){
		   				if($_SESSION['is_admin'] == 'TRUE'){
		   					$lUserAuthorized = TRUE;
		   				}// end if is_admin
		   			}// end if isseet $_SESSION['is_admin']
		   			
		   			if($lUserAuthorized){
		   				$lPage="phpinfo.php";
		   			}else{
		   				$lPage="authorization-required.php";
		   			}// end if $lUserAuthorized
		   			
		   		break;//case 5
		   	}// end switch
		    			
   		break;
   		default:break;
    }//end switch on page   	
	/* ------------------------------------------
     * END SIMULATE "SECRET" PAGES
     * ------------------------------------------ */
   	
	/* ------------------------------------------
    * BEGIN OUTPUT RESPONSE
    * ------------------------------------------ */
    if (strlen($lPage)==0 || !isset($lPage)){
	    include ("header.php");
    	include("home.php");
	    include ("footer.php");
    } elseif ($lPage=="rene-magritte.php") {
    	/* This page is our framing demonstration. 
    	 * The page goes in an iframe so we dont want to
    	 * show the header and footer again. They will
    	 * already show in the outer frame.
    	 */    	
	    include ($lPage);
    } else {
    	include ("header.php"); 
	    include ($lPage);
	    include ("footer.php");
    }// end if

    /* ------------------------------------------
     * Anti-framing protection (Older Browsers)
     * ------------------------------------------ */
    if ($lIncludeFrameBustingJavaScript){
    	require_once ("./includes/anti-framing-protection.inc");	
    }// end if    
    
    /* ------------------------------------------
     * LOG USER VISIT TO PAGE
     * ------------------------------------------ */
	require_once ("log-visit.php");
    
    /* ------------------------------------------
     * CLOSE DATABASE CONNECTION
     * ------------------------------------------ */
    $MySQLHandler->closeDatabaseConnection();
    
   	require_once ("./includes/create-html-5-web-storage-target.inc");	
   	require_once ("./includes/bubble-hints.inc");
?>