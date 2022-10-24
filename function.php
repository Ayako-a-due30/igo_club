<?php

//ログ
error_reporting(E_ALL);
ini_set('display_errors','On');


// //デバッグ
// $debug_flg = true;


// //デバッグログ関数
// function debug($str){
//     global $debug_flg;
//     if(!empty($debug_flg)){
//         error_log('デバッグ：'.$str);
//     }
// }

// ///セッション準備
// session_save_path("/var/tmp/");
// ini_set('session.gc_maxlifetime',60*60*24*30);
// ini_set('session.cookie_lifetime',60*60*24*30);
// session_start();
// session_regenerate_id();

// ///画面表示処理開始ログ吐き出し関数
// function debugLogStart(){
//     debug('>>>>>>>>>>>>>画面表示処理開始');
//     debug('セッション ID：'.session_id());
//     debug('セッション変数の中身：'.print_r($_SESSION['login_limit']));
//         if(!empty($_SESSION['login_date'])&& !empty($_SESSION['login_limit'])){
//             debug('ログイン期限日時タイムスタンプ：'.$SESSION['login_date'].$_SESSION['login_limit']);
//     }
// }

// //定数
// const MSG01 = '入力必須です';
// const MSG02 = 'Emailの形式で入力してください';
// const MSG03 = 'パスワード（再入力）が一致しません';
// const MSG04 = '半角英数字のみご利用いただけます';
// const MSG05 = '６文字以上で入力してください';
// const MSG06 = 'エラーが発生しました。しばらく経ってからやり直してください。';
// const MSG07 = 'そのEmailはすでに登録されています';

// //グローバル変数・エラーメッセージ格納用
// $err_msg=array();


// //////////////バリデーション///////////////////
// //未入力チェック
// function validRequired($str,$key){
//     if(empty($str)){
//         global $err_msg;
//         $err_msg[$key]= MSG01;
//     }
// }
//Email形式チェック
// function validEmail($str,$key){
//     if(!preg_match("\$\%&'*+/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&'*+/=?\^`{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/",$str)){
//         global $err_msg;
//         $errmsg[$key] =MSG02;
//     }
//Email重複チェック
//同値チェック
//６文字未満
//半角チェック


/////////////データベース///////////////////
function dbConnect(){
    $dsn='mysql:dbname=igo_club;host=localhost;charset=utf8';
    $user='root';
    $password='root';

    $options=array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY =>true,
    );
    $dbh = new PDO($dsn,$user,$password,$options);
    return $dbh;
}

function queryPost($dbh,$sql,$data){
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}
?>
