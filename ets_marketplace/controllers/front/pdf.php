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
class Ets_MarketPlacePdfModuleFrontController extends ModuleFrontController
{
    public $seller;
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
	}
    public function postProcess()
    {
        parent::postProcess();
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller,'orders'))
            die($this->module->l('You do not have permission','pdf'));
        if(Tools::getValue('submitAction')=='generateInvoicePDF')
            $this->processGenerateInvoicePDF();
        if(Tools::getValue('submitAction')=='generateDeliverySlipPDF')
            $this->processGenerateDeliverySlipPDF();
            
    }
    public function generateInvoicePDFByIdOrder($id_order)
    {
        $order = new Order((int) $id_order);
        if (!Validate::isLoadedObject($order)) {
            die($this->module->l('The order cannot be found within your database.','pdf'));
        }
        if(!$this->checkOrderValidateSeller($order->id))
            die($this->module->l('The invoice was not found.','pdf'));
        $order_invoice_list = $order->getInvoicesCollection();
        $this->generatePDF($order_invoice_list, PDF::TEMPLATE_INVOICE);
    }
    public function processGenerateInvoicePDF()
    {
        if (Tools::isSubmit('id_order')) {
            $this->generateInvoicePDFByIdOrder(Tools::getValue('id_order'));
        } elseif (Tools::isSubmit('id_order_invoice')) {
            $this->generateInvoicePDFByIdOrderInvoice(Tools::getValue('id_order_invoice'));
        } else {
            die($this->module->l('The order ID -- or the order invoice ID -- is missing.','pdf'));
        }
    }
    public function generateInvoicePDFByIdOrderInvoice($id_order_invoice)
    {
        $order_invoice = new OrderInvoice((int) $id_order_invoice);
        if (!Validate::isLoadedObject($order_invoice)) {
            die($this->module->l('The order invoice cannot be found within your database.','pdf'));
        }
        if(!$this->checkOrderValidateSeller($order_invoice->id_order))
            die($this->module->l('The invoice was not found.','pdf'));
        $this->generatePDF($order_invoice, PDF::TEMPLATE_INVOICE);
    }

    public function generatePDF($object, $template)
    {
        $pdf = new PDF($object, $template, Context::getContext()->smarty);
        $pdf->render();
        die();
    }
    public function processGenerateDeliverySlipPDF()
    {
        if (Tools::isSubmit('id_order')) {
            $this->generateDeliverySlipPDFByIdOrder((int) Tools::getValue('id_order'));
        } elseif (Tools::isSubmit('id_order_invoice')) {
            $this->generateDeliverySlipPDFByIdOrderInvoice((int) Tools::getValue('id_order_invoice'));
        } elseif (Tools::isSubmit('id_delivery')) {
            $order = Order::getByDelivery((int) Tools::getValue('id_delivery'));
            $this->generateDeliverySlipPDFByIdOrder((int) $order->id);
        } else {
            die($this->module->l('The order ID -- or the order invoice ID -- is missing.','pdf'));
        }
    }
    public function generateDeliverySlipPDFByIdOrder($id_order)
    {
        $order = new Order((int) $id_order);
        if (!Validate::isLoadedObject($order)) {
            die($this->module->l('Can\'t load Order object','pdf'));
        }
        if(!$this->checkOrderValidateSeller($order->id))
            die($this->module->l('The invoice was not found.','pdf'));
        $order_invoice_collection = $order->getInvoicesCollection();
        $this->generatePDF($order_invoice_collection, PDF::TEMPLATE_DELIVERY_SLIP);
    }

    public function generateDeliverySlipPDFByIdOrderInvoice($id_order_invoice)
    {
        $order_invoice = new OrderInvoice((int) $id_order_invoice);
        if(!$this->checkOrderValidateSeller($order_invoice->id_order))
            die($this->module->l('The invoice was not found.','pdf'));
        if (!Validate::isLoadedObject($order_invoice)) {
            die($this->module->l('Can\'t load Order Invoice object','pdf'));
        }
        if(!$this->checkOrderValidateSeller($order_invoice->id_order))
            die($this->module->l('The invoice was not found.','pdf'));
        $this->generatePDF($order_invoice, PDF::TEMPLATE_DELIVERY_SLIP);
    }
    public function checkOrderValidateSeller($id_order)
    {
        return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_order` WHERE id_order="'.(int)$id_order.'" AND id_customer="'.(int)$this->seller->id_customer.'"');
    }
}