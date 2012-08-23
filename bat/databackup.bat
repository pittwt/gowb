set "Ymd=%date:~,4%%date:~5,2%%date:~8,2%" 
set "Dbname=ecshop"
set "Newdbfile=%Dbname%%Ymd%.sql"
set "Bakfolder=D:\wamp\www\gowb\databackup\"
if NOT exist "%Bakfolder%%ymd%/*.*" 
md "%Bakfolder%%ymd%" 
"D:\wamp\bin\mysql\mysql5.5.24\bin\mysqldump.exe" --opt -Q %Dbname% -uroot -proot>%Bakfolder%%Ymd%\%Newdbfile%
"D:\Program Files\HaoZip\HaoZipC.exe" a -tzip %Bakfolder%%Ymd%\%Newdbfile%.zip %Bakfolder%%Ymd% 
del /f /s /q %Bakfolder%%Newdbfile% 
D:\wamp\bin\php\php5.3.13\php.exe -q D:/wamp/www/gowb/databackup/checkbak.php
