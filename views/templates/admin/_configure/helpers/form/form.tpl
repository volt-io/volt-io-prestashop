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
<div class="volt-header">
	<svg focusable="false" viewBox="0 0 80 80" aria-hidden="true" width="1em" height="1em">
		<g fill="#ffffff" fill-rule="nonzero">
			<path d="M40 .335c-22.09 0-40 17.707-40 39.55C0 61.73 17.91 79.437 40 79.437s40-17.706 40-39.55C80 18.042 62.09.336 40 .336zm0 3.075c20.38 0 36.898 16.332 36.898 36.476S60.38 76.36 40 76.36c-20.38 0-36.898-16.331-36.898-36.475C3.102 19.742 19.62 3.41 40 3.41z"></path>
			<path d="M12.727 35.685h3.39l4.842 11.143 4.811-11.143h3.39l-7.523 16.626h-1.352l-7.558-16.626zm49.216-4.24v4.24h4.77v2.95h-4.77v8.104c0 1.84.801 2.561 2.743 2.59h.12c.526 0 1.113-.052 1.816-.16l.165-.027.427-.069v2.812l-.288.066c-1.042.239-1.898.36-2.622.36-3.451 0-5.475-1.86-5.515-5.307v-8.369h-2.573v-2.95h2.573v-4.24h3.154zm-23.792 4.24l.126.002.13-.001c4.351.012 8.028 3.667 8.083 8.036v.387c.018 4.35-3.669 8.113-7.986 8.17l-.131.001-.126-.001-.126.001c-4.308.018-7.97-3.68-8.054-8.035l-.002-.133.001-.13-.002-.127c-.017-4.395 3.626-8.114 7.956-8.17h.131zm14.847-8.384v24.94h-3.154v-24.94h3.154zM38.362 38.733h-.169c-2.78-.013-5.142 2.406-5.082 5.25-.04 2.76 2.213 5.187 4.936 5.249h.21l.085.001h.084c2.674-.037 4.958-2.352 5.018-5.038v-.212c.06-2.816-2.254-5.194-4.998-5.25h-.084zM29.15 27.301H13.034v2.814H29.15z">
			</path>
		</g>
	</svg>

	<ul class="volt-menu">


		<li class="">
			<a href="tab_rule_0" data-hash="authorization" class="active" id="tab_rule_link_0">
                {l s='Authentication' mod='volt'}
			</a>
		</li>

		<li class="">
			<a href="tab_rule_1" data-hash="payment-options" class="" id="tab_rule_link_1">
                {l s='Payment Settings' mod='volt'}
			</a>
		</li>

	</ul>
</div>

