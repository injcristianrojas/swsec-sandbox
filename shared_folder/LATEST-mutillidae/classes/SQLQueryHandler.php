<?php
class SQLQueryHandler {
	protected $encodeOutput = FALSE;
	protected $stopSQLInjection = FALSE;
	protected $mSecurityLevel = 0;

	// private objects
	protected $mMySQLHandler = null;
	protected $mESAPI = null;
	protected $mEncoder = null;
	
	private function doSetSecurityLevel($pSecurityLevel){
		$this->mSecurityLevel = $pSecurityLevel;
		
		switch ($this->mSecurityLevel){
	   		case "0": // This code is insecure, we are not encoding output
			case "1": // This code is insecure, we are not encoding output
				$this->encodeOutput = FALSE;
				$this->stopSQLInjection = FALSE;
	   		break;
		    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
	  			// If we are secure, then we encode all output.
	   			$this->encodeOutput = TRUE;
	   			$this->stopSQLInjection = TRUE;
	   		break;
	   	}// end switch		
	}// end function
							
	public function __construct($pPathToESAPI, $pSecurityLevel){
		
		$this->doSetSecurityLevel($pSecurityLevel);
		
		//initialize OWASP ESAPI for PHP
		require_once $pPathToESAPI . 'ESAPI.php';
		$this->ESAPI = new ESAPI($pPathToESAPI . 'ESAPI.xml');
		$this->Encoder = $this->ESAPI->getEncoder();

		/* Initialize MySQL Connection handler */
		require_once 'MySQLHandler.php';
		$this->mMySQLHandler = new MySQLHandler($pPathToESAPI, $pSecurityLevel);
		$this->mMySQLHandler->connectToDefaultDatabase();
		
	}// end function
	
	public function setSecurityLevel($pSecurityLevel){
		$this->doSetSecurityLevel($pSecurityLevel);
		$this->mMySQLHandler->setSecurityLevel($pSecurityLevel);
	}// end function

	public function getSecurityLevel(){
		return $this->mSecurityLevel;
	}// end function

	public function getPageHelpTexts($pPageName){
		
		if ($this->stopSQLInjection == TRUE){
			$pPageName = $this->mMySQLHandler->escapeDangerousCharacters($pPageName);
		}// end if

		$lQueryString  = "
			SELECT CONCAT(
				'<div class=\"help-text\">
					<img src=\"./images/bullet_black.png\" style=\"vertical-align: middle;\" />',
				help_text,
				'</div>'
			) AS help_text
			FROM page_help
			INNER JOIN help_texts
			ON page_help.help_text_key = help_texts.help_text_key
			WHERE page_help.page_name = '" . $pPageName . "' " .
			"ORDER BY order_preference";
		
		return $this->mMySQLHandler->executeQuery($lQueryString);
	}//end public function getPageHelpTexts

	public function insertBlogRecord($pBloggerName, $pBlogEntry){
		
		if ($this->stopSQLInjection == TRUE){
			$pBloggerName = $this->mMySQLHandler->escapeDangerousCharacters($pBloggerName);
			$pBlogEntry = $this->mMySQLHandler->escapeDangerousCharacters($pBlogEntry);
		}// end if

		$lQueryString  = "
			INSERT INTO blogs_table(blogger_name, comment, date) VALUES ('".
				$pBloggerName . "', '".
				$pBlogEntry  . "', " .
				" now() )";
		
		return $this->mMySQLHandler->executeQuery($lQueryString);
	}//end public function insertBlogRecord

	public function getBlogRecord($pBloggerName){

		if ($this->stopSQLInjection == TRUE){
			$pBloggerName = $this->mMySQLHandler->escapeDangerousCharacters($pBloggerName);
		}// end if

		$lQueryString = "
			SELECT * FROM blogs_table 
			WHERE blogger_name like '{$pBloggerName}%'
			ORDER BY date DESC
			LIMIT 0 , 100";
				
		return $this->mMySQLHandler->executeQuery($lQueryString);
	}//end public function insertBlogRecord

}// end class