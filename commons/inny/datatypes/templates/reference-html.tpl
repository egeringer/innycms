<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>
    <select class="form-control m-select2" data-collection-name="{$collectionReference}" data-field-name="{$referenceFieldName}"  data-key-field="{$referenceKeyField}" id="{$field}" name="{$field}{if $multipleParam!=""}[]{/if}" aria-describedby='help-{$field}' {$requiredParam} {$disabledParam} {$readonlyParam} {$multipleParam} {$additionalParams|default:""}>
        {if $multipleParam==""}
            {$daoDocument = InnyCMS::getOneDocumentByField($referenceKeyField,$value,$collectionReference)}
            {if !empty($daoDocument)}
                <option value="{$value}" selected="selected">{$value} - {$daoDocument->$referenceFieldName}</option>
            {/if}
        {else}
            {if !empty($value)}
                {foreach $value as $k => $v}
                    {if $v != ""}
                        {$daoDocument = InnyCMS::getOneDocumentByField($referenceKeyField,$v,$collectionReference)}
                        <option value="{$v}" selected="selected" >{$v} - {$daoDocument->$referenceFieldName}</option>
                    {/if}
                {/foreach}
            {/if}
        {/if}
    </select>
    <span id='help-{$field}' data-original-text='{$helpText|default:""}' class='m-form__help'>{$helpText|default:""}</span>
</div>
