<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var customComponent $component */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

//echo "<pre>"; print_r($arResult); echo "</pre>";

foreach($arParams as $key => $val) {
	if(substr($key,0,1) == "~")
		continue;
	if($key == "ACTION")
		continue;
	$arParameters[$key] = $val;
}
$arJsParams = [
	"parameters" => $arParameters,
	"componentPath" => $componentPath,
	/*"activator" => "", // selector of element to bind click event to refresh info. Or use iplRefreshBasketInfo() function
	"base_summ_container" => "",*/
	"discount_summ_container" => "#ipl-cart-info .summ",
	/*"base_summ_formated_container" => "",
	"discount_summ_formated_container" => "",
	"quantity_container" => "",*/
	"positions_container" => "#ipl-cart-info .count",
	"errors_container" => "#ipl-cart-info .errors",
];

?>
<div id="ipl-cart-info" class="nd">
	<a href="/cart/">
		<?=Loc::getMessage("IPL_BI_CART")?>
		(<span class="count"><?=$arResult["POSITIONS"]?></span> - <span class="summ"><?=$arResult["DISCOUNT_SUMM_FORMATED"]?></span>)
	</a>
	<div class="errors"></div>
</div>
<script>
	let obJCSaleBasketInfoComponent = new JCSaleBasketInfoComponent(<?=CUtil::PhpToJSObject($arJsParams)?>);
	let iplRefreshBasketInfo = function() { obJCSaleBasketInfoComponent.sendRequest('refresh'); }
</script>