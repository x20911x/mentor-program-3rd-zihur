<?php
  // 判斷連線寫入是否成功的 function，防錯機制，成功便轉回首頁。
  function checkConn($result, $conn) {
    if ($result) {
      header('Location: ../index.php');
      exit();
    } else {
      echo 'Failed, '. $conn->error;
      echo '連線有問題';
    }
  }
  // 在 client 端轉址並提供訊息
  function redirect($location, $message = '') {
    if($message !== '') {
      echo "<script>alert(" . $message . ")</script>";
    }
    echo "<script>window.location = " . $location . "</script>";
  }
  // 簡化預防 XSS 輸出的函式
  function xssPreventStr($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
  }
  // 產生隨機亂數
  function randomToken() {
    $word = '023456789abcdefghijkmnopqrstuvwxyz';  // 字典檔，排除 1 和 l
    $length = strlen($word);
    $token = '';
    for ($i = 0; $i < 32; $i += 1) {
      $token .= $word[rand(0, $length-1)];
    };
    return $token;
  }
  // 預防 CSRF 攻擊
  function csrfPrevent() {
    $csrfToken = '';
    $res = array(
      'status'  => 0,
      'msg'     => '403！請重新登入',
    );
    if (!isset($_COOKIE['csrfToken'])) {
      echo json_encode($res);
      exit();
    }
    if (isset($_POST['csrfToken'])) {
      $csrfToken = $_POST['csrfToken'];
    }
    if (isset($_GET['csrfToken'])) {
      $csrfToken = $_GET['csrfToken'];
    }
    if ($_COOKIE['csrfToken'] !== $csrfToken) {
      echo json_encode($res);
      exit();
    }
  }
?>
