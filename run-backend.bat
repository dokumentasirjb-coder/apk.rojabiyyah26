@echo off
title Backend Laravel - Administrasi Rojabiyyah 2026
echo Menjalankan Backend Laravel pada http://127.0.0.1:8000 (Multi-Worker Enabled)...
set "PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64;C:\composer;C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin;%PATH%"
set PHP_CLI_SERVER_WORKERS=4
cd backend
php artisan serve --port=8000 --no-reload
pause
