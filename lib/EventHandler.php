<?
namespace Ik\Multiregional;
use Bitrix\Main\Localization\Loc;

Class EventHandler{
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
}
?>