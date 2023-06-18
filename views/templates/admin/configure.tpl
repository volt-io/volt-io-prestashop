{**
 * NOTICE OF LICENSE.
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Volt Technologies Holdings Limited
 * @copyright 2023, Volt Technologies Holdings Limited
 * @license   LICENSE.txt
 *}
{extends file="page_header_toolbar.tpl"}


{block name=pageTitle}
	<h2 class="page-title">
        {l s='Configure' d='Admin.Actions'}
	</h2>
{/block}
{block name=pageBreadcrumb}
	<ul class="breadcrumb page-breadcrumb">
        {if $breadcrumbs2.container.name != ''}
			<li class="breadcrumb-current">
                {$breadcrumbs2.container.name|escape:'htmlall':'UTF-8'}
			</li>
        {/if}
		<li>
			<i class="icon-wrench"></i>
            {l s='Configure' d='Admin.Actions'}
		</li>
	</ul>
{/block}
{block name=toolbarBox}
	<script type="text/javascript">
		var header_confirm_reset = '{l s='Confirm reset' d='Admin.Modules.Notification'}';
		var body_confirm_reset = '{l s='Would you like to delete the content related to this module ?' d='Admin.Modules.Notification'}';
		var left_button_confirm_reset = '{l s='No - reset only the parameters' d='Admin.Modules.Notification'}';
		var right_button_confirm_reset = '{l s='Yes - reset everything' d='Admin.Modules.Notification'}';
	</script>
	<div class="page-bar toolbarBox">
		<div class="btn-toolbar">
			<ul class="nav nav-pills pull-right">
				<li>
					<a id="desc-module-back" class="toolbar_btn" href="{url entity='sf' route='admin_module_manage'}">
						<i class="process-icon-back"></i>
						<div>{l s='Back' d='Admin.Global'}</div>
					</a>
				</li>


                {if isset($module_update_link)}
					<li>
						<a id="desc-module-update" class="toolbar_btn" href="{$module_update_link}"
						   title="{l s='Update' d='Admin.Modules.Feature'}">
							<i class="process-icon-refresh"></i>
							<div>{l s='Check update' d='Admin.Modules.Feature'}</div>
						</a>
					</li>
                {/if}
			</ul>
		</div>
	</div>
{/block}


