<?php

session_start();
require_once('../db/utilities.php');

$data = [$_SESSION['id']];
$alerts = selectOneAll("SELECT * FROM alerts WHERE user_id = ? ORDER BY updated_at DESC", $data);
echo json_encode($alerts);