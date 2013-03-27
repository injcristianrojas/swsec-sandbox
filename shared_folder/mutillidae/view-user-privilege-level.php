<?php 

	function PrettyPrintStringtoHex($lString) {
		$lHexText = "";
		for($i=0;$i<strlen($lString);$i++){ 
			$lHexText .= "0X" . str_pad(dechex(ord($lString{$i})), 2, "0", STR_PAD_LEFT) . " "; 
		}//end for
		return $lHexText;
	}

   	function __xor($lHexString1, $lHexString2) {
		$lBlocksize = 16;
   		$lResult = "";
		for($i=0;$i<$lBlocksize*2;$i+=2){
			$lResult .= str_pad(dechex(hexdec(substr($lHexString1,$i,2)) ^ hexdec(substr($lHexString2,$i,2))), 2, "0", STR_PAD_LEFT); 
		}//end for
		return $lResult;
   	}// end function
	
	try{
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure.
				$lEnableJavaScriptValidation = FALSE;
				$lEnableBufferOverflowProtection = FALSE;
				$lProtectAgainstMethodSwitching = FALSE;
				$lCreateParameterAdditionVulnerability = TRUE;
				$lLeakIVToBrowser = TRUE;
				$lIgnoreUserInfluence = FALSE;
    		break;

    		case "1": // This code is insecure.
				$lEnableJavaScriptValidation = TRUE;
				$lEnableBufferOverflowProtection = FALSE;
				$lProtectAgainstMethodSwitching = FALSE;
				$lCreateParameterAdditionVulnerability = TRUE;
				$lLeakIVToBrowser = TRUE;
				$lIgnoreUserInfluence = FALSE;
    		break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
    			$lEnableJavaScriptValidation = TRUE;
				$lEnableBufferOverflowProtection = TRUE;
				$lProtectAgainstMethodSwitching = TRUE;
				$lCreateParameterAdditionVulnerability = FALSE;
				$lLeakIVToBrowser = FALSE;
				$lIgnoreUserInfluence = TRUE;
    		break;
    	}// end switch
    	
		// if we want to enforce POST method, we need to be careful to specify $_POST
    	if(!$lProtectAgainstMethodSwitching){
	   		$lInitializationVector = $_REQUEST["iv"];
			$lSubmitButtonClicked = isSet($_REQUEST["view-user-privilege-level-php-submit-button"]);
	   	}else{
	   		$lInitializationVector = $_POST["iv"];
			$lSubmitButtonClicked = isSet($_POST["view-user-privilege-level-php-submit-button"]);
	   	}//end if
	   	    	
    	$lDefaultInitializationVector = "6bc24fc1ab650b25b4114e93a98f1eba";
    	$lCryptoKey = MD5("SecretSauce12345");
    	$lUserID = "100";
		$lUserGroupID = "100";
		$lPlaintext = "0000" . $lUserID . $lUserGroupID . "000000";
		$lBlocksize = 16;

		// in case IV is corrupt
		if (strlen($lInitializationVector) != $lBlocksize*2){
	   		$lInitializationVector = $lDefaultInitializationVector;
	   	}//end if
	   	
	   	// if site is secure, ignore user input
		if ($lIgnoreUserInfluence){
	   		$lInitializationVector = $lDefaultInitializationVector;
	   	}//end if

		if ($lLeakIVToBrowser){
			$lInitializationVectorValue = $lInitializationVector;
		}else{
			$lInitializationVectorValue = "Undisclosed";
		}//end if
	   	
	   	/* ******************************
	   	 * CONVERT PLAINTEXT INTO HEX
	   	 ******************************** */
		$lHexText = "";
		for($i=0;$i<$lBlocksize;$i++){ 
			$lHexText .= str_pad(dechex(ord($lPlaintext{$i})), 2, "0", STR_PAD_LEFT); 
		}//end for

	   	/* **********
	   	 * ENCRYPTION 
	   	 ************ */
		$lCiphertext .= __xor($lHexText, $lCryptoKey);
		$lChainedCipherBlock .= __xor($lDefaultInitializationVector, $lCiphertext); 

	   	/* **********
	   	 * DECRYPTION 
	   	 ************ */		
		$lUnchainedCiphertext .= __xor($lInitializationVector, $lChainedCipherBlock);
		$lUnchainedHexText .= __xor($lUnchainedCiphertext, $lCryptoKey);

		/* ******************************
	   	 * CONVERT HEX TO PLAINTEXT 
	   	 ******************************** */		
		$lUnchainedPlaintext = "";
		for($i=0;$i<$lBlocksize*2;$i+=2){
			$lUnchainedPlaintext .= chr(hexdec(substr($lUnchainedHexText,$i,2))); 
		}//end for

		$lUserIDValue = substr($lUnchainedPlaintext,4,3);
		$lUserGroupIDValue = substr($lUnchainedPlaintext,7,3);
		
		$lUserIsRoot = FALSE;
		if ($lUserIDValue == "000" && $lUserGroupIDValue == "000"){
			$lUserIsRoot = TRUE;
		}// end if
		
	} catch(Exception $e){
		$lSubmitButtonClicked = FALSE;
		echo "<div class=\"error-message\">".$lErrorMessage."</div>";
		echo $CustomErrorHandler->FormatError($e, "Error attempting to repeat string.");
	}// end try	
