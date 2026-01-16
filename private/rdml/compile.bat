@ECHO OFF

set /p ProgramName= Enter Program to compile (name not name.txt)?
set /p Html= Compile HTML (Yes/No)?
set /p DumpAST= Dump Abstract Syntax Tree (Yes/No)?
call:toLower ProgramName

@ECHO ON
"%~dp0..\spidermonkey\js.exe" "%~dp0z_compilePHP.js" %ProgramName% < %ProgramName%.txt > %ProgramName%.php 

@ECHO OFF
if /i (%Html%)==(y) set "Html=yes"

@ECHO ON
if /i (%Html%)==(yes) ("%~dp0..\spidermonkey\js.exe" "%~dp0z_compileHTML.js" %ProgramName% < %ProgramName%.txt > %ProgramName%.html)

@ECHO OFF
if /i (%DumpAST%)==(y) set "DumpAST=yes"

@ECHO ON
if /i (%DumpAST%)==(yes) ("%~dp0..\spidermonkey\js.exe" "%~dp0z_dumpAST.js" %ProgramName% < %ProgramName%.txt > %ProgramName%.ast)

@ECHO OFF
echo.
pause "press enter"
goto:eof

:toLower str -- converts uppercase character to lowercase
::           -- str [in,out] - valref of string variable to be converted
:$created 20060101 :$changed 20080219 :$categories StringManipulation
:$source http://www.dostips.com
if not defined %~1 EXIT /b
for %%a in ("A=a" "B=b" "C=c" "D=d" "E=e" "F=f" "G=g" "H=h" "I=i"
            "J=j" "K=k" "L=l" "M=m" "N=n" "O=o" "P=p" "Q=q" "R=r"
            "S=s" "T=t" "U=u" "V=v" "W=w" "X=x" "Y=y" "Z=z" "Ä=ä"
            "Ö=ö" "Ü=ü") do (
    call set %~1=%%%~1:%%~a%%
)
EXIT /b