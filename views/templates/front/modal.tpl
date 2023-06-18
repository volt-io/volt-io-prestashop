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
<div id="jsModal" class="volt-modal">
	<div class="volt-modal__overlay jsOverlay"></div>
	<div class="volt-modal__container">

		<div class="volt-modal__column">
			<div class="volt-modal__title">
				<h3 class="volt-modal__header-text">
                    {l s='How Volt works' mod='volt'}
				</h3>
				<p class="volt-modal__normal-text">
                    {l s='Check out in three easy steps:' mod='volt'}
				</p>
			</div>

			<div class="volt-modal__img--mobile">
				<img src="{$volt_dir|escape:'html':'UTF-8'}volt-how-works--mobile.png"
				     alt="{l s='How Volt works' mod='volt'}">
			</div>

			<div class="volt-modal__content">
				<ul class="volt-modal__list">
					<li>
						<h4 class="volt-modal__header-text">
                            {l s='Select your bank (99% of banks supported)' mod='volt'}
						</h4>
						<p class="volt-modal__normal-text">
                            {l s='Pay from your bank. No card needed.' mod='volt'}
						</p>
					</li>
					<li>
						<h4 class="volt-modal__header-text">
                            {l s='Log into your account' mod='volt'}
						</h4>
						<p class="volt-modal__normal-text">
                            {l s='Your bank details are never shared.' mod='volt'}
						</p>
					</li>
					<li>
						<h4 class="volt-modal__header-text">
                            {l s='Approve the payment' mod='volt'}
						</h4>
						<p class="volt-modal__normal-text">
                            {l s='That’s it. Faster and more secure.' mod='volt'}
						</p>
					</li>
				</ul>

				<button class="volt-modal__close jsModalClose">
                    {l s='Continue' mod='volt'}
				</button>

			</div>
		</div>

		<div class="volt-modal__column">
			<img src="{$volt_dir|escape:'html':'UTF-8'}volt-how-works.png"
			     alt="{l s='How Volt works' mod='volt'}">
		</div>




	</div>
</div>
