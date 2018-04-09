<?php
include_once('api.php');
include_once('PHPmailer.php');
include_once('capcha.php');

$data = clearDataBeforeInsert($_POST);
$checked = submit();
print_r($checked);

if(!$checked){
   throw new Exception('Вы не прошли проверку');
}

$id = authorization(
    $data['email'],
    "SELECT email, id FROM users ",
    "SELECT id FROM users WHERE email = :email");
$prepare = pdoQuery(
    "INSERT INTO users (name, email,phone) 
    VALUES (:name, :email,:phone )", ['name' => $data['name'],
    'email' => $data['email'], 'phone' => $data['phone']]);

$arrID = checkId(
    $id,
    "SELECT id FROM users WHERE email = :email",
    ['email' => $data['email']]);
$arrID ? $id = $arrID : null;
$arrID ? $newId = $arrID : null;

$prepare = pdoQuery(
    "INSERT INTO orders (user_id, street, home,part,aprt,floor,comment,payment,callback) 
    VALUES (:user_id, :street, :home, :part, :aprt, :floor, :comment, :payment, :callback)",
    ['user_id' => $id, 'street' => $data['street'], 'home' => $data['home'], 'part' =>
        $data['part'], 'aprt' => $data['aprt'], 'floor' => $data['floor'], 'comment' =>
        $data['comment'], 'payment' => $data['payment'], 'callback' => $data['callback']]);
$prepare = pdoQuery(
    "SELECT id , street , home , part , aprt , floor   
    FROM orders WHERE user_id = :user_id ",
    ['user_id' => $id]);
$dataClient = $prepare->fetchAll(PDO::FETCH_ASSOC);
$info = $dataClient[count($dataClient) - 1];

$prepare = pdoQuery("SELECT user_id FROM orders WHERE user_id = $id");
$orders = $prepare->fetchAll(PDO::FETCH_ASSOC);
$numOrders = count($orders);



isset($newId) ? $str = 'Спасибо это Ваш первый заказ' : $str = "Спасибо, это уже $numOrders заказ !!!";

$subject = "order";
$message = "
  <p>Заказ номер: {$info['id']} </p>
  <p>Ваш заказ будет доставлен по адресу: улица {$info['street']} дом {$info['home']} корпус  {$info['part']} квартира {$info['aprt']} этаж  {$info['floor']}  </p>
  <p>DarkBeefBurger за 500 рублей, 1 шт</p> 
  <p>$str</p>";
echo $message;
mailer($data['email'], $subject, $message);
echo "<a href=\"/\">Назад</a>";


file_put_contents('./orders.txt', $message, FILE_APPEND);

?>

<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>





