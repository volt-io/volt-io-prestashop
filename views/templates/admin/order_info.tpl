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
<div class="row">
	<div class="col-lg-12">
		<div class="panel card">
			<div class="card-header">
				<h3 class="card-header-title">
					<i class="material-icons">swap_horiz</i>
                    {l s='Volt refunds' mod='volt'}
				</h3>
			</div>

			<div class="card-body">
				{if $all_refunds}
					<table class="table">
					<thead>
					<tr>
						<th>
							<p>{l s='Refund ID' mod='volt'}</p>
						</th>
						<th>
							<p>{l s='Amount' mod='volt'}</p>
						</th>
						<th>
							<p>{l s='Reference' mod='volt'}</p>
						</th>
						<th>
							<p>{l s='Currency' mod='volt'}</p>
						</th>
						<th>
							<p>{l s='Status' mod='volt'}</p>
						</th>
					</tr>
					</thead>
                    {foreach from=$all_refunds item=ref}

						<tbody>
						<tr>
							<td>
                                {$ref['crc']}
							</td>
							<td>
                                {$ref['amount']/100|string_format:"%.2f"}
							</td>
							<td>
                                reference
							</td>
							<td>
                                {$ref['currency']}
							</td>
							<td>
                                {$ref['status']}
							</td>
						</tr>
						</tbody>
					{/foreach}
					</table>
				{/if}



                {if $_errors|count}
					<div role="alert" class="alert alert-danger">
						<p class="alert-text">
                            {foreach from=$_errors item=msg}
                                {$msg}
                            {/foreach}
						</p>
					</div>
                {/if}

                {if $_success|count}
					<div role="alert" class="alert alert-success">
						<p class="alert-text">
                            {foreach from = $_success item = msg}
                                {$msg}
                            {/foreach}
						</p>
					</div>
                {/if}


				<div class="row">
					<div class="col-sm-12">
                        {if $is_refundable}
							<form action=""
							      method="post"
							      onsubmit="return confirm('{l s='Do you really want to submit the refund request?' mod='volt'}');">

								<input type="hidden" name="volt-refund" value="1">

								<div class="row align-items-end">
									<div class="col-sm-12 col-md-4">
										<label for="volt_refund_type">
                                            {l s='Refund type' mod='volt'}
										</label>
										<select id="volt_refund_type" name="volt_refund_type" class="custom-select">
											<option value="full"{if $typeRefund eq "full"} selected="selected"{/if}>
                                                {l s='Full' mod='volt'}
											</option>
											<option value="partial"{if $typeRefund eq "partial"} selected="selected"{/if}>
                                                {l s='Partial' mod='volt'}
											</option>
										</select>
									</div>

									<div class="col-sm-12 col-md-4">
										<label for="volt_refund_amount">
                                            {l s='Amount' mod='volt'}
										</label>
										<input type="text" class="form-control" id="volt_refund_amount"
										       name="volt_refund_amount"
										       value="{$refund_amount|escape:'htmlall':'UTF-8'}"/>
									</div>

									<div class="col-sm-12 col-md-4">
										<button class="btn btn-primary">
                                            {l s='Order return' mod='volt'}
										</button>
									</div>
								</div>

							</form>
							<script>
                                {literal}
								$(document).ready(function () {
									var refund_type_select = $('#volt_refund_type');
									var set_type = function (type) {
										if ('full' === type) {
											$('#volt_refund_amount').attr('readonly', true).val('{/literal}{$refund_all|escape:'htmlall':'UTF-8'}{literal}');
										} else {
											$('#volt_refund_amount').attr('readonly', false);
										}
									};
									set_type(refund_type_select.val());
									refund_type_select.on('change', function () {
										set_type(refund_type_select.val());
									});
								});
                                {/literal}
							</script>
                        {else}
                            {l s='Returning an order is not available' mod='volt'}
                        {/if}

					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<style>
	.partial-refund-display {
		display: none;
    }
</style>
