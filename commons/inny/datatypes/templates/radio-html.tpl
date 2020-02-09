<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>

    {if !empty($disabledParam)}<input type='hidden' name='{$field}' value="{$value}" />{/if}
    {foreach $options as $optionKey => $optionValue}
        <div class='radio'>
            <label for='{$field}-{$optionKey}' class='control-label'>
                <input type='radio' name='{$field}' id='{$field}-{$optionKey}' value='{$optionKey}' {if $optionKey == $value}checked='checked'{/if} {$requiredParam} {$disabledParam}/>
                {$optionValue}
            </label>
        </div>
    {/foreach}

    <span id='help-{$field}' data-original-text='{$helpText|default:""}' class='m-form__help'>{$helpText|default:""}</span>
</div>