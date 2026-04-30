@echo off
echo Importando schema de base de datos...
c:\xampp\mysql\bin\mysql.exe -u root -p < database/schema.sql
echo.
echo Si la importacion fue exitosa, no veras errores arriba.
echo Si hay errores de foreign key, revisa que la base de datos este limpia.
pause