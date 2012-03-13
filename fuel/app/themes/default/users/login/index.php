<form action="/users/login" method="post" accept-charset="utf-8" id="login" class="form">

	<h2 class="page-title" id="page_title">Login</h2>

	<div style="display:none">
		<input type="hidden" name="redirect_to" value="<?php \Session::get('auth.redirect_to', ''); ?>" />
	</div>

	<?php echo $fieldset->build(); ?>

		<li class="form_buttons">
			<button type="submit" value="Login" name="btnSubmit" title="Login using email address and password">Login</button>
			| <a href="/users/register">Register</a>
			| <a href="/users/reset">Forgot your password?</a>
		</li>
	</ul>

	<p>&nbsp;</p>
	<h2>Or login using</h2>

	<ul>
		<li class="form_buttons">
			<button type="submit" formnovalidate="formnovalidate" value="Github" name="btnSubmit" title="Login using your Github account" class="button-github">Github</button>
			<button type="submit" formnovalidate="formnovalidate" value="Facebook" name="btnSubmit" title="Login using your Facebook account" class="button-facebook">Facebook</button>
			<button type="submit" formnovalidate="formnovalidate" value="Twitter" name="btnSubmit" title="Login using your Twitter account" class="button-twitter">Twitter</button>
			<button type="submit" formnovalidate="formnovalidate" value="Google+" name="btnSubmit" title="Login using your Google+ account" class="button-google">Google+</button>
		</li>
	</ul>

	<p class="critical_box" style="width:400px;">
		Please note that at the moment we require a Github login to get write access to the documentation.
		Contact the administrator if you don't have one but would like to help documenting FuelPHP.
	</p>
</form>
