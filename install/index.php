<?
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Config\Option;
IncludeModuleLangFile(__FILE__);

// Orm
use \Ik\Multiregional\Orm\RegionsTable;
use \Ik\Multiregional\Orm\RegionsVarsTable;
use \Ik\Multiregional\Orm\RegionsVarsValueTable;

Class Ik_Multiregional extends CModule
{

    var $MODULE_ID = "ik.multiregional";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $errors;

    function __construct(){
        //$arModuleVersion = array();
        $this->MODULE_VERSION = "0.0.1";
        $this->MODULE_VERSION_DATE = "15.07.2023";
        $this->MODULE_NAME = "Мультирегиональность";
        $this->MODULE_DESCRIPTION = "Модуль мультирегиональности";
    }

    function DoInstall(){
        global $APPLICATION;

        RegisterModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();


        $APPLICATION->includeAdminFile(
            "Установочное сообщение",
            __DIR__ . '/instalInfo.php'
        );
        return true;
    }

    function DoUninstall(){
        global $APPLICATION;

        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();

        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->includeAdminFile(
            "Сообщение деинсталяции",
            __DIR__ . '/deInstalInfo.php'
        );
        return true;
    }

    function InstallDB(){
        Loader::includeModule($this->MODULE_ID);

        if (!Application::getConnection()->isTableExists(RegionsTable::getTableName())) {
            RegionsTable::getEntity()->createDbTable();
        };
        if (!Application::getConnection()->isTableExists(RegionsVarsTable::getTableName())) {
            RegionsVarsTable::getEntity()->createDbTable();
        }
        if (!Application::getConnection()->isTableExists(RegionsVarsValueTable::getTableName())) {
            RegionsVarsValueTable::getEntity()->createDbTable();
        }
        return true;
    }
    
    function UnInstallDB(){
        Loader::includeModule($this->MODULE_ID);

        if (Application::getConnection()->isTableExists(RegionsTable::getTableName())) {
            Application::getConnection()->dropTable(RegionsTable::getTableName());
        }
        if (Application::getConnection()->isTableExists(RegionsVarsTable::getTableName())) {
            Application::getConnection()->dropTable(RegionsVarsTable::getTableName());
        }
        if (Application::getConnection()->isTableExists(RegionsVarsValueTable::getTableName())) {
            Application::getConnection()->dropTable(RegionsVarsValueTable::getTableName());
        }
        return true;
    }

    function InstallEvents(){
        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnBuildGlobalMenu',
            $this->MODULE_ID,
            'ik\multiregional\EventHandler',
            'OnBuildGlobalMenuHandler'
        );
    }

    function UnInstallEvents(){
        EventManager::getInstance()->unRegisterEventHandler(
            "main",
            "OnBuildGlobalMenu",
            $this->MODULE_ID,
            'ik\\multiregional\\EventHandler',
            'OnBuildGlobalMenuHandler');
    }

    function InstallFiles(){
        CopyDirFiles(
            __DIR__ . '/admin/settings',
            Application::getDocumentRoot() . '/bitrix/admin',
            true,
            true
        );
        CopyDirFiles(
            __DIR__ . '/components',
            Application::getDocumentRoot() . '/bitrix/components',
            true,
            true
        );
        return true;
    }

    function UnInstallFiles(){
        Directory::deleteDirectory(
            Application::getDocumentRoot() . '/bitrix/admin/multiregion_settings.php',
        );
        Directory::deleteDirectory(
            Application::getDocumentRoot() . '/bitrix/components/IK',
        );
        return true;
    }
}