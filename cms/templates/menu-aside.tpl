<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">

            {if InnyCMS::isLoggedSite()}
                <li class="m-menu__item {if $section|default:"" == "dashboard"}m-menu__item--active{/if}" aria-haspopup="true" >
                    <a  href="./" class="m-menu__link ">
                        <i class="m-menu__link-icon flaticon-squares-4"></i>
                        <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                {'Dashboard'|_t}
                            </span>
                        </span>
                    </span>
                    </a>
                </li>

                {if isset($sidebarUI) && !empty($sidebarUI)}
                    {foreach $sidebarUI as $key => $val}
                        {if $val.type == "text"}
                            {include file="menu-aside-text.tpl" item=$val}
                        {else}
                            {if sizeof($val.collection) == 1}
                                {include file="menu-aside-single.tpl" item=$val}
                            {else}
                                {include file="menu-aside-group.tpl" item=$val}
                            {/if}
                        {/if}
                    {/foreach}
                {/if}

                {if InnyCMS::checkPermission("bucket")}
                    <li class="m-menu__item {if $section|default:"" == "bucket"}m-menu__item--active{/if}" aria-haspopup="true" >
                        <a href="./bucket" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-folder"></i>
                            <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                                <span class="m-menu__link-text">
                                    Bucket
                                </span>
                            </span>
                        </span>
                        </a>
                    </li>
                {/if}

                <li class="m-menu__section">
                    <h4 class="m-menu__section-text">{'Site'|_t}</h4>
                </li>

                {if !empty(InnyCMS::getSiteUrl())}
                    <li class="m-menu__item" aria-haspopup="true" >
                        <a href="{InnyCMS::getSiteUrl()}" class="m-menu__link" target="_blank">
                            <i class="m-menu__link-icon flaticon-web"></i>
                            <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                                <span class="m-menu__link-text">
                                    {'Visit Site'|_t}
                                </span>
                            </span>
                        </span>
                        </a>
                    </li>
                    <li class="m-nav__separator m-nav__separator--fit"></li>
                {/if}
            {/if}

            <li class="m-menu__section">
                <h4 class="m-menu__section-text">{'User'|_t}</h4>
            </li>

            <li class="m-menu__item {if $section == "profile"}m-menu__item--active{/if}" aria-haspopup="true" >
                <a  href="./profile" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-profile"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                {'Profile'|_t}
                            </span>
                        </span>
                    </span>
                </a>
            </li>

            {if count(InnyCMS::getUserSites()) > 1}
                <li class="m-menu__item {if $section == "choose"}m-menu__item--active{/if}" aria-haspopup="true" >
                    <a href="./choose" class="m-menu__link ">
                        <i class="m-menu__link-icon flaticon-share"></i>
                        <span class="m-menu__link-title">
                            <span class="m-menu__link-wrap">
                                <span class="m-menu__link-text">
                                    {'Sites'|_t}
                                </span>
                            </span>
                        </span>
                    </a>
                </li>
            {/if}

            <li class="m-menu__item" aria-haspopup="true" >
                <a  href="./lock" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-lock"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                {'Lock'|_t}
                            </span>
                        </span>
                    </span>
                </a>
            </li>

            <li class="m-menu__item" aria-haspopup="true" >
                <a  href="./logout" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-logout"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                {'Logout'|_t}
                            </span>
                        </span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
