<?php

include('../../db/utilities.php');

if ($_POST) {
    $state = $_POST['state'];
    switch ($state) {
        case 'log': {
                if (logUser($_POST) == "Connected") {
                    header("location:../../index.php");
                } else {
                    echo logUser($_POST);
                }
                break;
            }
        case 'sub': {
                $data = [
                    $_POST['name'],
                    $_POST['email'],
                    hashPassword($_POST['password'])
                ];
                if (execute("INSERT INTO users (name, email, password) VALUES(?,?,?)", $data)) {
                    logUser($_POST);
                    header("location:../../index.php");
                } else {
                    echo execute("INSERT INTO users (name, email, password) VALUES(?,?,?)", $data);
                }
                break;
            }
        case 'update': {
                session_start();
                if ($_FILES['pic']['name'] != "") {
                    $file = $_FILES['pic']['name'];
                    $path = pathinfo($file);
                    $filename = $path['filename'];
                    $ext = $path['extension'];
                    $path_filename_ext = $filename . "." . $ext;
                    move_uploaded_file($_FILES['pic']['tmp_name'], "../../public/images/" . $_FILES['pic']['name']);
                    $data = [
                        $_POST['name'],
                        $_POST['email'],
                        $path_filename_ext,
                        $_SESSION['id']
                    ];
                    if (execute("UPDATE users SET name = ?, email = ?, pic = ? WHERE id = ?", $data)) {
                        echo "Successfully updated";
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['name'] = $_POST['name'];
                        $_SESSION['pic'] = $_POST['pic'];
                        session_destroy();
                        logUser($_POST);
                        header("location:profile.php");
                    } else {
                        echo "Something went wrong !" . execute("UPDATE users SET name = ?, email = ?, pic = ? WHERE id = ?", $data);
                        header("location:profile.php");
                    }
                } else {
                    $data = [
                        $_POST['name'],
                        $_POST['email'],
                        $_SESSION['id']
                    ];

                    if (execute("UPDATE users SET name = ?, email = ? WHERE id = ?", $data)) {
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['name'] = $_POST['name'];
                        header("location:profile.php");
                        echo "Successfully updated";
                    } else {
                        header("location:profile.php");
                        echo "Something went wrong !" . execute("UPDATE users SET name = ?, email = ? WHERE id = ?", $data);
                    }
                }
                break;
            }
        case 'delete': {
                session_start();
                $data = [
                    $_SESSION['id']
                ];
                if (execute("DELETE FROM users WHERE id = ?", $data) && execute("DELETE FROM alerts WHERE user_id = ?", $data)) {
                    header("location:user.php");
                } else {
                    header("location:profile.php");
                    echo execute("DELETE FROM users WHERE id = ?", $data) . execute("DELETE FROM alerts WHERE user_id = ?", $data);
                }
                break;
            }
        case 'disconnect': {
                session_unset();
                session_destroy();
                header("location:user.php");
                break;
            }
        default: {
                return null;
            }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/user.css" />
    <title>JellyCurrency - Tech Test</title>
</head>

<body>
    <div class="container">
        <div class="leftPart">
            <img src="../../public/icons/jellyfish-logo.svg">
            <p>Welcome to JellyCurrency !</p>
            <p>Currency ? Our priority.</p>
        </div>

        <div class="rightPartLogSignIn">
            <h1 class="formTitle">Log In</h1>
            <form action="user.php" method="POST" class="forms">
                <input type="text" name="email" class="inputs" placeholder="Name or Email" required>
                <input type="password" name="password" class="inputs" placeholder="Password" required>
                <button type="submit" value="log" name="state" class="btn">Login</button>
            </form>
            <h1 class="formTitle">Sign In</h1>
            <form action="user.php" method="POST" class="forms">
                <input type="email" name="email" class="inputs" placeholder="Email" required>
                <input type="text" name="name" class="inputs" placeholder="Name" required>
                <input type="password" name="password" class="inputs" placeholder="Password" required>
                <button type="submit" value="sub" name="state" class="btn">Submit</button>
            </form>
        </div>

    </div>
</body>

</html>