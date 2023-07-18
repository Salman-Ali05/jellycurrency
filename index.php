<?php

session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ./pages/user/user.php');
}

include('./db/utilities.php');

$data = [$_SESSION['id']];
$allAlerts = selectOneAll("SELECT * FROM alerts WHERE user_id = ? ORDER BY updated_at DESC", $data);

// $res = getHistoryValues();
// $hightsOnly = [];
// $i = 0;
// foreach ($res as $r) {
//     $hightsOnly[$i] = $res[$i]['rate_high'];
//     $i++;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/index.css" />
    <title>JellyCurrency - Tech Test</title>
</head>

<body onresize="resize()">

    <div class="container">

        <nav class="menu">

            <img src="./public/icons/jellyfish-logo.png" class="logo">
            <br>
            <div class="menuItem">
                <a href="index.php"><img src="./public/icons/dashboard.png" class="icons">Dashboard</a>
            </div>
            <div class="menuItem">
                <a href="./pages/user/profile.php"><img src="./public/icons/profile.png" class="icons">Profile</a>
            </div>
            <div class="menuItem">
                <a href="./pages/alert/alert.php"><img src="./public/icons/bell.png" class="icons">My Alerts</a>
            </div>
            <div class="menuItem">

                <form action="./pages/user/user.php" method="POST">
                    <button type="submit" name="state" value="disconnect" class="discon"><img src="./public/icons/logout.png" class="icons">Disconnect</button>
                </form>
            </div>
        </nav>

        <div class="mainInfo">
            <h1>
                Welcome to JellyCurrency !
            </h1>

            <h1>My alerts :</h1>

            <div class="alerts">
                <?php
                $alertCount = 0; // Variable de compteur d'alertes affichées

                if (count($allAlerts) > 0) {
                    foreach ($allAlerts as $alert) {
                        if ($alertCount < 2) {
                            $apiData = makeAPiRequest($alert['currency']);
                            $asset_id_base = $apiData['asset_id_base'];
                            $asset_id_quote = $apiData['asset_id_quote'];
                            $rate = $apiData['rate'];
                ?>
                            <div class="alert">
                                <a href="./pages/alert/alert.php">
                                    <p><?= "Your alert for : $asset_id_quote is currently valuing $rate" ?></p>
                                    <p><?= "You will be notified if the $asset_id_quote is $alert[type] $alert[limit]" ?></p>
                                </a>
                            </div>
                        <?php
                            $alertCount++;
                        } else { ?>
                            <a href="./pages/alert/alert.php" style="align-self:center; text-decoration:none; color:black">
                                <p>more ...</p>
                            </a>
                    <?php
                        }
                    }
                } else {
                    ?>
                    <div class="alert">
                        <a href="./pages/alert/alert.php">
                            <p><em>No alerts found :/ Click here to add new!</em></p>
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="graphs">
                <h1>Some graph :</h1>
            </div>

        </div>

    </div>

    <script src="./public/js/main.js"></script>

</body>

</html>