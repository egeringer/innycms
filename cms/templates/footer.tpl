<!-- begin::Footer -->
<footer class="m-grid__item m-footer ">
    <div class="m-container m-container--fluid m-container--full-height m-page__container">
        <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
            <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                <span class="m-footer__copyright"> 2007 - {$smarty.now|date_format:"%Y"} &copy; InnyCMS by <a href="https://www.dokkogroup.com.ar/" class="m-link">Dokko Group</a></span>
            </div>
        </div>
    </div>
</footer>
<!-- end::Footer -->
</div>
<!-- end:: Page -->
<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->
<!--begin::Base Scripts -->
<script src="js/vendors.bundle.js" type="text/javascript"></script>
<script src="js/scripts.bundle.js" type="text/javascript"></script>
<script src="js/jquery-ui.blundle.js" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Custom Scripts -->

{if isset($script) && !empty($script)}
    {$jsPath = InnyCMS::getCustomizationPath('js')|cat:"/$script.js"}
    {if (file_exists($jsPath))}
        {$jsPath = str_replace("web/","",$jsPath)}
        <script src="{$jsPath}" type="text/javascript"></script>
    {else}
        {$jsPath = "js/$script.js"}
        <script src="{$jsPath}" type="text/javascript"></script>
    {/if}
{/if}

<!--end::Custom Scripts -->
</body>
<!-- end:: HTML Body -->
</html>