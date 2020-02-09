<div class="m-portlet ">
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-md">
            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">Enabled Sites</h4><br>
                        <span class="m-widget24__desc">Total Sites Enabled</span>
                        <span class="m-widget24__stats m--font-brand">{$enabledSites}</span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                            <div class="progress-bar m--bg-brand" role="progressbar" style="width: {round($enabledSites * 100 / $totalSites)}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="m-widget24__change"></span>
                        <span class="m-widget24__number">{round($enabledSites * 100 / $totalSites)}%</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">Disabled Sites</h4><br>
                        <span class="m-widget24__desc">Total Sites Disabled</span>
                        <span class="m-widget24__stats {if $disabledSites == "0"}m--font-brand{else}m--font-danger{/if}">{$disabledSites}</span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                            <div class="progress-bar m--bg-danger" role="progressbar" style="width: {round($disabledSites * 100 / $totalSites)}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="m-widget24__change"></span>
                        <span class="m-widget24__number">{round($disabledSites * 100 / $totalSites)}%</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">Maintenance Sites</h4><br>
                        <span class="m-widget24__desc">Total Sites Under Maintenance</span>
                        <span class="m-widget24__stats {if $maintenanceSites == "0"}m--font-brand{else}m--font-warning{/if}">{$maintenanceSites}</span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                            <div class="progress-bar m--bg-warning" role="progressbar" style="width: {round($maintenanceSites * 100 / $totalSites)}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="m-widget24__change"></span>
                        <span class="m-widget24__number">{round($maintenanceSites * 100 / $totalSites)}%</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">Enabled Users</h4><br>
                        <span class="m-widget24__desc">Total Enabled Users</span>
                        <span class="m-widget24__stats m--font-brand">{$enabledUsers}</span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                            <div class="progress-bar m--bg-brand" role="progressbar" style="width: {round($enabledUsers * 100 / $totalUsers)}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="m-widget24__change"></span>
                        <span class="m-widget24__number">{round($enabledUsers * 100 / $totalUsers)}%</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">Disabled Users</h4><br>
                        <span class="m-widget24__desc">Total Users Disabled</span>
                        <span class="m-widget24__stats {if $disabledUsers == "0"}m--font-brand{else}m--font-danger{/if}">{$disabledUsers}</span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                            <div class="progress-bar m--bg-danger" role="progressbar" style="width: {round($disabledUsers * 100 / $totalUsers)}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="m-widget24__change"></span>
                        <span class="m-widget24__number">{round($disabledUsers * 100 / $totalUsers)}%</span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="m-widget24">
                    <div class="m-widget24__item">
                        <h4 class="m-widget24__title">Admin Users</h4><br>
                        <span class="m-widget24__desc">Total SysAdmin Users</span>
                        <span class="m-widget24__stats m--font-success">{$adminUsers}</span>
                        <div class="m--space-10"></div>
                        <div class="progress m-progress--sm">
                            <div class="progress-bar m--bg-success" role="progressbar" style="width: {round($adminUsers * 100 / $totalUsers)}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="m-widget24__change"></span>
                        <span class="m-widget24__number">{round($adminUsers * 100 / $totalUsers)}%</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
