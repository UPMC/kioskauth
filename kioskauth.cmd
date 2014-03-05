@echo off

rem Define the tx directory path
set TX=%~dp0

rem Add the PHP directory into the system path
set PATH=%PATH%;%TX%php

rem Start the web server
start /b /d apache\bin httpd.exe

rem Open the default page in the web browser
rem start http://localhost:8080/
