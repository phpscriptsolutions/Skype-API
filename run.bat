@ECHO OFF
:start
  php index.php
  echo Restarting client
  timeout 10
goto start