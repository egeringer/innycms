{assign var="active" value=""}
{foreach $item.collection as $key => $value}
    {if $name|default:"" == $value}{assign var="active" value="m-menu__item--open m-menu__item--expanded"}{/if}
{/foreach}
<li class="m-menu__item  m-menu__item--submenu {$active}" aria-haspopup="true"  m-menu-submenu-toggle="hover">
    <a  href="#" class="m-menu__link m-menu__toggle">
        <i class="m-menu__link-icon {$item.icon|default:"flaticon-squares"}"></i>
        <span class="m-menu__link-text">
            {$item.label}
        </span>
        <i class="m-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="m-menu__submenu">
        <span class="m-menu__arrow"></span>
        <ul class="m-menu__subnav">
            <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                <span class="m-menu__link">
                    <span class="m-menu__link-text">
                        {$item.label}
                    </span>
                </span>
            </li>
            {foreach $item.collection as $key => $value}
                {$daoCollection = InnyCMS::getCollectionDao($value)}
                {$collectionMetadata = json_decode($daoCollection->metadata,true)}
                <li class="m-menu__item {if $name|default:"" == $value}m-menu__item--active{/if}" aria-haspopup="true" >
                    <a href="{collectionURL($value)}" class="m-menu__link ">
                        <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                        <span class="m-menu__link-text">{$collectionMetadata.name}</span>
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
</li>
