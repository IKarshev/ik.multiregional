<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>


<?if( $arParams["POPUP"]=="Y" ):?>
    <a href="" class="ui-btn-main open_popup" data-popup="Popup_<?=$arResult['form_id']?>"><?=$arParams['POPUP_BTN_TITLE']?></a>
<?endif;?>

<div id="Popup_<?=$arResult['form_id']?>" class="form form_default" <?=($arParams["POPUP"]=="Y") ? "style='display:none'" : ""?> >
    
    <div class="form_head">
        <div class="title"><?=$arParams["FORM_TITLE"]?></div>
    </div>

    <div class="form_body">
        <form id="<?=$arResult["form_id"]?>" method="post" action="" enctype="multipart/form-data">

            <?foreach ($arResult["FIELDS"] as $arkey => $arItem):?>
                <?switch ($arItem["TYPE"]) {
                    case 'STRING'?>
                        
                        <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                            <label for="<?=$arItem["CODE"]?>" id="<?=$arItem["CODE"]?>"><?=$arItem["NAME"]?></label>
                            <input <?=($arItem["IS_REQUIRED"])?"required":""?> data-mask="<?//=$arItem["MASK"]?>" name="<?=$arItem["CODE"]?>" type="text" placeholder="Введите <?=$arItem["NAME"]?>">
                            <div class="error_placement"></div>
                        </div>     
                        
                        <?break;
                    case 'LIST'?>

                        <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                            <label for="<?=$arItem["CODE"]?>"><?=$arItem["NAME"]?></label>
                            <select <?=($arItem["IS_REQUIRED"])?"required":""?> name="<?=$arItem["CODE"]?>" id="<?=$arItem["CODE"]?>">
                                <option value="" selected disabled>Выберите пункт</option>

                                <?foreach ($arItem["LIST_ITEMS"] as $ListKey => $ListItem):?>
                                    <option value="<?=$ListItem["ID"]?>"><?=$ListItem["VALUE"]?></option>
                                <?endforeach;?>
                            </select>
                            <div class="error_placement"></div>
                        </div>
                    
                        <?break;
                    case 'FILE'?>

                        <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                            <span class="title"><?=$arItem["NAME"]?></span>
                            <label for="<?=$arItem["CODE"]?>" >выберите файл</label>
                            <input <?=($arItem["IS_REQUIRED"])?"required":""?> type="file" name="<?=$arItem["CODE"]?><?=($arItem["MULTIPLE"] == "Y")? '[]' : ''?>" id="<?=$arItem["CODE"]?>" <?=($arItem["MULTIPLE"] == "Y")? 'multiple' : ''?>>
                            <div class="error_placement"></div>
                        </div>
                    
                        <?break;
                    case 'CHECKBOX'?>

                    <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                        <span class="title"><?=$arItem["NAME"]?></span>

                        <?foreach ($arItem["LIST_ITEMS"] as $checkboxKey => $checkboxItem):?>
                            <div class="checkbox_cont">
                                <input type="checkbox" name="<?=$arItem["CODE"]?>[]" value="<?=$checkboxItem['ID']?>" id="<?=$checkboxItem["XML_ID"]?>">
                                <label for="<?=$checkboxItem["XML_ID"]?>"><?=$checkboxItem["VALUE"]?></label>
                            </div>
                        <?endforeach;?>
                        <div class="error_placement"></div>
                    
                    </div>     
                
                    <?break;
                    case "TEXT_AREA"?>
                        
                        <div class="input_cont <?=$arItem["PROPERTY_TYPE"];?>">
                            <label for="<?=$arItem["CODE"]?>"><?=$arItem["NAME"]?></label>
                            <textarea id="<?=$arItem["CODE"]?>" <?=($arItem["IS_REQUIRED"])?"required":""?> data-mask="<?=$arItem["MASK"]?>" name="<?=$arItem["CODE"]?>" type="text" placeholder="Введите <?=$arItem["NAME"]?>"></textarea>
                            <div class="error_placement"></div>
                        </div>      
            
                    <?break;                    
                }?>
            <?endforeach;?>

            <div class="error_placement"></div>
            <button type="submit">Отправить</button>

        </form>
    </div>

</div>


<script>
    <?// импорт переменных в js?>
	var form_id = <?=CUtil::PhpToJSObject($arResult["form_id"])?>;
    var ERROR_MESSAGES = <?=CUtil::PhpToJSObject($arParams["ERROR_MESSAGES"])?>;
    var COMPONENT_PARAMS = <?=CUtil::PhpToJSObject( array(
        "DRAGGABLE" => ( isset($arParams["DRAGGABLE"]) && $arParams["DRAGGABLE"] == "Y") ? true : false,
        "RESIZABLE" => ( isset($arParams["RESIZABLE"]) && $arParams["RESIZABLE"] == "Y") ? true : false,
    ));?>
</script>