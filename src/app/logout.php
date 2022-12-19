<?php 
require('../function/function.php');
debug('「「「「「「「「「「「「「「');
debug('「「「ログアウトページ');
debug('「「「「「「「「「「「「「「');
debugLogStart();

debug('ログアウトします');
session_destroy();
header("Location:login.php");
?>