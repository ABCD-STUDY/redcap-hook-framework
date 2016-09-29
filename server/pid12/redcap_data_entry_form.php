<?php

  // We will do this for every form in redcap.

  // A hint for the webpage if a user has filled out each form entry.
  // At every point we want to show if some entry has been filled out already.

?>

<script type='text/javascript'>

  // if we have a specific label on the page, show a barcode version of the string
  function addBarcode( where, what ) {
     jQuery.getScript("../../hooks/server/pid12/js/JsBarcode.all.min.js", function() {
        // add the barcode now using functions provided in JsBarcode
	jQuery(where).JsBarcode(what);
     });
  }

  var languageModification = true;
  function updateLanguage() {
      if (languageModification == false) {
	  return; // don't do anything
      }
    
      // check the language setting first, we should have an id on this page that ends with 'select_language-tr'
      var language = "";
      jQuery('#questiontable').find('tr').each(function() {
	  if (typeof this.id != 'undefined' && this.id != "") {
	      var target = 'select_language-tr';
	      if ( (this.id).slice(-target.length) === target ) {
		  var cb = jQuery(this).find('td.data input:checked');
		  if (cb.length == 1) {
			// spanish
		      language = "es";
		  } else {
		      // english
		      language = "en";
		  }
	      }
	  }
      });
      if (languageModification == true && language == "") {
	  // we are called the first time, but there is no language setting on this page
	  languageModification = false; // never go here again - this should speed up the code
      }
      
      // if we find a language setting on this page we should have either es or en in language
      // if we don't have an entry at this point - there is no reason to do anything on this page
      // as we don't know the language
      
      jQuery("#questiontable").find('span').each(function() {
	  var lang = jQuery(this).attr('lang');
	  if (typeof lang  != 'undefined' && lang != "") {
	      if (lang == language) {
		  jQuery(this).show();
	      } else {
		  // disable this field
		  jQuery(this).hide();
	      }
	  }
      });
  }


  // color all rows that have values assigned by the user
  function updateBackgrounds() {
     jQuery('tbody tr').each(function() {
       // for each row we check now if there is at least one entry in there that has been set
       existingEntries = false;
       // every field that requires values has an input (assumption)
       var inputs = jQuery(this).find('input');

       // for each of the inputs in this row see if at least one entry has a value (muliple-choice answers have multiple inputs)
       for (var i = 0; i < inputs.length; i++) {
         if (inputs[i].type == "radio" && inputs[i].checked) {
            //console.log("Found set in " + jQuery(this).attr('id') + " as " + inputs[i].value + " input: " + inputs[i]);
	    existingEntries = true; // at least one input in this tr is not empty
	    break;	    
	 } else if ( inputs[i].type == "text" && inputs[i].value !== "") {
             //console.log("Found set in " + jQuery(this).attr('id') + " as " + inputs[i].value + " input: " + inputs[i]);

	     // we could have a calculated field, in that case we don't want to mark it
	     if (jQuery(inputs[i]).attr('readonly') == "readonly") {
		 break;
	     }
	     existingEntries = true; // at least one input in this tr is not empty	 
	     break;
         }
       }
       if (existingEntries) {
          jQuery(this).find('td').css('background-color', '#dbf7df');
       } else if (inputs.length > 0) {
          // console.log("Nothing set in " + jQuery(this).attr('id'));
          // jQuery(this).find('td').css('background-color', '#999');
       }
     });
  }

  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  };


  $(document).ready(function() {
     // console.log("now in redcap data entry form");
     jQuery('tbody tr').click(function() {
        //console.log("Someone clicked on something (in redcap_data_entry_form)");
	 updateBackgrounds();
	 updateLanguage(); // every time we click on something
     });
     // only add a barcode if we have this id on the page barcode_id_redcap-tr
     var placeholder = jQuery('#barcode_id_redcap-tr').find('td');
     if (placeholder) {
        var here = jQuery(placeholder).append('<img id="barcode">');
	//console.log(jQuery('#id_redcap-tr td.data').text());

        addBarcode( '#barcode', getUrlParameter('id'));
     }
     updateLanguage(); // first time as well
  });
  
</script>
