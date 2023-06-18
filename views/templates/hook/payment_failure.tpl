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
{extends file=$layout}

{block name='content'}
	<section class="volt-status">
		<div class="volt-status__inner">
			<a href="https://volt.io" target="_blank">
				<img src="{$volt_dir|escape:'html':'UTF-8'}logo-payment.png" width="70" class="payment-brand" alt="Volt" />
			</a>
			<h1>
                {l s='Payment status' mod='volt'}
			</h1>
			<p class="warning">
                {l s='Failure during payment at the bank' mod='volt'}
			</p>

			<div class="volt-status__navigate">
				<a href="{$urls.base_url}" class="btn btn-primary">
                    {l s='Return to the shop' mod='volt'}
				</a>
				<a class="btn btn-primary" href="{$urls.pages.history}">
                    {l s='View order history' mod='volt'}
				</a>
			</div>
		</div>
	</section>
{/block}
