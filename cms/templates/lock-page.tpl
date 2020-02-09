<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>InnyCMS :: {InnyCMS::getSiteName()}</title>
    <base href="./" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>WebFont.load({ google: { "families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"] }, active: function() { sessionStorage.fonts = true; } });</script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="css/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="css/login.css" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#f4516c">
    <meta name="apple-mobile-web-app-title" content="InnyCMS">
    <meta name="application-name" content="InnyCMS">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128379244-1"></script>
    <script>window.dataLayer = window.dataLayer || [];function gtag(){ dataLayer.push(arguments); }gtag('js', new Date());gtag('config', 'UA-128379244-1');</script>
</head>
<!-- end::Head -->
<!-- end::Body -->
<body  class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--locked" id="m_login">
        <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
            <div class="m-stack m-stack--hor m-stack--desktop">
                <div class="m-stack__item m-stack__item--fluid">
                    <div class="m-login__wrapper">
                        <div class="m-login__logo"><a href="./" title="InnyCMS"><img src="./images/inny-logo.svg" class="logo" alt="InnyCMS"></a></div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                                <div class="m-widget4">
                                    <!--begin::Widget 14 Item-->
                                    <div class="m-widget4__item">
                                        <div class="m-widget4__img m-widget4__img--pic">
                                            <img src="{InnyCMS::getUserProperty("email")|gravatar:100}" alt="{InnyCMS::getUserProperty("lastname")} {InnyCMS::getUserProperty("name")}">
                                        </div>
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__title">{'Locked User'|_t}</span>
                                            <br/>
                                            <span class="m-widget4__sub">{InnyCMS::getUserProperty("lastname")}, {InnyCMS::getUserProperty("name")}</span>
                                        </div>
                                    </div>
                                    <!--end::Widget 14 Item-->
                                </div>
                            </div>
                            <form class="m-login__form m-form" action="">
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input" type="password" placeholder="{'Password'|_t}" name="password" autocomplete="current-password" autofocus="autofocus">
                                </div>
                                <div class="row m-login__form-sub">
                                    <div class="col m--align-right">
                                        <a href="./logout" class="m-link m-link--danger">
                                            {"Not <b>%s</b> ?"|_t:InnyCMS::getUserProperty("name")}
                                        </a>
                                    </div>
                                </div>
                                <div class="m-login__form-action">
                                    <input type="hidden" name="action" value="unlock"/>
                                    <button id="m_login_locked_submit" class="btn btn-danger  m-btn m-btn--pill m-btn--custom m-btn--air">{'Sign In'|_t}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content m-grid-item--center" style="background-image: url('images/login-background.jpg')">
            <div class="m-grid__item">
                <h3 class="m-login__welcome">{InnyCMS::getSiteName()}</h3>
                <p class="m-login__msg">{'Login to your InnyCMS Admin Panel.'|_t}</p>
            </div>
        </div>
    </div>
</div>

<!-- end:: Page -->
<!--begin::Base Scripts -->
<script src="js/vendors.bundle.js" type="text/javascript"></script>
<script src="js/scripts.bundle.js" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Snippets -->
<script src="js/login.js" type="text/javascript"></script>
<!--end::Page Snippets -->
</body>
<!-- end::Body -->
</html>