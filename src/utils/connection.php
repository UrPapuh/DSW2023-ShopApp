<?php
  $host = 'localhost';
  $username = 'root';
  $passwd = '';
  $dbname = 'shop';
  @$link = new mysqli($host, $username, $passwd, $dbname);
  $error = $link->connect_error;
