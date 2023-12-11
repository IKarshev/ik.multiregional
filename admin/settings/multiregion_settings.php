<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); // второй общий пролог
// require Classes
use \Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\PageNavigation;

use Ik\Multiregional\Helper;
use Ik\Multiregional\Orm\RegionsTable;
use Ik\Multiregional\Orm\RegionsVarsTable;
use Ik\Multiregional\Orm\RegionsVarsValueTable;

// require modules
Loader::includeModule('ik.multiregional');

// settings
$APPLICATION->SetTitle( Loc::getMessage('GLOBAL_MENU_TAB_NAME') );
$list_id = 'RegionList';
$grid_options = new GridOptions($list_id);
$sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
$UI_Filter = [
	['id' => 'NAME', 'name' => 'Название', 'type'=>'text', 'default' => true],
	['id' => 'DOMAIN', 'name' => 'Домен', 'type'=>'text', 'default' => true],
];

$nav_params = $grid_options->GetNavParams();
$nav = new PageNavigation('request_list');
$nav->allowAllRecords(true)
	->setPageSize($nav_params['nPageSize'])
	->initFromUri();

$filterOption = new Bitrix\Main\UI\Filter\Options($list_id);
$filterData = $filterOption->getFilter([]);
$filter = [];

foreach ($filterData as $k => $v) {
    if( Helper::IsFilterItem( $UI_Filter, $k ) ){
        $filter[$k] = "%".$filterData[$k]."%";
    }
}

$res = RegionsTable::getList([
	'filter' => $filter,
	'select' => [
		"*",
	],
	'offset'      => $nav->getOffset(),
	'limit'       => $nav->getLimit(),
	'order'       => $sort['sort']
]);
?>
    <h3><?=Loc::getMessage('GLOBAL_MENU_FILTER_TITLE')?></h3>
    <div>
		<?$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
			'FILTER_ID' => $list_id,
			'GRID_ID' => $list_id,
			'FILTER' => $UI_Filter,
			'ENABLE_LIVE_SEARCH' => true,
			'ENABLE_LABEL' => true
		]);?>
    </div>
    <div style="clear: both;"></div>

    <hr>

    <h3><?=Loc::getMessage('GLOBAL_MENU_REGION_LIST_TITLE')?></h3>
<?php
$columns = [];
$columns[] = ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true];
$columns[] = ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true];
$columns[] = ['id' => 'DOMAIN', 'name' => 'Домен', 'sort' => 'DOMAIN', 'default' => true];

foreach ($res->fetchAll() as $row) {
	$list[] = [
		'data' => [
			"ID" => $row['ID'],
			"NAME" => $row['NAME'],
			"DOMAIN" => $row['DOMAIN'],
		],
		'actions' => [
			[
				'text'    => 'Просмотр',
				'default' => true,
				'onclick' => 'document.location.href="?op=view&id='.$row['ID'].'"'
			], [
				'text'    => 'Удалить',
				'default' => true,
				'onclick' => 'if(confirm("Точно?")){document.location.href="?op=delete&id='.$row['ID'].'"}'
			]
		]
	];
}

$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
	'GRID_ID' => $list_id,
	'COLUMNS' => $columns,
	'ROWS' => $list,
	'SHOW_ROW_CHECKBOXES' => false,
	'NAV_OBJECT' => $nav,
	'AJAX_MODE' => 'Y',
	'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
	'PAGE_SIZES' =>  [
		['NAME' => '20', 'VALUE' => '20'],
		['NAME' => '50', 'VALUE' => '50'],
		['NAME' => '100', 'VALUE' => '100']
	],
	'AJAX_OPTION_JUMP'          => 'N',
	'SHOW_CHECK_ALL_CHECKBOXES' => false,
	'SHOW_ROW_ACTIONS_MENU'     => true,
	'SHOW_GRID_SETTINGS_MENU'   => true,
	'SHOW_NAVIGATION_PANEL'     => true,
	'SHOW_PAGINATION'           => true,
	'SHOW_SELECTED_COUNTER'     => true,
	'SHOW_TOTAL_COUNTER'        => true,
	'SHOW_PAGESIZE'             => true,
	'SHOW_ACTION_PANEL'         => true,
	'ALLOW_COLUMNS_SORT'        => true,
	'ALLOW_COLUMNS_RESIZE'      => true,
	'ALLOW_HORIZONTAL_SCROLL'   => true,
	'ALLOW_SORT'                => true,
	'ALLOW_PIN_HEADER'          => true,
	'AJAX_OPTION_HISTORY'       => 'N'
]);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>