<div class="volt-configuration">
	<div class="col-lg-10">

        {if isset($fields.title)}
			<h3>{$fields.title}</h3>
        {/if}

        {block name="defaultForm"}

            {if isset($identifier_bk) && $identifier_bk == $identifier}
                {capture name='identifier_count'}{counter name='identifier_count'}{/capture}
            {/if}

            {assign var='identifier_bk' value=$identifier scope='parent'}
            {if isset($table_bk) && $table_bk == $table}
                {capture name='table_count'}{counter name='table_count'}{/capture}
            {/if}

            {assign var='table_bk' value=$table scope='parent'}
			<form autocomplete="off" id="{if isset($fields.form.form.id_form)}{$fields.form.form.id_form|escape:'html':'UTF-8'}{else}{if $table == null}configuration_form{else}{$table}_form{/if}{if isset($smarty.capture.table_count) && $smarty.capture.table_count}_{$smarty.capture.table_count|intval}{/if}{/if}"
			      class="defaultForm form-horizontal{if isset($name_controller) && $name_controller} {$name_controller}{/if}"{if isset($current) && $current} action="{$current|escape:'html':'UTF-8'}{if isset($token) && $token}&amp;token={$token|escape:'html':'UTF-8'}{/if}"{/if}
			      method="post" enctype="multipart/form-data"{if isset($style)} style="{$style}"{/if} novalidate>
                {if $form_id}
					<input type="hidden" name="{$identifier}"
					       id="{$identifier}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}"
					       value="{$form_id}"/>
                {/if}
                {if !empty($submit_action)}
					<input type="hidden" name="{$submit_action}" value="1"/>
                {/if}
                {$tabkey = 0}


                {foreach $fields as $f => $fieldset}
                    {foreach $fieldset.form.section as $fieldset2}


                        {if $tabkey == 0 || $tabkey == 1 || $tabkey == 3}
							<div id="tab_rule_{$tabkey}" class="{$submit_action} tab_rule_tab ">
                        {/if}

                        {block name="fieldset"}
                            {capture name='fieldset_name'}{counter name='fieldset_name'}{/capture}
							<div class="panel"
							     id="fieldset_{$f}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
                                {foreach $fieldset.form as $key => $field}

                                    {if $key == 'legend'}
                                        {block name="legend"}
											<div class="panel-heading">
                                                {if isset($field.image) && isset($field.title)}<img src="{$field.image}"
												                                                    alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                                                {if isset($field.icon)}<i class="{$field.icon}"></i>{/if}
                                                {$field.title}
											</div>
                                        {/block}
                                    {elseif $key == 'description' && $field}
										<!-- <div class="alert alert-info">{$field}</div> -->
                                    {elseif $key == 'input'}

                                        {foreach $field as $input}
                                            {include file="./configure_fields.tpl" _input=$input}
                                        {/foreach}

                                    {elseif $key == 'form_group'}

                                        {foreach $fieldset.form.form_group.fields as $key2 => $fields_group_input}
                                            {foreach $fields_group_input as $kkk => $fields_group_form}
                                                {foreach $fields_group_form as $form_key => $form_subgroup_input}

                                                    {if $form_key === 'legend'}
														<div class="section-heading">
                                                            {$form_subgroup_input.title}
														</div>
                                                    {elseif $form_key === 'input'}

                                                        {foreach $form_subgroup_input as $form_subgroup_field}
                                                            {include file="./configure_fields.tpl" _input=$form_subgroup_field}
                                                        {/foreach}

                                                    {/if}

                                                {/foreach}
                                            {/foreach}
                                        {/foreach}



                                    {/if}



                                {/foreach}

                                {block name="footer"}
                                    {capture name='form_submit_btn'}{counter name='form_submit_btn'}{/capture}
                                    {if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
										<div class="panel-footer">

                                            {if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
												<button type="submit" value="1"
                                                        {if isset($fieldset['form']['submit']['save_event']) && !empty($fieldset['form']['submit']['save_event'])}
															data-save-event="{$fieldset['form']['submit']['save_event']}"
                                                        {/if}
														id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']}{else}{$table}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}"
														name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}"
														class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-primary pull-right{/if}">
                                                    {$fieldset['form']['submit']['title']}
												</button>
                                            {/if}

                                            {if isset($fieldset['form']['buttons'])}
                                                {foreach from=$fieldset['form']['buttons'] item=btn key=k}
                                                    {if isset($btn.href) && trim($btn.href) != ''}
														<a href="{$btn.href}"
                                                           {if isset($btn['id'])}id="{$btn['id']}"{/if}
														   class="btn btn-primary{if isset($btn['class'])} {$btn['class']}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}
																<i class="{$btn['icon']}"></i>
                                                            {/if}{$btn.title}</a>
                                                    {else}
														<button type="button"
                                                                {if isset($btn['id'])}id="{$btn['id']}"{/if}
														        class="btn btn-primary{if isset($btn['class'])} {$btn['class']}{/if}"
														        name="{if isset($btn['name'])}{$btn['name']}{else}submitOptions{$table}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}
																<i class="{$btn['icon']}"></i>
                                                            {/if}{$btn.title}
														</button>
                                                    {/if}
                                                {/foreach}
                                            {/if}

										</div>
                                    {/if}
                                {/block}
							</div>
                        {/block}
                        {block name="other_fieldsets"}{/block}

                    {/foreach}

                    {if $tabkey == 0 || $tabkey == 2 || $tabkey == 3}
						</div>
                    {/if}

                    {$tabkey = $tabkey+1}

                {/foreach}

			</form>
        {/block}
        {block name="after"}{/block}


	</div>


    {include file="./controllers.tpl"}

	<script type="text/javascript">
		$('.tab_rule_tab').hide();
		$('#tab_rule_link_0').addClass('active');
		$('#tab_rule_0').show();

		window.location.hash = 'authorization';

		$('.volt-menu li').on('click', function (e) {
			e.preventDefault();

			var target = $(e.target).attr("href");

			$('.volt-menu li a').removeClass('active');
			$(this).find('a').addClass('active');
			$('.tab_rule_tab').hide();
			$('#' + target).show();

			window.location.hash = $(this).find('a').data('hash');
		});
	</script>

    {if $firstCall}
		<script type="text/javascript">
			var module_dir = '{$smarty.const._MODULE_DIR_}';
			var id_language = {$defaultFormLanguage|intval};
			var languages = new Array();

            {foreach $languages as $k => $language}
			languages[{$k}] = {
				id_lang: {$language.id_lang},
				iso_code: '{$language.iso_code}',
				name: '{$language.name}',
				is_default: '{$language.is_default}'
			};
            {/foreach}

			allowEmployeeFormLang = {$allowEmployeeFormLang|intval};
			displayFlags(languages, id_language, allowEmployeeFormLang);

			$(document).ready(function () {
                {if isset($use_textarea_autosize)}
				$(".textarea-autosize").autosize();
                {/if}
			});

            {*state_token = '{getAdminToken tab='AdminStates'}';*}
            {block name="script"}{/block}
		</script>

		<style>
            .volt-switcher {
                width: 300px !important;
            }
		</style>
    {/if}

	<script type="text/javascript">
			let volt_ajax = "{$ajax_controller}";
			let volt_token = "{$ajax_token}";

			let success_msg = "{l s='Configuration saved successfully' mod='volt'}"
			let error_msg = "{l s='Error, configuration not saved' mod='volt'}"


			$(document).ready(function () {
				$('#configuration_form').on('submit', function (e) {
					e.preventDefault();

					var data = $(this).serialize() + '&ajax=true&action=SaveConfiguration&token=' + volt_token;
					$.ajax({
						type: 'POST',
						cache: false,
						dataType: 'json',
						url: volt_ajax,
						data: data,
						success: function (data) {
							if (data.success) {
								showSuccessMessage(success_msg);
							} else {
								showErrorMessage(error_msg);
							}
						},
						error: function (data) {
							showErrorMessage(error_msg);
						}
					});
				});

				var voltEnv = $("input[name=VOLT_ENV_SWITCH]");
				var voltEnvValue = $("input[name=VOLT_ENV_SWITCH]:checked").val();

				function checkVoltEnv(state) {

					console.log(state);
					console.log('test');

					if (state === '0') {



						$('.VOLT_SANDBOX_CLIENT_ID').hide();
						$('.VOLT_SANDBOX_CLIENT_SECRET').hide();
						$('.VOLT_SANDBOX_USERNAME').hide();
						$('.VOLT_SANDBOX_PASSWORD').hide();
						$('.VOLT_SANDBOX_NOTIFICATION_SECRET').hide();

						$('.VOLT_SANDBOX_CLIENT_CREDENTIALS').hide();
						$('.VOLT_SANDBOX_CUSTOMER_CREDENTIALS').hide();

						$('.VOLT_PROD_CLIENT_ID').show();
						$('.VOLT_PROD_CLIENT_SECRET').show();
						$('.VOLT_PROD_USERNAME').show();
						$('.VOLT_PROD_PASSWORD').show();
						$('.VOLT_PROD_NOTIFICATION_SECRET').show();

						$('.VOLT_PROD_CLIENT_CREDENTIALS').show();
						$('.VOLT_PROD_CUSTOMER_CREDENTIALS').show();


					} else {
						$('.VOLT_SANDBOX_CLIENT_ID').show();
						$('.VOLT_SANDBOX_CLIENT_SECRET').show();
						$('.VOLT_SANDBOX_USERNAME').show();
						$('.VOLT_SANDBOX_PASSWORD').show();
						$('.VOLT_SANDBOX_NOTIFICATION_SECRET').show();

						$('.VOLT_SANDBOX_CLIENT_CREDENTIALS').show();
						$('.VOLT_SANDBOX_CUSTOMER_CREDENTIALS').show();

						$('.VOLT_PROD_CLIENT_ID').hide();
						$('.VOLT_PROD_CLIENT_SECRET').hide();
						$('.VOLT_PROD_USERNAME').hide();
						$('.VOLT_PROD_PASSWORD').hide();
						$('.VOLT_PROD_NOTIFICATION_SECRET').hide();

						$('.VOLT_PROD_CLIENT_CREDENTIALS').hide();
						$('.VOLT_PROD_CUSTOMER_CREDENTIALS').hide();
					}
				}

				checkVoltEnv(voltEnvValue);

				$("input[name=VOLT_ENV_SWITCH]").click(function () {
					checkVoltEnv($(this).val());
				})

			});

	</script>

</div>
