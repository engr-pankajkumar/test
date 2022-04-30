function Scrip() {
	Base.call(this);

	const root 			=  this;

	const applyFilter	=  jQuery('button.applyFilter');

    this.init =  function() {

    	root.baseInit();
    	initialConfiguration();
	};

	const initialConfiguration = function() {



		$("#test1,#test2,#test3,#test4,#test5,#test6").ionRangeSlider({
			// skin: "square",
			type: "double",
	        grid: true,
	        min: 0,
	        max: 1000,
	        from: 200,
	        to: 800,
	         max_postfix: "%",

	        prettify_enabled: true,
        	prettify_separator: ","

		});

		// $("#country_risk_range").ionRangeSlider({
		// 	type:"double",
		// 	grid:false,
		// 	step:1,
		// 	from:0,
		// 	to:5,
		// 	values:[1,2,3,4,5],
		// 	onChange: function(data) {
		// 	   $('#country_range_val').val(data.from_value + '-' + data.to_value);
		// 	},
		// 	onFinish: function(data) {
		// 		$('#country_range_val').val(data.from_value + '-' + data.to_value);
		// 	}
		// });

		jQuery('#sector-dropdown, #industry-dropdown').multiselect({
	        enableFiltering: true,
	        enableCaseInsensitiveFiltering: true,
	        allSelectedText: 'All',
	        maxHeight: 200,
	        includeSelectAllOption: true,
	        buttonWidth: '100%',
	        disableIfEmpty: true
	    });


	    pageActions();

	}
	const initTable = function() {
		$('#stock-table').DataTable({
			// "scrollY": 200,
			scrollY:        '50vh',
        	scrollCollapse: true,
        	"scrollX": true
    	});
	}


	const pageActions =  function() {

		loadSectors();
		
        jQuery(document).on("click", ".applyFilter" , function(e) {
        	loadScips();
		});

		jQuery(document).on("click", ".badge-cols" , function(e) {
			let colno = $(this).data('key');

			$(this).toggleClass('badge-success');
			$(this).toggleClass('badge-dark');

			if($(this).hasClass('badge-dark')) {
				$('#stock-table td:nth-child('+colno+')').hide();
            	$('#stock-table th:nth-child('+colno+')').hide();
			} else {
				$('#stock-table td:nth-child('+colno+')').show();
            	$('#stock-table th:nth-child('+colno+')').show();
			}

        	
		});

	    jQuery(document).on("change", "#sector-dropdown" , function(e) {
	    	let sector  = $(this).val();

			loadIndustry(sector);
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



		
	};

	

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

	const loadSectors = function() {
		// let supplier_type  = $(this).val();
		let url  = root.BASE_PATH + '/sectors';

		let data = {};
		root.ajaxCall(url, data).done(function(response, status) {
			// console.log(response);
			if(response.status) {
				var $optStr = '';
                jQuery('#sector-dropdown').find("option").remove().end(); 				//Remove all nodes
                jQuery.each(response.payload.sectors, function(key, val) {

                    $optStr += '<option value="'+key+'">'+val+'</option>';
                });

                jQuery('#sector-dropdown').html($optStr);
                // jQuery('#sector-dropdown').val('').multiselect('rebuild');
                jQuery('#sector-dropdown').multiselect('rebuild');
	  		}

	    }).fail(root.ajaxFailed);
	    
	}

	

	const loadIndustry = function(sector) {

		let url  = root.BASE_PATH + '/industries';
		let data = {'sector' : sector};
		
		if(sector != '' && typeof(sector) != 'undefined') {
			root.ajaxCall(url, data).done(function(response, status) {
				console.log(response);
				if(response.status) {
					var $optStr = '';
	                // jQuery('#industry-dropdown').find("option").remove().end(); 	
	                jQuery('#industry-dropdown').empty();			
	                jQuery.each(response.payload.industries, function(key, val) {

	                    $optStr += '<option value="'+key+'">'+val+'</option>';
	                });
	                jQuery('#industry-dropdown').html($optStr);
	                jQuery('#industry-dropdown').multiselect('rebuild');
		  		}

		    }).fail(root.ajaxFailed);

		}
	}

	const loadScips = function() {

		let url  = root.BASE_PATH + '/scrips';

		let sector = jQuery('#sector-dropdown').val();
		let industry = jQuery('#industry-dropdown').val();

		if(sector != '' && typeof(sector) != 'undefined' && industry != '' && typeof(industry) != 'undefined') {
			
			let data = {'sector' : sector, 'industry' : industry};
			root.ajaxCall(url, data).done(function(response, status) {
				console.log(response);
				if(response.status) {
					let scripHtml = response.payload.scrips;
					
					$('#company').html(scripHtml);
					// initTable();
					
		  		}

		    }).fail(root.ajaxFailed);

		}
	}

}

jQuery(document).ready(function() {

	Scrip.prototype = Object.create(Base.prototype);
	var content = new Scrip();
	content.init();
});
