<?php
require_once __DIR__ . '/functions.php';

session_start();
session_destroy();
    clear_remember_me();

header('Location: login.php');
exit;
