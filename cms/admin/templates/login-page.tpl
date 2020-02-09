
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>InnyCMS :: System Administrator Dashboard Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/login.css" rel="stylesheet">
</head>

<body>
<form class="form-signin" action="login" method="post">
    <div class="text-center mb-4">
        <img class="mb-4" src="images/inny-logo.svg" alt="InnyCMS" height="72px">
        <h1 class="h3 mb-3 font-weight-normal">System Administrator Dashboard</h1>
    </div>

    <div class="form-label-group">
        <input class="form-control" id="username" type="text" placeholder="Username" name="username" autocomplete="off" required autofocus>
        <label for="username">Username</label>
    </div>

    <div class="form-label-group">
        <input class="form-control" type="password" placeholder="Password" name="password" autocomplete="current-password" required>
        <label for="password">Password</label>
    </div>

    <div class="checkbox mb-3">
        <label><input type="checkbox" name="remember" /> Remember me</label>
    </div>

    <input type="hidden" name="action" value="login" />
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-muted text-center">InnyCMS &copy; 2007 - {$smarty.now|date_format:"%Y"}</p>
</form>
</body>
</html>
