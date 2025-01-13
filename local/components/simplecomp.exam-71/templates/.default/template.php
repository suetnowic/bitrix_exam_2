<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<p>---</p>

<? $url = $APPLICATION->GetCurPage() . "?F=Y"; ?>
<a href="<?=$url?>"><?=$url;?></a>

<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<ul>
	<? foreach($arResult["ITEMS"] as $news): ?>
		<li>
			<?=$news["NAME"];?>
			<ul>
				<? foreach($news["PRODUCTS"] as $product): ?>
					<li>
						<?=$product["NAME"];?> - 
						<?=$product["PRICE"];?> - 
						<?=$product["MATERIAL"];?> - 
						<?=$product["ARTNUMBER"];?> - 
						(<?=$product["DETAIL_URL"];?>)
					</li>
				<? endforeach; ?>
			</ul>
		</li>
	<? endforeach; ?>
</ul>

<b><?=GetMessage("NAVIGATION");?></b><br>
<?=$arResult["NAV_STRING"];?>
