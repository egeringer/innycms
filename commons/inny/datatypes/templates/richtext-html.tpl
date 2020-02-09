<div class='form-group m-form__group form-group-{$field}'>
    <label class='control-label' for='{$field}'><strong>{$fieldName} <span class='text-danger'>{$requiredText}</span></strong></label>
    <textarea class='form-control' id='{$field}' name='{$field}' aria-describedby='help-{$field}' {$requiredParam} {$disabledParam} {$readonlyParam}>{$value}</textarea>
    <span id='help-{$field}' data-original-text='{$helpText}' class='help-block'>{$helpText}</span>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        CKEDITOR.replace('{$field}',
            {
                'toolbarGroups':{if !empty($toolbar)}[{$toolbar}]{else}[]{/if},
                'removeButtons':'{$removeButtons|default:"[]"}',
                'disableNativeSpellChecker': false,
                'allowedContent': true
            }
        );
    });
</script>
