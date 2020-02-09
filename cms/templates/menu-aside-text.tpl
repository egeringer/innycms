{if !empty($item.url)}
    <li class="m-menu__item" aria-haspopup="false">
        <a href="{$item.url}" title="{$item.label}" class="m-menu__link ">
            <i class="m-menu__link-icon {$item.icon|default:"flaticon-list-3"}"></i>
            <span class="m-menu__link-title">
                <span class="m-menu__link-wrap">
                    <span class="m-menu__link-text">
                        {$item.label}
                    </span>
                </span>
            </span>
        </a>
    </li>
{else}
    <li class="m-menu__section">
        <h4 class="m-menu__section-text">{$item.label}</h4>
        <i class="m-menu__section-icon"></i>
    </li>
{/if}