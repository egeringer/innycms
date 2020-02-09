{if !empty($fieldName)}<h3>{$fieldName}</h3><hr/>{/if}
{if !empty($fieldLang)}<h4>{$fieldLang}</h4>{/if}
{if $multiple}
    {foreach $value as $v}
        <pre>{$options[$v]}</pre>
    {/foreach}
{else}
    <pre>{$options[$value]}</pre>
{/if}