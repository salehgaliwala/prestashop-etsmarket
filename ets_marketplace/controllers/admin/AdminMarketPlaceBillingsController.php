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
class AdminMarketPlaceBillingsController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
       if(Tools::isSubmit('dowloadpdf') && $id_billing = Tools::getValue('id_ets_mp_seller_billing'))
       {
            $billing = new Ets_mp_billing($id_billing);
            if(Validate::isLoadedObject($billing))
            {
                $pdf = new PDF($billing,'BillingPdf', Context::getContext()->smarty);
                $pdf->render(true);
            }
            else
                $this->module->_errors[] = $this->l('Billing is not valid');
       }
       if(Tools::isSubmit('saveBilling'))
       {
            if(!($id_seller =Tools::getValue('id_seller')))
            {
                $this->module->_errors[] = $this->l('Seller is required');
            }
            elseif(!Validate::isUnsignedId($id_seller) || !Validate::isLoadedObject(Ets_mp_seller::_getSellerByIdCustomer($id_seller)))
                $this->module->_errors[] = $this->l('Seller is not valid');
            if(!Tools::getValue('amount'))
                $this->module->_errors[] = $this->l('Amount is required');
            elseif(!Validate::isPrice(Tools::getValue('amount')))
                $this->module->_errors[] = $this->l('Amount is not valid');
            if(Tools::getValue('note') && !Validate::isCleanHtml(Tools::getValue('note')))
                $this->module->_errors[] = $this->l('Description is not valid');
            if(Tools::getValue('date_from') && !Validate::isDate(Tools::getValue('date_from')))
                $this->module->_errors[] = $this->l('"From" date is not valid');
            if(Tools::getValue('date_to') && !Validate::isDate(Tools::getValue('date_to')))
                $this->module->_errors[] = $this->l('"To" date is not valid');
            if(Tools::getValue('date_to') && Tools::getValue('date_from') && Validate::isDate(Tools::getValue('date_to')) && Validate::isDate(Tools::getValue('date_from')) && strtotime(Tools::getValue('date_from')) > strtotime(Tools::getValue('date_to')))
                $this->module->_errors[] = $this->l('"From" date must be smaller than "To" date'); 
            if(!$this->module->_errors)
            {
                $billing = new Ets_mp_billing();
                $billing->id_customer = (int)Tools::getValue('id_seller');
                $billing->amount = (float)Tools::getValue('amount');
                $billing->note = Tools::getValue('note');
                $billing->date_from = Tools::getValue('date_from');
                $billing->date_to = Tools::getValue('date_to');
                $billing->active= (int)Tools::getValue('active');
                $billing->id_employee = $this->context->employee->id;
                $billing->used=1;
                if($billing->add())
                {
                    $this->context->cookie->success_message = $this->l('Added successfully');
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceBillings'));
                }
                else
                    $this->module->_errors[] = $this->l('An error occurred while saving the billing');
            }
       }
    }
    public function initContent()
    {
        parent::initContent();
        if(Tools::isSubmit('search_seller') && $query = Tools::getValue('q'))
        {
            $sql = 'SELECT s.id_customer,s.id_seller,c.email,CONCAT(c.firstname," ",c.lastname) as seller_name FROM `'._DB_PREFIX_.'ets_mp_seller` s
            INNER JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer =s.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller=sl.id_seller AND sl.id_lang="'.(int)$this->context->language->id.'")
            WHERE s.id_shop="'.(int)$this->context->shop->id.'" AND (CONCAT(c.firstname," ",c.lastname) LIKE "%'.pSQL(trim($query)).'%" OR sl.shop_name LIKE "%'.pSQL(trim($query)).'%" OR c.email LIKE "%'.pSQL(trim($query)).'%")';
            $sellers = Db::getInstance()->executeS($sql);
            if($sellers)
            {
                foreach($sellers as $seller)
                {
                    echo $seller['id_customer'].'|'.$seller['seller_name'].'|'.$seller['email']."\n";
                }
            }
            die();            
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        if(Tools::isSubmit('addnewbillng'))
        {
            $this->context->smarty->assign(
                array(
                    'ets_mp_body_html'=> Ets_mp_billing::getInstance()->_renderFromBilling(),
                    'ets_link_search_seller' => $this->context->link->getAdminLink('AdminMarketPlaceBillings').'&search_seller=1',
                )
            );
        }
        else
        {
            $this->context->smarty->assign(
                array(
                    'ets_mp_body_html'=> Ets_mp_billing::getInstance()->_renderBilling(),
                )
            );
        }
        
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