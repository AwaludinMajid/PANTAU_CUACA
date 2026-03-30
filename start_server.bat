@echo off
echo Starting Pantau Cuaca Server...
cd /d "C:\laragon\www\PANTAU_CUACA"
"C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" -S 127.0.0.1:8000 -t public
pause