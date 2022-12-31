<?php

//ログ
error_reporting(E_ALL);
ini_set('display_errors','On');
ini_set('log_errors','on');
ini_set('error_log','php.log');


/////////デバッグ///////////////
$debug_flg = true;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}

///セッション準備
session_save_path("/var/tmp/");
ini_set('session.gc_maxlifetime',60*60*24*30);
ini_set('session.cookie_lifetime',60*60*24*30);
session_start();
session_regenerate_id();

///画面表示処理開始ログ吐き出し関数
function debugLogStart(){
    debug('>>>>>>>>>>>>>画面表示処理開始');
    debug('セッション ID：'.session_id());
    debug('セッション変数の中身：'.print_r($_SESSION,true));
    debug('現在日時タイムスタンプ：'.time());
        if(!empty($_SESSION['login_date'])&& !empty($_SESSION['login_limit'])){
            debug('ログイン期限日時タイムスタンプ：'.($_SESSION['login_date']+ $_SESSION['login_limit']));
    }
}

// //定数
const MSG01 = '入力必須です';
const MSG02 = 'Emailの形式で入力してください';
const MSG03 = 'パスワード（再入力）が一致しません';
const MSG04 = '半角英数字のみご利用いただけます';
const MSG05 = '６文字以上で入力してください';
const MSG06 = 'エラーが発生しました。しばらく経ってからやり直してください。';
const MSG07 = 'そのEmailはすでに登録されています';
const MSG09 = 'メールアドレスまたはパスワードが違います';
const MSG10 = '２５６文字以内で入力してください';
const MSG11 = '古いパスワードが違います';
const MSG12 = '古いパスワードと同じです';
const MSG13 = '文字で入力してください';
const MSG14 = '正しくありません';
const MSG15 = '有効期限が切れています';
const SUC01 = 'パスワードを変更しました';
const SUC02 = 'プロフィールを変更しました';
const SUC03 = 'メールを送信しました';
const SUC04 = 'ノートに記録しました。';

// //グローバル変数・エラーメッセージ格納用
$err_msg = array();


// //////////////バリデーション///////////////////

//未入力
function validRequired($str,$key){
    if(empty($str)){
        global $err_msg;
        $err_msg[$key]= MSG01;
    }
}
// Email未入力および形式チェック
function validEmail($str,$key){
    if(empty($str)){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }elseif(!preg_match("/^[a-zA-Z0-9_.+-]+[@][a-zA-Z0-9.-]+$/",$str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
//Email重複チェック
function validDupEmail($email){
    global $err_msg;
    try{
        // DB接続
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email'=>$email);
        //クエリ実行
        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty(array_shift($result))){
            $err_msg['email']= MSG07;
        }
    }catch (Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG06;
    }
}
// 同値チェック
function validMatch($str1,$str2,$key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
// ６文字未満
function valildMinLength($str,$key,$min = 6){
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key]= MSG05;
    }
}
//２５６文字以上
function validMaxLength($str,$key,$max = 256){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}
// 半角チェック
function validHalf($str,$key){
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
//////パスワード変更バリデーション
function validPass($str,$key){
    validHalf($str,$key);
    valildMinLength($str,$key);
    validMaxLength($str,$key);
}
//固定長チェック
function validLength($str,$key,$len=8){
    if(mb_strlen($str)!==$len){
        global $err_msg;
        $err_msg[$key] = $len.MSG14;
    }
}

/////////////データベース///////////////////
function dbConnect(){
    $dsn='mysql:dbname=igo_club;host=localhost;charset=utf8';
    $user='root';
    $password='root';

    $options=array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    $dbh = new PDO($dsn,$user,$password,$options);
    return $dbh;
}

function queryPost($dbh,$sql,$data){
    $stmt=$dbh->prepare($sql);
    if(!$stmt->execute($data)){
        $err_msg['common'] = MSG07;
        return 0;
        debug('クエリに失敗しました');
    }
    $stmt->execute($data);
    debug('クエリ成功');
    return $stmt;
}
function uploadImg($file,$key){
    debug('画像アップロード開始、FILE情報：'.print_r($file,true));
    if(isset($file['error'])&& is_int($file['error'])){
        try{
            switch ($file['error']){
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('ファイルが選択されていません');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default:
                    throw new RuntimeException('その他のエラーが発生しました');
            }
            $type=@exif_imagetype($file['tmp_name']);
            if(!in_array($type,[IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG],true)){
                throw new RuntimeException('画像形式が未対応です');
            }
            $path='uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            if(!move_uploaded_file($file['tmp_name'],$path)){
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            chmod($path,0644);
            debug('ファイルは正常にアップロードされました');
            debug('ファイルパス：'.$path);
            return $path;
        }catch(RuntimeException $e){
            debug($e->getMessage());
            global $err_msg;
            $err_msg[$key]= $e->getMessage();
        }
    }
}
/////////////データベース出力
function getRecord($u_id){
    debug('記録を取得します');
    debug('ユーザーデータ：'.print_r($_SESSION['user_id']));
    try{
        $dbh = dbConnect();
        $sql = 'SELECT*FROM record WHERE user_id = :user_id';
        $data = array(':user_id'=>$u_id);
        $stmt = queryPost($dbh,$sql,$data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return $result;
            }else{
            return false;
        }
    }catch (Exception $e){
       global $err_msg;
       $err_msg = MSG06;
    }
}


/////////////メール送信

function sendMail($from,$to,$subject,$comment){
    if(!empty($to) && !empty($subject) && !empty($comment)){
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        $result = mb_send_mail($to,$subject,$comment,"From:".$from);
        if($result){
            debug('メールを送信しました');
        }else{
            debug('[エラー発生]メールの送信に失敗しました');
        }
    }
}

////////////ユーザー情報取得
function getUser($u_id){
    debug('ユーザー情報を取得します。');
try{
    $dbh= dbConnect();
    $sql = 'SELECT*FROM users WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    $stmt= queryPost($dbh,$sql,$data);

    if($stmt){
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
        return false;
    }
}catch (Exception $e){
    error_log('エラー発生：'.$e ->getMesssage());
}
}
function makeRandKey($length = 8){
    static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0;$i<$length;++$i){
        $str.=$chars[mt_rand(0,61)];
    }
    return $str;
}
///////////////サニタイズ
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}

///////////////フォーム入力保持
function getFormData ($str,$flg = false){
    if($flg){
        $method = $_GET;
    }else{
        $method = $_POST;
    }
    global $dbFormData;
        if(!empty($dbFormData)){
            if(!empty($err_msg[$str])){
                    if(isset($method[$str])){
                        return sanitize($method[$str]);
                    }else{
                        return sanitize($dbFormData[$str]);
                    }
                }else{
                    //POSTにデータがあり、DBの情報と違う場合
                    if(isset($method[$str])&& $method[$str]!== dbFormData[$str]){
                        return sanitize($method[$str]);
                    }else{
                        return sanitize($dbFormData[$str]);
                    }
            }
    }else{
        if(isset($method[$str])){
            return sanitize ($method[$str]);
        }
    }
}
//sessionを一回だけ取得できる
function getSessionFlash($key){
    if(!empty($_SESSION[$key])){
        $data = $_SESSION[$key];
        $_SESSION[$key] = '';
        return $data;
    }
}

///////////お問合せメール送信