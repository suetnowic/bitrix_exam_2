<?

if(!$this->__template) {
    $this->InitComponentTemplate();
}

	$this->__template->SetViewTarget("min_max_price");?>

	<div style="color:red; margin: 34px 15px 35px 15px">
		<p><?=GetMessage("MIN", ["#MIN#" => $arResult["PRICE"]["MIN"]]);?></p>
		<p><?=GetMessage("MAX", ["#MAX#" => $arResult["PRICE"]["MAX"]]);?></p>
	</div>
	
	<? $this->__template->EndViewTarget(); ?>
