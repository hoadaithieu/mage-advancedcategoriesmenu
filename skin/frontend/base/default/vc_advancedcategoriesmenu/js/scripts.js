
var AjaxLayeredNavigation = {
	options:{
		currentLayout:'',
		container: '',
		overlay: '',
		formKey:'',
		url:'',
		id:0
	},
	
	
	getContent: function(element, query) {
		data = {id: this.options.id, 'layout': this.options.currentLayout,  'form_key': this.options.formKey};
		_self = AjaxLayeredNavigation;
		_self.showOverlay();
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: this.options.url +'?'+ query,
			data: data
		}).done(function(result) {
			_self.success(result);
			_self.hideOverlay();
			
		}).error(function(result) {
			_self.error(result);
			_self.hideOverlay();			
		});	
		
		window.history.pushState("object or string", "Title",jQuery(element).attr('href'));
		return false;
	},
	
	success: function(result) {
		if (result.content) {
			jQuery(_self.options.container).html(result.content);
		}
	},
	
	error: function(result) {
	},
	
	showOverlay: function() {
		jQuery(this.options.overlay).show();
	},
	
	hideOverlay: function() {
		jQuery(this.options.overlay).hide();
	},
	
}
