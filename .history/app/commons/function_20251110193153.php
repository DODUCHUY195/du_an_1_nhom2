<?php

function connectDB(){
    $host = DB_HOST;
    $port= DB_PORT;
    $dbname = DB_NAME;

    try{
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname",DB_USERNAME,DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        return $conn;
    }catch(PDOException $e){
        echo("Connection Failed:".$e->getMessage());
    }
}

function uploadfile($file, $uploadDir)
{
    if ($file['error'] !== UPLOAD_ERR_OK) return false;

    $fileName = basename($file['name']);
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowed)) return false;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $newFileName = uniqid() . '.' . $ext;
    $dest = rtrim($uploadDir, '/') . '/' . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $newFileName;
    }
    return false;
}

