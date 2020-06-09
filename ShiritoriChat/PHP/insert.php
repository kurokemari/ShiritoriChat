<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>しりとりアプリ</title>
        <link rel="stylesheet" type="text/css" href="reset.css" />
        <link rel="stylesheet" type="text/css" href="style.css" />

    </head>
    <body>
    
    
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
    try {
      $stmt2 = $pdo->prepare('SELECT * FROM ShiritoriChat_KU_table WHERE initial=:initial');
      $stmt2->bindValue(':initial', $initial, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
      $stmt2->execute();
      } catch (PDOException $e) {
          // 「500 Internal Server Error」にして，HTMLではなくテキストでエラーメッセージを表示して終了
          header('Content-Type: text/plain; charset=UTF-8', true, 500);
          exit($e->getMessge());
      }
      foreach ($stmt2 as $row) {
        $reword = $row['text'];
        $comment = $row['comment'];

      }
      
    // 取得した返信用単語をfirebaseに保存
    // $view="";
    // if($status==false){
    //   //execute（SQL実行時にエラーがある場合）
    //   $error = $stmt->errorInfo();
    //   exit("ErrorQuery:".$error[2]);
    // }else{
    //   //Selectデータの数だけ自動でループしてくれる $resultの中に「カラム名」が入ってくるのでそれを表示させる例
    //   $view .= $reword;
  
      
      

    // }
      
    echo '<input type="hidden" id="reword" value="' . htmlspecialchars($reword) . '" />'."\n";
    echo '<input type="hidden" id="comment" value="' . htmlspecialchars($comment) . '" />'."\n";
    echo '<input type="text" id="enma" value="戻ってみ！" />'."\n";
    // echo '<a href="http://localhost/shiritoriChat/PHP/Shiritori.php/"><input type="submit" name="back" value="えんまちゃんの答えを見る" />'."\n";

      ?>
      <br>
        <img src="イメージ001.jpg" alt="" title="えんまちゃん" width="600" height="600" borderRadius=300px>


<!-- <a href="http://localhost/shiritoriChat/PHP/Shiritori.php/">
<input type="submit" name="back" id="back" value="えんまちゃんの答えを見る">
</a> -->

<!-- 以下Jquery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- 以下firebase -->
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyBQwYc8oKl8IoyXuluvfWoa43rh-m7FsYo",
    authDomain: "camp07-d036c.firebaseapp.com",
    databaseURL: "https://camp07-d036c.firebaseio.com",
    projectId: "camp07-d036c",
    storageBucket: "camp07-d036c.appspot.com",
    messagingSenderId: "455482938798",
    appId: "1:455482938798:web:fee1bdbed398d01414243f"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
    var newPostRef = firebase.database().ref();
</script>

<!-- firebase.js読み込み -->
<script src="http://localhost/shiritoriChat/JS/firebase.js"></script>

    </body>
</html>