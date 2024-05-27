<?php
include_once "PDO.php";

function GetOneUserFromId($id)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM user WHERE id = :id");
  $response->execute([':id' => $id]);
  return $response->fetch();
}

function GetAllUsers()
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM user ORDER BY nickname ASC");
  $response->execute();
  return $response->fetchAll();
}

function GetUserIdFromUserAndPassword($username, $password)
{
  global $PDO;

  $sqlQuery = 'SELECT * FROM user WHERE nickname = :nickname';
  $pdo_prepare = $PDO->prepare($sqlQuery);
  $pdo_prepare->execute(['nickname' => $username]);
  $user = $pdo_prepare->fetch();

  if ($user && $password === $user['password']) {
    $_SESSION['userId'] = $user['id'];
    return $user['id'];
  } else {
    return -1;
  }
}

function IsNicknameFree($nickname)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM user WHERE nickname = :nickname ");
  $response->execute(
    array(
      "nickname" => $nickname
    )
  );
  return $response->rowCount() == 0;
}

function CreateNewUser($nickname, $password)
{
  global $PDO;
  $response = $PDO->prepare("INSERT INTO user (nickname, password) values (:nickname , :password )");
  $response->execute(
    array(
      "nickname" => $nickname,
      "password" => $password
    )
  );
  return $PDO->lastInsertId();
}
