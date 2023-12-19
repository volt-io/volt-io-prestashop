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
{block name="input_row"}
	<div class="form-group {$_input.name|escape:'html':'UTF-8'}{if isset($_input.form_group_class)} {$_input.form_group_class|escape:'html':'UTF-8'}{/if}"{if $_input.name == 'id_state'}
		id="contains_states"{if !$contains_states}
		style="display:none;"{/if}{/if}{if isset($tabs) && isset($_input.tab)}
	data-tab-id="{$_input.tab|escape:'html':'UTF-8'}"{/if}>
        {if $_input.type == 'hidden'}
			<input type="hidden" name="{$_input.name|escape:'html':'UTF-8'}" id="{$_input.name|escape:'html':'UTF-8'}"
			       value="{$fields_value[$_input.name|escape:'html':'UTF-8']|escape:'html':'UTF-8'}"/>
        {elseif $_input.type == 'description'}
			<div class="infoheading_class col-sm-12">
                {assign var=desc_template value=$_input.content|escape:'html':'UTF-8'}
                {include file="$desc_template"}
			</div>
        {elseif $_input.type == 'infoheading'}
	       <div class="control-label text-left text-lg-right col-xs-12 col-lg-3"></div>
			<div class="header-section col-xs-12 col-lg-5">
				{$_input.label|escape:'html':'UTF-8'}
			</div>
        {else}
            {block name="label"}
                {if isset($_input.label)}
					<label class="control-label text-left text-lg-right col-xs-12 col-lg-3
	{if isset($_input.required) && $_input.required} required{/if}">
                        {if isset($_input.hint)}
						<span class="label-tooltip"
						      data-toggle="tooltip" data-html="true"
						      title="{if is_array($_input.hint)}
										{foreach $_input.hint as $hint}
											{if is_array($hint)}
												{$hint.text|escape:'html':'UTF-8'}
											{else}
												{$hint|escape:'html':'UTF-8'}
											{/if}
										{/foreach}
										{else}
											{$_input.hint|escape:'html':'UTF-8'}
										{/if}">
									{/if}
                            {$_input.label|escape:'html':'UTF-8'}
                            {if isset($_input.doc)}
								<span class="doc_class">
									<a target="_blank" href="{$_input.doc|escape:'html':'UTF-8'}">?</a>
								</span>
                            {/if}
                            {if isset($_input.hint)}
										</span>
                        {/if}
					</label>
                {/if}
            {/block}

            {block name="field"}
				<div class="col-xs-12 col-lg-5 {if !isset($_input.label)}col-lg-offset-3{/if}">
                    {block name="input"}
                        {if $_input.type == 'text'}
                            {if isset($_input.lang) AND $_input.lang}
                                {if $languages|count > 1}
									<div class="form-group">
                                {/if}

                                {foreach $languages as $language}
                                    {if isset($fields_value[$_input.name][$language.id_lang])}
                                        {assign var='value_text' value=$fields_value[$_input.name|escape:'html':'UTF-8'][$language.id_lang|escape:'html':'UTF-8']}
                                    {else}
                                        {assign var='value_text' value=""}
                                    {/if}

                                    {if $languages|count > 1}
										<div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
										<div class="col-lg-9">
                                    {/if}

	                                {if isset($_input.maxchar) || isset($_input.prefix) || isset($_input.suffix)}
										<div class="input-group{if isset($_input.class|escape:'html':'UTF-8')} {$_input.class|escape:'html':'UTF-8'}{/if}">
	                                {/if}
                                    {if isset($_input.maxchar)}
										<span id="{if isset($_input.id)}{$_input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$_input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}_counter" class="input-group-addon">
											<span class="text-count-down">{$_input.maxchar|escape:'html':'UTF-8'}</span>
										</span>
                                    {/if}
                                    {if isset($_input.prefix)}
										<span class="input-group-addon">
											{$_input.prefix|escape:'html':'UTF-8'}
										</span>
                                    {/if}

									<input type="text"
									       id="{if isset($_input.id)}{$_input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$_input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}"
									       name="{$_input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}"
									       class="{if isset($_input.class)}{$_input.class|escape:'html':'UTF-8'}{/if}"
									       value="{if isset($_input.string_format) && $_input.string_format}{if isset($value_text) && !empty($value_text)}{$value_text|string_format:$_input.string_format|escape:'html':'UTF-8'}{else}{if isset($_input.default_val) && !empty($_input.default_val)}{$_input.default_val|string_format:$_input.string_format|escape:'html':'UTF-8'}{/if}{/if}{else}{if isset($value_text) && !empty($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if isset($_input.default_val) && !empty($_input.default_val)}{$_input.default_val|escape:'html':'UTF-8'}{/if}{/if}{/if}"
									       onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                            {if isset($_input.size)} size="{$_input.size|escape:'html':'UTF-8'}"{/if}
                                            {if isset($_input.maxchar)} data-maxchar="{$_input.maxchar|escape:'html':'UTF-8'}"{/if}
                                            {if isset($_input.maxlength)} maxlength="{$_input.maxlength|escape:'html':'UTF-8'}"{/if}
                                            {if isset($_input.readonly) && $_input.readonly} readonly="readonly"{/if}
                                            {if isset($_input.disabled) && $_input.disabled} disabled="disabled"{/if}
                                            {if isset($_input.autocomplete) && !$_input.autocomplete} autocomplete="off"{/if}
                                            {if isset($_input.required) && $_input.required} required="required" {/if}
                                            {if isset($_input.placeholder) && $_input.placeholder} placeholder="{$_input.placeholder|escape:'html':'UTF-8'}"{/if} />
		                                    {if isset($_input.suffix)}
												<span class="input-group-addon">
													{$_input.suffix|escape:'html':'UTF-8'}
												</span>
		                                    {/if}

	                                {if isset($_input.maxchar) || isset($_input.prefix) || isset($_input.suffix)}
										</div>
	                                {/if}

                                    {if $languages|count > 1}
										</div>
										<div class="col-lg-3">
											<div class="d-flex">
											<button type="button"
											        class="btn btn-default dropdown-toggle"
											        tabindex="-1"
											        data-toggle="dropdown">
                                                {$language.iso_code|escape:'html':'UTF-8'}
												<i class="icon-caret-down"></i>
											</button>

											<ul class="dropdown-menu">
                                                {foreach from=$languages item=language}
													<li>
														<a href="javascript:hideOtherLanguage({$language.id_lang|escape:'html':'UTF-8'});"
														   tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a>
													</li>
                                                {/foreach}
											</ul>

											</div>


										</div>
                                        {if isset($_input.help)}
											<div class="col-lg-12">
												<p class="help-text">{$_input.help|escape:'html':'UTF-8'}</p>
											</div>
                                        {/if}
										</div>

                                    {/if}
                                {/foreach}

                                {if $languages|count > 1}
									</div>
                                {/if}
                            {else}

                                {assign var='value_text' value=$fields_value[$_input.name|escape:'html':'UTF-8']}
                                {if isset($_input.maxchar) || isset($_input.prefix) || isset($_input.suffix)}
									<div class="input-group{if isset($_input.class)} {$_input.class|escape:'html':'UTF-8'}{/if}">
                                {/if}

                                {if isset($_input.prefix)}
									<span class="input-group-addon">
										  {$_input.prefix|escape:'html':'UTF-8'}
										</span>
                                {/if}
								<input type="text"
								       name="{$_input.name|escape:'html':'UTF-8'}"
								       id="{if isset($_input.id)}{$_input.id|escape:'html':'UTF-8'}{else}{$_input.name|escape:'html':'UTF-8'}{/if}"
								       value="{if isset($_input.string_format) && $_input.string_format}{if isset($value_text) && !empty($value_text)}{$value_text|string_format:$_input.string_format|escape:'html':'UTF-8'}{else}{if isset($_input.default_val) && !empty($_input.default_val)}{$_input.default_val|string_format:$_input.string_format|escape:'html':'UTF-8'}{/if}{/if}{else}{if isset($value_text) && !empty($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if isset($_input.default_val) && !empty($_input.default_val)}{$_input.default_val|escape:'html':'UTF-8'}{/if}{/if}{/if}"
								       class="{if isset($_input.class)}{$_input.class|escape:'html':'UTF-8'}{/if}"
                                        {if isset($_input.size)} size="{$_input.size|escape:'html':'UTF-8'}"{/if}
                                        {if isset($_input.maxchar)} data-maxchar="{$_input.maxchar|escape:'html':'UTF-8'}"{/if}
                                        {if isset($_input.maxlength)} maxlength="{$_input.maxlength|escape:'html':'UTF-8'}"{/if}
                                        {if isset($_input.readonly) && $_input.readonly} readonly="readonly"{/if}
                                        {if isset($_input.disabled) && $_input.disabled} disabled="disabled"{/if}
                                        {if isset($_input.autocomplete) && !$_input.autocomplete} autocomplete="off"{/if}
                                        {if isset($_input.required) && $_input.required } required="required" {/if}
                                        {if isset($_input.placeholder) && $_input.placeholder } placeholder="{$_input.placeholder|escape:'html':'UTF-8'}"{/if}
								/>

                                {if isset($_input.suffix)}
									<span class="input-group-addon">
										  {$_input.suffix|escape:'html':'UTF-8'}
										</span>
                                {/if}

                                {if isset($_input.help)}
									<p class="help-text">{$_input.help|escape:'html':'UTF-8'}</p>
                                {/if}

                                {if isset($_input.maxchar) || isset($_input.prefix) || isset($_input.suffix)}
									</div>
                                {/if}

                            {/if}


                        {elseif $_input.type == 'select'}
                            {if isset($_input.options.query) && !$_input.options.query && isset($_input.empty_message)}
                                {$_input.empty_message|escape:'html':'UTF-8'}
                                {$_input.required = false}
                                {$_input.desc = null}
                            {else}
								<select name="{$_input.name|escape:'html':'UTF-8'}"
								        class="{if isset($_input.class)}{$_input.class|escape:'html':'UTF-8'}{/if}"
								        id="{if isset($_input.id)}{$_input.id|escape:'html':'UTF-8'}{else}{$_input.name|escape:'html':'UTF-8'}{/if}"
                                        {if isset($_input.multiple)}multiple="multiple" {/if}
                                        {if isset($_input.size)}size="{$_input.size|escape:'html':'UTF-8'}"{/if}
                                        {if isset($_input.onchange)}onchange="{$_input.onchange|escape:'html':'UTF-8'}"{/if}>
                                    {if isset($_input.options.default)}
										<option value="{$_input.options.default.value|escape:'html':'UTF-8'}">{$_input.options.default.label|escape:'html':'UTF-8'}</option>
                                    {/if}
                                    {if isset($_input.options.optiongroup)}
                                        {foreach $_input.options.optiongroup.query AS $optiongroup}
											<optgroup
													label="{$optiongroup[$_input.options.optiongroup.label]|escape:'html':'UTF-8'}">
                                                {foreach $optiongroup[$_input.options.options.query] as $option}
													<option value="{$option[$_input.options.options.id]|escape:'html':'UTF-8'}"
                                                            {if isset($_input.multiple)}
                                                                {foreach $fields_value[$_input.name] as $field_value}
                                                                    {if $field_value == $option[$_input.options.options.id]}selected="selected"{/if}
                                                                {/foreach}
                                                            {else}
                                                                {if $fields_value[$_input.name] == $option[$_input.options.options.id]}selected="selected"{/if}
                                                            {/if}
													>{$option[$_input.options.options.name]|escape:'html':'UTF-8'}</option>
                                                {/foreach}
											</optgroup>
                                        {/foreach}
                                    {else}
                                        {foreach $_input.options.query AS $option}
                                            {if is_object($option)}
												<option value="{$option->$_input.options.id|escape:'html':'UTF-8'}"
                                                        {if isset($_input.multiple)}
                                                            {foreach $fields_value[$_input.name] as $field_value}
                                                                {if $field_value == $option->$_input.options.id}
																	selected="selected"
                                                                {/if}
                                                            {/foreach}
                                                        {else}
                                                            {if $fields_value[$_input.name] == $option->$_input.options.id}
																selected="selected"
                                                            {/if}
                                                        {/if}
												>{$option->$_input.options.name|escape:'html':'UTF-8'}</option>
                                            {elseif $option == "-"}
												<option value="">-</option>
                                            {else}
												<option value="{$option[$_input.options.id]|escape:'html':'UTF-8'}"
                                                        {if isset($_input.multiple)}
                                                            {foreach $fields_value[$_input.name] as $field_value}
                                                                {if $field_value == $option[$_input.options.id]}
																	selected="selected"
                                                                {/if}
                                                            {/foreach}
                                                        {else}
                                                            {if $fields_value[$_input.name] == $option[$_input.options.id]}
																selected="selected"
                                                            {/if}
                                                        {/if}
												>{$option[$_input.options.name]|escape:'html':'UTF-8'}</option>
                                            {/if}
                                        {/foreach}
                                    {/if}
								</select>
                            {/if}
                        {elseif $_input.type == 'radio'}
                            {foreach $_input.values as $value}
								<div class="radio {if isset($_input.class)}{$_input.class|escape:'html':'UTF-8'}{/if}">
                                    {strip}
										<label>
											<input type="radio"
											       name="{$_input.name|escape:'html':'UTF-8'}"
											       id="{$value.id|escape:'html':'UTF-8'}"
											       value="{$value.value|escape:'html':'UTF-8'}"{if $fields_value[$_input.name] == $value.value} checked="checked"{/if}{if isset($_input.disabled) && $_input.disabled} disabled="disabled"{/if}/>
                                            {$value.label|escape:'html':'UTF-8'}
										</label>
                                    {/strip}
								</div>
                                {if isset($value.p) && $value.p}<p
										class="help-block">{$value.p|escape:'html':'UTF-8'}</p>{/if}
                            {/foreach}

                        {elseif $_input.type == 'switch' || $_input.type == 'shop' || $_input.type == 'switch-choose' }
	                        <div class="d-flex">
                            {if isset($_input.image)}
		                        <img width="80" style="margin-right: 12px;" class="img-fluid"
		                             src="{$src_img|escape:'html':'UTF-8'}/helpers/{$_input.image|escape:'html':'UTF-8'}"/>
                            {/if}
							<span class="volt-switcher fixed-width-lg {if $_input.type == 'switch-choose'}volt-switcher--choose{/if}"

                                {if isset($_input.size) && $_input.size == 'auto'}
                                    style="width: 350px !important;"
                                {/if}

                                {if isset($_input.size) && $_input.size == 'full'}
                                    {if isset($_input.modal)}
	                                    style="width: calc(100% - 45px) !important;"
	                                    {else}
	                                    style="width: 100% !important;"
                                    {/if}

                                {/if}

                                >
								{foreach $_input.values as $value}
									<input type="radio" name="{$_input.name|escape:'html':'UTF-8'}"
									{if $value.value == 1}
										id="{$_input.name|escape:'html':'UTF-8'}_on"
                                    {else}
										id="{$_input.name|escape:'html':'UTF-8'}_off"
                                    {/if}
									value="{$value.value|escape:'html':'UTF-8'}"
									{if $fields_value[$_input.name] == $value.value}
										checked="checked"
                                    {/if}
                                    {if isset($_input.disabled) && $_input.disabled}
										disabled="disabled"
                                    {/if}
									/>
									{strip}
										<label {if $value.value == 1}
										for="{$_input.name|escape:'html':'UTF-8'}_on"{else}for="{$_input.name|escape:'html':'UTF-8'}_off"{/if}>
											{if $value.value == 1}{$value.label|escape:'html':'UTF-8'}{else}{$value.label|escape:'html':'UTF-8'}{/if}
										</label>
	                                {/strip}
                                {/foreach}
								<a class="slide-button btn"></a>
								</span>
	                        </div>


                            {if isset($_input.help)}
								<p class="help-text">{$_input.help|escape:'html':'UTF-8'}</p>
                            {/if}


                        {elseif $_input.type == 'checkbox'}
                            {if isset($_input.expand)}
								<a class="btn btn-default show_checkbox{if strtolower($_input.expand.default) == 'hide'} hidden{/if}"
								   href="#">
									<i class="icon-{$_input.expand.show.icon|escape:'html':'UTF-8'}"></i>
                                    {$_input.expand.show.text|escape:'html':'UTF-8'}
                                    {if isset($_input.expand.print_total) && $_input.expand.print_total > 0}
										<span class="badge">{$_input.expand.print_total|escape:'html':'UTF-8'}</span>
                                    {/if}
								</a>
								<a class="btn btn-default hide_checkbox{if strtolower($_input.expand.default) == 'show'} hidden{/if}"
								   href="#">
									<i class="icon-{$_input.expand.hide.icon|escape:'html':'UTF-8'}"></i>
                                    {$_input.expand.hide.text|escape:'html':'UTF-8'}
                                    {if isset($_input.expand.print_total) && $_input.expand.print_total > 0}
										<span class="badge">{$_input.expand.print_total|escape:'html':'UTF-8'}</span>
                                    {/if}
								</a>
                            {/if}
                            {foreach $_input.values.query as $value}
                                {assign var=id_checkbox value=$_input.name|cat:'_'|cat:$value[$_input.values.id]}
								<div class="checkbox{if isset($_input.expand) && strtolower($_input.expand.default) == 'show'} hidden{/if}">
                                    {strip}
										<label for="{$id_checkbox|escape:'html':'UTF-8'}">
											<input type="checkbox"
											       name="{$id_checkbox|escape:'html':'UTF-8'}"
											       id="{$id_checkbox|escape:'html':'UTF-8'}"
											       class="{if isset($_input.class)}{$_input.class|escape:'html':'UTF-8'}{/if}"{if isset($value.val)} value="{$value.val|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
                                            {$value[$_input.values.name]|escape:'html':'UTF-8'}
										</label>
                                    {/strip}
								</div>
                            {/foreach}
                        {elseif $_input.type == 'group'}
                            {assign var=groups value=$_input.values}
                            {include file='helpers/form/form_group.tpl'}
                        {elseif $_input.type == 'html'}
                            {if isset($_input.html_content)}
                                {$_input.html_content|escape:'htmlall':'UTF-8'}
                            {else}
                                {$_input.name|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/block}


				</div>
            {/block}
        {/if}
	</div>
{/block}
