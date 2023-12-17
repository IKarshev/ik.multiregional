<?
namespace Ik\Multiregional\Orm;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\BooleanField;

/**
 * ORM-класс, описывающий таблицу с переменными региона
 */
Class RegionsVarsTable extends DataManager
{
    private const TABLE_NAME = 'IkMultiregional_RegionsVars';

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
            new StringField('NAME', [
                'required' => true,
            ]),
            new StringField('CODE', [
                'required' => true,
            ]),
            new StringField('TYPE', [
                'required' => true,
            ]),
            new BooleanField('IS_REQUIRED', [
                'required' => false,
                'values' => ["1", '0'],
            ]),
        ];
    }
}
?>