<?
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loader::registerAutoloadClasses(
	"ik.multiregional",
	array(
		"Ik\\Multiregional\\Main" => "lib/Main.php",
		"Ik\\Multiregional\\EventHandler" => "lib/EventHandler.php",
	)
);