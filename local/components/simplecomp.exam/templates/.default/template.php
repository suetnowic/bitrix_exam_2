<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<uL>
	<? foreach($arResult["ITEMS"] as $item): ?>
		<li>
			<?=$item["NAME"];?> - (<?=implode(', ', array_column($item["SECTIONS"], "NAME"))?>)
			<ul>
				<? foreach($item["PRODUCTS"] as $product): ?>
					<li>
						<?=$product["NAME"];?> - 
						<?=$product["PROPERTY_PRICE_VALUE"];?> - <?=$product["PROPERTY_MATERIAL_VALUE"];?> - <?=$product["PROPERTY_ARTNUMBER_VALUE"];?>
					</li>
				<? endforeach; ?>
			</ul>
		</li>
	<? endforeach; ?>
</uL>