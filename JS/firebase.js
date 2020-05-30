// しりとしの単語をfirebaseに登録し、shiritori.phpに履歴として表示させる

// 送信処理
$("#send").on("click", function () {
  newPostRef.push({
    text: $("#text").val(),
  });
});

// 受信処理
newPostRef.on("child_added", function (data) {
  var v = data.val();
  var k = data.key;
  var str = '<dl><dd>"+v.text+"</dd></dl>';
  $("#output").prepend(str);
});
