<?php

function newKey() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $key = "";
    for ($i = 0; $i < 20; $i++) {
        $key .= $characters[rand(0, $charactersLength - 1)];
    }
    return time() . "-" . $key;
}

function userKeyValidation($key, $conn) {
    $sql = "select * from usuario where k = '" . $key . "';";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

function userValidation($user, $password, $conn) {
    $sql = "select * from usuario where id = '" . $user . "' and senha = '" . $password . "';";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $key = newKey();
        $sql = "update usuario set k = '$key' where id = '" . $user . "' and senha = '" . $password . "';";
        $conn->query($sql);
        return $key;
    }
    
    return "";
}
