<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/layout.css" rel="stylesheet" type="text/css">
    <link href="css/login_register.css" rel="stylesheet" type="text/css">
	<link href="css/components.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
		<div class="left">

		</div>
		<div class="right">
			<h1>Create a new account</h1>
			<p>Please complete the fields below to create an account.</p>

			<form method="post" action="login.php">
				<label for="username">
                    <p>Username:</p>
                </label>
				<input type="text" id="username" name="username" required>

				<label for="username">
                    <p>Email:</p>
                </label>
				<input type="text" id="username" name="username" required>

				<label for="password">
                    <p>Password:</p>
                </label>
				<input type="password" id="password" name="password" required>

				<label for="password">
                    <p>Confirm password:</p>
                </label>
				<input type="password" id="password" name="password" required>

                <br>
				<input type="submit" value="Create account">
			</form>

            <p>If you already have an account, <a href="/login.php">sign in here</a>.</p>
		</div>
	</div>
</body>
</html>
