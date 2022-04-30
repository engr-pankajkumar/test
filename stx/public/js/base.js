"use strict";

function Base() {
	const root 				= this;
	this.BASE_PATH  		=  jQuery('#base_path').val();
	var CSRF_TOKEN			=  jQuery('meta[name="csrf-token"]').attr('content');

	var CONFIRMATION_CALLBACK  =  null;
	var CONFIRMATION_PARAMS    =  null;

	var contentHeader = {
				'X-XSS-Protection' : 1,
				'X-Content-Type-Options' : 'nosniff',
				'X-Frame-Options' : 'SAMEORIGIN',
				'X-CSRF-TOKEN' : CSRF_TOKEN
			};

	var isBrowserCookieEnabled = function() {
		if (! navigator.cookieEnabled) {
			alert('Your browser cookie setting is disable. Please enable it.');
			return false;
		}
		return true;
	};

	this.baseInit =  function() {
		setupAjaxRequestHeaders();
		// setupAjaxLoader();
		configureConfirmBox();
	};

	var setupAjaxRequestHeaders = function() {
		jQuery.ajaxSetup({
			timeout: (60 * 1000),
			headers : contentHeader
		});
	};

	this.ajaxCall 	= function (url, data, dataType, method) {
		if (typeof dataType === "undefined") {
			dataType = 'json';
		}

		if (typeof method === "undefined") {
			method = 'POST';
		}

		return jQuery.ajax({
		    url 	: url,
		    method 	: method,
		    data 	: data,   
		    dataType : dataType,
		    global 	: true,
	    }); 
	};

	this.ajaxFailed = function(xhr, textStatus, errorThrown) {
		console.log('failed');
		// console.log(xhr.responseText);
	};

	this.alertBox =  function(title, message) {
		
		var modalHeadings = {
			'Information': 'btn-info',
			'Warning': 'btn-supernova',
			'Error': 'btn-danger',
			'Success': 'btn-success',
		};
		
		var modalHeaderClass = modalHeadings[title];
		
		jQuery('#alertModal .modal-content .modal-header').removeClass();
		jQuery('#alertModal .modal-content div:first').addClass('modal-header ' + modalHeaderClass);
		
		jQuery('#alertModal .modal-title').text(title);
	    jQuery('#alertModal .modal-body p').text(message);
	    
	    jQuery("#alertModal").modal({
			backdrop: 'static'
		});	
		
		jQuery('#alertModal').on('hidden.bs.modal', function (e) {
			jQuery('#alertModal .modal-title').text('Information');
		    jQuery('#alertModal .modal-body p').text('');
		    jQuery('#alertModal .modal-content .modal-header').removeClass();
		    jQuery('#alertModal .modal-content div:first').addClass('modal-header btn-info');
		})
	};

	this.confirmBox = function(message, callback, params) {

		CONFIRMATION_CALLBACK = callback;

		if (typeof params !== "undefined") {
			CONFIRMATION_PARAMS = params;
			
			if (typeof params.hideCancelButton !== "undefined" && params.hideCancelButton) {
				jQuery("#confirmCancelBtn").hide();
			}
			
			if (typeof params.title !== "undefined") {
				
				var modalHeadings = {
					'Information': 'btn-info',
					'Warning': 'btn-supernova',
					'Error': 'btn-danger',
					'Success': 'btn-success',
				};
				
				if (jQuery.inArray(params.title, Object.keys(modalHeadings)) != -1) {
					var modalHeaderClass = modalHeadings[params.title];
					
					jQuery('#confirmModal .modal-content .modal-header').removeClass();
					jQuery('#confirmModal .modal-content div:first').addClass('modal-header ' + modalHeaderClass);					
				} 
				
				jQuery('#confirmModalLabel').text(params.title);
			}
			
			if (typeof params.buttonTitle !== "undefined") {
				jQuery('#confirmBtn').text(params.buttonTitle);
			}

			if (typeof params.cancelButtonTitle !== "undefined") {
				jQuery('#confirmCancelBtn').text(params.cancelButtonTitle);
			}
		}
		
		jQuery('#confirmModal .modal-body p').text(message);
		
		jQuery("#confirmModal").modal({
			backdrop: true
		});
	};

	var configureConfirmBox =  function() {
		//jQuery('#confirmBtn,#confirmSendBtn').click(function(event) {
		jQuery('#confirmBtn').click(function(event) {
			if (event.target == this) {
				if (typeof CONFIRMATION_CALLBACK == 'function') {
					if (jQuery.isEmptyObject(CONFIRMATION_PARAMS)) {
						CONFIRMATION_CALLBACK();
					} else {
						CONFIRMATION_CALLBACK(CONFIRMATION_PARAMS);
					}
					jQuery('#confirmModal').modal('hide');
					jQuery("#confirmCancelBtn").show();
					
				} else {
					CONFIRMATION_CALLBACK = null;
					CONFIRMATION_PARAMS = null;
					jQuery("#confirmCancelBtn").show();
				}
				
				jQuery('#confirmModal').on('hidden.bs.modal', function (e) {
					jQuery('#confirmModal .modal-title').text('Information');
				    jQuery('#confirmModal .modal-body p').text('');
				    jQuery('#confirmModal .modal-content .modal-header').removeClass();
				    jQuery('#confirmModal .modal-content div:first').addClass('modal-header btn-info');
				})
			}
		});
	};
}