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
////棋譜アップロード

if(!empty($_FILES)){
    $file = $_FILES['image'];
    $msg = '';
    $img_path = '';

    /////////////
    // $fileについてのバリデーションを書くこと
    //拡張子がjpeg,pngを確認

    ////////////////
    $upload_path ='game_images/'.$file['name'];
    $rst = move_uploaded_file($file['tmp_name'],$upload_path);
    if($rst){
        $msg ='画像をアップしました。'.$file['name'];
        $img_path = $upload_path;
    }else{
        $msg='画像はアップできませんでした。'.$file['error'];
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
<section class="site-width">
    <main class="mypage-wrap">
        <div class="game-record">
            <h2><img src="kuroishi.png" alt=""> オンライン感想戦</h2>
            <h3>棋譜</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="image">
                <input type="submit" value ="アップロード">
            </form>
            <p><?php if(!empty($msg)) echo $msg; ?></p>
            <?php if(!empty($img_path)){ ?>
                <div class="img_area">
                    <p>棋譜</p>
                    <img src="<?php echo $img_path; ?>" alt="" class="record-pic">
                </div>
            <?php }?>
    
        </div>
        <div class="sidebar">
            <h3 class="mymenu">マイメニュー</h3>
            <ul class="my-menu">
                <li class="handle"><a href="logout.php">ログアウト</li></a> 
                <li class="handle"><a href="passEdit.php">パスワード変更</li></a> 
                <li class="handle"><a href="withdraw.php">退会する</li></a>
            </ul>
            <h3 class="meetingday">出欠登録</h3>
            <ul class="meeting-day">
                <li><input type="checkbox"><?php echo date('Y-m-d',strtotime('friday')); ?></li>
                <li><input type="checkbox"><?php echo date('Y-m-d',strtotime('friday next week')); ?></li>
            </ul>
        </div>
        
    </main>
</section>
<!-- フッター -->

<?php
require('footer.php');
?>
</body>
</html>
