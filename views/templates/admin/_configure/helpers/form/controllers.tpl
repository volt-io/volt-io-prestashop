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
<div class="panel">
	<div class="panel-heading">
        {l s='Notification' mod='volt'}
	</div>

	<h4>
        {l s='Notification URL (https)' mod='volt'}
	</h4>
	<p>
		<input type="text" disabled
		       value="{Context::getContext()->link->getModuleLink('volt', 'notifications')|escape:'html':'UTF-8'}">
	</p>
</div>


<div class="panel">
	<div class="panel-heading">
        {l s='Payment return URLs' mod='volt'}
	</div>

	<h4>
        {l s='On Payment Success' mod='volt'}
	</h4>
	<p>
		<input type="text" disabled
		       value="{Context::getContext()->link->getModuleLink('volt', 'success')|escape:'html':'UTF-8'}">
	</p>
	<h4>
        {l s='On Payment Pending' mod='volt'}
	</h4>
	<p>
		<input type="text" disabled
		       value="{Context::getContext()->link->getModuleLink('volt', 'pending')|escape:'html':'UTF-8'}">
	</p>
	<h4>
        {l s='On Payment Failure' mod='volt'}
	</h4>
	<p>
		<input type="text" disabled
		       value="{Context::getContext()->link->getModuleLink('volt', 'failure')|escape:'html':'UTF-8'}">
	</p>
</div>
