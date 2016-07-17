<?php

  // We will do this for every form in redcap.

  // A hint for the webpage if a user has filled out each survey entry.
  // At every point we want to show if some entry has been filled out already.

?>

<script type='text/javascript'>

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

  $(document).ready(function() {
     jQuery('tbody tr').click(function() {
	updateBackgrounds();
     });
  });
  
</script>