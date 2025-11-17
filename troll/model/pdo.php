<?php
function pdo_get_connection() {
    $dburl = "mysql:host=localhost;dbname=techbuc;charset=utf8";
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dburl, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}

function pdo_execute($sql) {
    $args = array_slice(func_get_args(), 1);
    try {
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
    } catch(PDOException $e) {
        throw $e;
    } finally {
        unset($conn);
    }
}

function pdo_query($sql) {
    $args = array_slice(func_get_args(), 1);
    try {
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        $rows = $stmt->fetchAll();
        return $rows;
    } catch(PDOException $e) {
        throw $e;
    } finally {
        unset($conn);
    }
}

function pdo_query_one($sql) {
    $args = array_slice(func_get_args(), 1);
    try {
        $conn = pdo_get_connection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($args);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } catch(PDOException $e) {
        throw $e;
    } finally {
        unset($conn);
    }
}