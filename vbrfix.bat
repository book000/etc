@echo off
echo.
echo VBR TIME FIXER
echo.

@if "%~f1"=="" (
  echo "plz file path arg"
  exit /B
)

set FILE=%~n1%~x1
set TOFILE=%~n1.bak%~x1
set BAKFILE=[BAK]%FILE%

echo cd /d "%~d1%~p1"
cd /d "%~d1%~p1"

echo lame -b 320 "%FILE%" "%TOFILE%"
lame -b 320 "%FILE%" "%TOFILE%"

echo ren "%FILE%" "%BAKFILE%"
ren "%FILE%" "%BAKFILE%"

echo ren "%TOFILE%" "%FILE%"
ren "%TOFILE%" "%FILE%"