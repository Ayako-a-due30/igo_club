<?php
    
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    require('../function/function.php');
    $u_id = $_SESSION['user_id'];


    // ////棋譜アップロード

if((!empty($_POST) || ($_FILES))){
    
    $game_date=$_POST['game_date'];
    $player_black = $_POST['player_black'];
    $agehama_black = $_POST['agehama_black'];
    $player_white = $_POST['player_white'];
    $agehama_white = $_POST['agehama_white'];
    $komi = $_POST['komi'];
    $outcome = $_POST['outcome']; 
    $comment1= $_POST['comment1'];
    $comment2 = $_POST['comment2'];
    $comment3 = $_POST['comment3'];
    $game_pic1 = (!empty($_FILES['game_pic1']['name'])) ? uploadImg($_FILES['game_pic1'],'game_pic1'):'';
    $game_pic1 = (empty($game_pic1) && !empty($dbFormData['game_pic1']) )? $dbFormData['game_pic1']: $game_pic1;
    $game_pic2 = (!empty($_FILES['game_pic2']['name'])) ? uploadImg($_FILES['game_pic2'],'game_pic2'):'';
    $game_pic2 = (empty($game_pic2) && !empty($dbFormData['game_pic2']) )? $dbFormData['game_pic2']: $game_pic2;
    $game_pic3 = (!empty($_FILES['game_pic3']['name'])) ? uploadImg($_FILES['game_pic3'],'game_pic3'):'';
    $game_pic3 = (empty($game_pic3) && !empty($dbFormData['game_pic3'])) ? $dbFormData['game_pic3']: $game_pic3;     
    

        try{
            $dbh = dbConnect();
            $sql='INSERT INTO 
            `record`
            (game_date,player_black,agehama_black,player_white,agehama_white,komi,outcome,game_pic1,comment1,
            game_pic2,comment2,game_pic3,comment3,user_id)
            VALUES
            (:game_date,:player_black,:agehama_black,:player_white,:agehama_white,:komi,:outcome,:game_pic1,:comment1,
            :game_pic2,:comment2,:game_pic3,:comment3,:user_id)';
            $data = array(
                ':game_date'=>$game_date,
                ':player_black'=>$player_black,
                ':agehama_black'=>$agehama_black,
                ':player_white'=>$player_white,
                ':agehama_white'=>$agehama_white,
                ':komi'=>$komi,
                ':outcome'=>$outcome,
                ':game_pic1'=>$game_pic1,
                ':comment1'=>$comment1,
                ':game_pic2'=>$game_pic2,
                ':comment2'=>$comment2,
                ':game_pic3'=>$game_pic3,
                ':comment3'=>$comment3,
                ':user_id'=>$u_id);
                //クエリ実行
                debug('流し込みデータ：'.print_r($data));
                $stmt = queryPost($dbh,$sql,$data);
                if($stmt){
                    $_SESSION['msg_success']= SUC04;
                    // header("Location:record.php");
                }
            }catch(Exception $e){
                $err_msg['common']=MSG06;
                
            }
        }



    $getRecordList = getRecord($u_id);

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
                こんにちは、<?php echo(getUser($u_id)['nickname']); ?>さん。
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
        <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="record-note">
                <tr class="record-tr">
                    <td class="record-td">対局日</td>
                    <td class="record-td"><input type="date" name="game_date"></td>
                </tr>
                <tr class="record-tr">
                    <td class="record-td">黒</td>
                    <td class="record-td"><input type="text" name="player_black" placeholder="名前"></td>
                    <td class="record-td"><input type="text" name="agehama_black" placeholder="アゲハマ"></td>
                    <td class="record-td">白</td>
                    <td class="record-td"><input type="text" name="player_white" placeholder="名前"></td>
                    <td class="record-td"><input type="text" name ="agehama_white" placeholder="アゲハマ"></td>
                </tr>
                <tr class="record-tr">
                    <td class="record-td">コミ</td>
                    <td class="record-td" colspan="2"><input type="text" name="komi"></td>
                    <td class="record-td"> 結果</td>
                    <td class="record-td" colspan="2"><input type="text" name="outcome"></td>
                </tr>
                <tr class="record-tr">
                    <td class="record-td gameRecPic" colspan="4"><input type="file" name="game_pic1" placeholder="棋譜１"></td> 
                    <td class="record-td" colspan="2"><input type="textarea" name="comment1" placeholder="コメント１"></td>
                </tr>
                <tr class="record-tr">
                    <td class="record-td gameRecPic" colspan="4"><input type="file" name="game_pic2" placeholder="棋譜２"></td> 
                    <td class="record-td" colspan="2"><input type="textarea" name="comment2" placeholder="コメント２"></td>
                </tr>
                <tr class="record-tr">
                    <td class="record-td gameRecPic" colspan="4"><input type="file" name="game_pic3" placeholder="棋譜３"></td> 
                    <td class="record-td" colspan="2"><input type="textarea" name="comment3" placeholder="コメント３"></td>
                </tr>

                <tr class="record-tr">
                    <td colspan="6"><input type="submit" value="記録する"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="showNote">
        <h3><img src="../../assets/img/kuroishi.png" alt="">対局記録</h3>
                <?php
                $counter = 0; 
                foreach($getRecordList as $key => $val): ?>
        <div class="showRecord">
            <!-- ループ内容 -->
            日時：<?php echo $val["game_date"]; ?><br>
            黒：<?php echo $val["player_black"]; ?><br>
            白：<?php echo $val["player_white"]; ?><br>
            白アゲハマ：<?php echo $val["agehama_white"]; ?><br>
            黒アゲハマ：<?php echo $val["agehama_black"]; ?><br>
            コミ：<?php echo $val ["komi"];?><br>
            結果：<?php echo $val["outcome"]; ?><br>
                <img src="<?php echo showImg(sanitize($val["game_pic1"])); ?>" alt="" class="RecordPic"><br>
                コメント：<?php echo $val["comment1"]; ?><br>
                <img src="<?php echo showImg(sanitize($val["game_pic2"])); ?>" alt="" class="RecordPic"><br>
                コメント：<?php echo $val["comment2"]; ?><br>
                <img src="<?php echo showImg(sanitize($val["game_pic3"])); ?>" alt="" class="RecordPic"><br>
                コメント：<?php echo $val["comment3"]; ?><br>
        </div>
        <?php 
                if($counter >= 2){break;} 
                $counter++; 
            endforeach; ?>
    </div>
</main>
<div class="goNote">
    <a href="record.php" >記録ページを見る</a>
</div>
</section>
<!-- フッター -->

<?php
require('footer.php');
?>
</body>
</html>
