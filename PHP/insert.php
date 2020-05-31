<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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

//３−２．最後の文字をlastのカラムにUPDATEする
$stmt0 = $pdo->prepare("UPDATE ShiritoriChat_text_table SET last=RIGHT(:text,1) WHERE text=:text");
$stmt0->bindValue(':text', $text, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status0 = $stmt0->execute();

//４．データ登録後、エラーがなければShiritori.phpに戻る
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else if($status0==false){
  $error = $stmt0->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //Location: in この:　のあとは半角スペースがいるので注意！！
  // header("Location: Shiritori.php");
  // exit;
}

// DBからBOTのコメントを受信
// まずユーザーが送信した単語の最後の文字を取得
try {
$stmt1 = $pdo->prepare('SELECT last FROM ShiritoriChat_text_table WHERE text=:text');
$stmt1->bindValue(':text', $text, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt1->execute();
} catch (PDOException $e) {
    // 「500 Internal Server Error」にして，HTMLではなくテキストでエラーメッセージを表示して終了
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessge());
}
foreach ($stmt1 as $row) {
  $initial = $row['last'];
}

// 最後の文字を返信用テーブルの最初の文字から探し、返信用単語を取得

$stmt2 = $pdo->prepare('SELECT * FROM ShiritoriChat_KU_table WHERE initial=:initial');
$stmt2->bindValue(':initial', $initial, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt2->execute();

if( is_array($stmt2)) {
foreach ($stmt2 as $row) {
  $reword = $row['text'];
};
}else{
  echo ("配列じゃないって！！");
  $reword = $stmt2['text'];
}

var_dump($row2);


// // 取得した返信用単語をfirebaseに保存
// define("DEFAULT_URL","https://camp07-d036c.firebaseio.com/");
// define("DEFAULT_TOKEN","wRY9YBWCvIrnQyE7BaexZtYkYwLUODec5XVJkUeI");
// $test = array(
//   "name" => "えんまちゃん",
//   "text" => $reword,
// );

// $firebase = new \Firebase\FirebaseLib(DEFAULT_URL,DEFAULT_TOKEN);

// // set
// // $firebase->set("/users",$test);






?>



<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

