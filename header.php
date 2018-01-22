<?php
echo
    '<header>
	<div class="usercont">
		<p>Eingeloggt als:</p>
		<div class="username">
			&lt;' . $_SESSION["username"] . ' <br> permission: ' . $_SESSION["permission"] . '&gt;
		</div>
	</div>
	<div class="logocont">
		<img src="img/logo.png" class="logo_small">';

if (isset($_SESSION['username']))
    echo '<a href="?controller=Login&do=logoutUser"> <input type="button" id="logout" class="button_m" value="Logout"> </a>';
echo
'</div>
	<div style="clear: both">
	</div>
</header>';
?>