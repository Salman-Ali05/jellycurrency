<?php

session_start();
include('../../db/utilities.php');

if ($_POST) {
    $state = $_POST['state'];
    switch ($state) {
        case 'create': {
                $data = [
                    $_SESSION['id'],
                    $_POST['currency'],
                    $_POST['limit'],
                    $_POST['type']
                ];
                $insert = execute("INSERT INTO alerts (user_id, currency, `limit`, `type`) VALUES (?,?,?,?)", $data);
                if ($insert != 0) {
                    echo "data insert";
                }
                break;
            }
        case 'update': {
                $data = [
                    $_POST['currency'],
                    $_POST['limit'],
                    $_POST['type'],
                    $_POST['id'],
                    $_SESSION['id']
                ];
                if (execute("UPDATE alerts SET currency = ?, `limit` = ?, `type` = ? WHERE id = ? AND user_id = ?", $data)) {
                    echo "data updated";
                } else {
                    echo "data failed";
                }
                break;
            }
        case 'delete': {
                $data = [
                    $_POST['id']
                ];
                if (execute("DELETE FROM alerts WHERE id = ?", $data)) {
                    echo "Alerts deleted";
                } else {
                    echo "Alerts not deleted" . execute("DELETE FROM alerts WHERE id = ?", $data);
                }
                break;
            }
        default: {
                return null;
            }
    }
}
$data = [$_SESSION['id']];
$allAlerts = selectOneAll("SELECT * FROM alerts WHERE user_id = ?", $data);

$allCurency = getAllCurrency();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/alert.css" />
    <title>JellyCurrency - Tech Test</title>
</head>

<body>

    <div id="overlay" class="overlay">
        <div class="popup" id="popup">
            <img src="../../public/icons/close.png" class="iconClose" onclick="hidePopup()">
            <h1>Create a new alert</h1>
            <form action="alert.php" method="POST">
                <select name="currency" class="drops">
                    <?php

                    foreach ($allCurency as $currency) { ?>
                        <option value="<?= $currency['asset_id'] ?>"><?= $currency['asset_id'] ?></option>
                    <?php

                    }

                    ?>
                </select>
                <select name="type" class="drops">
                    <option value="below">Below</option>
                    <option value="above">Above</option>
                </select>
                <input type="number" name="limit" class="inputs" required placeholder="Limit">
                <br>
                <button type="submit" value="create" name="state" class="btn">Create my alert</button>
            </form>
        </div>
    </div>
    <div class="container">

        <nav class="menu">

            <img src="../../public/icons/jellyfish-logo.png" class="logo">
            <br>
            <div class="menuItem">
                <a href="../../index.php"><img src="../../public/icons/dashboard.png" class="icons">Dashboard</a>
            </div>
            <div class="menuItem">
                <a href="../user/profile.php"><img src="../../public/icons/profile.png" class="icons">Profile</a>
            </div>
            <div class="menuItem">
                <a href="alert.php"><img src="../../public/icons/bell.png" class="icons">My Alerts</a>
            </div>
            <div class="menuItem">

                <form action="../user/user.php" method="POST">
                    <button type="submit" name="state" value="disconnect" class="discon"><img src="../../public/icons/logout.png" class="icons">Disconnect</button>
                </form>
            </div>
        </nav>

        <div class="mainInfo">

            <div class="alertContainer">

                <h1>My Alerts</h1>

                <div class="searchAdd">

                    <img src="../../public/icons/new.png" class="icon" onclick="displayPopup()">
                    <input type="search" class="inputsSearch" placeholder="Search by name">

                </div>

                <div class="alerts">

                    <table class="tableAlerts">
                        <th>Currency</th>
                        <th>Type</th>
                        <th>Limit</th>
                        <th>Actions</th>
                        <?php
                        if (count($allAlerts) > 0) {
                            foreach ($allAlerts as $alert) { ?>
                                <tr>
                                    <div class="alert">
                                        <td>
                                            <form action="alert.php" method="POST">
                                                <select name="currency" class="drops">
                                                    <?php

                                                    foreach ($allCurency as $currency) {
                                                        $selected = "";
                                                        $currency['asset_id'] == $alert['currency'] && $selected = "selected"
                                                    ?>
                                                        <option value="<?= $currency['asset_id'] ?>" <?= $selected ?>><?= $currency['asset_id'] ?></option>

                                                    <?php

                                                    }

                                                    ?>
                                                </select>
                                        </td>
                                        <td>
                                            <select name="type" class="drops">
                                                <?php
                                                if ($alert['type'] == "below") {
                                                    echo "<option selected value='below'>Below";
                                                    echo "<option value='above'>Above</option>";
                                                } else {
                                                    echo "<option value='above' selected>Above</option>";
                                                    echo "<option value='below'>Below";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="limit" value="<?= $alert['limit'] ?>" class="inputs" required>
                                        </td>
                                        <input type="hidden" value="<?= $alert['id'] ?>" name="id" class="inputs">
                                        <td>
                                            <button type="submit" value="update" name="state" class="btn">Update my alert</button>
                                            </form>
                                            <form action="alert.php" method="POST">
                                                <input type="hidden" value="<?= $alert['id'] ?>" name="id" class="inputs" required>
                                                <button type="submit" value="delete" name="state" class="btn delete">Delete my alert</button>
                                            </form>
                                        </td>

                                    </div>
                                </tr>
                        <?php
                            }
                        } ?>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script src="../../public/js/main.js"></script>

</body>

</html>