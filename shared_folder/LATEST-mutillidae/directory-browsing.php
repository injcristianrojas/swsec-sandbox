<div class="page-title">Directory Browsing</div>

<?php include_once './includes/back-button.inc';?>

<table style="margin-left:auto; margin-right:auto; width: 600px;">
	<tr>
		<td class="form-header">Directory Browsing</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td>Some web servers are misconfigured and allow directory browsing. This an easy mistake to make. While
		most sites disable directory browsing on the "home" or root page, some allow browsing on other directories.
		For each folder found in the site, attempt to browse to the folder without the page name. If using grep,
		look for "Index Of" as a match.</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td style="text-align:center;">
			NOWASP Mutillidae seems to disallow directory browsing on the root page. Try browsing to 
			http://localhost/mutillidae. Likely this will load the home page. However, the site may not
			be configured perfectly. Perhaps if a folder name was known, we could try to browse to that 
			folder (i.e. - http://localhost/mutillidae/&lt;folder&gt;).
			<br>
			<br>
			If help is needed figuring out folder names, try activating the hints below.
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table style="margin-left:auto; margin-right:auto; width: 600px;">
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li>
						  		Try brute forcing directory names with Burp Intruder in sniper mode
							</li>
							<li>
								Try using Nikto which does a nice job of testing for
								directory browsing for common directory names
							</li>
						  	<li>Read the NOWASP Mutillidae page concerning robots.txt</li>
							<li>Try guessing. For example, what folder might hold include files? Perhaps
								"inc" or "include" or similar?
							</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section
?>