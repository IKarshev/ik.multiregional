<?
use Bitrix\Main\Loader;

Loader::registerAutoloadClasses(
	"ik.multiregional",
	array(
		"Ik\\Multiregional\\Main" => "lib/Main.php",
		"Ik\\Multiregional\\EventHandler" => "lib/EventHandler.php",
		"Ik\\Multiregional\\Helper" => "lib/Helper.php",
	)
);