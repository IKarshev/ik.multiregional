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

Class RegionController{


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
    public function GetRegionData( array $RegionID = [] ):array{
        $filter = ( !empty($RegionID) ) ? ["=ID" => $RegionID] : [] ;

        $Regions = RegionsTable::getList([
            'filter' => $filter,
            'select' => ['*'],
        ])->fetchAll();

        foreach ($Regions as $reg) {
            $RegionID[] = $reg["ID"];
        };
        
        $RegionData = $this->GetRigionValues($RegionID);

        foreach ($Regions as $Regionkey => $regionDefaultData) {
            $regionID = $regionDefaultData["ID"];
            
            foreach ($RegionData as $RegionDataValue) {
                if( $RegionDataValue["REGION_ID"] == $regionID ){
                    $Regions[$Regionkey][$RegionDataValue["NAME"]] = $RegionDataValue["VALUE"];
                };
            };
        };

        return $Regions;
    }


}