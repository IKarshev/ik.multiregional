<?php
namespace Ik\Multiregional;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use \Bitrix\Main\DB\Result;


use Ik\Multiregional\Orm\RegionsTable;
use Ik\Multiregional\Orm\RegionsVarsTable;
use Ik\Multiregional\Orm\RegionsVarsValueTable;

use \Bitrix\Main\Entity\ReferenceField;

Loader::includeModule('ik.multiregional');

/**
 * Класс для манипуляции регионами
 */
Class RegionController{

    // const DefaultRegionFields = ['ID', 'NAME', 'DOMAIN'];
    const DefaultRegionFields = array(
        array(
            "ID" => "ID",
            "CODE" => "ID",
            "NAME" => "ID",
            "TYPE" => "INTEGER",
            "IS_REQUIRED" => 1,
        ),
        array(
            "ID" => "NAME",
            "CODE" => "NAME",
            "NAME" => "Название",
            "TYPE" => "STRING",
            "IS_REQUIRED" => 1,
        ),
        array(
            "ID" => "DOMAIN",
            "CODE" => "DOMAIN",
            "NAME" => "Домен",
            "TYPE" => "STRING",
            "IS_REQUIRED" => 1,
        ),
    );

    /**
     * @return array стандартные поля Регионов
     */
    public static function GetDefaultRegionFields(){
        return self::DefaultRegionFields;
    }

    /**
     * @return array доп свойства Регионов
     */
    public static function GetRegionPropertyFields(){
        
        $result = RegionsVarsTable::getList([
            'select' => ['*'] 
        ])->fetchAll();

        return $result;
    }

    /**
     * @return array Все поля регионов
     */
    public static function GetAllRegionFields(){
        return array_merge(
            self::GetDefaultRegionFields(),
            self::GetRegionPropertyFields(),
        );
    }

    /**
     * Возвращает переменные регионов
     * 
     * @param array $RegionID ID-региона
     * 
     * @return array 
     */
    public function GetRigionValues( array $RegionID ):array{        
        $result = RegionsVarsValueTable::getList([
            'filter' => [
                '=REGION_ID' => $RegionID,
            ],
            'select' => [
                'REGION_ID',
                'NAME' => 'RegionsVarsTable.NAME',
                'CODE' => 'RegionsVarsTable.CODE',
                'TYPE' => 'RegionsVarsTable.TYPE',
                'VALUE',
            ], 
            'runtime' => [
                new ReferenceField(
                    'RegionsVarsTable',
                    'Ik\Multiregional\Orm\RegionsVarsTable',
                    array(
                        '=this.REGION_VALUE_ID' => 'ref.ID'
                    ),
                ),
            ]
        ])->fetchAll();

        return $result;
    }

    /**
     * Возвращает структурированный массив данных о регионе
     * 
     * @param array $regionID ID-региона
     * 
     * @return array
     */
    public function GetRegionData( array $filter = [] ):array{
        // $filter = ( !empty($RegionID) ) ? ["=ID" => $RegionID] : [] ;

        $Regions = RegionsTable::getList([
            'filter' => $filter,
            'select' => ['*'],
        ])->fetchAll();

        foreach ($Regions as $reg) {
            $RegionID[] = $reg["ID"];
        };
        
        foreach ($Regions as $Regionkey => $regionDefaultData) {
            $regionID = $regionDefaultData["ID"];
            
            $RegionData = $this->GetRigionValues( [$regionDefaultData["ID"]] );
            foreach ($RegionData as $RegionDataValue) {
                if( $RegionDataValue["REGION_ID"] == $regionID ){
                    $Regions[$Regionkey][$RegionDataValue["CODE"]] = $RegionDataValue["VALUE"];
                };
            };
        };

        return $Regions;
    }

    /**
     * Метод создает новый регион
     * 
     * @param object $Data Bitrix\Main\Type\ParameterDictionary : post запрос из формы
     * @param array $files : Данные файлов
     * 
     * @return bool Результат операции
     */
    public static function CreateNewRegion( object $Data, array $files = array() ):bool{
        
        // Получаем стандартные поля Регионов
        foreach (self::GetDefaultRegionFields() as $value) {
            $regionDefaultFieldList[] = $value["CODE"];
        };
        foreach (self::GetRegionPropertyFields() as $value) {
            $regionVarsFieldList[$value["CODE"]] = $value;
        };
        // Получаем доп. поля Регионов
        foreach ($Data as $arkey => $arItem) {
            if( in_array($arkey, $regionDefaultFieldList) ){
                $RegionDefaultVars[$arkey] = $arItem;
            };
            if( array_key_exists($arkey, $regionVarsFieldList) ){
                $RegionVars[$arkey] = array_merge($regionVarsFieldList[$arkey], ["VALUE" => $arItem]);
            };
        };

        // Создаем регион
        try {
            $RegionID = RegionsTable::add($RegionDefaultVars)->getId();
        } catch (\Throwable $th) {
            return false;
        }
        
        if( !empty($RegionVars) && ( isset($RegionID) && $RegionID!="") ){
            try {
                foreach ($RegionVars as $regionData) {
                    RegionsVarsValueTable::add(
                        array(
                            "REGION_ID" => $RegionID,
                            "REGION_VALUE_ID" => $regionData["ID"],
                            "VALUE" => $regionData["VALUE"],
                        )
                    )->getId();
                }

            } catch (\Throwable $th) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array корректный массив фильтров для регионов
     */
    public static function GetCorrectRegionUIFilter():array{
        $filterArr = self::GetAllRegionFields();

        foreach ($filterArr as &$arItem) {
            
            $type="";
            switch ($arItem["TYPE"]) {
                case 'STRING':
                    $type="string";
                    break;
                case 'INTEGER':
                    $type="number";
                    break;
            }            
            
            $arItem = array(
                "id" => $arItem["CODE"],
                "name" => $arItem["NAME"],
                "type" => $type,
                "default" => true,
            );
        }


        return $filterArr;

    }


}