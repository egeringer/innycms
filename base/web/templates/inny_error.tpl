<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>InnyCMS :: {$message}</title>
    <base href="{Denko::getBaseHref()}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>WebFont.load({ google: { "families":["Poppins:300,600"] }, active: function() { sessionStorage.fonts = true; } });</script>
    <!--end::Web font -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#f4516c">
    <meta name="apple-mobile-web-app-title" content="InnyCMS">
    <meta name="application-name" content="InnyCMS">
    <meta name="msapplication-TileColor" content="#ffffff">
    <style type="text/css"> body,html{ margin:0;font-family:Poppins;height:100%;padding:0;font-size:14px;font-weight:300;-ms-text-size-adjust:100%;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale } body{ display:flex;flex-direction:column;font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff } .m-error_container .m-error_desc,.m-error_container .m-error_number>h1{ color:#f4516c } .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile).m-grid--root{ flex:1;-ms-flex:1 0 0px } .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile){ display:flex;display:-ms-flexbox;flex-direction:column;-ms-flex-direction:column } .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile)>.m-grid__item.m-grid__item--fluid{ flex:1 0 auto;-ms-flex:1 0 auto } .m-grid.m-grid--hor:not(.m-grid--desktop):not(.m-grid--desktop-and-tablet):not(.m-grid--tablet):not(.m-grid--tablet-and-mobile):not(.m-grid--mobile)>.m-grid__item{ flex:none;-ms-flex:none } .m-error-1{ background-position:center;background-repeat:no-repeat;background-attachment:fixed;background-size:cover } .m-error-1 .m-error_container .m-error_number>h1{ margin:9rem 0 0 80px;font-size:150px;font-weight:600 } .m-error-1 .m-error_container .m-error_desc{ padding-right:.5rem;font-size:1.5rem;margin-left:80px } p{ margin-top:0;margin-bottom:1rem } @media (max-width:768px){ body,html{ font-size:13px } } </style>
    <meta name="theme-color" content="#ffffff">
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-error-1" style="background-image: url('{Denko::getBaseHref()}images/inny-background.jpg');">
        <div class="m-error_container">
            <span class="m-error_number"><h1>{$errorCode|default:"InnyCMS"}</h1></span>
            <p class="m-error_desc">{$message}</p>
        </div>
    </div>
</div>
<!-- end:: Page -->
</body>
<!-- end::Body -->
</html>