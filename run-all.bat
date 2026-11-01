@echo off
title Starter - Administrasi Rojabiyyah 2026
echo Menjalankan Backend dan Frontend...
start "Backend Laravel API" cmd /k "run-backend.bat"
start "Frontend Vue 3 SPA" cmd /k "run-frontend.bat"
echo.
echo ========================================================
echo Backend berjalan di:  http://localhost:8000
echo Frontend berjalan di: http://localhost:5173
echo ========================================================

