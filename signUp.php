<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');

require('function.php');

if(!empty($_POST)){
    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $level=$_POST['level'];
    $attendance=$_POST['attendance'];
    $pass= $_POST['pass'];


    try{
        $dbh = dbConnect();
        $sql='INSERT INTO `users`(`email`, `nickname`, `level`, `attendance`, `pass`, `login_time`,`update_date`) 
        VALUES (:email,;nickname,:level,:attendance,:pass,:login_time,:update_date,:update_date)';
        $data = array(':email' => $email,
        ':nickname' => $nickname,
        ':level' => $level,
        ':attendance' => $attendance,
        ':pass'=> $pass,
        ':login_time' => date('Y-m-d H:i:s'));
        //クエリ実行
        $stmt=queryPost($dbh,$sql,$data);
        //クエリ成功の場合
        // if($stmt){
        //     $sesLimit=60*60;
        //     $_SESSION['login_date']=time();
        //     $_SESSION['login_limit']=$sesLimit;
        //     //ユーザーIDを格納
        //     $_SESSION['user_id']=$dbh->lastInsertId();

        //     debug('セッション変数の中身：'.print_r($_SESSION,true));
        //     header("Location:mypage.php");   
        // }else{
        // error_log('クエリに失敗しました');
        // $err_msg['common']=MSG07;
        // }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common']=MSG07;
    }
}
?>
<?php
$siteTitle='会員登録：';
require('head.php');
?>
<body>

<?php
require('header.php');
?>
<!-- メインコンテンツ -->
<div id="contents" class="site-width">
    <section class="register">
        <h2><img src="kuroishi.png" alt=""> 新規会員登録</h2>
        <form action="post" class="register">
            <table class="registerTable">
                <?php if(!empty($_POST)) echo $_POST['email']; ?>
                <label for="email">
                    <tr>
                        <td>メールアドレス</td><td><input type="email" name="email"></td>
                    </tr>
                </label>
                <label for="nickname">
                    <tr>
                        <td>ニックネーム</td> <td><input type="text" name="nickname"></td>
                    </tr>
                </label>
                <label for="level">
                    <tr>
                        <td>棋力（任意）</td><td><input type="text" name="level"></td>
                    </tr>
                </label>
                <label for="frequency">
                    <tr>
                        <td>出席予定</td><td><input type="radio" name="frequency" value="always">毎回参加予定<input type="radio" name="frequency" value="byChance">都合の良い時のみ</td>
                    </tr>
                </label>
                <label for="pass">
                    <tr>
                        <td>パスワード<br>（半角６文字以上で入力してください）</td><td><input type="password" name="pass"></td>
                    </tr>
                </label>
                    <tr>
                        <td colspan="2"><input type="submit" value="登録"></td>
                    </tr>
            </table>
        </form>
    </section>
</div>
<!-- フッター -->

<?php
require('footer.php');
?>
</body>
</html>
