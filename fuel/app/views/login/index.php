<form action="http://fuelphp.com/users/login" method="post" accept-charset="utf-8" id="login">

	<h2 class="page-title" id="page_title">Login</h2>

	<div style="display:none">
		<input type="hidden" name="redirect_to" value="" />
	</div>
	<ul>
		<li>
			<label for="email">E-mail</label>
			<input type="text" id="email" name="email" maxlength="120" />
		</li>
		<li>
			<label for="password">Password</label>
			<input type="password" id="password" name="password" maxlength="20" />
		</li>
		<li id="remember_me">
			<input type="checkbox" name="remember" value="1"  />Remember Me
		</li>
		<li class="form_buttons">
			<input type="submit" value="Login" name="btnLogin" /> | <a href="http://fuelphp.com/register">Register</a>
		</li>
		<li>
			<a href="http://fuelphp.com/users/reset_pass">Forgot your password?</a>
		</li>
	</ul>
</form>
