<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#f4516c">
    <meta name="apple-mobile-web-app-title" content="InnyCMS">
    <meta name="application-name" content="InnyCMS">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <title>InnyCMS :: Install a New Instance of InnyCMS</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
</head>

<body>
{if !$installed}
    <form class="form-signin" action="./" method="post">
        <div class="text-center mb-4">
            <img class="mb-4" src="images/inny-logo.svg" alt="InnyCMS" height="72px">
            <h1 class="h3 mb-3 font-weight-normal">Install a New Instance of InnyCMS</h1>
            {if $error}<strong class="text-danger">There was a problem with the params provided. Please fill in the form again.</strong>{/if}
        </div>

        <b>MySQL Settings</b>
        <div class="form-label-group">
            <input class="form-control" id="username" type="text" placeholder="MySQL Username" name="username" autocomplete="off" required autofocus>
            <label for="username">MySQL Username</label>
        </div>

        <div class="form-label-group">
            <input class="form-control" type="password" placeholder="MySQL Password" name="password" autocomplete="current-password">
            <label for="password">MySQL Password</label>
        </div>

        <div class="form-label-group">
            <input class="form-control" id="databasename" type="text" placeholder="MySQL Database Name" name="databasename" autocomplete="off" required>
            <label for="username">MySQL Database Name</label>
        </div>

        <div class="form-label-group">
            <input class="form-control" id="databasehost" type="text" placeholder="MySQL Database Host" name="databasehost" autocomplete="off" value="localhost" required>
            <label for="username">MySQL Database Host</label>
        </div>

        <b>InnyCMS Settings</b>
        <div class="form-label-group">
            <input class="form-control" id="adminusername" type="text" placeholder="Admin Username" name="adminusername" autocomplete="off" required>
            <label for="username">Admin Username</label>
        </div>

        <div class="form-label-group">
            <input class="form-control" type="password" placeholder="Admin Password" name="adminpassword" autocomplete="current-admin-password" required>
            <label for="password">Admin Password</label>
        </div>

        <div class="form-label-group">
            <input class="form-control" type="password" placeholder="Confirm Admin Password" name="confirmadminpassword" autocomplete="current-admin-password" required>
            <label for="password">Confirm Admin Password</label>
        </div>

        <input type="hidden" name="action" value="install" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Install</button>
        <p class="mt-5 mb-3 text-muted text-center">InnyCMS &copy; 2007 - {$smarty.now|date_format:"%Y"}</p>
    </form>
{else}
    <form class="form-signin">
        <div class="text-center mb-4">
            <img class="mb-4" src="images/inny-logo.svg" alt="InnyCMS" height="72px">
            <h1 class="h3 mb-3 font-weight-normal">Thanks for installing InnyCMS</h1>
        </div>
    </form>
    <script>setTimeout(function(){ window.location.href = '../cms' },2500);</script>
{/if}
</body>
</html>
