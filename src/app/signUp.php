<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    ini_set('log_errors','on');
    ini_set('error_log','php.log');

    

require('../function/function.php');


if(!empty($_POST)){
    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $level=$_POST['level'];
    $attendance=$_POST['attendance'];
    $pass= $_POST['pass'];
    $re_pass = $_POST['re_pass'];

    
    // //////////未入力チェック///////////////

    // ニックネーム
    validRequired($nickname,'nickname');

    // パスワード
    validrequired($pass,'pass');

    //出席予定
    validRequired($attendance,'attendance');


    /////////////Email///////////////////
    //形式チェック
    validEmail($email,'email');

    //email重複
    validDupEmail($email);


    ///////////パスワード/////////////////
    //一致確認
    validMatch($pass,$re_pass,'re_pass');
    
    //最小文字数確認
    valildMinLength($pass,'pass');

   //半角チェック
    validHalf($pass,'pass');


    if(empty($err_msg)){
        try{
            $dbh = dbConnect();
            $sql='INSERT INTO `users`(`email`, `nickname`, `level`, `attendance`, `pass`, `login_time`,`update_date`,`delete_flg`) 
            VALUES (:email,:nickname,:level,:attendance,:pass,:login_time,current_timestamp,0)';
            $data = array(
            ':email' => $email,
            ':nickname' => $nickname,
            ':level' => $level,
            ':attendance' => $attendance,
            ':pass'=> password_hash($pass, PASSWORD_DEFAULT),
            ':login_time' => date('Y-m-d H:i:s')
            );
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
                header("Location:mypage.php");   
            }else{
            error_log('クエリに失敗しました');
            $err_msg['common']=MSG06;
            }
        }catch(Exception $e){
            error_log('エラー発生：'.$e->getMessage());
            $err_msg['common'] = MSG06;
        }
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
        <h2><img src="../../assets/img/kuroishi.png" alt=""> 新規会員登録</h2>
        <form method="post" class="register">
            <span class="small">＊項目は必須です。</span>
            <table class="registerTable"> 
                <span class="<?php if(!empty($err_msg['common'])) echo 'err'; ?>">
                <div class="area-msg">
                    <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                </div>
                </span>
                <tr>
                    <td>
                        <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">メールアドレス(＊)
                        </label>
                    </td>
                    <td>
                        <input type="email" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'];?>">
                        <div class="area-msg">
                            <?php if(!empty($err_msg['email'])) echo $err_msg['email'];?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="<?php if (!empty($err_msg['nickname'])) echo 'err'; ?>" for = "nickname">ニックネーム(＊)
                        </label>
                    </td> 
                    <td>
                        <input type="text" name="nickname" value="<?php if(!empty($_POST['nickname'])) echo $_POST['nickname'];?>">
                        <div class="area-msg">
                            <?php if(!empty($err_msg['nickname'])) echo $err_msg['nickname'];?>
                        </div>
                    </td>
                    </tr>
                    <tr>
                        <label for="level">
                        <td>棋力（任意）</td>
                        <td><input type="text" name="level"></td>
                    </label>
                    </tr>
                    <tr>
                        <td>
                            <label for="attendance" class="<?php if(!empty($err_msg['attendance'])) echo 'err'?>">出席予定(＊)
                            </label>
                        </td>
                        <td>
                            <input type="radio" name="attendance" value="always">毎回参加予定
                            <input type="radio" name="attendance" value="byChance">都合の良い時のみ
                            <div class="area-msg">
                                <?php if(!empty($err_msg['attendance'])) echo $err_msg['attendance'];?>
                            </div>
                        </td>
                    </tr>
                <label for="pass">
                    <tr>
                        <td>
                            <label for="" class="<?php if(!empty($err_msg['pass'])) echo 'err'?>">パスワード(＊)</label>
                            <br><span class="small">（半角６文字以上で入力してください）</span>
                        </td>
                        <td><input type="password" name="pass">
                        <div class="area-msg">
                            <?php if(!empty($err_msg['pass'])) echo $err_msg['pass'];?>
                        </div>
                        </td>
                    </tr>
                </label>
                <label for="re_pass">
                    <tr>
                        <td>
                            <label for="" class="<?php if(!empty($err_msg['re_pass'])) echo 'err'?>">パスワード再入力(＊)</label>
                        </td>
                        <td><input type="password" name="re_pass">
                        <div class="area-msg">
                            <?php if(!empty($err_msg['re_pass'])) echo $err_msg['re_pass'];?>
                        </div>

                        </td>

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
