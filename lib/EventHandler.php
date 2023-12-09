<?
namespace Ik\Multiregional;

Class EventHandler{
    public static function OnBuildGlobalMenuHandler(&$arGlobalMenu, &$arModuleMenu){
        global $USER;
        if(!$USER->IsAdmin()) return;
    
        $aGlobalMenu[] = [
            'menu_id' => 'IK',
            'text' => 'IK',
            'title' => 'IK',
            'url' => 'settingss.php?lang=ru',
            'sort' => 1000,
            'items_id' => 'global_menu_custom',
            'help_section' => 'custom',
            'items' => [
                [
                    'parent_menu' => 'global_menu_custom',
                    'sort'        => 10,
                    'url'         => '/bitrix/admin/excel_parse.php',
                    'text'        => "Мультирегиональность",
                    'title'       => "Мультирегиональность",
                    'icon'        => 'fav_menu_icon',
                    'page_icon'   => 'fav_menu_icon',
                    'items_id'    => 'menu_custom',
                ],
            ],
        ];
    }
    public static function OnAfterIBlockElementAdd(&$arFields){
        ob_start();
        echo "test";
        print_r($arFields);
        $debug = ob_get_contents();
        ob_end_clean();
        $fp = fopen($_SERVER['DOCUMENT_ROOT'].'/lk-params.log', 'w+');
        fwrite($fp, $debug);
        fclose($fp); 
    }
}
?>