<?php
require('function.php');

debug('「「「「「「「「「「「「「「');
debug('「「パスワード再発行メール送信ページ「「「');
debug('「「「「「「「「「「「「「「');

debugLogStart();

if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST,true));

    $email = $_POST['email'];
    validRequired($email,'email');

    if(empty($err_msg)){
        debug('未入力チェックOK');
        validEmail($email,'email');
        validMaxLength($email,'email');

        if(empty($err_msg)){
            debug('バリデーションOK');

            try{
                $dbh = dbConnect();
                $sql = 'SELECT count(*) FROM users WHERE email =:email AND delete_flg = 0';
                $data = array(':email' => $email);
                $stmt = queryPost($dbh,$sql,$data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                //EmailがDBに登録されている場合
                if($stmt && array_shift($result)){
                    debug('クエリ成功、DB登録あり');
                    $_SESSION['msg_success'] = SUC03;

                    $auth_key =makeRandKey();


                    $from = 'sonnige.seite.str@gmail.com';
                    $to = $email;
                    $subject = 'パスワード再発行認証｜天元囲碁クラブ';
                    $comment =<<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力いただくとパスワードが再発行されます。
認証キー入力ページ：http://localhost:8888/igo_club/passRemindReceive.php
認証キー：{$auth_key}
＊認証キーの有効期限は３０分となります。
認証キーを再発行される場合は下記ページより再発行をお願いいたします。
http://localhost:8888/igo_club/passRemindSend.php

//////////////////////
天元囲碁クラブ
//////////////////////
EOT;
                    sendMail($from,$to,$subject,$comment);
                    $_SESSION['auth_key']= $auth_key;
                    $_SESSION['auth_email']= $email;
                    $_SESSION['auth_key_limit']= time() + (60*30);

                    debug('セッション変数の中身：'.print_r($_SESSION,true));
                    header("Location:passRemindReceive.php");

                }else{
                    debug('クエリに失敗したかDBに登録のないEmailが入力されました。');
                    $err_msg['common'] = MSG06;
                }
            }catch(Exception $e){
                error_log('エラー発生：'.$e->getMessage());
                $err_msg['common']= MSG06;
            }
        }
    }
}
?>
<?php
$siteTitle = 'パスワード再発行メール送信';
require('head.php');
?>
<body class="page-signup page-1column">
<?php
require('header.php');
?>
<div class="site-width" id= "contents">
    <section id="main">
        <div class="form-container">
            <form action="" class="form" method="post">
                <p>ご登録いただいているメールアドレス宛にパスワード再発行用のURLと認証キーをお送りいたします</p>
                <div class="area-msg">
                    <?php
                    if(!empty($err_msg['common'])) echo $err_msg['common'];
                    ?>
                </div>
                <label for="" class="<?php if(!empty($err_msg['email'])) echo 'err';?>">Email
                    <input type="text" name="email" value="<?php echo getFormData('email');?>">
                </label>
                <div class="area-msg">
                    <?php
                    if(!empty($err_msg['email'])) echo $err_msg['email'];  
                    ?>
                </div>
                <div class="btn-container">
                    <input type="submit" class="btn-mid" value="送信する">
                </div>
            </form>
        </div>
        <a href="mypage.php">&lt; マイページに戻る</a>
    </section>
</div>
<?php
require('footer.php');
?>
</body>

