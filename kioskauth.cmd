@echo off

rem Define the kioskauth directory path
set KA=%~dp0

rem Add the PHP directory into the system path
set PATH=%PATH%;%KA%php

rem Start the web server
start /b /d apache\bin httpd.exe

rem Open the default page in the web browser
start http://localhost:9000/
