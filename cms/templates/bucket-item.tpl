<div class="form-group m-form__group row">
    <div class="col-xs-5 col-sm-4 col-md-3">
        {include file="bucket-item-preview.tpl"}
    </div>
    <div class="col-xs-7 col-sm-5 col-md-6 overflow-hidden tags-container">
        <b>{"Name"|_t}:</b> {$daoBucket->name}<br/>
        <b>{"File Url"|_t}:</b> {InnyCMS::getSiteUrl()}{InnyCMS::getBucketFileUrl($daoBucket,"full")}<br/>
        <b>{"Size"|_t}:</b> {Denko::bytesToFriendlyUnit({$daoBucket->size})}<br/>
        <b>{"Count"|_t}:</b> {$daoBucket->count}<br/>
        <b>{"Created"|_t}:</b> {$daoBucket->aud_ins_date}<br/>
        {if $daoBucket->aud_ins_date != $daoBucket->aud_upd_date}<b>{"Modified"|_t}:</b> {$daoBucket->aud_upd_date}<br/>{/if}
        <b>{"Tags"|_t}:</b> {InnyCMS::getBucketItemTags($daoBucket)}<br/>
    </div>
    <div class="clearfix hidden-sm hidden-md hidden-lg"></div>
    <div class="col-12 col-sm-3 col-md-3 listing-icons">
        <a class="btn btn-info btn-block" title="{'View'|_t} {$daoBucket->name}" href="{InnyBucket::getBucketActionUrl($daoBucket,'view')}"><span><i class='fa fa-eye'></i>&nbsp;{"View"|_t}</span></a>
        <a class="btn btn-success btn-block" title="{'Add Tags'|_t} {$daoBucket->name}" href="{InnyBucket::getBucketActionUrl($daoBucket,'tags')}"><span><i class='fa fa-tags'></i>&nbsp;{"Add Tags"|_t}</span></a>
        <a class="btn btn-primary btn-block" title="{'Download'|_t} {$daoBucket->name}" href="./{InnyCMS::getBucketFileUrl($daoBucket,"download")}"><span><i class='fa fa-cloud-download'></i>&nbsp;{"Download"|_t}</span></a>
        {if $daoBucket->count == "0"}<a class="btn btn-danger btn-block" href="#" data-name="{$daoBucket->name}" data-id="{$daoBucket->id_bucket}" data-hash="{substr($daoBucket->hash,0,5)}" data-from="bucket" title="{'Delete'|_t} {$daoBucket->name}" data-toggle="modal" data-target="#deleteItem"><span><i class='fa fa-trash'></i>&nbsp;{"Delete"|_t}</span></a>{/if}
    </div>
</div>