<?php

$text = $_POST["text"];

//2. DB接続します
//ここから作成したDBに接続をしてデータを登録します
try {
    $pdo = new PDO('mysql:dbname=ShiritoriChat_db;charset=utf8;host=localhost','root','');
  } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
  }
  
  
  //３．データ登録SQL作成 //ここにカラム名を入力する
  //テーブル名を入力します
  $last = $pdo->prepare("RIGHT($text,1)");
  $stmt = $pdo->prepare("INSERT INTO ShiritoriChat_text_table(id, text, last)VALUES(NULL, :text, :last");
  $stmt->bindValue(':text', $text, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':last', $last, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status = $stmt->execute();
  
  //４．データ登録処理後
  if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    //５．index.phpへリダイレクト 書くときにLocation: in この:　のあとは半角スペースがいるので注意！！
    // header("Location: select.php");
    echo ("やったぜ");
    exit;
  
  }
  


?>