<!-- BEGIN: Subheader -->
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS &raquo; {$title}</h3>

            <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                <li class="m-nav__item m-nav__item--home">
                    <a href="./dashboard" class="m-nav__link m-nav__link--icon">
                        <i class="m-nav__link-icon la la-home"></i>
                    </a>
                </li>
                {if isset($resource)}
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="./{createSingleResourceUrl("list",$resource)}" class="m-nav__link">
                            <span class="m-nav__link-text">List {$resource}</span>
                        </a>
                    </li>
                    {if isset($resource2)}
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item">
                            <a href="./{createSingleResourceUrl("view",$resource,$resourceId)}" class="m-nav__link">
                                <span class="m-nav__link-text">View {$resource}</span>
                            </a>
                        </li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item">
                            <a href="./{createMultipleResourceUrl($action,$resource,$resourceId,$resource2,$resourceId2|default:"")}" class="m-nav__link">
                                <span class="m-nav__link-text">{ucfirst($action)} {$resource} {$resource2}</span>
                            </a>
                        </li>
                    {elseif isset($resourceId)}
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item">
                            <a href="./{createSingleResourceUrl($action,$resource,$resourceId)}" class="m-nav__link">
                                <span class="m-nav__link-text">{ucfirst($action)} {$resource}</span>
                            </a>
                        </li>
                    {/if}
                {/if}
            </ul>

        </div>
    </div>
</div>
<!-- END: Subheader -->