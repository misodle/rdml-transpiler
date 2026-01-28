@echo off
setlocal enabledelayedexpansion

set "ONLY="
set "UPDATE=0"

:parse
if "%~1"=="" goto parsed
if /i "%~1"=="--only" (
  set "ONLY=%~2"
  shift
  shift
  goto parse
)
if /i "%~1"=="--update" (
  set "UPDATE=1"
  shift
  goto parse
)
shift
goto parse

:parsed
set "QJS=.\private\quickjs\qjs.exe"
set "RDML=.\private\rdml"
set "CASES=.\private\tests\cases"

if not exist "%QJS%" (
  echo Missing: %QJS%
  exit /b 2
)
if not exist "%CASES%" (
  echo Missing: %CASES%
  exit /b 2
)

set /a CASE_PASS=0
set /a CASE_FAIL=0
set /a CASE_PASS_MISSING=0

for /d %%D in ("%CASES%\*") do (
  set "CASE=%%~nxD"

  if defined ONLY (
    if /i not "!CASE!"=="%ONLY%" (
      REM skip
    ) else (
      call :runCase "%%D" "!CASE!"
    )
  ) else (
    call :runCase "%%D" "!CASE!"
  )
)

echo Summary: pass=%CASE_PASS% pass+missing=%CASE_PASS_MISSING% fail=%CASE_FAIL%
if %CASE_FAIL% GTR 0 exit /b 1
exit /b 0

REM -------------------------------------------------------
REM runCase updates global counters CASE_PASS/CASE_FAIL/CASE_PASS_MISSING
REM -------------------------------------------------------
:runCase
set "CASE_DIR=%~1"
set "PROG=%~2"
set "IN_FILE=%CASE_DIR%\%PROG%.txt"
set "ACT_DIR=%CASE_DIR%\actual"
set "EXP_DIR=%CASE_DIR%\expected"

echo === %PROG% ===

if not exist "%IN_FILE%" (
  echo [FAIL] %PROG% - missing input "%IN_FILE%"
  set /a CASE_FAIL+=1
  echo.
  exit /b 0
)

if not exist "%ACT_DIR%" mkdir "%ACT_DIR%" >nul

REM --- Compile all outputs ---
"%QJS%" --std "%RDML%\z_compileHTML.js" "%PROG%" < "%IN_FILE%" > "%ACT_DIR%\%PROG%.html"
if errorlevel 1 (
  echo [FAIL] %PROG% compile HTML
  set /a CASE_FAIL+=1
  echo.
  exit /b 0
)

"%QJS%" --std "%RDML%\z_compilePHP.js" "%PROG%" < "%IN_FILE%" > "%ACT_DIR%\%PROG%.php"
if errorlevel 1 (
  echo [FAIL] %PROG% compile PHP
  set /a CASE_FAIL+=1
  echo.
  exit /b 0
)

"%QJS%" --std "%RDML%\z_dumpAST.js" "%PROG%" < "%IN_FILE%" > "%ACT_DIR%\%PROG%.ast"
if errorlevel 1 (
  echo [FAIL] %PROG% dump AST
  set /a CASE_FAIL+=1
  echo.
  exit /b 0
)

REM --- Optional update: bless actual -> expected ---
if "%UPDATE%"=="1" (
  if not exist "%EXP_DIR%" mkdir "%EXP_DIR%" >nul
  copy /Y "%ACT_DIR%\%PROG%.html" "%EXP_DIR%\%PROG%.html" >nul
  copy /Y "%ACT_DIR%\%PROG%.php"  "%EXP_DIR%\%PROG%.php"  >nul
  copy /Y "%ACT_DIR%\%PROG%.ast"  "%EXP_DIR%\%PROG%.ast"  >nul
  echo   [UPDATE] expected overwritten
)

REM --- Compare expected vs actual ---
set "ANY_FAIL=0"
set "ANY_MISSING=0"

call :cmpFile "%PROG%" "%PROG%.html" "%EXP_DIR%\%PROG%.html" "%ACT_DIR%\%PROG%.html"
if errorlevel 2 set "ANY_FAIL=1"
if errorlevel 1 set "ANY_MISSING=1"

call :cmpFile "%PROG%" "%PROG%.php"  "%EXP_DIR%\%PROG%.php"  "%ACT_DIR%\%PROG%.php"
if errorlevel 2 set "ANY_FAIL=1"
if errorlevel 1 set "ANY_MISSING=1"

call :cmpFile "%PROG%" "%PROG%.ast"  "%EXP_DIR%\%PROG%.ast"  "%ACT_DIR%\%PROG%.ast"
if errorlevel 2 set "ANY_FAIL=1"
if errorlevel 1 set "ANY_MISSING=1"

if "%ANY_FAIL%"=="1" (
  echo [FAIL] %PROG%
  set /a CASE_FAIL+=1
) else (
  if "%ANY_MISSING%"=="1" (
    echo [PASS+MISSING] %PROG%
    set /a CASE_PASS_MISSING+=1
  ) else (
    echo [PASS] %PROG%
    set /a CASE_PASS+=1
  )
)

echo.
exit /b 0

REM -------------------------------------------------------
REM cmpFile returns:
REM   exit /b 0 => PASS
REM   exit /b 1 => MISSING expected
REM   exit /b 2 => FAIL (differs or missing actual)
REM -------------------------------------------------------
:cmpFile
set "CASE=%~1"
set "FNAME=%~2"
set "EXP=%~3"
set "ACT=%~4"

if not exist "%EXP%" (
  echo   [MISSING] %CASE% :: %FNAME% no expected
  exit /b 1
)

if not exist "%ACT%" (
  echo   [FAIL] %CASE% :: %FNAME% no actual produced
  exit /b 2
)

fc /b "%EXP%" "%ACT%" >nul
if errorlevel 1 (
  echo   [FAIL] %CASE% :: %FNAME% differs
  exit /b 2
)

echo   [PASS] %CASE% :: %FNAME%
exit /b 0