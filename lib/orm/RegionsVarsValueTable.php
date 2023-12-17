<?
namespace Ik\Multiregional\Orm;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

/**
 * ORM-класс, описывающий данные переменных региона
 */
Class RegionsVarsValueTable extends DataManager
{
    private const TABLE_NAME = 'IkMultiregional_RegionsVarsValue';

    public static function getTableName()
    {
        return self::TABLE_NAME;
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new IntegerField('REGION_ID', [
                'required' => true,
            ]),
            new IntegerField('REGION_VALUE_ID', [
                'required' => true,
            ]),
            new StringField('VALUE', [
                'required' => true,
            ]),
        ];
    }
}
?>