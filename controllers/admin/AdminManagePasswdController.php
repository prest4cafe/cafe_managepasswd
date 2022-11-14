<?php

require_once(_PS_MODULE_DIR_ . 'cafe_managepasswd/classes/ManagePasswd.php');

class AdminManagePasswdController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'cafe_managepasswd';
        $this->className = 'ManagePasswd';
        $this->deleted = false;
        $this->list_no_link = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->context = Context::getContext();
        $this->required_database = false;
        $this->allow_export = true;
        $this->_use_found_rows = true;
        $this->_orderBy = 'id_cafe_managepasswd';
        $this->_orderWay = 'DESC';

        $this->fields_list = array(
            'id_cafe_managepasswd' => array('title' => 'ID', 'align' => 'text-left', 'class' => 'fixed-width-xs'),
            'name' => array('title' => 'Nom', 'search' => true,),
            'user_login' => array('title' => 'Login', 'search' => true, 'callback' => 'printLogin',),
            'password' => array('title' => 'Password', 'search' => false, 'callback' => 'printPasswd',),
            'url' => array('title' => 'Url de connexion', 'search' => false, 'callback' => 'printUrl',),
            'description' => array('title' => 'Description', 'search' => false,),

        );

        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }


        $key = "H%g;d;vYh@gd:78&$32/ndbs&Yte";
        $decrypted_chaine = openssl_decrypt($obj->password, "AES-128-ECB", $key);
        $obj->password = $decrypted_chaine;


        $this->fields_form = array('legend' => array('title' => 'Password', 'icon' => 'icon-user'),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'label' => 'Nom',
                            'name' => 'name',
                            'required' => true,
                            'col' => '4'
                        ),
                        array(
                            'type' => 'text',
                            'label' => 'login',
                            'name' => 'user_login',
                            'required' => true,
                            'col' => '4'
                        ),
                        array(
                            'type' => 'text',
                            'label' => 'password',
                            'name' => 'password',
                            'required' => true,
                            'col' => '4'
                        ),
                        array(
                            'type' => 'text',
                            'label' => 'url',
                            'name' => 'url',
                            'required' => false,
                            'col' => '6'
                        ),
                        array(
                            'type' => 'textarea',
                            'label' => 'Description',
                            'name' => 'description',
                            'required' => false,
                            'cols' => 40,
                            'rows' => 10,
                        ),


                    ));

        $this->fields_form['submit'] = array('title' => $this->l('Save'),);

        return parent::renderForm();
    }

    public function initToolbarTitle()
    {
        parent::initToolbarTitle();
        switch ($this->display) {
            case '':
            case 'list':


                array_pop($this->toolbar_title);
                $this->toolbar_title[] = 'Gestion de passwords';
                break;
            case 'view':

                if (($cafePassword = $this->loadObject(true)) && Validate::isLoadedObject($cafePassword)) {
                    $this->toolbar_title[] = sprintf('Editer password:');
                }
                break;
            case 'add':
            case 'edit':
                array_pop($this->toolbar_title);

                if (($cafePassword = $this->loadObject(true)) && Validate::isLoadedObject($cafePassword)) {
                    $this->toolbar_title[] = sprintf('Editer password:');
                    $this->page_header_toolbar_btn['new_cafe_managepasswd'] = array('href' => self::$currentIndex . '&addcafe_managepasswd&token=' . $this->token, 'desc' => $this->l('Ajouter un nouveau password', null, null, false), 'icon' => 'process-icon-new');
                } else {
                    $this->toolbar_title[] = 'Créer un nouveau password';
                }
                break;
        }
        array_pop($this->meta_title);
        if (count($this->toolbar_title) > 0) {
            $this->addMetaTitle($this->toolbar_title[count($this->toolbar_title) - 1]);
        }
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_cafe_managepasswd'] = array('href' => self::$currentIndex . '&addcafe_managepasswd&token=' . $this->token, 'desc' => $this->l('Ajouter un nouveau password', null, null, false), 'icon' => 'process-icon-new');
        }
    }

    public function printPasswd($value, $tr)
    {
        $key = "H%g;d;vYh@gd:78&$32/ndbs&Yte";
        $decrypted_chaine = openssl_decrypt($value, "AES-128-ECB", $key);

        return '
   			<div class="input-group">
      			<input type="password" value="'.$decrypted_chaine.'" class="form-control" name="password" autocomplete="new-password" minlength="6">
				<div class="material-icons input-group-append toggle-password">
					<span class="input-group-text mdi mdi-eye-outline"></span>
				</div>
			</div>
		  
		';
    }
    public function printLogin($value, $tr)
    {
        return '
		   <input type="text" value="'.$value.'" class="form-control">
		';
    }

    public function printUrl($value, $tr)
    {
        return '<a href="'. $value .'">'. $value .'</a>';
    }
}
