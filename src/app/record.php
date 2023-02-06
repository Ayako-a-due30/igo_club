<?php
    error_reporting(E_ALL);
    ini_set('display_errors','On');

    require('../function/function.php');

    $word = '';
    $start_date='';
    $end_date='';
    $getRecordList = '';
    $wordRec = '';
    $dateRec = '';
    $game_id = '';

    $u_id = $_SESSION['user_id'];
//検索
    if(empty($_GET)){
        $getRecordList = getRecord($u_id);
    }elseif(!empty($_GET['word'] && $_GET['start_date']&& $_GET['end_date'])){
        //ワードと期間の指定
        $word = $_GET['word'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $getRecordList = searchKifuWordDate($word,$start_date, $end_date);
    }elseif((empty($_GET['word'])) && (!empty($_GET['start_date']) && ($_GET['end_date']))){
        //期間だけの指定
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $getRecordList = searchKifuDate($start_date,$end_date);
    }elseif(!empty ($_GET['word'])){
        //ワードだけの指定
        $word = $_GET['word'];
        $getRecordList = searchKifuWord($word);
    }
//削除
if (!empty($_POST)){
    $game_id = array_key_first($_POST);
    deleteFlgOn($u_id, $game_id);
    header("Location: " . $_SERVER['PHP_SELF']);
}
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
    <div class="recpage-wrap">
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
            <form action="" method="get">
                <table class="rec_search">
                    <tr>
                        <td>検索ワード</td>
                        <td class="td_search"><input type="text" name="word"></td>
                    </tr>
                    <tr>
                        <td>日時検索</td>
                        <td class="td_search"><input type="date" name="start_date">から</td>
                        <td class="td_search"><input type="date" name="end_date">まで</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <input type="submit" value="検索">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="RecordArea">
            <h3><img src="../../assets/img/kuroishi.png" alt="">対局記録</h3>
            <pre>
            </pre>
            <?php foreach($getRecordList as $key => $val): ?>
                <div class="ViewRecord">
                    日時：<?php echo $val["game_date"]; ?><br>
                    黒：<?php echo $val["player_black"]; ?><br>
                    白：<?php echo $val["player_white"]; ?><br>
                    白アゲハマ：<?php echo $val["agehama_white"]; ?><br>
                    黒アゲハマ：<?php echo $val["agehama_black"]; ?><br>
                    コミ：<?php echo $val ["komi"]; ?><br>
                    結果：<?php echo $val["outcome"]; ?><br>
                    <img src="<?php echo showImg(sanitize($val["game_pic1"])); ?>" alt="" class="RecPic"><br>
                    コメント：<?php echo $val["comment1"]; ?><br>
                    <img src="<?php echo showImg(sanitize($val["game_pic2"])); ?>" alt="" class="RecPic"><br>
                    コメント：<?php echo $val["comment2"]; ?><br>
                    <img src="<?php echo showImg(sanitize($val["game_pic3"])); ?>" alt="" class="RecPic"><br>
                    コメント：<?php echo $val["comment3"]; ?><br>
                    <form action="" method="post" class="delete_btn">
                        <?php $game_id = $val["game_id"]; ?>
                            <input type="submit" value="削除" name="<?php echo $val["game_id"]; ?>" class= >
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
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
