<?
use Bitrix\Main\Loader;

Loader::registerAutoloadClasses(
	"ik.multiregional",
	array(
		// lib
		"Ik\\Multiregional\\Main" => "lib/Main.php",
		"Ik\\Multiregional\\EventHandler" => "lib/EventHandler.php",
		"Ik\\Multiregional\\Helper" => "lib/Helper.php",
		"Ik\\Multiregional\\RegionController" => "lib/RegionController.php",
		// orm
		"Ik\\Multiregional\\Orm\\RegionsTable" => "lib/orm/RegionsTable.php",
		"Ik\\Multiregional\\Orm\\RegionsVarsTable" => "lib/orm/RegionsVarsTable.php",
		"Ik\\Multiregional\\Orm\\RegionsVarsValueTable" => "lib/orm/RegionsVarsValueTable.php",
	)
);