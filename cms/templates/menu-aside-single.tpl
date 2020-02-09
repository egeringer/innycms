<li class="m-menu__item {if $name|default:"" == $item.collection[0]}m-menu__item--active{/if}" aria-haspopup="true" >
    <a href="{collectionURL($item.collection[0])}" class="m-menu__link ">
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
