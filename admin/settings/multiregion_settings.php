<?
use \Bitrix\Main\Loader;
use Ik\Multiregional\Helper;
Loader::includeModule('ik.multiregional');

echo "<span>test</span>";
require($_SERVER["DOCUMENT_ROOT"]."/".Helper::GetModuleDirrectory()."/modules/ik.multiregional/admin/settings/multiregion_settings.php");
?>