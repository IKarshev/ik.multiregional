<?
namespace Ik\Multiregional;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

/**
 * Класс-обработчик событий
 * 
 * @category Class 
 */
Class EventHandler{

    /**
     * Вывод меню в админку
     */
    public static function OnBuildGlobalMenuHandler(&$arGlobalMenu, &$arModuleMenu){
        global $USER;
        if(!$USER->IsAdmin()) return;
    
        $arGlobalMenu["global_menu_IKMultiregional"] = [
            'menu_id' => 'IK',
            'text' => 'IK',
            'title' => 'IK',
            'url' => 'settingss.php?lang=ru',
            'sort' => 1000,
            'items_id' => 'GlobalMenu_IKMultiregional',
            'help_section' => 'custom',
            'items' => [
                [
                    'parent_menu' => 'GlobalMenu_IKMultiregional',
                    'sort'        => 10,
                    'url'         => '/bitrix/admin/multiregion_settings.php',
                    'text'        => Loc::getMessage('MULTIREGION_SETTINGS_TAB'),
                    'title'       => Loc::getMessage('MULTIREGION_SETTINGS_TAB'),
                    'icon'        => 'fav_menu_icon',
                    'page_icon'   => 'fav_menu_icon',
                    'items_id'    => 'menu_custom',
                ],
            ],
        ];
    }

    /**
     * Обработчик 'перед выводом буферизированного контента'
     */
    public static function OnBeforeEndBufferContentHandler(){
        $module_id = pathinfo(dirname(__DIR__))["basename"];

        /**
         * Подключение js/css файлов модуля в административную часть
         */
        if(defined("ADMIN_SECTION") && ADMIN_SECTION === true){
            Asset::getInstance()->addJs("/bitrix/js/".$module_id."/admin.js");
        };
    }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               

}
?>