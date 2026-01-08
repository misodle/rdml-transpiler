@ECHO OFF
set /p ProgramName= Enter Program to compile (name not name.txt)?
set /p Html= Compile HTML (Yes/No)?
call:toLower ProgramName

@ECHO ON
js z_compile1.js %ProgramName% < %ProgramName%.txt > %ProgramName%.php 
if /i (%Html%)==(yes) (js z_compile2.js %ProgramName% < %ProgramName%.txt > %ProgramName%.html)
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