?>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
   		$lBufferOverflowInjectionPointBalloonTip = $BubbleHintHandler->getHint("BufferOverflowInjectionPoint");
		$lHTMLandXSSInjectionPointBalloonTip = $BubbleHintHandler->getHint("HTMLandXSSInjectionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
		$('[BufferOverflowInjectionPoint]').attr("title", "<?php echo $lBufferOverflowInjectionPointBalloonTip; ?>");
		$('[BufferOverflowInjectionPoint]').balloon();		
		$('[HTMLandXSSInjectionPoint]').attr("title", "<?php echo $lHTMLandXSSInjectionPointBalloonTip; ?>");
		$('[HTMLandXSSInjectionPoint]').balloon();		
	});
</script>

<div class="page-title">View User Privilege Level</div>

<?php include_once './includes/back-button.inc';?>

<?php 
	if ($lCreateParameterAdditionVulnerability) {
		echo "<!-- Diagnostics: Request Parameters - ";
		echo var_dump($_REQUEST);
		echo "-->";
	}// end if
?>

<div id="id-view-user-privilege-level-form-div" style="text-align:center;">
	<form 	action="index.php?page=view-user-privilege-level.php" 
			method="post" 
			enctype="application/x-www-form-urlencoded" 
			onsubmit="return onSubmitOfViewUserPrivilegeLevelForm(this);"
			id="idViewUserPrivilegeLevelForm">
		<table style="margin-left:auto; margin-right:auto;">
			<tr id="id-user-privilege-message" style="display: none;">
				<td colspan="2" class="error-message">
					User is root!
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" class="form-header">User Privilege Level</td>
			</tr>
			<tr><td></td></tr>
			<tr><td class="label" colspan="2">Note: UID/GID "000" is root</td></tr>
			<tr><td></td></tr>
			<tr>
				<td class="label" style="text-align: left;">Initialization Vector</td>
				<td style="text-align: left;">
					<input name="iv" id="id_iv" type="text" HTMLandXSSInjectionPoint="" value="<?php echo $lInitializationVectorValue; ?>" size="<?php echo 2 * $lBlocksize + 4 ?>" maxlength="<?php echo 2 * $lBlocksize ?>" />
				</td>
			</tr>
			<tr>
				<td class="label" style="text-align: left;">User ID</td>
				<td style="text-align: left;"><?php echo $lUserIDValue . " ( Hint: " . PrettyPrintStringtoHex($lUserIDValue) . ")"; ?></td>
			</tr>
			<tr>
				<td class="label" style="text-align: left;">Group ID</td>
				<td style="text-align: left;"><?php echo $lUserGroupIDValue . " ( Hint: " . PrettyPrintStringtoHex($lUserGroupIDValue) . ")"; ?></td>
			</tr>			
			<tr><td></td></tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="view-user-privilege-level-php-submit-button" class="button" type="submit" value="View Privileges" />
				</td>
			</tr>
			<tr><td></td></tr>
		</table>
	</form>
</div>

<div id="id-view-user-privilege-level-output-div" style="text-align: center; display: none;">
	<table align="center">
		<tr><td></td></tr>
		<tr>
			<td ReflectedXSSExecutionPoint="1" colspan="2" class="hint-header"><?php echo $lBuffer; ?></td>
		</tr>
		<tr><td></td></tr>
	</table>	
</div>

<script type="text/javascript">
<?php 
	if ($lUserIsRoot) {
		echo "var l_user_is_root = true;" . PHP_EOL;
	}else {
		echo "var l_user_is_root = false;" . PHP_EOL;
	}// end if
?>
	if (l_user_is_root){
		document.getElementById("id-user-privilege-message").style.display="";		
	}// end if l_user_is_root
</script>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<br/>
						<span class="report-header">CBC Bit Flipping Attack</span>
						<br/><br/>				
						<ul class="hints">
						  	<li>
							  	This page is vulnerable to cipher block chaining (CBC) bit flipping. 
							  	The goal is to modify the 
							  	initialization vector (IV) in order to cause the user ID and group ID
							  	to both be "000". When this occurs a message will appear.
							</li>
							<li>
								Note that the user ID and group ID are already "100". Only the first
								character ("1") needs to be modified. Try to leave the "00" alone.
							</li> 
						  	<li>
						  		First, determine which of the bytes affects the user ID and group ID respectively.
						  		Modify each byte until the user ID and group ID are affected. Note the position
						  		of the bytes carefully. One byte in the IV will affect the "1" in the user
						  		ID and another byte will affect the "1" in the group ID.
						  	</li>
						  	<li>
						  		A byte can only have 255 distinct values. One way to solve this problem
						  		is to brute force the answer by trying all 255 bytes until a "0" appears
						  		where the "1" is currently shown.
						  	</li>
						  	<li>
						  		A much better way is to XOR the value you input with the value that appears
						  		in the User ID or Group ID. This is the respective byte of the cipher text.
						  		Next, XOR this byte of cipher text with the byte you want to appear; "0" which
						  		is 0X30. Input this result into the IV in the same position that was tampered
						  		with to modify the "1" in the User ID or Group ID.
						  	<li>
							  	The answer is "6bc24fc1aa650b24b4114e93a98f1eba".
						  	</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>

<script type="text/javascript">
	try{
		document.getElementById("idViewUserPrivilegeLevelForm").iv.focus();
	}catch(e){
		alert('Error trying to set focus on field idViewUserPrivilegeLevelForm: ' + e.message);
	}// end try
</script>
