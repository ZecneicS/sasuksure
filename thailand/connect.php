<?php

$info = array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'sasuksure@111165',
    'dbname' => 'sasuksure'
);

$connect = mysqli_connect($info['host'], $info['user'], $info['password'], $info['dbname']) or die('Error connection database!');
mysqli_set_charset($connect, 'utf8');