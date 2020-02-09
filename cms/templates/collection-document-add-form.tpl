{$colCount=0}
{foreach $innyTypes as $innyType}
    {if $innyType->getParamValue("adminOnly",false) == false || InnyCMS::adminUserLogged()}
        {if $colCount%12 == 0}<div class="row">{/if}
        <div class="col-md-{$innyType->getParamValue('cols',12)} col-12">
            {$innyType->htmlInput()}
        </div>
        {$colCount=$colCount + $innyType->getParamValue('cols',12)}
        {if $colCount%12 == 0}
            </div>
            {if $innyType->getParamValue('separator',true)}<hr/>{/if}
        {/if}
    {/if}
{/foreach}
