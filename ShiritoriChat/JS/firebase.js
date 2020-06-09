// しりとしの単語をfirebaseに登録し、shiritori.phpに履歴として表示させる

// ユーザー単語送信処理
$("#send").on("click", function () {
  newPostRef.push({
    user: "あなた",
    text: $("#text").val(),
    comment: "",
  });
});

// ユーザー単語受信処理
// newPostRef.on("child_added", function (data) {
//   var v = data.val();
//   var k = data.key;
//   var str = "<dl><dt>" + v.user + "</dt><dd>" + v.text + "</dd></dl>";
//   $("#output").prepend(str);
// });

// 返信単語送信処理
window.onload = function () {
  // $("#back").on("click", function () {
  newPostRef.push({
    user: "えんまちゃん",
    text: $("#reword").val(),
    comment: $("#comment").val(),
  });
};

// 返信単語受信処理
newPostRef.on("child_added", function (data) {
  var v = data.val();
  var k = data.key;
  var str =
    "<dl><dt>" +
    v.user +
    "</dt><dd>" +
    "「" +
    v.text +
    "」" +
    "</dd><dd>" +
    v.comment +
    "</dd></dl>";
  $("#output").prepend(str);
});
