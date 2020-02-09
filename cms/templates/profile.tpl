{include file="header.tpl" section="profile"}

<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    {include file="menu-aside.tpl" section="profile"}
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS :: {InnyCMS::getSiteName()}</h3>
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item m-nav__item--home"><a href="./" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./profile" class="m-nav__link"><span class="m-nav__link-text">{'User Profile'|_t}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <div class="m-portlet m-portlet--full-height  ">
                        <div class="m-portlet__body">
                            <div class="m-card-profile">
                                <div class="m-card-profile__title m--hide">
                                    {'Your Profile'|_t}
                                </div>
                                <div class="m-card-profile__pic">
                                    <div class="m-card-profile__pic-wrapper">
                                        <img src="{$userInfo.email|gravatar:100}" alt=""/>
                                    </div>
                                </div>
                                <div class="m-card-profile__details">
                                    <span class="m-card-profile__name">
                                        {$userInfo.lastname}, {$userInfo.name}
                                    </span>
                                    <a class="m-card-profile__email m-link">
                                        {$userInfo.email}
                                    </a>
                                </div>
                            </div>
                            <div class="m-portlet__body-separator"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-tools">
                                <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link {if $tab == "profile"}active{/if}" data-toggle="tab" href="#m_user_profile_tab_1" role="tab">
                                            <i class="flaticon-share m--hide"></i>
                                            {'Update Personal Info'|_t}
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link {if $tab == "password"}active{/if}" data-toggle="tab" href="#m_user_profile_tab_2" role="tab">
                                            {'Change Password'|_t}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane {if $tab == "profile"}active{/if}" id="m_user_profile_tab_1">
                                <form class="m-form m-form--fit m-form--label-align-right" method="post">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">
                                                    1. {'Personal Details'|_t}
                                                </h3>
                                            </div>
                                        </div>

                                        {if $tab == "profile" && !empty($error)}
                                            <div class="form-group m-form__group row">
                                                <div class="col-12 ml-auto">
                                                    <div class="alert alert-danger alert-dismissible fade show  m-alert m-alert--square m-alert--air" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="{'Close'|_t}"></button>
                                                        <strong>{'Error'|_t}</strong><br/>
                                                        {foreach $error as $key=>$val}
                                                            &bull; {$val}<br/>
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}

                                        {if $tab == "profile" && !empty($ok)}
                                            <div class="form-group m-form__group row">
                                                <div class="col-12 ml-auto">
                                                    <div class="alert alert-success alert-dismissible fade show  m-alert m-alert--square m-alert--air" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="{'Close'|_t}"></button>
                                                        <strong>{'Success!'|_t}</strong> {$ok}
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}

                                        <div class="form-group m-form__group row">
                                            <label for="profile-name" class="col-2 col-form-label">
                                                {'Name'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="text" name="name" value="{$userInfo.name}" id="profile-name"/>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="profile-lastname" class="col-2 col-form-label">
                                                {'Last Name'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="text" name="lastname" value="{$userInfo.lastname}" id="profile-lastname" />
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="profile-email" class="col-2 col-form-label">
                                                {'E-Mail'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="email" name="email" value="{$userInfo.email}" id="profile-email" />
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="profile-username" class="col-2 col-form-label">
                                                {'Username'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="text" disabled value="{$userInfo.username}" id="profile-username" />
                                                <span class="m-form__help">
                                                    {'If you want to change your username, please contact your webmaster.'|_t}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <div class="row">
                                                <div class="col-2"></div>
                                                <div class="col-7">
                                                    <input type="hidden" value="profile" name="action" />
                                                    <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                                                        {'Save changes'|_t}
                                                    </button>
                                                    &nbsp;&nbsp;
                                                    <button type="reset" class="btn btn-secondary m-btn m-btn--air m-btn--custom">
                                                        {'Cancel'|_t}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane {if $tab == "password"}active{/if}" id="m_user_profile_tab_2">
                                <form class="m-form m-form--fit m-form--label-align-right" method="post">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                            <div class="col-10 ml-auto">
                                                <h3 class="m-form__section">
                                                    2. {'Update Your Password'|_t}
                                                </h3>
                                            </div>
                                        </div>

                                        {if $tab == "password" && !empty($error)}
                                            <div class="form-group m-form__group row">
                                                <div class="col-12 ml-auto">
                                                    <div class="alert alert-danger alert-dismissible fade show  m-alert m-alert--square m-alert--air" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="{'Close'|_t}"></button>
                                                        <strong>{'Error'|_t}</strong><br/>
                                                        {foreach $error as $key=>$val}
                                                            &bull; {$val}<br/>
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}

                                        {if $tab == "password" && !empty($ok)}
                                            <div class="form-group m-form__group row">
                                                <div class="col-12 ml-auto">
                                                    <div class="alert alert-success alert-dismissible fade show  m-alert m-alert--square m-alert--air" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="{'Close'|_t}"></button>
                                                        <strong>{'Success!'|_t}</strong> {$ok}
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}

                                        <div class="form-group m-form__group row has-error">
                                            <label for="profile-currpass" class="col-2 col-form-label">
                                                {'Current Password'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="password" name="old_pass" value="" required id="profile-currpass" />
                                                <span class="m-form__help"></span>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="profile-newpass" class="col-2 col-form-label">
                                                {'New Password'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="password" name="new_pass" value="" required id="profile-newpass" />
                                                <span class="m-form__help"></span>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label for="profile-re-newpass" class="col-2 col-form-label">
                                                {'Repeat New Password'|_t}
                                            </label>
                                            <div class="col-7">
                                                <input class="form-control m-input" type="password" name="new_pass_repeat" value="" required id="profile-re-newpass" />
                                                <span class="m-form__help"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <div class="row">
                                                <div class="col-2"></div>
                                                <div class="col-7">
                                                    <input type="hidden" value="password" name="action" />
                                                    <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                                                        {'Update Password'|_t}
                                                    </button>
                                                    &nbsp;&nbsp;
                                                    <button type="reset" class="btn btn-secondary m-btn m-btn--air m-btn--custom">
                                                        {'Cancel'|_t}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- end::Body -->
{include file="footer.tpl"}
