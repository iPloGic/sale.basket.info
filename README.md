# README #

Компонент **sale.basket.info** - виджет для вывода короткой информации о товарах в корзине. Например, для использования в шапке интернет-магазина. Может выводить следующие значения:

* Базовая стоимость всех товаров в корзине (BASE_SUMM)
* Стоимость товаров с учетом скидок (DISCOUNT_SUMM)
* Базовая стоимость всех товаров в корзине в формате базовой цены (BASE_SUMM)
* Стоимость товаров с учетом скидок в формате базовой цены (DISCOUNT_SUMM)
* Общее количество товаров (QUANTITY)
* Количество позиций (POSITIONS)


### Подключение ###

Создайте на сайте директорию `\local\components\iplogic`. Скопируйте в нее скачанную директорию компонента sale.basket.info.

В нужном месте шаблона сайта добавьте вызов компонента. Код для подключения:

```
<?$APPLICATION->IncludeComponent(
    "iplogic:sale.basket.info",
    ".default", 
    array(
        "SHOW_ERRORS" => "Y",                   //  If "N" errors will not be displayed
        "AJAX_MODE" => "Y",
        "CACHE_TYPE" => "N",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);?>



```

### Параметры ###

Параметры компонента описаны в таблице. Стандартные параметры для компонента опущены, о них можно узнать в документации Битрикс.

| Параметр | Описание                    |
| ------------- | ------------------------------ |
| SHOW_ERRORS   | Выводить или нет ошибки при их возникновении     |


### Инициация обновления информации ###

Обновить информацию в компоненте ajax запросом можно двумя путями:

* Добавлением запуска функции `iplRefreshBasketInfo()` к нужным обработчикам событий в шаблоне сайта (рекомендуется).
* Определением селектора элемента по клику на который должна обновляться информация. Для этого в шаблоне компонента определяется параметр `activator` массива `$arJsParams`

Внимание! Представленные выше способы обновления информации актуальны только для дефолтного шаблона компонента и шаблонов созданных на его основе без значительных изменений логики работы скриптов.