<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');

require('function.php');

if(!empty($_POST)){
    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $level=$_POST['level'];
    $attendance=$_POST['attendance'];
    $frequency=$_POST['frequency'];
    $pass= $_POST['pass'];
    var_dump($email);


    try{
        $dbh = dbConnect();
        $sql='INSERT INTO `users`(email,nickname,password,level,attendance,login_time,update_date)
        VALUES(:email,:nickname,:password,:level,:attendance,:login_time,:update_date)';
        $data = array(':email' => $email,
        ':nickname' => $nickname,
        ':password' => $password,
        ':level' => $level,
        ':attendance' => $attendance,
        ':login_time' => date('Y-m-d H:i:s'),
        ':update_date' => date('Y-m-d H:i:s'));
        //クエリ実行
        $stmt=queryPost($dbh,$sql,$data);
        //クエリ成功の場合
        if($stmt){
            $sesLimit=60*60;
            $_SESSION['login_date']=time();
            $_SESSION['login_limit']=$sesLimit;
            //ユーザーIDを格納
            $_SESSION['user_id']=$dbh->lastInsertId();

            debug('セッション変数の中身：'.print_r($_SESSION,true));
            // header("Location:mypage.php");   
        }else{
        error_log('クエリに失敗しました');
        $err_msg['common']=MSG07;
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common']=MSG07;
    }
}
?>
<?php
$siteTitle='マイページ：';
require('head.php');
?>
<body>

<?php
require('header.php');
?>
<!-- メインコンテンツ -->
<div id="contents" class="site-width">
    <section class="register">
        <h2><img src="kuroishi.png" alt=""> マイページ</h2>
    </section>
    <?php if(!empty($_POST)) echo $_POST['email']; ?>
</div>
<!-- フッター -->

<?php
require('footer.php');
?>
</body>
</html>
