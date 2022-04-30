function RiskUpdate() {
	Base.call(this);

	const root 			=  this;

	const applyFilter	=  jQuery('button.applyFilter');

	let  defaultSupplierRisks = jQuery('#default_supplier_risks').val();

	let  defaultSupplierCountry = jQuery('#default_supplier_risks').val();

	let  defaultCountryRisks = jQuery('#default_country_risks').val();

	const  defaultSupplierType = jQuery('#default_supplier_type').val();
	const  defaultCategory = jQuery('#default_category').val();
	const  defaultSupplier = jQuery('#default_supplier').val();
	const  defaultCountry = jQuery('#default_country').val();

 //    alert(defaultCategory);
	// alert(defaultCountry);

	const  defaultSourcingCountry = jQuery('#default_sourcing_country').val();
	// const date_format = jQuery("#date_format").val();

	let page = {
		supplier :1,
		country :1,
	};

	let proceed = {
		supplier :1,
		country :1,
	}


    this.init =  function() {

    	defaultSupplierRisks  = JSON.parse(defaultSupplierRisks);

    	defaultCountryRisks  = JSON.parse(defaultCountryRisks);

    	root.baseInit();
    	initialConfiguration();
		// manageOperations();
		// loadSupplierNews();
		// loadCountryNews();

	};

	const initialConfiguration = function() {



		$("#supplier_risk_range").ionRangeSlider({
			type:"double",
			grid:false,
			step:1,
			from:0,
			to:5,
			values:[1,2,3,4,5],
			onChange: function(data) {
			   $('#supplier_range_val').val(data.from_value + '-' + data.to_value);
			},
			onFinish: function(data) {
				$('#supplier_range_val').val(data.from_value + '-' + data.to_value);
			}
		});

		$("#country_risk_range").ionRangeSlider({
			type:"double",
			grid:false,
			step:1,
			from:0,
			to:5,
			values:[1,2,3,4,5],
			onChange: function(data) {
			   $('#country_range_val').val(data.from_value + '-' + data.to_value);
			},
			onFinish: function(data) {
				$('#country_range_val').val(data.from_value + '-' + data.to_value);
			}
		});


		jQuery('.riskSelect').multiselect({
			selectAll:true,
	        enableFiltering: true,
	        enableCaseInsensitiveFiltering: true,
	        allSelectedText: 'All',
	        maxHeight: 200,
	        includeSelectAllOption: true,
	        buttonWidth: '100%'
	    });

	    jQuery('#supplier_cat, #supplier_by_cat,.supplier_type, #country-filter, #supplier_sourcing_country').multiselect({
	        enableFiltering: true,
	        enableCaseInsensitiveFiltering: true,
	        allSelectedText: 'All',
	        maxHeight: 200,
	        includeSelectAllOption: true,
	        buttonWidth: '100%',
	        disableIfEmpty: true
	    });

		// jQuery('#country-filter').val('');

		jQuery('#country-filter').val(defaultCountry);

		// alert(jQuery('#country-filter').val());


	    // jQuery('#country-filter').multiselect('select', [defaultCountry]);
		jQuery('#country-filter').multiselect('rebuild');

		// jQuery('.supplier_type').val('Strategic');


	    // jQuery('#supplier_by_cat').multiselect({
	    //     enableFiltering: true,
	    //     enableCaseInsensitiveFiltering: true,
	    //     allSelectedText: 'All',
	    //     maxHeight: 200,
	    //     includeSelectAllOption: true,
	    //     buttonWidth: '100%'
	    // });
		datePickerAction();
	    manageOperations();

	    setTimeout(function(){
	    	loadSupplierNews();
	    }, 3000);
		// loadSupplierNews();
		loadCountryNews();

	}

	const datePickerAction = function() {

		var date_format = 'dd-mm-yyyy'; //jQuery("#date_type").val();
		// jQuery('#from_date_supplier, #to_date_supplier, #from_date_country, #to_date_country').datepicker({
		// 	format: date_format,
		// 	endDate: "+0d",
		// 	autoclose: true
		// });

		$('.input-daterange').datepicker({

			format: date_format,
			endDate: "+0d",
			autoclose: true
		});


		jQuery('#from_date_supplier').change(function() {
			jQuery('.datepicker').hide();
			jQuery("#to_date_supplier").focus();
		});

		jQuery('#from_date_country').change(function() {
			jQuery('.datepicker').hide();
			jQuery("#to_date_country").focus();
		});

	}

	const manageOperations =  function() {

		let supplier_type = jQuery('.supplier_type').val();

		loadSupplierCategory(supplier_type);
		loadSuppliers(defaultCategory);

		//updated by rachna
		// loadSourcingCountries(defaultCategory);

        jQuery(document).on("click", ".applySupplierFilter" , function(e) {
        	page.supplier = 1;
        	proceed.supplier = 1;
			loadSupplierNews();
		});

		jQuery(document).on("click", ".applyCountryFilter" , function(e) {
        	page.country = 1;
        	proceed.country = 1;
			loadCountryNews();
		});

		jQuery(document).on("click", ".resetSupplierFilter" , function(e) {
			resetSupplierNews();

			setTimeout(function(){
				loadSupplierNews();
			},3000);
			
		});

		jQuery(document).on("click", ".resetCountryFilter" , function(e) {
			resetCountryNews();
			loadCountryNews();
		});


	    jQuery(document).on("change", ".supplier_type" , function(e) {
	    	let supplier_type  = $(this).val();

	    	

			loadSupplierCategory(supplier_type, true);
		});

		jQuery(document).on("change", "#supplier_cat" , function(e) {
			let cat_id  = $(this).val();
			// loadSuppliers(cat_id);

			jQuery('#supplier_by_cat').empty();
			jQuery('#supplier_by_cat').multiselect('rebuild');
			loadSourcingCountries(cat_id);
		});

		jQuery(document).on("change", "#supplier_sourcing_country" , function(e) {
			// let cat_id  = $(this).val();

			let cat_id  = $('#supplier_cat').val();

			loadSuppliers(cat_id);

		});



		jQuery('.supplier-news').scroll(function() {
			if ((jQuery('.supplier-news')[0].scrollHeight - jQuery('.supplier-news').scrollTop() - 2) <= jQuery('.supplier-news').height()) {
				if (proceed.supplier) {
					// riskAlerts.RESET.SUPPLIER = 0;
					page.supplier += 1;
					loadSupplierNews();
				}
				// alert('sds');
		    }
        });

        jQuery('.country-news').scroll(function() {
			if ((jQuery('.country-news')[0].scrollHeight - jQuery('.country-news').scrollTop() - 2) <= jQuery('.country-news').height()) {
				if (proceed.country) {
					page.country += 1;
					loadCountryNews();
				}
				// alert('sds');
		    }
        });
	};

	const resetSupplierNews = function() {
		jQuery('#search_text_supplier').val('');
		jQuery('#supplier_range_val').val('1-5');

		// jQuery('#supplier_cat, #supplier_by_cat').multiselect('refresh');
		jQuery('#supplier_cat, #supplier_by_cat').multiselect('rebuild');

		jQuery('#supplier_sourcing_country').empty();
		jQuery('#supplier_sourcing_country').multiselect('rebuild');


		jQuery('#supplier_risk_types').multiselect('select', defaultSupplierRisks);

		// jQuery('.riskSelect').multiselect('refresh');
		jQuery('#supplier_risk_types').multiselect('rebuild');

		jQuery("#to_date_supplier").val('');
		jQuery("#from_date_supplier").val('');

		let supplier_risk_range = $("#supplier_risk_range").data("ionRangeSlider");
		supplier_risk_range.reset();

		jQuery('.supplier_type').multiselect('select', [defaultSupplierType]);
		jQuery('.supplier_type').multiselect('rebuild');

		page.supplier = 1;
		proceed.supplier = 1;

		loadSupplierCategory(defaultSupplierType, true);

		loadSourcingCountries(defaultCategory, true)
		// loadSuppliers(defaultCategory, true);
		loadSuppliers(defaultCategory, true);

		// alert('sdsd');
	}

	const resetCountryNews = function() {
		jQuery('#search_text_country').val('');
		jQuery('#country_range_val').val('1-5');
		console.log(defaultCountryRisks);

		// jQuery('#supplier_cat, #supplier_by_cat').multiselect('refresh');

		jQuery('#country-filter').val('');
		// jQuery('#country-filter').multiselect('select', []);
		// jQuery('#country-filter').multiselect('refresh');
		jQuery('#country-filter').multiselect('rebuild');

		jQuery('#risk_types_country').multiselect('select', defaultCountryRisks);

		jQuery('#risk_types_country').multiselect('rebuild');

		jQuery("#from_date_country").val('');
		jQuery("#to_date_country").val('');


		let country_risk_range = $("#country_risk_range").data("ionRangeSlider");
		country_risk_range.reset();

		page.country = 1;
		proceed.country = 1;

		// jQuery('.riskSelect').multiselect('select', defaultSupplierRisks);

		// jQuery('.riskSelect').multiselect('refresh');
		// jQuery('.riskSelect').multiselect('rebuild');
		// alert('sdsd');
	}

	const loadSupplierNews = function() {
		$('.s-loader').show();
		let url  = root.BASE_PATH + '/supplier-news';

		let filterText = jQuery('#search_text_supplier').val();

		let riskRange = jQuery('#supplier_range_val').val();

		let risk_types = jQuery('#supplier_risk_types').val();

		let supplier_type = jQuery('.supplier_type').val();

		let category = jQuery('#supplier_cat').val();

		let suppliers = jQuery('#supplier_by_cat').val();

		let fromDate = jQuery('#from_date_supplier').val();
		let toDate = jQuery('#to_date_supplier').val();

		let supplier_sourcing_country = jQuery('#supplier_sourcing_country').val();


		let data = {
			'page': page.supplier,
			'filterText' : filterText,
			'riskRange' : riskRange,
			'riskTypes' : risk_types,
			'category' : category,
			'suppliers' : suppliers,
			'supplier_type' : supplier_type	,
			'fromDate' : fromDate,
			'toDate' : toDate,
			'country' : supplier_sourcing_country
		};


		console.log(data);

		root.ajaxCall(url, data).done(function(response, status) {
			console.log(response);
			if(response.status) {
				let newsHtml = response.payload.supplier_news;
				if(newsHtml != '') {
					if(page.supplier == 1) {
						$('.supplier-news').html(newsHtml);
					} else {
						$('.supplier-news').append(newsHtml);
					}
					loadRiskProgress();
				} else {
					if(page.supplier == 1) {
						$('.supplier-news').html('No records to display.');
					}
					proceed.supplier = 0;
				}

				$('.s-loader').hide();
	  		}

	    }).fail(root.ajaxFailed);
	}

	const loadCountryNews = function() {
		$('.c-loader').show();
		let url  = root.BASE_PATH + '/country-news';

		let filterText = jQuery('#search_text_country').val();

		let riskRange = jQuery('#country_range_val').val();

		let risk_types = jQuery('#risk_types_country').val();

		let country = jQuery('.country-filter').val();

		let fromDate = jQuery('#from_date_country').val();
		let toDate = jQuery('#to_date_country').val();


		let data = {
			'page': page.country,
			'filterText' : filterText,
			'riskRange' : riskRange,
			'riskTypes' : risk_types,
			'country' : country,
			'fromDate' : fromDate,
			'toDate' : toDate
		};

		// console.log(data);

		root.ajaxCall(url, data).done(function(response, status) {
			console.log(response);
			if(response.status) {
				let newsHtml = response.payload.country_news;
				if(newsHtml != '') {
					if(page.country == 1) {
						$('.country-news').html(newsHtml);
					} else {
						$('.country-news').append(newsHtml);
					}
					loadRiskProgress();

				} else {
					if(page.country == 1) {
						$('.country-news').html('No records to display.');
					}
					proceed.country = 0;
				}

				$('.c-loader').hide();
	  		}

	    }).fail(root.ajaxFailed);
	}

	const loadSupplierCategory = function(supplier_type, resetSupplier = false) {

		// let supplier_type  = $(this).val();
		let url  = root.BASE_PATH + '/supplier-categories';

		let data = {'supplier_type' : supplier_type};
		// console.log(data);
		if(supplier_type != '' && typeof(supplier_type) != 'undefined') {
			root.ajaxCall(url, data).done(function(response, status) {
				// console.log(response);
				if(response.status) {
					var $optStr = '';
	                jQuery('#supplier_cat').find("option").remove().end(); 				//Remove all nodes
	                jQuery.each(response.payload.categories, function(key, obj) {

	                    $optStr += '<option value="'+obj.id+'">'+obj.title+'</option>';
	                });

	                jQuery('#supplier_cat').html($optStr);

	                if(resetSupplier) {
						jQuery('#supplier_cat').val('').multiselect('rebuild');
						jQuery('#supplier_by_cat').val('').multiselect('rebuild');
	                } else {
	                	jQuery('#supplier_cat').val(defaultCategory).multiselect('rebuild');
	                }


	                // loadSuppliers(defaultCategory, resetSupplier);
		  		}

		    }).fail(root.ajaxFailed);
	    }
	}

	const loadSourcingCountries = function(cat_id, resetSupplier = false ) {
		// alert(jQuery('#default_country').val());

		let url  = root.BASE_PATH + '/sourcing-countries';

		let supplier_type = jQuery('.supplier_type').val();

		let data = {'cat_id' : cat_id, 'supplier_type' : supplier_type};
		// console.log(data);
		if(cat_id != '' && typeof(cat_id) != 'undefined') {
			root.ajaxCall(url, data).done(function(response, status) {
				console.log(response.payload);
				if(response.status) {
					var $optStr = '';
	                jQuery('#supplier_sourcing_country').find("option").remove().end(); 				//Remove all nodes
	                jQuery.each(response.payload.countries, function(key, obj) {
	                    $optStr += '<option value="'+obj.id+'">'+obj.title+'</option>';
	                });
	                // alert($optStr);
	                jQuery('#supplier_sourcing_country').html($optStr);
	                // jQuery('#supplier_sourcing_country').val('').multiselect('rebuild');

	                if(resetSupplier) {
						jQuery('#supplier_sourcing_country').val('').multiselect('rebuild');
	                } else {
	                	jQuery('#supplier_sourcing_country').val(defaultSourcingCountry).multiselect('rebuild');
	                }
		  		}

		    }).fail(root.ajaxFailed);					
		}
	}

	const loadSuppliers = function(cat_id, resetSupplier = false ) {

		let url  = root.BASE_PATH + '/supplier-by-category';

		let supplier_type = jQuery('.supplier_type').val();

		let country = jQuery('.supplier_sourcing_country').val();

		let data = {'cat_id' : cat_id, 'supplier_type' : supplier_type,'country' : country};
		console.log(data);
		if(resetSupplier) {
			jQuery('#supplier_by_cat').find("option").remove().end(); 
			jQuery('#supplier_by_cat').val('').multiselect('rebuild');
        } 
		if(cat_id != '' && typeof(cat_id) != 'undefined') {
			root.ajaxCall(url, data).done(function(response, status) {
				// console.log(response);
				if(response.status) {
					var $optStr = '';
	                jQuery('#supplier_by_cat').find("option").remove().end(); 				//Remove all nodes
	                jQuery.each(response.payload.suppliers, function(key, obj) {

	                    $optStr += '<option value="'+obj.id+'">'+obj.supplier_name+'</option>';
	                });
	                jQuery('#supplier_by_cat').html($optStr);

	                if(resetSupplier) {
						jQuery('#supplier_by_cat').val('').multiselect('rebuild');
	                } else {
	                	jQuery('#supplier_by_cat').val(defaultSupplier).multiselect('rebuild');
	                }
		  		}

		    }).fail(root.ajaxFailed);

		}
	}

	const loadRiskProgress = function() {
		// alert('sds');
		// var id = 'news1';
		// var score = 2;
		// riskProgressBar('#'+id, score,"#92bf24");



		$('.news-score').each(function(i,elem) {

			if( ! $(elem).hasClass('loaded')) {
				let id = $(elem).attr('id');
				let score = $(elem).data('score');
				// console.log(id);
				riskProgressBar('#'+id, score, getRiskScoreRangeColorCode(score));

				$(elem).addClass('loaded');
			}

		});
	}


	const  riskProgressBar = function(ref, value, ratingColor){
	    var bar = new ProgressBar.Line(ref, {
	        strokeWidth: 8,
	        easing: 'easeInOut',
	        duration: 1400,
	        color: ratingColor,
	        trailColor: '#cde1e4',
	        trailWidth: 4,
	        // svgStyle: {width: '30%', height: '100%'},
	        text: {
	          style: {
	            // Text color.
	            // Default: same as stroke color (options.color)
	            color: '#112636',
	            position: 'absolute',
	            left: '33%',
	            top: '8px',
	            padding: 0,
	            margin: 0,
	            transform: null
	          },
	          autoStyleContainer: false
	        },
	        from: {color: '#29bb3c'},
	        to: {color: '#29bb3c'},
	        step: (state, bar) => {
	          bar.setText(value);
	        }
	      }, false);

	      bar.animate(parseInt(value)/5);  // Number from 0.0 to 1.0
	}
}

jQuery(document).ready(function() {

	RiskUpdate.prototype = Object.create(Base.prototype);
	var content = new RiskUpdate();
	content.init();
});
