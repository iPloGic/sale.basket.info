<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\ErrorCollection;
use \Bitrix\Catalog\Product\Basket;


class iplogicSaleBasketInfo extends \CBitrixComponent implements \Bitrix\Main\Engine\Contract\Controllerable, \Bitrix\Main\Errorable
{

	/** @var ErrorCollection */
	protected $errorCollection;

	function __construct($component = null)
	{
		parent::__construct($component);

		$this->errorCollection = new ErrorCollection();

		if(!Loader::includeModule('sale')){
			$this->setError('No sale module');
		};

		if(!Loader::includeModule('catalog')){
			$this->setError('No catalog module');
		};
	}

	public function configureActions()
	{
		//fill it, or use default
		return [];
	}

	public function onPrepareComponentParams($arParams)
	{
		if(
			isset($arParams['IS_AJAX'])
			&& ($arParams['IS_AJAX'] == 'Y' || $arParams['IS_AJAX'] == 'N')
		) {
			$arParams['IS_AJAX'] = $arParams['IS_AJAX'] == 'Y';
		}
		else {
			if(
				isset($this->request['is_ajax'])
				&& ($this->request['is_ajax'] == 'Y' || $this->request['is_ajax'] == 'N')
			) {
				$arParams['IS_AJAX'] = $this->request['is_ajax'] == 'Y';
			}
			else {
				$arParams['IS_AJAX'] = false;
			}
		}

		$arParams['ACTION'] = $this->getParam('ACTION', $arParams);

		return $arParams;
	}

	protected function getParam($name, $arParams)
	{
		if( isset($this->request[strtolower($name)]) && strlen($this->request[strtolower($name)]) > 0 ) {
			return strval($this->request[strtolower($name)]);
		}
		else {
			if( isset($arParams[strtoupper($name)]) && strlen($arParams[strtoupper($name)]) > 0 ) {
				return strval($arParams[strtoupper($name)]);
			}
			else {
				return '';
			}
		}
	}

	function executeComponent()
	{
		global $APPLICATION;

		if ($this->arParams['IS_AJAX']) {
			$APPLICATION->RestartBuffer();
		}

		if (!empty($this->arParams['ACTION'])) {
			if (is_callable([$this, $this->arParams['ACTION'] . "Action"])) {
				try {
					call_user_func([$this, $this->arParams['ACTION'] . "Action"]);
				} catch (\Exception $e) {
					$this->setError($e->getMessage());
				}
			}
		}

		if (count($this->errorCollection)) {
			$this->arResponse['errors'] = $this->getError();
		}

		if ($this->arParams['IS_AJAX']) {
			header('Content-Type: application/json');
			echo json_encode($this->arResponse);
			$APPLICATION->FinalActions();
			die();
		} else {
			$this->prepareResult();
			$this->includeComponentTemplate();
		}
	}

	protected function refreshAction()
	{
		$this->prepareResult();
		$this->arResponse["data"] = $this->arResult;
	}

	protected function prepareResult() {
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

		$this->arResult['BASE_SUMM'] = $basket->getBasePrice();
		$this->arResult['DISCOUNT_SUMM'] = $basket->getPrice();

		$rsCurrency = \Bitrix\Currency\CurrencyTable::getList(["filter" => ["BASE" => "Y"], "select" => ["CURRENCY"]]);
		$currency = $rsCurrency->fetch()["CURRENCY"];
		$this->arResult['BASE_SUMM_FORMATED'] = \SaleFormatCurrency($this->arResult['BASE_SUMM'], $currency);
		$this->arResult['DISCOUNT_SUMM_FORMATED'] = \SaleFormatCurrency($this->arResult['DISCOUNT_SUMM'], $currency);

		$arQuantityList = $basket->getQuantityList();
		$this->arResult['QUANTITY'] = round(array_sum($arQuantityList));
		$this->arResult['POSITIONS'] = count($arQuantityList);
	}


	/**
	 * Setting error.
	 * @return boolean
	 */
	protected function setError($str, $code = 0)
	{
		$error = new \Bitrix\Main\Error($str, $code, "");
		$this->errorCollection->setError($error);
	}

	/**
	 * Getting array of errors.
	 * @return Error[]
	 */
	public function getErrors()
	{
		return $this->errorCollection->toArray();
	}

	/**
	 * Getting once error with the necessary code.
	 * @param string $code Code of error.
	 * @return Error
	 */
	public function getErrorByCode($code)
	{
		return $this->errorCollection->getErrorByCode($code);
	}

}