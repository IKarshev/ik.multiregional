<?
namespace Ik\Multiregional;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

use Ik\Multiregional\RegionController;

Loader::includeModule('ik.multiregional');

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
     * @param string $FilterNameType Тип фильтра
     * 
     * @return (array || bool)
     */
    public static function GetCorrectFilter( string $FilterName, string $FilterValue, $FilterNameType="" ){

        if( $FilterName == "" || $FilterValue == "" ) return false;

        if( str_contains($FilterName, '_from')){
            $operator = ( $FilterNameType == 'more' ) ? '>' : '>=';
            $NewFilterName = $operator.str_replace('_from', '', $FilterName);
            $NewFilterValue = $FilterValue;
        };

        if( str_contains($FilterName, '_to') ){
            $operator = ( $FilterNameType == 'less' ) ? '<' : '<=';
            $NewFilterName = $operator.str_replace('_to', '', $FilterName);
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


    /**
     * Возвращает корректный массив фильтров
     * 
     * @param array $FilterArray Массив необработанных фильтров
     * 
     * @return array Корректный массив фильтров для orm
     *  
     */
    public static function GetCorrectFilter2( array $FilterArray):array{

        foreach ($FilterArray as $arkey => $arItem) {
            // Проверяем является ли фильтруемое поле одним из orm модуля
            if( self::IsFilterItem( RegionController::GetCorrectRegionUIFilter(), $arkey ) ){
                
                $FilterNameType = "";
                $FilterTypeKey = str_replace(['_from', '_to'], '_numsel', $arkey);

                if(array_key_exists($FilterTypeKey, $FilterArray)){
                    $FilterNameType = $FilterArray[$FilterTypeKey];
                };

                // Получаем валидный массив фильтров
                $CorrectFilterData = self::GetCorrectFilter( $arkey, $arItem, $FilterNameType );
                if( $CorrectFilterData ){
                    $CorrectFilters[ $CorrectFilterData['CorrectFilterName'] ] = $CorrectFilterData['CorrectFilterValue'];
                };
            };
        };


        // return filter
        if( !isset($CorrectFilters) && empty($CorrectFilters) ) $CorrectFilters = array();
        return $CorrectFilters;
    }









}
?>