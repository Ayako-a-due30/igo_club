<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');

require('../function/function.php');

if(!empty($_POST)){
    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $level=$_POST['level'];
    $attendance=$_POST['attendance'];
    $frequency=$_POST['frequency'];
    $pass= $_POST['pass'];

    try{
        $dbh = dbConnect();
        $sql='INSERT INTO `users`(email,nickname,password,level,login_time,update_date)
        VALUES(:email,:nickname,:password,:level,,:login_time,:update_date)';
        $data = array(':email' => $email,
        ':nickname' => $nickname,
        ':password' => $password,
        ':level' => $level,
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
// ////棋譜アップロード

// if(!empty($_FILES)){
//     $file = $_FILES['image'];
//     $msg = '';
//     $img_path = '';

//     /////////////
//     // $fileについてのバリデーションを書くこと
//     //拡張子がjpeg,pngを確認

//     ////////////////
//     $upload_path ='game_images/'.$file['name'];
//     $rst = move_uploaded_file($file['tmp_name'],$upload_path);
//     if($rst){
//         $msg ='画像をアップしました。'.$file['name'];
//         $img_path = $upload_path;
//     }else{
//         $msg='画像はアップできませんでした。'.$file['error'];
//     }
// }
?>
<?php
$siteTitle='囲碁部ノート|マイページ：';
require('head.php');
?>
<body>

<?php
require('header.php');
?>
<!-- メインコンテンツ -->
<section class="site-width">
    <main class="mypage-wrap">
    <div class="bar">
        <span>
            こんにちは、さん。
        </span>
        <h3 class="mymenu"><img src="../../assets/img/shiroishi.png" alt="">マイメニュー</h3>
            <ul class="my-menu">
                <li class="handle"><a href="passEdit.php">
                    <span class="material-symbols-outlined">lock_clock</span>パスワード変更</li></a> 
                <li class="handle"><a href="logout.php">
                    <span class="material-symbols-outlined">logout</span>ログアウト</li></a> 
                <li class="handle"><a href="withdraw.php">
                    <span class="material-symbols-outlined">delete</span>囲碁部ノートアカウント消去</li></a>
            </ul>
        </div>        
        <div class="game-record">
            <h3><img src="../../assets/img/kuroishi.png" alt="">書き込み</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <table class="record-note">
                    <tr>
                        <td class="record-td">対局日</td>
                        <td class="record-td"><input type="date" name="game_date"></td>
                    </tr>
                    <tr>
                        <td class="record-td">黒</td>
                        <td class="record-td"><input type="text" name="player_black" value="名前"></td>
                        <td class="record-td"><input type="text" name="agehama-black" value="アゲハマ"></td>
                        <td class="record-td">白</td>
                        <td class="record-td"><input type="text" name="player_white" value="名前"></td>
                        <td class="record-td"><input type="text" name ="agehama-white" value="アゲハマ"></td>
                    </tr>
                    <tr>
                        <td class="record-td">コミ</td>
                        <td class="record-td" colspan="2"><input type="text" name="komi"></td>
                        <td class="record-td"> 結果</td>
                        <td class="record-td" colspan="2"><input type="text" name="outcome"></td>
                    </tr>
                    <tr>
                        <td class="record-td gameRecPic" colspan="4"><input type="file" name="game_pic1" value="棋譜１"></td> 
                        <td class="record-td" colspan="2"><input type="textarea" name="comment1" value="コメント１"></td>
                    </tr>

                </table>
            </form>
            <h3><img src="../../assets/img/kuroishi.png" alt="">対局記録</h3>
    
        </div>

    </main>
</section>
<!-- フッター -->

<?php
require('footer.php');
?>
</body>
</html>
