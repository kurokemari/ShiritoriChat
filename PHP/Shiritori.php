<!DOCTYPE html>
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>しりとりアプリ</title>
    </head>
    <body>
    
        <!-- タイトル表示 -->
        <header>
        <div class="titel">
        <p>つべこべ言わずに尻を取れ！！！</p>
        </div>
        <div class="subtitel">
        <p>えんまちゃんが性格の悪さをはっきする地獄のしりとりだよ！</p>
        </div>
        </header>
        <!-- 挑戦者欄 -->
        <form method="post" action="insert.php" class="challenger">
        <input type="text" name="text" id="text">
        <input type="submit" value="送信" id="send">
        </form>
        <!-- 履歴表示 -->
        <div id=output>
        
        </div>
        

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
<script src="JS/firebase.js"></script>

    </body>
</html>