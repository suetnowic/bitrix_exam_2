<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if(!empty($arResult["RECOMM"]) && !empty($arResult["FAV_CUR_USER"])): ?>

    <p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

    <p><b><?=GetMessage("FAV_ELEMENTS")?></b></p>
    <ul>
        <? foreach($arResult['FAV_CUR_USER']["PRODUCTS"] as $product): ?>
            <li>
                <?=$product["NAME"];?> - <?=$product["PRICE"];?> - <?=$product["MATERIAL"];?> - <?=$product["ARTNUMBER"];?>
            </li>
        <? endforeach; ?>
    </ul>
    <p><b><?=GetMessage("RECOMMEND_ELEMENTS")?></b></p>
    <ul>
        <? foreach($arResult["RECOMM"] as $product): ?>
            <li>
                <?=$product["NAME"];?> - <?=$product["PRICE"];?> - <?=$product["MATERIAL"];?> - <?=$product["ARTNUMBER"];?>
                <br>
                <span><?=GetMessage("IN_USER_FAV", ["#ARRAY#" => implode(", ", $product["USERS"])])?></span>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>