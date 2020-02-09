<!-- BEGIN: Header -->
<header id="m_header" class="m-grid__item    m-header "  m-minimize-offset="200" m-minimize-mobile-offset="200" >
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-dark ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        {$customLogoPath = InnyCMS::getCustomizationPath('images')|cat:"/cms-logo.png"}
                        {if file_exists($customLogoPath)}
                            {$customLogoPath = str_replace("web/","",$customLogoPath)}
                            <a href="./" class="m-brand__logo-wrapper"><img src="{$customLogoPath}" alt="InnyCMS" height="30px"/></a>
                        {else}
                            <a href="./" class="m-brand__logo-wrapper"><img src="./images/inny-logo.svg" alt="InnyCMS" height="30px"/></a>
                        {/if}
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block"><span></span></a>
                        <!-- END -->
                        <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block"><span></span></a>
                        <!-- END -->
                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block"><i class="flaticon-more"></i></a>
                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>
            <!-- END: Brand -->
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-topbar__userpic">
                                        <img src="{InnyCMS::getUserProperty("email")|gravatar:40}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                    </span>
                                    <span class="m-topbar__username text-dark text-capitalize m--padding-5">
                                        {InnyCMS::getUserProperty("lastname")}, {InnyCMS::getUserProperty("name")}<i class="m--padding-left-5 fa fa-caret-down"></i>
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center" style="background: url('images/login-background.jpg'); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">
                                                    <img src="{InnyCMS::getUserProperty("email")|gravatar:40}" class="m--img-rounded m--marginless" alt=""/>
                                                </div>
                                                <div class="m-card-user__details">
                                                <span class="m-card-user__name m--font-weight-500">
                                                    {InnyCMS::getUserProperty("username")}
                                                </span>
                                                    <a class="m-card-user__email m--font-weight-300 m-link">
                                                        {InnyCMS::getUserProperty("email")}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__section m--hide">
                                                    <span class="m-nav__section-text">
                                                        {'Section'|_t}
                                                    </span>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="./profile" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-profile"></i>
                                                            <span class="m-nav__link-title">
                                                            <span class="m-nav__link-wrap">
                                                                <span class="m-nav__link-text">
                                                                    {'Profile'|_t}
                                                                </span>
                                                            </span>
                                                        </span>
                                                        </a>
                                                    </li>
                                                    {if count(InnyCMS::getUserSites()) > 1}
                                                        <li class="m-nav__item">
                                                            <a href="./choose" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                                <span class="m-nav__link-text">{'Sites'|_t}</span>
                                                            </a>
                                                        </li>
                                                    {/if}
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    {if !empty(InnyCMS::getSiteUrl())}
                                                        <li class="m-nav__item">
                                                            <a href="{InnyCMS::getSiteUrl()}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-web"></i>
                                                                <span class="m-nav__link-text">{'Visit My Site'|_t}</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    {/if}
                                                    <li class="m-nav__item">
                                                        <a href="./lock" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-lock"></i>
                                                            <span class="m-nav__link-text">
                                                            {'Lock'|_t}
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="./logout" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-logout"></i>
                                                            <span class="m-nav__link-text">
                                                            {'Logout'|_t}
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        {include file="language.tpl"}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Topbar -->
            </div>
        </div>
    </div>
</header>
<!-- END: Header -->
