<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/layout.css" rel="stylesheet" type="text/css">
    <link href="css/login_register.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
		<div class="left">

		</div>
		<div class="right">
            <h1>Welcome Back!</h1>
			<p>Please enter your username and password to login.</p>
			
			<form method="post" action="login.php">
				<label for="username">
                    <p>Username:</p>
                </label>
				<input type="text" id="username" name="username" required>

				<label for="password">
                    <p>Password:</p>
                </label>
				<input type="password" id="password" name="password" required>

                <br>
				<input type="submit" value="Login">
			</form>

            <p>If you don't have an account, <a href="/register.php">sign up here</a>.</p>
		</div>
	</div>
</body>
</html>
