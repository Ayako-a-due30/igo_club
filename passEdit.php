<?php
require('head.php');
?>
<?php
require('header.php');
?>
    <section class="pass_edit">
        <h2><img src="shiroishi.png" alt="">パスワード変更</h2>
        <form action="post" class="pass_edit_form">
            <table class="registerTable">
                <?php if(!empty($_POST)) echo $_POST['oldPass']; ?>
                <label for="oldPass">
                    <tr>
                        <td>旧パスワード</td><td><input type="password" name="oldPassword"></td>
                    </tr>
                </label>
                <label for="newPass">
                    <tr>
                        <td>新パスワード</td> <td><input type="password" name="newPassword"></td>
                    </tr>
                </label>
                <label for="reNewPass">
                    <tr>
                        <td>新パスワード（再入力）</td><td><input type="password" name="reNewPassword"></td>
                    </tr>
                </label>
                    <tr>
                        <td colspan="2"><input type="submit" value="変更"></td>
                    </tr>
            </table>
        </form>
    </section>
<?php
require('footer.php');
?>
</body>
</html>