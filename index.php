<?php 
// Including the database function
include './resources/includes/databasefunc.inc';
$login_form = is_logged(); 
if ($login_form){
	header('location: character.php');
}
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="resources/css/pure.css" />

<style>

.containter{
position: absolute;
width: 100%;
   	background:  rgb(0, 120, 231);
text-align:center;
}


.pure-menu-link:hover {
    background-color: #006ED4;
}

.pure-menu-link{
	font-size:100px;  
	font-size:11vmin;
	color:rgb(255, 255, 255);
	text-align:center;
}


img{
position: relative;
max-width:100%;
}


.mof1{
  display: block;
    margin-left: auto;
    margin-right: auto;
width: 20%;
}
body{
	   	background:  rgb(0, 120, 231);
}
</style>
<body>
<div class="containter"><div class="pure-menu">
<ul class="pure-menu-list">
<li class="pure-menu-item">
<div class="mof1">
<div class="image"><img src="https://www.namepros.com/styles/default/xenforo/avatars/avatar_m.png">
</div></div>
        <li class="pure-menu-item"><a href="/login.php" class="pure-menu-link">Play</a></li>
        <li class="pure-menu-item"><a href="#" class="pure-menu-link">News</a></li>
        <li class="pure-menu-item"><a href="#" class="pure-menu-link">Contact Us</a></li>


</ul>
</div>
</div>
</body>
</html>

