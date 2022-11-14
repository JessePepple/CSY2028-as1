<?php
require_once('head.php');

session_unset();
session_destroy();

header("Location: login.php");

$db = null;