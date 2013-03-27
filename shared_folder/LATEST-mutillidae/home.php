
<div class="page-title">
	Mutillidae: Deliberately Vulnerable Web Pen-Testing Application
</div>
<div>&nbsp;</div>
<div>
	<span title="See the latest vulnerabilities in Mutillidae">
		<img alt="See the latest vulnerabilities in Mutillidae" align="middle" src="./images/new-icon-96-96.png" width="48px" height="48px" />
		<a class="label" href="./index.php?page=documentation/change-log.htm">What's New? Click Here</a>
	</span>
	<?php include_once './includes/help-button.inc';?>
</div>
<table style="margin:0px;">
	<tr>
		<td title="Installation Instructions">
			<img alt="Installation Instructions" align="middle" src="./images/installation-icon-48-48.png" />	
		</td>
		<td class="label" style="padding-right: 30px" title="Installation Instructions">
			<ul>
				<li><a title="Latest Version" href="http://sourceforge.net/projects/mutillidae/files/mutillidae-project/" target="_blank">Latest Version</a></li>
				<li><a title="Installation Instructions" href="./index.php?page=installation.php">Installation Instructions</a></li>
				<li><a title="Usage Instructions" href="./index.php?page=usage-instructions.php">Usage Instructions</a></li>
				<li><a title="Get rid of those pesky PHP errors" href="./index.php?page=php-errors.php">Get rid of those pesky PHP errors</a></li>		
				<li><a title="Notes" href="./index.php?page=notes.php">Notes</a></li>
			</ul>			
		</td>
		<td>
			<img 	title="Pentesting Tools" 
					alt="Tools" 
					align="middle" 
					src="./images/tools-icon-64-64.png" width="48px" height="48px" />	
		</td>
		<td class="label">
			<ul>
				<li>
					<a 	title="Download Backtrack" 
						href="http://www.backtrack-linux.org/" 
						target="_blank">Backtrack</a></li>
				<li>
					<a 	title="Download Samurai Web Testing Framework" 
						href="http://samurai.inguardians.com/" 
						target="_blank">Samurai Web Testing Framework</a>
				</li>
				<li>
					<a href="http://sqlmap.org/" target="_blank" title="SQLMap Automated SQL Injection Tool (Included in Backtrack)">sqlmap</a>
				</li>
				<li>
					<a href="https://addons.mozilla.org/en-US/firefox/collections/jdruin/pro-web-developer-qa-pack/" target="_blank">Some Useful Firefox Add-ons</a>
				</li>
			</ul>
		</td>
	</tr>
</table>
<div>
	<span class="Label" style="white-space: nowrap;" title="Helpful hints and scripts">
		<img alt="Help" align="middle" src="./images/help-icon-48-48.png" width="48px" height="48px" />
		<span style="font-weight: bold;">Hints?: See "/documentation/mutillidae-test-scripts.txt"</span>
	</span>
</div>

<?php
	if ($_SESSION["showhints"]){
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li><b>Security Misconfiguration and Error Handling</b>
						  	This is not directly a vulnerability with the web app, but with 
						  	how it is installed or how the web server is configured. 
						  	Things to check for would be items like:
							</li>
							<li>1. Is the webserver software (Apache, IIS, etc) up to date?
							</li>
							<li>2. How about the libraries your application uses? Are they up to date? Problems could exist because of code you never wrote, but were included as a library.
							</li>
							<li>3. Is you web app running on a box with unneeded services? The web app may be fine, but some other vulnerable service could let someone in.
							</li>
							<li>4. Make sure you are not using default passwords.
							</li>
							<li>5. How does your server handle errors? Sometimes too much information is given back to the attacker via error message and banners. No reason to help out your attackers. Mutillidae has his issue in spades, just type a single quote into some of the forms to see what I mean.
							</li>
							<li>6. Some functions are rather dangerous. If the configuration was hardened many of the problems under "Malicious File Execution" would be harder to exploit since an attacker could not directly tell PHP to grab a file from an offsite URL.
							</li>
							<li>
							Also, depending on your application software stack, there could be a sorts of recommended ways to harden configuration. In the case of PHP, Madirish has a guide that may help: <a href="http://www.madirish.net/?article=229">http://www.madirish.net/?article=229</a>
							</li>
				  		</ul>
					</td>
				</tr>
			</table>'; 
	}// end if
// End hints section
?>
