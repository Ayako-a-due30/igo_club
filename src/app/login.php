<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');


require('function.php');

debug('[[[[[[[[[[');
debug('ログインページ');
debug(']]]]]]]]]]]');
debugLogStart();

require('auth.php');

//-----------------
//ログイン画面処理
//-----------------

if(!empty($_POST)){
    debug('POST送信があります');
    //変数にユーザー情報を代入
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $pass_save=(!empty($_POST['pass_save']))?true:false;

////////Email////////////////////
//未入力
validRequired($email,'email');

//形式
validEmail($email,'email');

////////パスワード////////////////////
//未入力
validRequired($pass,'pass');

//形式、半角英数字
validHalf($pass,'pass');


    if(empty($err_msg)){
        debug('バリデーションOK');
        //例外処理
        try{
            $dbh =dbConnect();
            $sql ='SELECT pass,id FROM users WHERE email = :email';
            $data=array(':email'=>$email);
            $stmt=queryPost($dbh,$sql,$data);
            $result =$stmt->fetch(PDO::FETCH_ASSOC);
            debug('クエリ結果の中身：'.print_r($result,true));

            if(!empty($result)&& password_verify($pass,array_shift($result))){
                debug('パスワードがマッチしました。');

                //ログイン有効期限（デフォルト１時間）
                $sesLimit = 60*60;
                //最終ログイン日時を現在日時に
                $_SESSION['login_date']=time();
                //ログイン保持チェックの場合
                if($pass_save){
                    debug('ログイン保持にチェックがあります。');
                    //ログイン有効期限を３０日に
                    $_SESSION['login_limit'] = $sesLimit*24*30;
                }else{
                    debug('ログイン保持にチェックはありません。');
                    //ログイン有効期限は１時間
                    $_SESSION['login_limit'] =$sesLimit;
                }
                //ユーザーIDを格納
                $_SESSION['user_id']=$result['id'];
                debug('セッション変数の中身：'.print_r($_SESSION,true));
                debug('マイページへ遷移します');
                header("Location:mypage.php");
            }else{
                debug('パスワードがアンマッチです。');
                $err_msg['common'] = MSG09;
            }
        }catch(Exception $e){
            error_log('エラー発生：'.$e->getMessage());
            $err_msg['common'] = MSG06;
        }
    }
}
debug('画面表示終了<<<<<<<<<<<<<<<<');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+1p" rel="stylesheet">
    <title>天元囲碁クラブ</title>
</head>
<body>
    <header>
        <div class="site-width">
            <h1><a href="">天元囲碁クラブ</a></h1>
        </div>
    </header>
    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
        <section class="introduction">
            <h2> <img src="kuroishi.png" alt="">私たちについて</h2>
            <p>天元囲碁クラブは毎週金曜日１９時から天元町公民館で囲碁を打っています。<br>
                初心者・有段者問わず、見学随時歓迎です！メールは<a href="sendMail.php">こちら</a>
            </p>
        </section>
        <div class="wrap">
        <section class="schedule">
            <h2> <img src="shiroishi.png" alt="">活動予定</h2>
            <iframe src="https://calendar.google.com/calendar/embed?height=280&wkst=1&bgcolor=%23ffffff&ctz=Asia%2FTokyo&src=MWYwZTBiMTM1MzVkODc2NWYxZWZhYzhmZDgyZTE2M2E0YjVhOWNmMjZlMjFmOTcyMjk3N2MyYTBlOGQ1ZWM4YUBncm91cC5jYWxlbmRhci5nb29nbGUuY29t&color=%23F6BF26" style="border:solid 1px #777" width="300" height="280" frameborder="0" scrolling="no"></iframe>
        </section>
        <section class="login">
            <h2><img src="kuroishi.png" alt="">会員ログイン</h2>
            <form method="post" class="signIn">
                <div class="area-msg"><?php if(!empty($err_msg)) echo $err_msg['common']?></div>
                <table class="loginForm">
                    <tr>
                        <label for="">
                            <td>メール</td>
                            <td><input type="email" name="email"></td>
                        </label>
                    </tr>
                    <tr>
                        <label for="">
                            <td>パスワード</td>
                            <td><input type="password" name="pass"></td>
                        </label>
                    </tr>
                        <td colspan="2">
                            <input type="submit" value="マイページへ">
                        </td>
                    <tr>                                           
                        <td colspan="2">パスワードを忘れた方は<a href="">こちら</a></td>
                    </tr>
                    <tr>
                    <td colspan="2"><input type="checkbox" class="btn" name="pass_save">次回ログインを省略する</td>
                    </tr>
                    <tr>
                        <td colspan="2">新規会員登録は<a href="signUp.php">こちら</a></td>
                    </tr>
                </table>
            </form>
        </section>
        </div>
    </div>    
    <footer>
        <a href="">
            天元囲碁クラブ all right reserved.
        </a>
    </footer>
</body>
</html>