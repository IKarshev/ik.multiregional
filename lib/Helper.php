<?
namespace Ik\Multiregional;
use Bitrix\Main\Localization\Loc;

Class Helper{
    /**
     * @return string Дирректория ( bitrix || local ), где находится модуль
     */
    public static function GetModuleDirrectory():string{
        $modulePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__));
        if (strpos($modulePath, DIRECTORY_SEPARATOR . 'bitrix' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR) !== false) {
            // Модуль в /bitrix/modules/
            return "bitrix";
        } elseif (strpos($modulePath, DIRECTORY_SEPARATOR . 'local' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR) !== false) {
            // Модуль в /local/modules/
            return "local";
        };
    }

    /**
     * Проверяет, является ли переменная фильтром
     * 
     * @param array $FilterArray корректные фильтры
     * @param string $FilterKey переменная, где проверки
     * 
     * @return bool является ли переменная фильтром
     */
    public static function IsFilterItem( array $FilterArray, string $FilterKey ):bool{
 
        foreach ($FilterArray as $arItem) {

            $filterSettings = array(
                $arItem["id"]."_from",
                $arItem["id"]."_to",
            );

            if( $arItem["id"] == $FilterKey ){
                return true;
            };
            if( in_array($FilterKey, $filterSettings) ){
                return true;
            };
        };

        return false;
    }


    /**
     * Возвращает корректное название фильтра, корректное значение фильтра
     * 
     * @param string $FilterName Название фильтра
     * @param string $FilterValue Значение фильтра
     * 
     * @return (array || bool)
     */
    public static function GetCorrectFilter( string $FilterName, string $FilterValue ){

        if( $FilterName == "" || $FilterValue == "" ) return false;

        if( str_contains($FilterName, '_from')){
            $NewFilterName = ">=".str_replace('_from', '', $FilterName);
            $NewFilterValue = $FilterValue;
        };

        if( str_contains($FilterName, '_to') ){
            $NewFilterName = "<=".str_replace('_to', '', $FilterName);
            $NewFilterValue = $FilterValue;
        };

        if( !isset($NewFilterName, $NewFilterValue) ){
            $NewFilterName = $FilterName;
            $NewFilterValue = "%".$FilterValue."%";
        };

        return array(
            'CorrectFilterName' => $NewFilterName,
            'CorrectFilterValue' => $NewFilterValue,
        );
    }
}
?>