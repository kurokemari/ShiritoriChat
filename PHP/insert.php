<?php

// １．値を変数に登録（最後の文字はとりあえず空欄を登録）
$text = $_POST["text"];
$last = "";
$FLG = 0;

//2. DB接続
try {
    $pdo = new PDO('mysql:dbname=ShiritoriChat_db;charset=utf8;host=localhost','root','');
  } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
  }

// DB内に同じ単語があるか確認

// 同じ単語が既にあった場合は負けとジャッジしコメント送信


//３．データ登録SQL作成
//３−１． カラムに値代入
$stmt = $pdo->prepare("INSERT INTO ShiritoriChat_text_table (id, text, last, FLG) VALUES (NULL, :text, :last, :FLG)");
$stmt->bindValue(':text', $text, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':last', $last, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':FLG', $FLG, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//３−２．最後の文字を抽出しUPDATEする
$stmt0 = $pdo->prepare("UPDATE ShiritoriChat_text_table SET last=RIGHT(:text,1) WHERE text=:text");
$stmt0->bindValue(':text', $text, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status0 = $stmt0->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else if($status0==false){
  $error = $stmt0->errorInfo();
  exit("QueryError:".$error[2]);
}else{
//５．index.phpへリダイレクト 書くときにLocation: in この:　のあとは半角スペースがいるので注意！！
  header("Location: Shiritori.php");
  exit;
}
  


?>