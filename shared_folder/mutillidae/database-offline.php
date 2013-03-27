<?php 
	/* ------------------------------------------------------
	 * INCLUDE CLASS DEFINITION PRIOR TO INITIALIZING
	 * ------------------------------------------------------ */
	require_once 'classes/MySQLHandler.php';
	try {
		MySQLHandler::databaseAvailable();
	} catch (Exception $e) {
		$lErrorMessage = $e->getMessage();
	}
	
	//Here because of very weird error
	session_start();

	$lSubmitButtonClicked = isSet($_REQUEST["database-offline-php-submit-button"]);	
	
	if ($lSubmitButtonClicked) {
		$_SESSION["UserOKWithDatabaseFailure"] = "TRUE";
		//header("Location: index.php", true, 302);
	}//end if

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" type="text/css" href="./styles/global-styles.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>Database Offline</title>
</head>

<div class="page-title">Database Offline</div>

<table style="margin-left:auto; margin-right:auto;">
	<tr id="id-bad-page-tr">
		<td class="error-message">
			The database appears to be offline. <?php echo $lErrorMessage ?>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<div>
	<form 	action="database-offline.php"
			method="post" 
			enctype="application/x-www-form-urlencoded"
			id="idDatabaseOffline">
		<table style="margin-left:auto; margin-right:auto;">
			<tr><td></td></tr>
			<tr>
				<td colspan="2" class="form-header">Opt out of database warnings</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="label">You can opt out of database connection warnings for the remainder of this session</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="database-offline-php-submit-button" class="button" type="submit" value="Opt Out" />
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="error-header" style="text-align: center;">Did you <a href="set-up-database.php">setup/reset the DB</a>?</td>
			</tr>
		</table>
	</form>
</div>
