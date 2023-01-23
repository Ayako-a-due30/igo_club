<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');

require('../function/function.php');
$u_id = $_SESSION['user_id'];

    $getRecordList = getRecord($_SESSION['user_id']);
?>
<?php
$siteTitle='囲碁部ノート|記録ページ：';
require('head.php');
?>
<body>

<?php
require('header.php');
?>
<!-- メインコンテンツ -->
<section class="site-width">
    <main class="recpage-wrap">
    <div class="bar">
        <h3 class="mymenu"><img src="../../assets/img/shiroishi.png" alt="">記録ページ</h3>
            <ul class="my-menu">
                <li class="handle"><a href="passEdit.php">
                    <span class="material-symbols-outlined">lock_clock</span>パスワード変更</li></a> 
                <li class="handle"><a href="logout.php">
                    <span class="material-symbols-outlined">logout</span>ログアウト</li></a> 
                <li class="handle"><a href="withdraw.php">
                    <span class="material-symbols-outlined">delete</span>囲碁部ノートアカウント消去</li></a>
            </ul>
    </div>        
    <div class="showNote">
        <h3><img src="../../assets/img/kuroishi.png" alt="">対局記録</h3>

        <form action="" method="post">
            <table class="rec_search">
                <tr>
                    <td>検索ワード</td>
                    <td><input type="text"></td>
                    <td>日時検索</td>
                    <td><input type="date"></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <input type="submit" value="検索">
                    </td>
                </tr>
            </table>
        </form>
                <?php foreach($getRecordList as $key => $val): ?>
        <div class="ViewRecord">
            日時：<?php echo $val["game_date"]; ?><br>
            黒：<?php echo $val["player_black"]; ?><br>
            白：<?php echo $val["player_white"]; ?><br>
            白アゲハマ：<?php echo $val["agehama_white"]; ?><br>
            黒アゲハマ：<?php echo $val["agehama_black"]; ?><br>
            コミ：<?php echo $val ["komi"] ?><br>
            結果：<?php echo $val["outcome"]; ?><br>
            <div class="RecordPic">
                <img src="<?php echo $val["game_pic1"] ?>" alt="" class="RecPic"><br>
                コメント：<?php echo $val["comment1"] ?><br>
                <img src="<?php echo $val["game_pic2"] ?>" alt="" class="RecPic"><br>
                コメント：<?php echo $val["comment2"] ?><br>
                <img src="<?php echo $val["game_pic3"] ?>" alt="" class="RecPic"><br>
                コメント：<?php echo $val["comment3"] ?><br>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    </main>
    <div class="goNote">
        <a href="mypage.php" >マイページへ</a>
    </div>
</section>
<!-- フッター -->

<?php
require('footer.php');
?>
</body>
</html>
