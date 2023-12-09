<?
if( file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ik.multiregional/admin/settings/multiregion_settings.php") ){
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ik.multiregional/admin/settings/multiregion_settings.php");
} elseif( file_exists($_SERVER["DOCUMENT_ROOT"]."/local/modules/ik.multiregional/admin/settings/multiregion_settings.php") ){
    require($_SERVER["DOCUMENT_ROOT"]."/local/modules/ik.multiregional/admin/settings/multiregion_settings.php");
};
?>