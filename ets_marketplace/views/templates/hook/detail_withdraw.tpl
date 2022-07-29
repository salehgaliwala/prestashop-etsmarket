{*
* 2007-2020 ETS-Soft
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
* 
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs, please contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2020 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-body pb-0">
                <div class="info-box">
                    <h3>{l s='Withdrawals' mod='ets_marketplace'} #{$withdraw_detail.id_ets_mp_withdrawal|intval}</h3>
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-5 pl-15 pr-15">
                                    <div class="ets_mp-title-section">
                                        <h3 class="h-title">{l s='Commission status' mod='ets_marketplace'}</h3>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="row">
                    						<label class="control-label col-lg-6 col-sm-6"><em>{l s='Shop name' mod='ets_marketplace'}</em></label>
                    						<div class="col-lg-6 col-sm-6">
                    							<p class="form-control-static">{if $seller->id} <a href="{$seller->getLink()|escape:'html':'UTF-8'}" title="{l s='View shop' mod='ets_marketplace'}" target="_blank">{$seller->shop_name|escape:'html':'UTF-8'}</a>
													{else}
														<span class="deleted_shop row_deleted">{l s='Shop deleted' mod='ets_marketplace'}</span>
													{/if}
												</p>
                    						</div>
                    					</div>
                                        <div class="row">
                                            <label class="control-label col-sm-6 col-lg-6">
                                                <em>{l s='Total commission balance' mod='ets_marketplace'}</em>
                                                <i class="fa fa-question-circle">
												    <span class="ets_tooltip" data-tooltip="top">{l s='The remaining amount of commission after converting into voucher, withdrawing, paying for orders or being deducted by marketplace admin' mod='ets_marketplace'}</span>
												</i>
                                            </label>
                                            <div class="col-sm-6 col-lg-6">
                                                <p class="form-control-static" id="total_commission">{displayPrice price = $seller->getTotalCommission(1)-$seller->getToTalUseCommission(1)}</p>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <label class="control-label col-sm-6 col-lg-6">
                                                <em>{l s='Total commission' mod='ets_marketplace'}</em>
                                                <i class="fa fa-question-circle">
														<span class="ets_tooltip" data-tooltip="top">{l s='Total commission money this seller has earned' mod='ets_marketplace'}</span>
												</i>
                                            </label>
                                            <div class="col-sm-6 col-lg-6">
                                                <p class="form-control-static" id="total_commission">{displayPrice price=$seller->getTotalCommission(1)}</p>
                                            </div> 
                                        </div>
                                        <div class="row">
            								<label class="control-label col-sm-6 col-lg-6"><em>{l s='Withdrawn' mod='ets_marketplace'}</em>
            									<i class="fa fa-question-circle">
														<span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has withdrawn' mod='ets_marketplace'}</span>
												</i>
            								</label>
            								<div class="col-sm-6 col-lg-6">
            									<p class="form-control-static" id="total_withdrawn">{displayPrice price=$seller->getToTalUseCommission(1,false,false,true)}</p>
            								</div>
            							</div>
                                        <div class="row">
            								<label class="control-label col-sm-6 col-lg-6"><em>{l s='Paid for orders' mod='ets_marketplace'}</em>
            									<i class="fa fa-question-circle">
														<span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has used to pay for orders' mod='ets_marketplace'}</span>
												</i>
            								</label>
            								<div class="col-sm-6 col-lg-6">
            									<p class="form-control-static" id="total_paid_for_orders">{displayPrice price=$seller->getToTalUseCommission(1,true)}</p>
            								</div>
            							</div>
                                        <div class="row">
            								<label class="control-label col-sm-6 col-lg-6"><em>{l s='Converted to voucher' mod='ets_marketplace'}</em>
            									<i class="fa fa-question-circle">
														<span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has used to convert into vouchers' mod='ets_marketplace'}</span>
												</i>

            								</label>
            								<div class="col-sm-6 col-lg-6">
            									<p class="form-control-static" id="total_convert_to_voucher">{displayPrice price=$seller->getToTalUseCommission(1,false,true)}</p>
            								</div>
            							</div>
                                        <div class="row">
            								<label class="control-label col-sm-6 col-lg-6"><em>{l s='Total used' mod='ets_marketplace'}</em>
            									<i class="fa fa-question-circle">
														<span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has withdrawn, paid for orders, converted into vouchers and commission money deducted by marketplace admin' mod='ets_marketplace'}</span>
												</i>
            								</label>
            								<div class="col-sm-6 col-lg-6">
            									<p class="form-control-static" id="total_commission_used">{displayPrice price=$seller->getToTalUseCommission(1)}</p>
            								</div>
            							</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-7 pl-15 pr-15">
                                    <div class="ets_mp-title-section">
                                        <h3 class="h-title">{l s='Withdrawal info' mod='ets_marketplace'}</h3>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="row">
 											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Withdrawal amount' mod='ets_marketplace'}</em> :</label>
											<div class="col-lg-6 col-sm-6">
												<p class="form-control-static">{displayPrice price=$withdraw_detail.amount} <em>({l s='Withdrawal fee included' mod='ets_marketplace'})</em> </p>
											</div>
  										</div>
                                        {if $withdraw_detail.status==0}
                                            <div class="row">
     											<label class="control-label col-lg-6 col-sm-6">&nbsp;</label>
    											<div class="col-lg-6 col-sm-6 withdraw_info">
                                                    {assign var ='total_commission_balance' value = $seller->getTotalCommission(1)-$seller->getToTalUseCommission(1)+$withdraw_detail.amount}
    												{if $withdraw_detail.amount > $total_commission_balance}
                                                        <span class="ets_mp_status invalid">{l s='Invalid withdrawal (insufficient balance)' mod='ets_marketplace'}</span>
                                                    {else}
                                                        <span class="ets_mp_status valid">{l s='Valid withdrawal' mod='ets_marketplace'}</span>
                                                    {/if}
    											</div>
      										</div>
                                        {/if}
                                        <div class="row">
											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Amount to pay' mod='ets_marketplace'}</em>
												:
											</label>
											<div class="col-lg-6 col-sm-6">
												<p class="form-control-static">{displayPrice price=$withdraw_detail.pay_amount}</p>
											</div>
										</div>
                                        <div class="row">
											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Payment method' mod='ets_marketplace'}</em>
												:
											</label>
											<div class="col-lg-6 col-sm-6">
												<p class="form-control-static">{$withdraw_detail.payment_name|escape:'html':'UTF-8'}</p>
											</div>
										</div>
                                        <div class="row">
											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Fee type' mod='ets_marketplace'}</em>
												:
											</label>
											<div class="col-lg-6 col-sm-6">
                                                <p class="form-control-static">
    												{if $withdraw_detail.fee_type=='NO_FEE'}
                                                        {l s='Free' mod='ets_marketplace'}
                                                    {elseif $withdraw_detail.fee_type=='FIXED'}
                                                        {l s='Fixed' mod='ets_marketplace'}
                                                    {else}
                                                        {l s='Percent' mod='ets_marketplace'}
                                                    {/if}
                                                </p>
											</div>
										</div> 
                                        {if $withdraw_detail.fee!=0}
                                            <div class="row">
    											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Fee value' mod='ets_marketplace'}</em>
    												:
    											</label>
    											<div class="col-lg-6 col-sm-6">
    												<p class="form-control-static">{displayPrice price=$withdraw_detail.fee|escape:'html':'UTF-8'}</p>
    											</div>
    										</div>
                                        {/if}
                                        {if $withdraw_fields}
                                            {foreach from = $withdraw_fields item='withdraw_field'}
                                                <div class="row">
        											<label class="control-label col-lg-6 col-sm-6 col-xs-6"><em>{if $withdraw_field.id_ets_mp_payment_method_field}{$withdraw_field.title|escape:'html':'UTF-8'}{else}{l s='Invoice' mod='ets_marketplace'}{/if}</em>
        												:
        											</label>
        											<div class="col-lg-6 col-sm-6 col-xs-6">
        												<p class="form-control-static">{if $withdraw_field.id_ets_mp_payment_method_field}{$withdraw_field.value|escape:'html':'UTF-8'}{else}<a href="../upload/ets_marketplace/mp_withdraw/{$withdraw_field.value|escape:'html':'UTF-8'}" target="_blank">{$withdraw_field.value|escape:'html':'UTF-8'}</a>{/if}</p>
        											</div>
        										</div>
                                            {/foreach}
                                        {/if}
                                        <div class="row">
											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Date of withdrawal' mod='ets_marketplace'}</em>
												:
											</label>
											<div class="col-lg-6 col-sm-6">
												<p class="form-control-static">{dateFormat date=$withdraw_detail.date_add full=1}</p>
											</div>
										</div>
                                        <div class="row">
											<label class="control-label col-lg-6 col-sm-6"><em>{l s='Status' mod='ets_marketplace'}</em>
												:
											</label>
											<div class="col-lg-6 col-sm-6">
												<p class="form-control-static">
                                                    {if $withdraw_detail.status==0}
                                                        <label class="label label-warning ets_mp_status pending" style="margin-top: 0;">{l s='Pending' mod='ets_marketplace'}</label>
                                                    {/if}
                                                    {if $withdraw_detail.status==1}
                                                        <label class="label label-success ets_mp_status approved" style="margin-top: 0;">{l s='Approved' mod='ets_marketplace'}</label>
                                                    {/if}
                                                    {if $withdraw_detail.status==-1}
                                                        <label class="label label-default ets_mp_status declined" style="margin-top: 0;">{l s='Declined' mod='ets_marketplace'}</label>
                                                    {/if}
                                                </p>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 pl-15 pr-15">
                            <div class="form-group withdraw_header">
                                {if $withdraw_detail.status==0}
                                    <form action="" method="POST" accept-charset="utf-8">
                                        <button onclick="return confirm('{l s='Do you want to approve withdraw?' mod='ets_marketplace'}');" class="btn btn-default js-confirm-approve-withdraw mb-5" type="submit" name="approveets_withdraw">
                                            <i class="fa fa-check"></i>
                                            {l s='Approve' mod='ets_marketplace'}
                                        </button>
                                        <button onclick="return confirm('{l s='Do you want to decline with return commission this withdrawal?' mod='ets_marketplace'}');" class="btn btn-default js-confirm-approve-withdraw mb-5" type="submit" name="returnets_withdraw">
                                            <i class="fa fa-undo"></i>
                                            {l s='Decline - Return commission' mod='ets_marketplace'}
                                        </button>
                                        <button class="btn btn-default js-confirm-approve-withdraw mb-5" type="submit" name="deductets_withdraw">
                                            <i class="fa fa-close"></i>
                                            {l s='Decline - Deduct commission' mod='ets_marketplace'}
                                        </button>
										<a class="btn btn-default" onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_marketplace'}');" href="{$link->getAdminLink('AdminMarketPlaceWithdrawals')|escape:'html':'UTF-8'}&id_ets_mp_withdrawal={$withdraw_detail.id_ets_mp_withdrawal|intval}&del=yes"><i class="icon-trash"></i> {l s='Delete' mod='ets_marketplace'}</a>

									</form>
								{else}
									<div class="row">
										<a class="btn btn-default" onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_marketplace'}');" href="{$link->getAdminLink('AdminMarketPlaceWithdrawals')|escape:'html':'UTF-8'}&id_ets_mp_withdrawal={$withdraw_detail.id_ets_mp_withdrawal|intval}&del=yes"><i class="icon-trash"></i> {l s='Delete' mod='ets_marketplace'}</a>
									</div>
                                {/if}
                            </div>
                            <div class="divider-horizontal"></div>
                            <div class="form-group">
                                <form action="" method="POST" accept-charset="utf-8">
									<div class="form-group">
										<label><strong>{l s='Description' mod='ets_marketplace'}</strong>
										</label>
										<textarea name="note" rows="3">{$withdraw_detail.note nofilter}</textarea>
									</div>
									<input name="id_ets_mp_commission_usage" value="{$withdraw_detail.id_ets_mp_commission_usage|intval}" type="hidden" />
									<div class="form-group">
										<button type="submit" name="submitSaveNoteWithdrawal" class="btn btn-default"><i class="fa fa-save"></i> {l s='Save' mod='ets_marketplace'}</button>
									</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider-horizontal"></div>
                <div class="row">
                    <div class="col-lg-12">
							<a href="{$link->getAdminLink('AdminMarketPlaceWithdrawals')|escape:'html':'UTF-8'}" title="" class="btn btn-default"><i class="fa fa-close eam-icon-back"></i> {l s='Back' mod='ets_marketplace'}</a>
				    </div>
                </div>
            </div>
        </div>
    </div>
</div>