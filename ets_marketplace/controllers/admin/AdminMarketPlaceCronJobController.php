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
class AdminMarketPlaceCronJobController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
    }
    public function initContent()
    {
        parent::initContent();
        if(Tools::isSubmit('ETS_MP_SAVE_CRONJOB_LOG'))
        {
            Configuration::updateGlobalValue('ETS_MP_SAVE_CRONJOB_LOG',Tools::getValue('ETS_MP_SAVE_CRONJOB_LOG'));
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Updated successfully'),
                    )
                )
            );
        }
        if(Tools::isSubmit('etsmpSubmitClearLog'))
        {
            if(file_exists(dirname(__FILE__).'/../../cronjob_log.txt'))
                @unlink(dirname(__FILE__).'/../../cronjob_log.txt');
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Clear log successfully'),
                    )
                )
            );
        }
        if(Tools::isSubmit('etsmpSubmitUpdateToken'))
        {
            if(Tools::getValue('ETS_MP_CRONJOB_TOKEN'))
            {
                Configuration::updateGlobalValue('ETS_MP_CRONJOB_TOKEN',Tools::getValue('ETS_MP_CRONJOB_TOKEN'));
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->l('Updated successfully'),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->l('Token is required'),
                        )
                    )
                );
            }   
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> $this->_renderCronjob(),
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
    public function _renderCronjob()
    {
        if(!Configuration::getGlobalValue('ETS_MP_CRONJOB_TOKEN'))
            Configuration::updateGlobalValue('ETS_MP_CRONJOB_TOKEN',Tools::passwdGen(12));
        $cronjob_last= '';
        $run_cronjob = false;
        if(file_exists(dirname(__FILE__).'/../../cronjob_time.txt') && $cronjob_time = Tools::file_get_contents(dirname(__FILE__).'/../../cronjob_time.txt'))
        {
            $last_time = strtotime($cronjob_time);
            $time = strtotime(date('Y-m-d H:i:s'))-$last_time;
            if($time <= 43200 && $time)
                $run_cronjob = true;
            else
                $run_cronjob = false;
            if($time > 86400)
                $cronjob_last = Tools::displayDate($cronjob_time,null,true);
            elseif($time)
            {
                if($hours =floor($time/3600))
                {
                    $cronjob_last .= $hours.' '.$this->l('hours').' ';
                    $time = $time%3600;
                }
                if($minutes = floor($time/60))
                {
                    $cronjob_last .= $minutes.' '.$this->l('minutes').' ';
                    $time = $time%60;
                }
                if($time)
                    $cronjob_last .= $time.' '.$this->l('seconds').' ';
                $cronjob_last .= $this->l('ago');
            }    
        }
        /* _ARM_ Debug: Transform $this with $module */
        $module = Module::getInstanceByName('ets_marketplace');
        $this->context->smarty->assign(
            array(
                'dir_cronjob' => dirname(__FILE__).'/cronjob.php',
                'link_conjob' => $module->getBaseLink().'/modules/'.$module->name.'/cronjob.php',
                'ETS_MP_CRONJOB_TOKEN' => Tools::getValue('ETS_MP_CRONJOB_TOKEN',Configuration::getGlobalValue('ETS_MP_CRONJOB_TOKEN')),
                'cronjob_log' => file_exists(dirname(__FILE__).'/../../cronjob_log.txt') ? Tools::file_get_contents(dirname(__FILE__).'/../../cronjob_log.txt'):'',
                'ETS_MP_SAVE_CRONJOB_LOG' => Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'),
                'run_cronjob' => $run_cronjob,
                'cronjob_last' => $cronjob_last,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/cronjob.tpl');
    }
}