<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
session_start();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

use \Bitrix\Main\Application;
use \Bitrix\Iblock\SectionTable;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\PropertyTable;

use Ik\Multiregional\RegionController;

Loader::includeModule('iblock');
Loader::includeModule('ik.multiregional');

CUtil::InitJSCore( array('jquery', 'ajax' , 'popup'));

class FormComponent extends CBitrixComponent implements Controllerable{

    public function randomString($length = 8) { 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $charactersLength = strlen($characters); 
        $randomString = ''; 
        for ($i = 0; $i < $length; $i++) { 
            $randomString .= $characters[rand(0, $charactersLength - 1)]; 
        } 
        return $randomString; 
    }

    public function generate_form_id($form_prefix) {
        $this->form_postfix = $this->randomString();
        $this->form_id = $form_prefix."_".$this->form_postfix;
        
        return $this->form_id;
    }

    public function validate_string($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function configureActions(){
        // сбрасываем фильтры по-умолчанию
        return [
            'Send_Form' => [
                'prefilters' => [],
                'postfilters' => []
            ]
        ];
    }

    public function executeComponent(){// подключение модулей (метод подключается автоматически)
        try{
            // Проверка подключения модулей
            $this->checkModules();
            // Генерируем название формы
            $this->form_id = $this->generate_form_id("Order_call");
            // формируем arResult
            $this->getResult($this->form_id);
            // подключение шаблона компонента
            $this->includeComponentTemplate();
        }
        catch (SystemException $e){
            ShowError($e->getMessage());
        }
    }

    protected function checkModules(){// если модуль не подключен выводим сообщение в catch (метод подключается внутри класса try...catch)
        if (!Loader::includeModule('iblock')){
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }
        if (!Loader::includeModule('ik.multiregional')){
            throw new SystemException(Loc::getMessage('IK_MULTIREGIONAL_MODULE_NOT_INSTALLED'));
        }
    }


    public function onPrepareComponentParams($arParams){//обработка $arParams (метод подключается автоматически)
        $arParams["ERROR_MESSAGES"] = array(
            "FILE" => Loc::getMessage('ERROR_FILE'),
            "STRING" => Loc::getMessage('ERROR_STRING'),
            "CHECKBOX" => Loc::getMessage('ERROR_CHECKBOX'),
            "LIST" => Loc::getMessage('ERROR_LIST'),
            "TEXT_AREA" => Loc::getMessage('ERROR_TEXT_AREA'),
            "EMAIL_VALIDATE" => Loc::getMessage('EMAIL_VALIDATE'),
        );
        
        return $arParams;
    }

    protected function getResult($form_id){ // подготовка массива $arResult (метод подключается внутри класса try...catch)
        // Формируем массив arResult
        $this->arResult["form_id"] = $this->form_id;
        // Передаем параметры в сессию, чтобы получить иметь доступ в ajax
        $_SESSION['arParams'] = $this->arParams;

        $this->arResult["FIELDS"] = $this->arParams["FIELDS"];

        return $this->arResult;
    }

    public function Send_FormAction(){
        $request = Application::getInstance()->getContext()->getRequest();
        // получаем файлы, post
        $post = $request->getPostList();
        $files = $request->getFileList()->toArray();
        // Получаем параметры компонента из сессии
        $this->arParams = $_SESSION['arParams'];

        
        $PROP = array();
        // Валидация текстовых полей
        foreach ($post as $arkey => $arItem) {
            if( $arItem != "" ){// Проверяем пустые массивы
                if( is_array($arItem) ){
                    foreach ($arItem as $key => $value) {
                        $PROP[$arkey][] = $this->validate_string( $value );
                    };
                } else{// Валидация ключей массивов (списки)
                    $i = false;
                    foreach ($this->arParams["filed_data"] as $paramkey => $paramItem) {
                        if( $paramItem["PROPERTY_TYPE"] == "TEXT_AREA" && $paramItem["CODE"] == $arkey ){
                            $PROP[$arkey] = array( "VALUE" => array(
                                "TEXT" => $this->validate_string( $arItem ),
                                "TYPE" => "text",
                            ));
                            $i = true;
                            break;
                        };
                    };
                    if( !$i ){
                        $PROP[$arkey] = $this->validate_string( $arItem );
                    };

                };
            };
        };


        
    } 

}