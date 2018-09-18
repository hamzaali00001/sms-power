jQuery(window).load(function() { // makes sure the whole site is loaded
        jQuery("#preloader").delay(200).fadeOut(); // will first fade out the loading animation
});
function navFunction() {
	if(jQuery(window).width() < 768) {
		jQuery("#responsive_menu").css('height', '0px');
		jQuery("#responsive_menu").removeClass('in');
		jQuery(".navbar-toggle").addClass('collapsed');
	}
	else{
		jQuery("#responsive_menu").css('height', 'auto');
		jQuery("#responsive_menu").removeClass('in');
		jQuery(".navbar-toggle").removeClass('collapsed');
	}
}
function nav_height(){
	if(jQuery(window).width() > 767) {
	jQuery('.sms_navigation').css('max-height', '');
	jQuery('.sms_navigation .mCustomScrollBox').css('max-height', 'inherit');
	var win_height	 	=	jQuery(window).height();
	var win_height2	 	=	win_height - 100;
	var nav_height	 	=	jQuery('.sms_navigation').height();
	if (win_height2 < nav_height){
	jQuery('.sms_navigation').css('max-height', win_height2);
	}
	jQuery(".sms_navigation").mCustomScrollbar();
	}
}
var TableManaged = function () {
    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            // begin second table
            $('#data_tables').dataTable({
				aaSorting: [[5, 'asc']],
                "aLengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 10,
                "sPaginationType": "bootstrap",
				"sDom": "<'row sort-table-header'<'col-xxs-12 col-xs-6 col-sm-8 col-md-9'l><'col-xxs-12 col-xs-6 col-sm-4 col-md-3'<''f>>r>t<'row mt-10 mt-0-xs'<'col-xs-12 col-sm-5 col-md-5'i><'col-xs-12 col-sm-7 col-md-7'p>>",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ per page",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },
				"aoColumnDefs": [
					{ "aTargets": [ 0 ], "bSortable": false },
					{ "aTargets": [ 1 ], "bSortable": true },
					{ "aTargets": [ 2 ], "bSortable": true },
					{ "aTargets": [ 3 ], "bSortable": true },
					{ "aTargets": [ 4 ], "bSortable": true },
					{ "aTargets": [ 5 ], "bSortable": true },
					{ "aTargets": [ 6 ], "bSortable": false }
				]
            });

            jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small"); // modify table search input
            jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
            jQuery('#sample_2_wrapper .dataTables_length select').select2(); // initialize select2 dropdown
        }
    };
}();

function CreditVolume(){
$("#quantity").keyup(function() {
				    var value = $(this).val();
					if (value != "")
				    {
					    if (isNaN(value))
					    {
					    	value = 0;
					    }

					    var credits = $(this).val();

						$(this).val(Math.ceil(value));
					    var value = credits * 0.8000;

					    value = (Math.ceil(value));

					    var mobile_value = value;	
						if (mobile_value > 70000)
					    {
					    	mobile_value = 70000;
					    }
					    $('#amount').val(value + ".00");
					    $('.display-amount').text(mobile_value);
					    $('#cc_amount').val(value);

					    $('#quantity_slider').slider("setValue", credits);

					}
				});

				$('#quantity_slider').on('slide', function (ev) {
					$('#quantity').val($('#quantity_slider').val());
					$("#quantity").keyup();
				});
}

