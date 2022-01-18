(function() {
	'use strict';

	if (!!window.JCSaleBasketInfoComponent)
		return;

	window.JCSaleBasketInfoComponent = function(params) {
		this.componentPath = params.componentPath || '';
		this.parameters = params.parameters || '';
		this.activator = params.activator || '';
		this.base_summ_container = params.base_summ_container || '';
		this.discount_summ_container = params.discount_summ_container || '';
		this.base_summ_formated_container = params.base_summ_formated_container || '';
		this.discount_summ_formated_container = params.discount_summ_formated_container || '';
		this.quantity_container = params.quantity_container || '';
		this.positions_container = params.positions_container || '';
		this.errors_container = params.errors_container || '';

		/* uncomment if use activator parameter
		$('body').on('click', this.activator, {obj:this}, function(e) {
			e.data.obj.sendRequest('refresh');
		});*/
	};

	window.JCSaleBasketInfoComponent.prototype =
		{
			sendRequest: function(action) {

				let data = {
					action: action,
					parameters: this.parameters,
				};
				let obj = this;

				$.post(
					this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
					data,
					function(res) {
						if (res.errors !== undefined) {
							if(this.parameters.SHOW_ERRORS) {
								$(this.errors_container).html(res.errors);
							}
						}
						else {
							$(obj.errors_container).empty();
							if(obj.base_summ_container != '') {
								$(obj.base_summ_container).html(res.data.BASE_SUMM);
							}
							if(obj.discount_summ_container != '') {
								$(obj.discount_summ_container).html(res.data.DISCOUNT_SUMM);
							}
							if(obj.base_summ_formated_container != '') {
								$(obj.base_summ_formated_container).html(res.data.BASE_SUMM_FORMATED);
							}
							if(obj.discount_summ_formated_container != '') {
								$(obj.discount_summ_formated_container).html(res.data.DISCOUNT_SUMM_FORMATED);
							}
							if(obj.quantity_container != '') {
								$(obj.quantity_container).html(res.data.QUANTITY);
							}
							if(obj.positions_container != '') {
								$(obj.positions_container).html(res.data.POSITIONS);
							}
						}
					}
				);
			},

		}
})();