<?php

// so basically, I retreive all the alert of the current user, and json_encode them, so I can read them in JS, it is an Ajax method

session_start();
require_once('../db/utilities.php');

$data = [$_SESSION['id']];
$alerts = selectOneAll("SELECT * FROM alerts WHERE user_id = ? ORDER BY updated_at DESC", $data);
echo json_encode($alerts);