<?php
require('../function/function.php');

debug('「「「「「「「「「「「「「「「「「「「');
debug('「パスワード変更ページ「「「「「「「「');
debug('「「「「「「「「「「「「「「「「「「「');
//ログイン認証


//DBから」ユーザーデータを取得
$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($userData,true));

if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST,true));

    $pass_old = $_POST['oldPassword'];
    $pass_new = $_POST['newPassword'];
    $pass_new_re = $_POST['reNewPassword'];

    //未入力チェック
    validRequired($pass_old, 'pass_old');
    validRequired($pass_new,'pass_new');
    validRequired($pass_new_re,'pass_new_re');
 
    if(empty($err_msg)){
        debug('未入力チェックOK');
        validPass($pass_old,'pass_old');
        validPass($pass_new, 'pass_new');

        if(!password_verify($pass_old,$userData['pass'])){
            $err_msg['pass_old'] = MSG11;
        }
        //旧・新パスワードが同値の場合メッセージ
        if($pass_old === $pass_new){
            $err_msg['pass_new'] = MSG12;
        }
        //新パスワードと再入力が一致しているかチェック
        validMatch($pass_new,$pass_new_re,'pass_new_re');
        if(empty($err_msg)){
            debug('バリデーションOK');

            try{
                $dbh = dbConnect();
                $sql = 'UPDATE users SET pass = :pass WHERE id = :id';
                $data = array (':id' => $_SESSION['user_id'],':pass' => password_hash($pass_new,PASSWORD_DEFAULT));
                $stmt = queryPost($dbh, $sql, $data);

                if($stmt){
                    debug('クエリ成功');
                    $_SESSION['msg_success'] = SUC01;

                    $username = ($userData['nickname']) ? $userData['nickname']:'名無し';
                    $from = 'sonnige.seite.str@gmail.com';
                    $to = $userData['email'];

                    $subject = 'パスワード変更通知｜天元囲碁クラブ';
                    $comment = <<<EOT
{$username}さん
パスワードが変更されました。
///////////
囲碁部ノート
///////////
EOT;
    sendMail($from,$to,$subject,$comment);
    header("Location:mypage.php");
                }
            }catch(Exeception $e){
                error_log('エラー発生：'.$e->getMessage());
                $err_msg['common'] = MSG06;
            }
        }

    }
}

?>

<?php
$siteTitle ='囲碁部ノート｜パスワード変更';
require('head.php');
?>
<?php
require('header.php');
?>
    <section class="pass_edit">
        <h2 class="pass-title"><img src="../../assets/img/shiroishi.png" alt="">パスワード変更</h2>
        <form method="post" class="pass_edit_form">
            <table class="registerTable">
                <label for="oldPass">
                    <tr class="changePassTr">
                        <td class="changePassTd">旧パスワード<div class="area-msg"><?php if(!empty($err_msg['pass_old'])) echo $err_msg['pass_old']?></div></td>
                        <td class="changePassTd"><input type="password" name="oldPassword"></td>
                    </tr>
                </label>
                <label for="newPass">
                    <tr class="changePassTr">
                        <td class="changePassTd">新パスワード<div class="area-msg"><?php if(!empty ($err_msg['pass_new'])) echo $err_msg['pass_new']?></td> 
                        <td class="changePassTd"><input type="password" name="newPassword" value=></td>
                    </tr>
                </label>
                <label for="reNewPass">
                    <tr class="changePassTr">
                        <td class="changePassTd">新パスワード（再入力）<div class="area-msg"><?php if(!empty($err_msg['pass_new_re'])) echo $err_msg['pass_new_re']?></td>
                        <td class="changePassTd"><input type="password" name="reNewPassword"></td>
                    </tr>
                </label>
                    <tr class="changePassTr">
                        <td colspan="2" class="changePassTd"><input type="submit" value="変更"></td>
                    </tr>
            </table>
        </form>
    </section>
<?php
require('footer.php');
?>
</body>
</html>