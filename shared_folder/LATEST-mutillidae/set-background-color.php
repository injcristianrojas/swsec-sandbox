<?php
	if (isset($_POST["set-background-color-php-submit-button"])){
		
		try {	    	
	    	switch ($_SESSION["security-level"]){
	    		case "0": // This code is insecure. No output encoding is performed.
	    		case "1": // This code is insecure. No output encoding is performed.
	    			// Grab inputs insecurely. $_REQUEST allows any input parameter. Not just POST.
					$lBackgroundColor = $lBackgroundColorText = $_REQUEST["background_color"];
					
	    			//$$lBackgroundColorValidated = true; 			// do not perform validation
	    		break;
	    		
		   		case "2":
		   		case "3":
		   		case "4":
	    		case "5": // This code is fairly secure
					/* Protect against one form of patameter pollution 
					 * by grabbing inputs only from POST parameters. */ 

	    			/* Protect against XSS by output encoding */
	    			$lBackgroundColor = $Encoder->encodeForCSS($_POST["background_color"]);
					$lBackgroundColorText = $Encoder->encodeForHTML($_POST["background_color"]);					

					/* Validation: */
	    			//$lBackgroundColorValidated = preg_match(CSS_COLOR_VALUE_REGEX_PATTERN, $lBackgroundColor);
	    		break;
	    	}// end switch

    	} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, "Input: " . $lBackgroundColor);
    	}// end try
    	
	}else{
		$lBackgroundColor = $lBackgroundColorText = "eecccc";
	}// end if (isset($_POST)) 
?>


<!-- Bubble hints code -->
<?php 
	try{
   		$lCSSInjectionPointBallonTip = $BubbleHintHandler->getHint("CSSInjectionPoint");
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[CSSInjectionPoint]').attr("title", "<?php echo $lCSSInjectionPointBallonTip; ?>");
		$('[CSSInjectionPoint]').balloon();
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
	});
</script>

<div class="page-title">Set Background Color</div>

<?php include_once './includes/back-button.inc'; ?>

<form 
		action="index.php?page=set-background-color.php" 
		method="post" 
		enctype="application/x-www-form-urlencoded"
		style="background-color:#<?php echo $lBackgroundColor; ?>"
	>
	<table style="margin-left:auto; margin-right:auto;">
		<tr id="id-bad-cred-tr" style="display: none;">
			<td colspan="2" class="error-message">
				Error: Invalid Input
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" class="form-header">Please enter the background color you would like to see<br/><br/>Enter the color in RRGGBB format<br/>(Example: Red = FF0000)</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td class="label">Background Color</td>
			<td>
				<input CSSInjectionPoint="1" type="text" name="background_color" size="6">
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input name="set-background-color-php-submit-button" class="button" type="submit" value="Set Background Color" />
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td ReflectedXSSExecutionPoint="1" class="informative-message" colspan="2" style="text-align: center;">
				The current background color is <?php echo $lBackgroundColorText; ?>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
	</table>
</form>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li>
						  		<b>Cascading Style Injection:</b> This injection uses a different syntax but the 
						  		methodology to exploit is the same. 
							</li>
							<li>Inject arbitrary input then check the resulting response for your input</li>
							<li>To inject HTML or JavaScript into the style, look to close off the style, then
							start the injection, then comment out the remaining part of the style or complete
							the remaining part with valid syntax.</li>
							<li>Example Target:&lt;body style="color:#{dynamic input}"&gt;</li>
							<li>Possible Solution: style="&lt;body color:#"<b style="color:red;">"&gt;&lt;H1&gtHELLO WORLD&lt;/H1&gt;&lt;br anything="</b>"&gt;</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>