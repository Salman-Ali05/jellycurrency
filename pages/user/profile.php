<?php

session_start();
include('../../db/utilities.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/profile.css">
    <title>JellyCurrency - Tech Test</title>
</head>

<body>
    <div class="container">
        <nav class="menu">

            <img src="../../public/icons/jellyfish-logo.png" class="logo">
            <br>
            <div class="menuItem">
                <a href="../../index.php"><img src="../../public/icons/dashboard.png" class="icons">Dashboard</a>
            </div>
            <div class="menuItem">
                <a href="profile.php"><img src="../../public/icons/profile.png" class="icons">Profile</a>
            </div>
            <div class="menuItem">
                <a href="../alert/alert.php"><img src="../../public/icons/bell.png" class="icons">My Alerts</a>
            </div>
            <div class="menuItem">

                <form action="user.php" method="POST">
                    <button type="submit" name="state" value="disconnect" class="discon"><img src="../../public/icons/logout.png" class="icons">Disconnect</button>
                </form>
            </div>
        </nav>

        <div class="mainInfo">
            <h1>Welcome back <?= $_SESSION['name']; ?> !</h1>
            <?php
            if ($_SESSION['pic'] != "") { ?>
                <img src="../../public/images/<?= $_SESSION['pic'] ?>" width="20%">
            <?php
            } else { ?>
                <img src="../../public/images/default_user.png" width="20%">
            <?php
            }
            ?>

            <form action="user.php" method="POST" enctype="multipart/form-data">
                <input type="email" name="email" class="inputs" value="<?= $_SESSION['email'] ?>" placeholder="email">
                <input type="text" name="name" class="inputs" value="<?= $_SESSION['name'] ?>" placeholder="name">
                <input type="file" name="pic" class="inputs">
                <input type="password" name="password" class="inputs" placeholder="password">
                <br>
                <button type="submit" value="update" name="state" class="btn update">Update</button>
            </form>
            <form action="user.php" method="POST">
                <button type="submit" value="delete" name="state" class="btn delete">Delete my account</button>
            </form>
        </div>
    </div>

</body>

</html>