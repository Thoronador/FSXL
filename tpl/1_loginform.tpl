<-- if user_loggedin -->
<-- else user_loggedin -->
<div class="menucat">LOGIN</div>
<div class="menulogin">
	<form action="" method="post">
		Username:
		<input class="textinput" name="username" style="width:150px;">
		Passwort:
		<input class="textinput" name="userpass" type="password" style="width:150px; margin-bottom:5px;">
		angemeldet bleiben:
		<input type="checkbox" name="staylogged"><br>
		<input type="submit" class="button" value="login" style="width:50px; margin-left:105px;; margin-bottom:5px;">
		<a href="?section=register">Noch nicht registriert? Klicke <u>hier</u></a>
	</form>
</div>
<p>
<-- /if user_loggedin -->