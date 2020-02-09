<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light " data-menu-vertical="true" m-menu-scrollable="true" m-menu-dropdown-timeout="500">
    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
        {*************************************************************************************}
        {*************************************** ADD *****************************************}
        {*************************************************************************************}
        <li class="m-menu__item m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="click" m-menu-link-redirect="1">
            <a href="javascript:;" class="m-menu__link m-menu__toggle"><i class="m-menu__link-icon flaticon-add"></i><span class="m-menu__link-text">Add</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
            <div class="m-menu__submenu ">
                <span class="m-menu__arrow"></span>
                <ul class="m-menu__subnav">
                    <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" m-menu-link-redirect="1"><span class="m-menu__link"><span class="m-menu__link-text">Add</span></span></li>
                    <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("add","user")}" class="m-menu__link "><i class="m-menu__link-icon flaticon-users"></i><span class="m-menu__link-text">User</span></a></li>
                    <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("add","site")}" class="m-menu__link "><i class="m-menu__link-icon flaticon-responsive"></i><span class="m-menu__link-text">Site</span></a></li>
                    <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("add","collection")}" class="m-menu__link "><i class="m-menu__link-icon flaticon-squares-4"></i><span class="m-menu__link-text">Collection</span></a></li>
                </ul>
            </div>
        </li>
        {*************************************************************************************}
        {*************************************** SITES ***************************************}
        {*************************************************************************************}
        <li class="m-menu__item  m-menu__item--submenu m-menu__item--submenu-fullheight" aria-haspopup="true" m-menu-submenu-toggle="click" m-menu-dropdown-toggle-class="m-aside-menu-overlay--on">
            <a href="javascript:;" class="m-menu__link m-menu__toggle">
                <i class="m-menu__link-icon flaticon-responsive"></i><span class="m-menu__link-text">Sites</span><i class="m-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                <div class="m-menu__wrapper">
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent m-menu__item--submenu-fullheight" aria-haspopup="true"><span class="m-menu__link"><span class="m-menu__link-text">Sites</span></span></li>
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text">Actions</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("list","site")}" class="m-menu__link"><span class="m-menu__link-text">List all sites</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("add","site")}" class="m-menu__link"><span class="m-menu__link-text">Add new site</span></a></li>

                        {$daoSite = Denko::daoFactory('innydb_site')}
                        {$daoSite->whereAdd("status > 0")}
                        {$daoSite->orderBy("status asc, public_id asc")}
                        {$count = $daoSite->find()}
                        {if $count}
                            <li class="m-menu__section ">
                                <h4 class="m-menu__section-text">List</h4>
                                <i class="m-menu__section-icon flaticon-more-v2"></i>
                            </li>
                        {/if}
                        {while $daoSite->fetch()}

                            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="click" m-menu-submenu-mode="accordion">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                    <span class="m-menu__link-title">
                                        <span class="m-menu__link-wrap">
                                            {if $daoSite->status == "2"}<span class="m-menu__link-badge"><span class="m-badge m-badge--danger m-badge--dot"></span></span>{elseif $daoSite->status == "3"}<span class="m-menu__link-badge"><span class="m-badge m-badge--warning m-badge--dot"></span></span>{/if}
                                            <span class="m-menu__link-text">&nbsp;&nbsp;{$daoSite->name}</span>
                                        </span>
                                    </span>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("view","site",$daoSite->id_site)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">View & Edit</span></a></li>
                                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("sidebar","site",$daoSite->public_id)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Edit Sidebar</span></a></li>
                                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("list","collection",$daoSite->public_id)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">List Collections</span></a></li>
                                    </ul>
                                </div>
                            </li>

                        {/while}

                    </ul>
                </div>
            </div>
        </li>

        {*************************************************************************************}
        {*************************************** USERS ***************************************}
        {*************************************************************************************}
        <li class="m-menu__item  m-menu__item--submenu m-menu__item--submenu-fullheight" aria-haspopup="true" m-menu-submenu-toggle="click" m-menu-dropdown-toggle-class="m-aside-menu-overlay--on">
            <a href="javascript:;" class="m-menu__link m-menu__toggle">
                <i class="m-menu__link-icon flaticon-users"></i><span class="m-menu__link-text">Users</span><i class="m-menu__ver-arrow la la-angle-right"></i>
            </a>
            <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                <div class="m-menu__wrapper">
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent m-menu__item--submenu-fullheight" aria-haspopup="true"><span class="m-menu__link"><span class="m-menu__link-text">Users Actions</span></span></li>
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text">Actions</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("list","user")}" class="m-menu__link"><span class="m-menu__link-text">List all users</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("add","user")}" class="m-menu__link"><span class="m-menu__link-text">Add new user</span></a></li>
                        <li class="m-menu__section ">
                            <h4 class="m-menu__section-text">List</h4>
                            <i class="m-menu__section-icon flaticon-more-v2"></i>
                        </li>

                        {$daoUser = Denko::daoFactory('innydb_user')}
                        {$daoUser->whereAdd("status > 0")}
                        {$daoUser->orderBy("role desc, username asc")}
                        {$count = $daoUser->find()}

                        {while $daoUser->fetch()}

                            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="click" m-menu-submenu-mode="accordion">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                    <span class="m-menu__link-title">
                                        <span class="m-menu__link-wrap">
                                            {if $daoUser->role == "sysadmin"}<i class="m-menu__link-icon la la-user-plus"></i>{/if}
                                            {if $daoUser->status == "0"}<span class="m-menu__link-badge"><span class="m-badge m-badge--danger m-badge--dot"></span></span>{/if}
                                            {if $daoUser->status == "2"}<span class="m-menu__link-badge"><span class="m-badge m-badge--warning m-badge--dot"></span></span>{/if}
                                            <span class="m-menu__link-text">&nbsp;&nbsp;{$daoUser->username}</span>
                                        </span>
                                    </span>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("view","user",$daoUser->id_user)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">View & Edit</span></a></li>
                                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("pass","user",$daoUser->id_user)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Change Password</span></a></li>
                                        <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createMultipleResourceUrl("list","user",$daoUser->id_user,"site")}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Relate Sites</span></a></li>
                                        {if $daoUser->role == "sysadmin"}
                                            <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("assignsiteadmin","user",$daoUser->id_user)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Assign SiteAdmin</span></a></li>
                                        {else}
                                            <li class="m-menu__item " aria-haspopup="true" m-menu-link-redirect="1"><a href="{createSingleResourceUrl("assignsysadmin","user",$daoUser->id_user)}" class="m-menu__link "><i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span class="m-menu__link-text">Assign SysAdmin</span></a></li>
                                        {/if}
                                    </ul>
                                </div>
                            </li>

                        {/while}

                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>