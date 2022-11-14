<?php

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ManagePasswd extends ObjectModel
{
    public $id_cafe_managepasswd;
    public $name;
    public $user_login;
    public $password;
    public $url;
    public $description;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'cafe_managepasswd',
        'primary' => 'id_cafe_managepasswd',
        'fields' => [
            'id_cafe_managepasswd' => ['type' => self::TYPE_INT, 'validate' => 'isInt', 'length' => 10],
            'name' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'length' => 50],
            'user_login' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'length' => 50],
            'password' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'length' => 50],
            'url' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'length' => 255],
            'description' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE,'validate' => 'isDate'],
        ]
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = true)
    {
        $success = parent::add($autodate, $null_values);
        return $success;
    }

    public function update($nullValues = false)
    {
        return parent::update(true);
    }

    public function delete()
    {
        return parent::delete();
    }

    public static function installSql(): bool
    {
        try {
            $createTable = Db::getInstance()->execute(
                "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cafe_managepasswd`(
                `id_cafe_managepasswd` int(10)  NOT NULL AUTO_INCREMENT,
                `name` VARCHAR (50),
                `user_login` VARCHAR (50),
                `password` VARCHAR (50),
                `url` VARCHAR (255),
                `description` VARCHAR(255),
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_cafe_managepasswd`)
                ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;"
            );
        } catch (PrestaShopException $e) {
            return false;
        }

        return $createTable;
    }

    public static function uninstallSql()
    {
        return Db::getInstance()->execute("DROP TABLE IF EXISTS "._DB_PREFIX_."cafe_managepasswd");
    }
}
