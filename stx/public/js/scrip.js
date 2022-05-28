function Scrip() {
	Base.call(this);

	const root 			=  this;

	const applyFilter	=  jQuery('button.applyFilter');

	let colSort = {};
	let filters = {};

    this.init =  function() {

    	root.baseInit();
    	initialConfiguration();
	};

	const initialConfiguration = function() {
		// $(".draggable" ).draggable({
		  
		// });


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

		jQuery('#sector-dropdown, #industry-dropdown,.sfilter').multiselect({
	        enableFiltering: true,
	        enableCaseInsensitiveFiltering: true,
	        allSelectedText: 'All',
	        maxHeight: 200,
	        includeSelectAllOption: true,
	        buttonWidth: '100%',
	        disableIfEmpty: true,
	        selectAllValue: 'all'
	    });

	    

	    $('#table-columns').multiselect({
	    	enableFiltering: true,
	        enableCaseInsensitiveFiltering: true,
	        allSelectedText: 'All',
	        maxHeight: 200,
	        includeSelectAllOption: true,
	        buttonWidth: '100%',
	        disableIfEmpty: true,
	        selectAllValue: 'all',
            enableClickableOptGroups: true
            // selectAll: false
        });

        jQuery('#table-columns').multiselect('selectAll', false);

	    // jQuery("#table-columns").multiselect('updateButtonText');


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
	    	// let sector  = $(this).val();

			loadIndustry();
		});

		jQuery(document).on("change", "#supplier_cat" , function(e) {
			let cat_id  = $(this).val();
			// loadSuppliers(cat_id);

			jQuery('#supplier_by_cat').empty();
			jQuery('#supplier_by_cat').multiselect('rebuild');
		});

		
		jQuery(document).on("change", ".sfilter" , function() {
			// let cat_id  = $(this).val();

			// console.log($(this).val());
			
			$('.sfilter').each(function(i,elem){
				let id = $(elem).attr('id');
				var allSelected = $("#"+id+" option:not(:selected)").length == 0;
				// console.log(allSelected);
				if(allSelected) {
					filters[id] = [];
				} else {
					filters[id] = $(elem).val();
				}
				
				// console.log($(elem).val());
			});

			loadScips();

			// console.log(filters);

			// let cat_id  = $(elem).val();

			// loadSuppliers(cat_id);

		});

		jQuery(document).on("click", ".sorting" , function() {
			// alert('sds');
			$(this).toggleClass('sortActive');
			$(this).siblings().removeClass('sortActive');
			let columns = $(this).parent('th').text().trim();

			colSort = {};

			if($(this).hasClass('sortActive')) {
				colSort[columns] = $(this).data('sort');
			} else {
				// colSort[columns] = '';
				// delete colSort[columns];
			}

			console.log(colSort);
			loadScips();
		});

		jQuery(document).on("change", "#table-columns" , function(e) {
			manageColumns();
		});		
	};



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

                loadIndustry();
	  		}

	    }).fail(root.ajaxFailed);
	    
	};

	

	const loadIndustry = function() {

		let url  = root.BASE_PATH + '/industries';
		let sector = $('#sector-dropdown').val();
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
	                loadScips();
		  		}

		    }).fail(root.ajaxFailed);

		};
	}

	const loadScips = function() {

		let url  = root.BASE_PATH + '/scrips';

		let sector = jQuery('#sector-dropdown').val();
		let industry = jQuery('#industry-dropdown').val();

		if(sector != '' && typeof(sector) != 'undefined' && industry != '' && typeof(industry) != 'undefined') {

			// console.log(colSort);
			
			let data = {'sector' : sector, 'industry' : industry, 'filters': filters, 'sort':colSort};
			console.log(data);
			root.ajaxCall(url, data).done(function(response, status) {
				console.log(response);
				if(response.status) {
					let scripHtml = response.payload.scrips;
					
					$('#company').html(scripHtml);
					// initTable();

					manageColumns();
					
		  		}

		    }).fail(root.ajaxFailed);

		}
	};


	const manageColumns = function() {

		$('#table-columns option:not(:selected)').each(function(i,elem){
    		// let colno = parseInt($(elem).val()) + 1;
			// alert($(elem).val());
			$('.col-'+$(elem).val()).hide();
			// $('#stock-table th:nth-child('+colno+')').hide();
			// $('#stock-table td:nth-child('+colno+')').hide();
        	
			// console.log(colno);
		});

		$('#table-columns option:selected').each(function(i,elem){
			$('.col-'+$(elem).val()).show();
   //  		let colno = parseInt($(elem).val()) + 1;
			// // alert($(elem).val());
			// $('#stock-table th:nth-child('+colno+')').show();
			// $('#stock-table td:nth-child('+colno+')').show();
        	
			// console.log(colno);
		});
	}

}

jQuery(document).ready(function() {

	Scrip.prototype = Object.create(Base.prototype);
	var content = new Scrip();
	content.init();
});
