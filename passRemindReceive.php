<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「');
debug('「パスワード再発行認証キー入力ページ「「「「');
debug('「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(empty($_SESSION['auth_key'])){
    header("Location:passRemindSend.php");
}
//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));

    $auth_key = $_POST['token'];

    validRequired($auth_key,'token');

    if(empty($err_msg)){
        debug('未入力チェックOK');
        validLength($auth_key,'token');
        validHalf($auth_key,'token');
        if(empty($err_msg)){
            debug('バリデーションOK');
            if($auth_key !== $_SESSION['auth_key']){
                $err_msg['common'] = MSG14;
            }
            if(time()> $_SESSION['auth_key_limit']){
                $err_msg['common']= MSG15;
            }
            if(empty($err_msg)){
                debug('認証OK');

                $pass = makeRandKey();

                try{
                    $dbh = dbConnect();
                    $sql = 'UPDATE users SET pass = :pass WHERE email = :email AND delete_flg = 0';
                    $data = array(':email' =>$_SESSION['auth_email'],':pass' => password_hash($pass,PASSWORD_DEFAULT));
                    $stmt = queryPost($dbh,$sql,$data);

                    if($stmt){
                        debug('クエリ成功');
                        //メールを送信
                        $from ='sonnige.seite.str@gmail.com';
                        $to = $_SESSION['auth_email'];
                        $subject = '【パスワード再発行完了】天元囲碁クラブ';
                        $comment = <<<EOT
本メールアドレス宛にパスワードの再発行をいたしました。
下記のURLにて再発行パスワードをご入力いただき、ログインしてください。

ログインページ：http://localhost:8888/igo_club/login.php
再発行パスワード：{$pass}
※ログイン後、パスワードのご変更をお願いいたします。
////////////
天元囲碁クラブ
////////////
EOT;
                        sendMail($from,$to,$subject,$comment);
                        //セッション削除
                        session_unset();
                        $_SESSION['msg_success']= SUC03;
                        debug('セッション変数の中身：'.print_r($_SESSION,true));
                        header("Location:Login.php");
                    }else{
                        debug('クエリに失敗しました');
                        $err_msg['common']=MSG06;
                    }
                } catch (Exception $e){
                    error_log('エラー発生：'.$e->getMessage());
                    $err_msg['common']= MSG06;
                }
            }
        }
    }
}
?>
<?php
$siteTitle = 'パスワード再発行認証';
require('head.php');
?>

<body class="page-signup page-1column">
    <?php
    require('header.php');
    ?>
    <p id="js-show-msg" class="msg-slide" style ="display:none;">
    <?php echo getSessionFlash('msg_success');?>
    </p>

    <div id="contents" class="site-width">
        <section id="main">
            <div class="form-container">
                <form action="" class="form" method="post">
                    <p>ご指定のメールアドレスにお送りした【パスワード再発行認証】メール内にある「認証キー」をご入力ください。</p>
                    <div class="area-msg">
                        <?php if(!empty($err_msg['common'])) echo $err_msg['common'];
                        ?>
                    </div>
                    <label for="" class="<?php if(!empty($err_msg['token'])) echo'err';?>">
                    認証キー
                    <input type="text" name="token" value="<?php echo getFormData('token');?>">
                    </label>
                    <div class="area-msg">
                        <?php if(!empty($err_msg['token'])) echo $err_msg['token'];?>
                    </div>
                    <div class="btn-container">
                        <input type="submit" class="btn btn-mid" value="再発行する">
                    </div>
                </form>
            </div>
            <a href="passRemindSend.php">&lt;パスワード再発行メールを再度送信する</a>
        </section>
    </div>
    <?php
    require('footer.php');
    ?>
</body>