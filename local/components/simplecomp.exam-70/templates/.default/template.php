<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<p>---</p>

<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<ul>
	<? foreach ($arResult["ITEMS"] as $news): ?>
		<li>
			<b><?=$news["NAME"];?></b> - <?=$news["ACTIVE_FROM"];?> - (<?=implode(", ", $news["SECTIONS"]);?>)
			<ul>
				<? foreach($news["PRODUCTS"] as $product): ?>
					<li>
						<?=$product["NAME"];?> - <?=$product["PRICE"];?> - <?=$product["MATERIAL"];?> - <?=$product["ARTNUMBER"];?>
					</li>
				<? endforeach; ?>
			</ul>
		</li>
	<? endforeach; ?>
</ul>