<?php
include_once "PDO.php";

function GetOnePostFromId($id)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM post WHERE id = :id");
  $response->execute([':id' => $id]);
  return $response->fetch();
}

function GetAllPosts()
{
  global $PDO;
  $response = $PDO->prepare(
    "SELECT post.*, user.nickname "
      . "FROM post LEFT JOIN user on (post.user_id = user.id) "
      . "ORDER BY post.created_at DESC"
  );
  $response->execute();
  return $response->fetchAll();
}

function SearchInPosts($search)
{
  global $PDO;
  $response = $PDO->prepare(
    "SELECT post.*, user.nickname "
      . "FROM post LEFT JOIN user on (post.user_id = user.id) "
      . "WHERE content like :search "
      . "ORDER BY post.created_at DESC"
  );
  $searchWithPercent = "%$search%";
  $response->execute(
    array(
      "search" => $searchWithPercent
    )
  );
  return $response->fetchAll();
}

function GetAllPostsFromUserId($userId)
{
  global $PDO;
  $response = $PDO->prepare("SELECT * FROM post WHERE user_id = :userId ORDER BY created_at DESC");
  $response->execute(['userId' => $userId]);
  return $response->fetchAll();
}

function CreateNewPost($userId, $msg)
{
  global $PDO;
  $sql = "INSERT INTO post(user_id, content) values (:user_id, :content)";
  $pdo_prepare = $PDO->prepare($sql);
  $pdo_execute = $pdo_prepare->execute(['user_id' => $userId, 'content' => $msg]);
  return $pdo_execute;
}
