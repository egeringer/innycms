{include file="header.tpl" section="bucket"}
<!-- begin::Body -->
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
    {include file="menu-aside.tpl" section="bucket"}
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator">InnyCMS :: {InnyCMS::getSiteName()}</h3>
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item m-nav__item--home"><a href="./" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./bucket" class="m-nav__link"><span class="m-nav__link-text">Bucket</span></a></li>
                        <li class="m-nav__separator">-</li>
                        <li class="m-nav__item"><a href="./bucket-statistics" class="m-nav__link"><span class="m-nav__link-text">Statistics</span></a></li>
                    </ul>
                </div>
                <div>
                    <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                        <a class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                            <i class="la la-plus m--hide"></i>
                            <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="m-dropdown__wrapper">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                            <div class="m-dropdown__inner">
                                <div class="m-dropdown__body">
                                    <div class="m-dropdown__content">
                                        <ul class="m-nav">
                                            <li class="m-nav__section m-nav__section--first m--hide">
                                                <span class="m-nav__section-text">
                                                    Bucket Quick Actions
                                                </span>
                                            </li>
                                            <li class="m-nav__item">
                                                <a href="./bucket" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-folder-open"></i>
                                                    <span class="m-nav__link-text">
                                                        Bucket
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="m-nav__separator m-nav__separator--fit"></li>
                                            <li class="m-nav__item">
                                                <a role="button" href="./bucket-statistics" data-toggle="modal" data-target="#cleanBucket" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-magic"></i>
                                                    <span class="m-nav__link-text">
                                                        Clean Bucket
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="m-nav__item">
                                                <a role="button" href="./bucket-statistics" data-toggle="modal" data-target="#emptyBucket" class="m-nav__link">
                                                    <i class="m-nav__link-icon fa fa-trash"></i>
                                                    <span class="m-nav__link-text">
                                                        Empty Bucket
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">

            <div class="m-portlet">
                <div class="m-portlet__body  m-portlet__body--no-padding">
                    <div class="row m-row--no-padding m-row--col-separator-xl">
                        <div class="col-xl-4">
                            <!--begin:: Widgets/Storage Info-->
                            <div class="m-widget1">
                                <div class="m-widget1__item">
                                    <div class="row m-row--no-padding align-items-center">
                                        <div class="col">
                                            <h3 class="m-widget1__title">Amount of Files</h3>
                                            <span class="m-widget1__desc">Uploaded</span>
                                        </div>
                                        <div class="col m--align-right">
                                            <span class="m-widget1__number m--font-brand">{$stats.filesCount}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget1__item">
                                    <div class="row m-row--no-padding align-items-center">
                                        <div class="col">
                                            <h3 class="m-widget1__title">Used Storage</h3>
                                            <span class="m-widget1__desc">From all files</span>
                                        </div>
                                        <div class="col m--align-right">
                                            <span class="m-widget1__number m--font-danger">{Denko::bytesToFriendlyUnit({$stats.filesSize})}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget1__item">
                                    <div class="row m-row--no-padding align-items-center">
                                        <div class="col">
                                            <h3 class="m-widget1__title">Free Storage</h3>
                                            <span class="m-widget1__desc">Left in your free plan</span>
                                        </div>
                                        <div class="col m--align-right">
                                            <span class="m-widget1__number m--font-success">{Denko::bytesToFriendlyUnit({$stats.freeSize})}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Storage Info-->
                        </div>
                        <div class="col-xl-4">
                            <!--begin:: Widgets/Storage Chart-->
                            <div class="m-widget14">
                                <div class="m-widget14__header">
                                    <h3 class="m-widget14__title">Storage</h3>
                                    <span class="m-widget14__desc">Used vs Free Storage</span>
                                </div>
                                <div class="row  align-items-center">
                                    <div class="col">
                                        <div id="m_chart_free_vs_used_space" class="m-widget14__chart" style="height: 160px">
                                            <div class="m-widget14__stat">{$stats.usedSpacePercentage}%</div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="m-widget14__legends">
                                            <div class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-danger"></span>
                                                <span class="m-widget14__legend-text">
                                                    {$stats.usedSpacePercentage}% Used Storage
                                                </span>
                                            </div>
                                            <div class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-success"></span>
                                                <span class="m-widget14__legend-text">
                                                    {$stats.freeSpacePercentage}% Free Storage
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Storage Chart-->
                        </div>
                        <div class="col-xl-4">
                            <!--begin:: Widgets/Amount of Files by Type-->
                            <div class="m-widget14">
                                <div class="m-widget14__header">
                                    <h3 class="m-widget14__title">Amount of Files by Type</h3>
                                    <span class="m-widget14__desc">Each column represents a file type</span>
                                </div>
                                <div class="row  align-items-center">
                                    <div class="col">
                                        <div id="m_chart_amount_file_types" class="m-widget14__chart1" style="height: 180px"></div>
                                    </div>
                                    <div class="col">
                                        <div class="m-widget14__legends">
                                            {$i = -1}
                                            {$colors =[0 =>["name"=>"brand","hexa"=>"#716aca"],1 =>["name"=>"accent","hexa"=>"#00c5dc"],2 =>["name"=>"primary","hexa"=>"#5867dd"],3 =>["name"=>"success","hexa"=>"#34bfa3"],4 =>["name"=>"info","hexa"=>"#36a3f7"],5 =>["name"=>"warning","hexa"=>"#ffb822"],6 =>["name"=>"danger","hexa"=>"#f4516c"],7 =>["name"=>"focus","hexa"=>"#9816f4"],8 =>["name"=>"metal","hexa"=>"#c4c5d6"]]}
                                            {foreach $stats.grouped as $group => $data}
                                                {$i = $i+1}
                                                {$stats.grouped[$group].hexaColor = $colors[$i].hexa}
                                                <div class="m-widget14__legend">
                                                    <span class="m-widget14__legend-bullet m--bg-{$colors[$i].name}"></span>
                                                    <span class="m-widget14__legend-text">
                                                        {$group|capitalize}: {$data.filesCount}
                                                    </span>
                                                </div>
                                            {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Amount of Files by Type-->
                        </div>
                    </div>
                </div>
            </div>
            <!--End::Section-->

            {include file="bucket-common-footer.tpl"}

        </div>
    </div>
</div>
<script type="text/javascript">
    var infoFromTemplate = [ {foreach $stats.grouped as $group => $data} {$i = $i+1} { label: "{$group|capitalize}", value: {$data.filesCount}, color: "{$data.hexaColor}" }, {/foreach} ];
    var usedSpaceFromTemplate = {$stats.usedSpacePercentage};
    var freeSpaceFromTemplate = {$stats.freeSpacePercentage};
</script>
<!-- end::Body -->
{include file="footer.tpl" script="bucket-statistics"}