<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<p>---</p>

<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<? $this->AddEditAction("news_add", $arResult["ADD_NEWS"]["LINK"], CIBlock::GetArrayByID($arParams["NEWS_IBLOCK_ID"], "ELEMENT_ADD")); ?>

<ul id="<?=$this->GetEditAreaId("news_add");?>">
	<? foreach ($arResult["ITEMS"] as $news): ?>

<?
$this->AddEditAction($news['ID'], $news['EDIT_LINK'], CIBlock::GetArrayByID($news["IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($news['ID'], $news['DELETE_LINK'], CIBlock::GetArrayByID($news["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>

		<li id="<?=$this->GetEditAreaId($news['ID']);?>">
			<b><?=$news["NAME"];?></b> - <?=$news["ACTIVE_FROM"];?> - (<?=implode(", ", $news["SECTIONS"]);?>)

<? $this->AddEditAction("product_add" . $news["ID"], $arResult["ADD_PRODUCTS"]["LINK"], CIBlock::GetArrayByID($arParams["PRODUCTS_IBLOCK_ID"], "ELEMENT_ADD")); ?>

			<ul id="<?=$this->GetEditAreaId("product_add" . $news["ID"]);?>">

				<? foreach($news["PRODUCTS"] as $product): ?>

<?
$this->AddEditAction($news['ID'] . '_' . $product['ID'], $product['EDIT_LINK'], CIBlock::GetArrayByID($arParams["PRODUCTS_IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($news['ID'] . '_' . $product['ID'], $product['DELETE_LINK'], CIBlock::GetArrayByID($arParams["PRODUCTS_IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
					<li id="<?=$this->GetEditAreaId($news['ID'] . '_' . $product['ID']);?>">
						<?=$product["NAME"];?> - <?=$product["PRICE"];?> - <?=$product["MATERIAL"];?> - <?=$product["ARTNUMBER"];?>
					</li>
				<? endforeach; ?>
			</ul>
		</li>
	<? endforeach; ?>
</ul>