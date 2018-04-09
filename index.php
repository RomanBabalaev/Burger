<?php
require './app/api.php';
require './vendor/autoload.php';
require './app/capcha.php';
// начинаем работать с сессией
session_start();

$appDir = realpath(__DIR__ . '/');

// стартовая страница
if ($_SERVER['REQUEST_URI'] == "/") {
    twig();
    return 0;
}

// добавляем в базу
if (!empty($_POST) && $_SERVER['REQUEST_URI'] == "/order/add") {
    require_once($appDir . DIRECTORY_SEPARATOR . './app/app.php');
    return 0;
}

// просмотр пользователей (административная панель)
if ($_SERVER['REQUEST_URI'] == "/admin/users") {
    $prepare = pdoQuery("SELECT id FROM users ");
    $users = $prepare->fetchAll(PDO::FETCH_ASSOC);
    $numClients = count($users);
    echo "Всего клиентов: " . $numClients . "<br>";
    return 0;
}

// просмотр заказов (административная панель)
if ($_SERVER['REQUEST_URI'] == "/admin/orders") {
    $prepare = pdoQuery("SELECT id FROM orders");
    $orders = $prepare->fetchAll(PDO::FETCH_ASSOC);
    $allBurgers = count($orders);
    echo "Всего заказов:" . $allBurgers . "<br>";
    return 0;
}

// такой страницы нет
header("HTTP/1.0 404 Not Found");
