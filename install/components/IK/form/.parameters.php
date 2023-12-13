<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Main\Loader;
use \Bitrix\Iblock\SectionTable;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\PropertyTable;
Loader::includeModule('iblock');



$arComponentParameters = array(
    "GROUPS" => array(
        "BASE" => array(
            "NAME" => "основные настройки",
        ),
        "PROPS" => array(
            "NAME" => "Свойства",
        ),
        "SAVE_SETTINGS" => array(
            "NAME" => "Настройки отправки",
        ),
    ),
    "PARAMETERS" => array(
        "FORM_TITLE" => array(
            "PARENT" => "BASE",
            "NAME" => "Название формы",
            "TYPE" => "STRING",
        ),
        "POPUP" => array(
            "PARENT" => "BASE",
            "NAME" => "Выводить форму в pop-up`е ?",
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
    ),
);

if ( $arCurrentValues["POPUP"] == "Y" ){
    $arComponentParameters['PARAMETERS']['POPUP_BTN_TITLE'] = array(
        "NAME" => "Название кнопки для открытия pop-up",
        "PARENT" => "BASE",
        "TYPE" => "STRING",
    );
    $arComponentParameters['PARAMETERS']['DRAGGABLE'] = array(
        "PARENT" => "BASE",
        "NAME" => "Разрешить перетаскивание pop-up`а",
        "TYPE" => "CHECKBOX",
    );
    $arComponentParameters['PARAMETERS']['RESIZABLE'] = array(
        "PARENT" => "BASE",
        "NAME" => "Разрешить растягивать pop-up`а",
        "TYPE" => "CHECKBOX",
    );

};
?>