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

/* _ARM_ Extends from Mail instead of MailCore */
class Ets_mp_email extends Mail
{
    public static $moduleName = 'ets_marketplace';
    public static function send(
        $idLang,
        $template,
        $subject,
        $templateVars,
        $to,
        $toName = null,
        $from = null,
        $fromName = null,
        $fileAttachment = null,
        $mode_smtp = null,
        $templatePath = _PS_MAIL_DIR_,
        $die = false,
        $idShop = null,
        $bcc = null,
        $replyTo = null,
        $replyToName = null
    )
    {
        if(is_array($to))
        {
            if(isset($to['customer']))
            {
                $toMail = $to['customer'];
                if(!$idLang)
                {
                    if(($customers = Customer::getCustomersByEmail($toMail)) && ($customer = $customers[0]) && ($lang = new Language($customer['id_lang'])) && $lang->id)
                        $idLang = $lang->id;
                }
            }
            elseif(isset($to['employee']))
            {
                $toMail = $to['employee'];
                if(!$idLang)
                {
                    $employeeObj = new Employee();
                    if(($employee = $employeeObj->getByEmail($toMail)) && ($lang = new Language($employee->id_lang)) && $lang->id)
                        $idLang = $employee->id_lang;
                }
            }
            else
                return false;
        }
        else
            $toMail = $to;
        if(!$idLang)
            $idLang = Context::getContext()->language->id;
        if(!$idLang)
            $idLang = Configuration::get('PS_LANG_DEFAULT');
        if(is_array($subject) && isset($subject['origin']) && isset($subject['translation']))
        {
            if(version_compare(_PS_VERSION_, '1.7', '>='))
            {
                $locale = isset($lang) ? $lang->locale : Language::getLocaleByIso(Language::getIsoById($idLang));
                $subject = Translate::getModuleTranslation(self::$moduleName, $subject['origin'], isset($subject['specific']) && $subject['specific'] ? $subject['specific']: self::$moduleName, isset($subject['params']) ? $subject['params'] : null, false, $locale);
            }
            else{
                $subject = Module::getInstanceByName('ets_marketplace')->getTextLang($subject['origin'],isset($lang) ? $lang:$idLang,isset($subject['specific']) && $subject['specific'] ? $subject['specific']: self::$moduleName);
            }
            if(!$subject)
                $subject = $subject['translation'];
        }
        elseif(is_array($subject))
            return false;
        return parent::send(
            $idLang,
            $template,
            $subject,
            $templateVars,
            $toMail,
            $toName,
            $from,
            $fromName,
            $fileAttachment,
            $mode_smtp,
            $templatePath,
            $die,
            $idShop ,
            $bcc,
            $replyTo,
            $replyToName
        );
    }
}