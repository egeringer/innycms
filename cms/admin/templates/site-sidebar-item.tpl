<div data-repeater-item class="form-group m-form__group row align-items-center">
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Type:</strong></label>
            <select class="form-control m-bootstrap-select m_selectpicker select-type" name="type" title="Choose one">
                <option value="collection" {if $item.type|default:"" == "collection" || $item.type|default:"" == "single" || $item.type|default:"" == "group"}selected="selected"{/if}>Collection</option>
                <option value="text" {if $item.type|default:"" == "text" || $item.type|default:"" == "heading"}selected="selected"{/if}>Text</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label><strong>Label:</strong></label>
            <input type="text" name="label" class="form-control" placeholder="Enter label for this item" value="{$item.label|default:""}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Icon:</strong></label>
            <input type="text" name="icon" class="form-control" placeholder="Icon class for this item" value="{$item.icon|default:""}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group {if $item.type|default:"" !== "collection" && $item.type|default:"" !== "single" && $item.type|default:"" !== "group"}d-none{/if} group-collection">
            <label><strong>Collections:</strong></label>
            <select class="form-control m-bootstrap-select m_selectpicker" name="collection" multiple data-live-search="true">
                {if !empty($collections)}
                    {foreach $collections as $collection}
                        <option value="{$collection}" {if is_array($item.collection) && (isset($item.collection[$collection]) || in_array($collection,$item.collection)) || $item.collection == $collection}selected{/if}>{$collection}</option>
                    {/foreach}
                {/if}
            </select>
        </div>
        <div class="form-group {if $item.type|default:"" !== "text" && $item.type|default:"" !== "heading"}d-none{/if} group-url">
            <label><strong>Url:</strong></label>
            <input type="text" name="url" class="form-control" placeholder="Enter Url for this item" value="{$item.url|default:""}">
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label><strong>Delete</strong></label><br/>
            <div data-repeater-delete="" class="btn btn-primary"><span><i class="fa fa-trash"></i></span></div>
        </div>
    </div>
</div>