<?php
require('function.php');

if(!empty($_POST)){
    $from = 'sonnige.seite.str@gmail.com';
    $to= $_POST['email'];
    $subject = $_POST['subject'];
    $comment = $_POST['comment'];

    validEmail($from,'email');
    validRequired($subject,'subject');
    validrequired($comment,'comment');

    if (empty($err_msg)){
        sendMail($from,$to,$subject,$comment);
        header("Location:login.php");
    }else{
        $msg='すべて入力してください';
    }
}
?>
<html>
<?php
$siteTitle = 'お問合せメール送信';
require('head.php');
?>
<?php
require('header.php');
?>
<body>
    <div class="mail-form-wrap">
        <h2 class="mail-title"><img src="kuroishi.png" alt=""> お問合せメールフォーム</h2>
        <span><?php if(!empty($msg)) echo $msg; ?></span>
        <form action="" method="post" class="mail-form">
            <table class="form-table">
                <tr><td class="mail-td">
                    <input class = "mail-input" type="email" name="email" placeholder="E-mail" value="<?php if (!empty($_POST['email'])) echo $_POST['email']; ?>">

                </td>
                </tr>
                <tr>
                    <td class="mail-td">
                        <input class = "mail-input" type="text" name="subject" placeholder="件名" value="<?php if (!empty($_POST['subject'])) echo $_POST['subject'];?>">
                    </td>
                </tr>
                <tr><td class="mail-td">
                    <textarea class = "text-input" name="comment" placeholder="内容"><?php if(!empty($_POST['comment'])) echo $_POST['comment'];?></textarea>
                </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="送信">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
<?php
require('footer.php');
?>
</html>