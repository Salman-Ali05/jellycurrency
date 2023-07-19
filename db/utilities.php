<?php

// database credentials
define('DBHOST', 'localhost');
define('DBIDT', 'root');
define('DBPASS', '');
define('DBNAME', 'jellyfish_database');

// connect to the database
function connectBdd()
{
    try {
        $mysql = new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME . ';', DBIDT, DBPASS);
        $mysql->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $mysql->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $mysql;
    } catch (Exception $e) {
        echo "Connexion impossible : " . $e->getMessage();
        die();
    }
}

// hash the password using crypt method
function hashPassword($post)
{
    $salt = '$2y$11$' . substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);
    return crypt($post, $salt);
}

// check the password for more security
function verifyPassword($post, $hashPassword)
{
    return crypt($post, $hashPassword) == $hashPassword;
}

// $sql = query like "SELECT ? FROM users", $data = data passed as second arg that fills the '?' to counter SQL injections
// used in logUser()
function selectOne($sql, $data)
{
    $mysql = connectBdd();
    $result = $mysql->prepare($sql);
    $count = 1;
    foreach ($data as $param) {
        $result->bindValue($count, $param);
        $count++;
    }
    $result->execute();
    $result = $result->fetchAll();
    if (count($result) == 0) {
        $result = false;
    } else {
        $result = $result[0];
    }
    $mysql = null;
    return $result;
}

// $sql = query like "SELECT ? FROM users", $data = data passed as second arg that fills the '?' to counter SQL injections
// will execute queries like "SELECT"
function selectOneAll($sql, $data)
{
    $mysql = connectBdd();
    $result = $mysql->prepare($sql);
    $count = 1;
    foreach ($data as $param) {
        $result->bindValue($count, $param);
        $count++;
    }
    $result->execute();
    $result = $result->fetchAll();
    $mysql = null;
    return $result;
}


// $sql = query like "SELECT ? FROM users", $data = data passed as second arg that fills the '?' to counter SQL injections
// will execute queries like "UPDATE", or "DELETE"
function execute($sql, $data)
{
    try {
        $mysql = connectBdd();
        $result = $mysql->prepare($sql);
        $result->execute($data);
        $mysql = null;
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
        die();
    }
}


// logs the user if the credentials are correct
function logUser($post)
{
    $table = "users";
    $login = selectOne("SELECT * FROM $table WHERE name = ? OR email = ?", [$post['email'], $post['email']]);
    if ($login === false) {
        return "<div class='errors'>Your name or email isn't correct :/</div>";
    } else {
        if ($login !== false && verifyPassword($post['password'], $login['password']) == true) {
            session_start();
            $_SESSION['id'] = $login['id'];
            $_SESSION['name'] = $login['name'];
            $_SESSION['email'] = $login['email'];
            if ($login['pic'] != NULL) {
                $_SESSION['pic'] = $login['pic'];
            } else {
                $_SESSION['pic'] = "";
            }
            return "Connected";
        } else {
            return "<div class='errors'>The password isn't correct :/</div>";
        }
    }
}

// API SIDE
// get the current value of the currency
function makeAPiRequest($currency)
{
    $url = "https://rest.coinapi.io/v1/exchangerate/BTC/" . $currency;

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-CoinAPI-Key: D8E98BD2-920A-40F7-BAA3-6892C418A306',
        'X-RateLimit-Used: 1000000',
        'X-RateLimit-Limit: 1000000',
        'X-RateLimit-Remaining: 4000',
        'X-RateLimit-Request-Cost: 1',
        'X-RateLimit-Reset: 2023-05-05T12:00:00.0000001Z',
        'X-RateLimit-Quota-Overage: ENABLED',
        'X-RateLimit-Quota-Allocated: 10000',
        'X-RateLimit-Quota-Remaining: 5000'
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        echo "Error: $error";
    } else {
        $responseData = json_decode($response, true);
        return $responseData;
    }

    curl_close($curl);
}

// retreive all the currencies name
function getAllCurrency()
{
    $url = "https://rest.coinapi.io/v1/assets/icons/32";

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-CoinAPI-Key: D8E98BD2-920A-40F7-BAA3-6892C418A306',
        'X-RateLimit-Used: 1000000',
        'X-RateLimit-Limit: 1000000',
        'X-RateLimit-Remaining: 4000',
        'X-RateLimit-Request-Cost: 1',
        'X-RateLimit-Reset: 2023-05-05T12:00:00.0000001Z',
        'X-RateLimit-Quota-Overage: ENABLED',
        'X-RateLimit-Quota-Allocated: 10000',
        'X-RateLimit-Quota-Remaining: 5000'
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        echo "Error: $error";
    } else {
        $responseData = json_decode($response, true);
        return $responseData;
    }

    curl_close($curl);
}


// get all the rates beetween USD and BTC from a period
// unsed, I wanted to make some graphs on the dashboard, but the number of API queries is limited, so the display would be awful
function getHistoryValues()
{
    $url = "https://rest.coinapi.io/v1/exchangerate/BTC/USD/history?period_id=1HRS&time_start=2023-07-01T00:00:00&time_end=2023-07-15T00:00:00&limit=100";

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-CoinAPI-Key: D8E98BD2-920A-40F7-BAA3-6892C418A306',
        'X-RateLimit-Used: 1000000',
        'X-RateLimit-Limit: 1000000',
        'X-RateLimit-Remaining: 4000',
        'X-RateLimit-Request-Cost: 1',
        'X-RateLimit-Reset: 2023-05-05T12:00:00.0000001Z',
        'X-RateLimit-Quota-Overage: ENABLED',
        'X-RateLimit-Quota-Allocated: 10000',
        'X-RateLimit-Quota-Remaining: 5000'
    ]);

    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        echo "Error: $error";
    } else {
        $responseData = json_decode($response, true);
        return $responseData;
    }

    curl_close($curl);
}
