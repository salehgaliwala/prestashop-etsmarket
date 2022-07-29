<?php
/**
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
 * needs please contact us for extra customization service at an affordable price
 *
 *  @author ETS-Soft <etssoft.jsc@gmail.com>
 *  @copyright  2007-2020 ETS-Soft
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

if (!defined('_PS_VERSION_'))
    	exit;
class AdminMarketPlaceSellersController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
    }
    public function postProcess()
    {
        parent::postProcess();
        if(Tools::isSubmit('getmapseller') && $id_seller= Tools::getValue('id_seller'))
        {
            Ets_mp_seller::getMaps($id_seller);
        }
        if(Tools::isSubmit('deletelogo') && $id_seller= Tools::getValue('id_seller'))
        {      
            $seller = new Ets_mp_seller($id_seller);
            $shop_logo = $seller->shop_logo;
            $seller->shop_logo='';
            if($seller->update(true))
            {
                if($shop_logo && file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_logo))
                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_logo);
                $this->context->cookie->success_message = $this->module->l('Deleted logo successfully', 'adminmarketplacesellerscontroller');
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceSellers').'&editets_seller=1&id_seller='.$seller->id);
            }
        }
        if(Tools::isSubmit('deletebanner') && $id_seller= Tools::getValue('id_seller'))
        {
            $id_lang = Tools::getValue('id_lang');
            $seller = new Ets_mp_seller($id_seller);
            $shop_banner = $seller->shop_banner[$id_lang];
            $seller->shop_banner[$id_lang]='';
            if($seller->update(true))
            {
                if($shop_banner && !in_array($shop_banner,$seller->shop_banner) && file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_banner))
                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner);
                $this->context->cookie->success_message = $this->module->l('Deleted banner successfully', 'adminmarketplacesellerscontroller');
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceSellers').'&editets_seller=1&id_seller='.$seller->id);
            }
        }
        if(Tools::isSubmit('saveStatusSeller') && $id_seller = (int)Tools::getValue('seller_id'))
        {
            $seller = new Ets_mp_seller($id_seller);
            $active_old = $seller->active;
            $error = '';
            if(Tools::getValue('active_seller')==0 || Tools::getValue('active_seller')==-3)
            {
                $seller->active = (int)Tools::getValue('active_seller');
                $seller->reason = Tools::getValue('reason');
                if(Tools::getValue('reason') && !Validate::isCleanHtml(Tools::getValue('reason')))
                    $error = $this->l('Reason is not valid');
            }    
            else{
                if(Tools::getValue('date_from') && !Validate::isDate(Tools::getValue('date_from')) && Tools::getValue('date_to') && !Validate::isDate(Tools::getValue('date_to')))
                    $error = $this->l('"From" date and "To" date are not valid');
                elseif(Tools::getValue('date_from') && !Validate::isDate(Tools::getValue('date_from')))
                    $error = $this->l('"From" date is not valid');
                elseif(Tools::getValue('date_to') && !Validate::isDate(Tools::getValue('date_to')))
                    $error = $this->l('"To" date is not valid');
                elseif(Tools::getValue('date_to') && Tools::getValue('date_from') && Validate::isDate(Tools::getValue('date_to')) && Validate::isDate(Tools::getValue('date_from')) && strtotime(Tools::getValue('date_from')) >= strtotime(Tools::getValue('date_to')))
                    $error = $this->l('"From" date must be smaller than "To" date');
                $seller->date_from = Tools::getValue('date_from');
                $seller->date_to = Tools::getValue('date_to');
                if((!$seller->date_from || strtotime($seller->date_from) <= strtotime(date('Y-m-d'))) && (!$seller->date_to || strtotime($seller->date_to) >= strtotime(date('Y-m-d'))))
                {
                    $seller->active =1;
                    $seller->mail_expired=0;
                    $seller->mail_going_to_be_expired=0;
                    //$seller->payment_verify=0;
                }
                else
                {
                    $seller->active =-2;
                    $seller->payment_verify=-1;
                }
            }
            $seller->date_upd = date('Y-m-d H:i:s');
            if($error)
            {
                if(Tools::isSubmit('ajax'))
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'errors' => $error
                            )
                        )
                    );
                }
            }
            else
            {
                if($seller->update(true))
                {
                    if($seller->active!=$active_old && $seller->active==-2)
                    {
                        $fee_type = $seller->getFeeType();
                        if($fee_type!='no_fee')
                        {
                            $billing = new Ets_mp_billing();
                            $billing->id_customer = $seller->id_customer;
                            $billing->amount = (float)$seller->getFeeAmount();
                            $billing->amount_tax = $this->module->getFeeIncludeTax($billing->amount,$seller);
                            $billing->active = 0;
                            $billing->date_from = $seller->date_to;
                            if($fee_type=='monthly_fee')
                                $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+1 month"));
                            elseif($fee_type=='quarterly_fee')
                                $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+3 month"));
                            elseif($fee_type=='yearly_fee')
                                $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+1 year"));
                            else
                                $billing->date_to ='';
                            $billing->fee_type = $fee_type;
                            if($billing->add(true,true))
                            {
                                $seller->id_billing = $billing->id;
                                $seller->update();
                            }
                        }
                    }
                    if($seller->active==1)
                        $status = '<'.'sp'.'an cla'.'ss="ets_mp_status actived">'.$this->l('Active').'<'.'/'.'span'.'>';
                    elseif($seller->active==-2)
                        $status = '<'.'spa'.'n cla'.'ss="ets_mp_status expired">'.$this->l('Expired').'<'.'/span'.'>';
                    elseif($seller->active==0)
                        $status = '<'.'sp'.'an cl'.'ass="ets_mp_status disabled">'.$this->l('Disabled').'<'.'/sp'.'an'.'>';
                    elseif($seller->active==-3)
                        $status = '<'.'s'.'pan cla'.'ss="ets_mp_status declined">'.$this->l('Declined payment').'<'.'/'.'sp'.'an'.'>';
                    else
                        $status='';

                    if(isset($billing) && $billing->id)
                        $payment_verify ='<'.'sp'.'an cla'.'ss="ets_mp_status awaiting_payment">'.$this->l('Pending').'<'.'/'.'spa'.'n'.'>';
                    else
                        $payment_verify ='';       
                    if($seller->date_from || $seller->date_to)
                        $date_approved = ($seller->date_from && $seller->date_from!='0000-00-00' ? $this->l('from'). ' '.Tools::displayDate($seller->date_from,null,false):'').' '.($seller->date_to && $seller->date_to!='0000-00-00' ? $this->l('to'). ' '.Tools::displayDate($seller->date_to):'');
                    else
                        $date_approved = $this->l('unlimited');
                    if(Tools::isSubmit('ajax'))
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'success' => $this->l('Updated successfully'),
                                    'status' => $status,
                                    'active' => $seller->active,
                                    'id_seller' => $seller->id,
                                    'date_approved' => $date_approved,
                                    'payment_verify' =>$payment_verify,
                                )
                            )
                        );
                    }
                }
                else
                {
                    if(Tools::isSubmit('ajax'))
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'errors' => $this->l('Update failed'),
                                )
                            )
                        );
                    }
                }
            }
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> Ets_mp_seller::getInstance()->_renderSellers(),
            )
        );
        $html ='';
        if($this->context->cookie->success_message)
        {
            $html .= $this->module->displayConfirmation($this->context->cookie->success_message);
            $this->context->cookie->success_message ='';
        }
        if($this->module->_errors)
            $html .= $this->module->displayError($this->module->_errors);
        return $html.$this->module->display(_PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.$this->module->name.'.php', 'admin.tpl');
    }
}