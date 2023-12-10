<?
namespace Ik\Multiregional\Orm;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

Class RegionsTable extends DataManager
{
    private const TABLE_NAME = 'IkMultiregional_Regions';

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
            new StringField('DOMAIN', [
                'required' => true,
            ]),
        ];
    }
}
?>