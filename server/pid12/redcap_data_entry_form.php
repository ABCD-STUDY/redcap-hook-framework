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
     });
     // only add a barcode if we have this id on the page barcode_id_redcap-tr
     var placeholder = jQuery('#barcode_id_redcap-tr').find('td');
     if (placeholder) {
        var here = jQuery(placeholder).append('<img id="barcode">');
	//console.log(jQuery('#id_redcap-tr td.data').text());

        addBarcode( '#barcode', getUrlParameter('id'));
     }
  });
  
</script>