jQuery(document).ready(function() {
	navFunction();
	nav_height();
	//jQuery('#sms_country_flag').flagStrap({
        //countries: {
            //"US": "United States",
			//"CA": "Canada",
			//"GB": "United Kingdom",
			//"KE": "Kenya"
        //}
    //});
	// fade in #back-top
	var win_height = jQuery(window).height();
	// scroll body to 0px on click
	jQuery('#back_top').click(function () {
			jQuery('body,html').animate({
					scrollTop: 0
			}, 800);
			return false;
	});
   jQuery('[data-toggle="tooltip"]').tooltip();
   jQuery('[data-toggle="popover"]').popover();

   TableManaged.init(); 
	
	/* ------------- Start Checkbox JS ------------- */
	$("#sms_all").hide();
	$('#checkAll').click(function (event) {
		if (this.checked) {
			$('.rowCheck').each(function () {
				this.checked = true;
			});
			$("#sms_all").show();
		} else {
			$('.rowCheck').each(function () {
				this.checked = false;
			});
			$("#sms_all").hide();
		}
	});
	$('.rowCheck').click(function () {
		
		if ($('#checkAll').is(':checked')) {
			$('#checkAll').attr('checked', false);
		}
		
		var checkCount = $('#data_tables').find('input[type="checkbox"]:checked').length;
		if (checkCount > 1) {
			$("#sms_all").show();
		}
		else {
			$("#sms_all").hide();
		}
	});
	$('ul.pagination').click(function () {
		$('#checkAll').attr('checked', false);
		$('.rowCheck').each(function () {
				this.checked = false;
		});
		$("#sms_all").hide();
	});
	
	/* ------------- End Checkbox JS ------------- */
	
	/* ------------- Start Select JS ------------- */
   jQuery(".select2").select2();
   jQuery(".dataTables_wrapper select").select2();
	function formatState (state) {
      if (!state.id) {
        return state.text;
      }
      var $state = jQuery(
        '<span>' +
          '<img src="img/flags/' +
            state.element.value.toLowerCase() +
          '.png" class="img-flag" /> ' +
          state.text +
        '</span>'
      );
      return $state;
    };
    jQuery(".select2-language").select2({
      templateResult: formatState,
      templateSelection: formatState
    });
   /* ------------- End Select JS ------------- */

   /* ------------- Start TextArea JS ------------- */
	var $remaining = $('#remaining'),
    $messages = $remaining.next();
	$('#message').keyup(function(){
    var chars = this.value.length,
        messages = Math.ceil(chars / 160),
        remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
    $remaining.html("<strong class='blue'>" + remaining + "</strong> characters remaining");
    $messages.html("<strong class='blue'>" + messages + "</strong> message(s)");
	});
   /* ------------- Start TextArea JS ------------- */
	
   /* ------------- Start SMS Later JS ------------- */
	jQuery('.sms_later').hide();
	jQuery('form#admin-contact').click(function() {								  
		if ( jQuery('#sms_schedule_later').is(':checked')) { 
			jQuery('.sms_later').show(); 
		} else {
			jQuery('.sms_later').hide();
		}							   
	});
	
	jQuery('.sms_later_single').hide();
	jQuery('form#single-sms').click(function() {			  
		if ( jQuery('#sms_schedule_later_single').is(':checked') ) { 
			jQuery('.sms_later_single').show(); 
		} else {
			jQuery('.sms_later_single').hide();
		}							   
	});
	jQuery('.sms_later_bulk').hide();
	jQuery('form#bulk-sms').click(function(){							  
		if ( jQuery('#sms_schedule_later_bulk').is(':checked') ) { 
			jQuery('.sms_later_bulk').show(); 
		} else {
			jQuery('.sms_later_bulk').hide();
		}							   
	});
	
	jQuery(function () {
        jQuery("input[name='sms_schedule_later']").click(function () {
            if (jQuery("#sms_schedule_later").is(":checked")) {
                jQuery('.sms_later').show(); 
            } else {
                jQuery('.sms_later').hide();
            }
        });
    });

   CreditVolume();
   /* ------------- Start Country Phone JS ------------- */
	/*jQuery("#phone").intlTelInput({
        // allowExtensions: true,
        // autoFormat: true,
        // autoHideDialCode: false,
        // autoPlaceholder: false,
        // dropdownContainer: "body",
        // excludeCountries: ["us"],
        // geoIpLookup: function(callback) {
        //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
        //     var countryCode = (resp && resp.country) ? resp.country : "";
        //     callback(countryCode);
        //   });
        // },
        initialCountry: "ke",
        // nationalMode: false,
        // numberType: "MOBILE",
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        // preferredCountries: ['cn', 'jp'],
        utilsScript: "js/utils.js"
      });*/
	var telInput = jQuery("#phone"),
  	errorMsg = jQuery("#error-msg"),
  	validMsg = jQuery("#valid-msg");
	var reset = function() {
	  telInput.removeClass("error");
	  errorMsg.addClass("hide");
	  validMsg.addClass("hide");
	};

	// on blur: validate
	telInput.blur(function() {
	  reset();
	  if (jQuery.trim(telInput.val())) {
		if (telInput.intlTelInput("isValidNumber")) {
		  validMsg.removeClass("hide");
		} else {
		  telInput.addClass("error");
		  errorMsg.removeClass("hide");
		}
	  }
	});
	// on keyup / change flag: reset
	telInput.on("keyup change", reset);
   /* ------------- End Country Phone JS ------------- */
   jQuery(":file").filestyle({buttonText: "Find file"});
});

jQuery(window).resize( function(){
	navFunction();
	nav_height();
});

// Append text to the textarea
jQuery.fn.extend({
    insertAtCaret: function(myValue){
        return this.each(function(i) {
            if (document.selection) {
                //For browsers like Internet Explorer
                this.focus();
                var sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            }
            else if (this.selectionStart || this.selectionStart == '0') {
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = startPos + myValue.length;
                this.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        });
    }
});