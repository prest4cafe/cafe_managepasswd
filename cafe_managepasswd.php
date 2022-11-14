<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

define('__KEY__', "H%g;d;vYh@gd:78&$32/ndbs&Yte");

require_once __DIR__ . '/classes/ManagePasswd.php';

class Cafe_managepasswd extends Module
{
    public function __construct()
    {
        $this->name = 'cafe_managepasswd';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'presta.cafe';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('cafe_managepasswd');
        $this->description = $this->l('Allow to manage your passwords');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Install module
     * @return bool
     */
    public function install()
    {
        $tab = new Tab();
        foreach (Language::getLanguages() as $language) {
            $tab->name[$language['id_lang']] = 'Passwords';
        }
        $tab->class_name = 'AdminManagePasswd';
        $tab->module = $this->name;
        $idParent = (int)Tab::getIdFromClassName('IMPROVE');
        $tab->id_parent = $idParent;
        $tab->position = Tab::getNbTabs($idParent);
        $tab->icon = 'lock';

        if (!$tab->save()) {
            return false;
        }

        Configuration::updateValue('CAFEMANAGEPASSWD_ADMIN_TAB', $tab->id);

        if (!parent::install()
            || !$this->registerHook([
                'displayBackOfficeHeader',
                'actionObjectManagePasswdAddBefore',
                'actionObjectManagePasswdUpdateBefore',
            ])
            || !ManagePasswd::installSql()
        ) {
            return false;
        }
        return true;
    }

    /**
     * Uninstall module
     * @return bool
     */
    public function uninstall()
    {
        if (
            !parent::uninstall()
            || !ManagePasswd::uninstallSql()
        ) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $output;
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        $this->context->controller->addJs(_MODULE_DIR_.$this->name.'/views/js/cafe_adminpassword.js');
        $this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/views/css/cafe_adminpassword.css', 'all');
        $this->context->controller->addCSS('https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.7.95/css/materialdesignicons.css', 'all');
    }


    public function hookactionObjectManagePasswdAddBefore($params)
    {
        $password = $params['object'];
        $textPasswd = $password->password;
        $encryptedPassword = openssl_encrypt($textPasswd, "AES-128-ECB", __KEY__);
        $password->password = $encryptedPassword;
    }

    public function hookactionObjectManagePasswdUpdateBefore($params)
    {
        $password = $params['object'];
        $textPasswd = $password->password;
        $encryptedPassword = openssl_encrypt($textPasswd, "AES-128-ECB", __KEY__);
        $password->password = $encryptedPassword;
    }
}
