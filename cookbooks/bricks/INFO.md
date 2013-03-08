# Notes for the OWASP Bricks version included in this proyect

This project has a modified version of the
[OWASP Bricks Betwa Release](http://sechow.com/bricks/download.html), which
needs to be tweaked before its use in a vagrant machine. Here are the reasons
and changes made:

* This app is made for use on Windows Machines (XAMPP, uWAMP, etc...). To use it
on Linux machines, it needs the following changes:
	* Change PATH concatenations, specially for require calls to
	`LocalSettings.php` and `MySQLHandler.php` from `\` to `/`.
	* The _file upload_ pages don't work if you don't give world-write
	permissions to the `bricks/upload-1/uploads` folder.
* In some pages, the __Home__ breadcrumb points to `index.html` instead of
`index.php`.
