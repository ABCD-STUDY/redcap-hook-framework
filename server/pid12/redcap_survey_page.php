<?php

  // We will do this for every form in redcap.

  // A hint for the webpage if a user has filled out each survey entry.
  // At every point we want to show if some entry has been filled out already.

?>

<script type='text/javascript'>
  function getXPath( element ) {
      var xpath = '';
      for ( ; element && element.nodeType == 1; element = element.parentNode )
          {
              var id = $(element.parentNode).children(element.tagName).index(element) + 1;
              id > 1 ? (id = '[' + id + ']') : (id = '');
              xpath = '/' + element.tagName.toLowerCase() + id + xpath;
          }
      return xpath;
  }
    
  var languageModification = true;
  var dropDownEntries = {};
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
      // we have to do something special for the drop-down menu entries, they don't contain the html
      // code required to switch (all span's are removed), we have to look for those entries independently
      // for this we have to keep a list of entries and the original content ready as we will remove the entries
      // present the first time around
      jQuery('#questiontable').find('select option').each(function() {
          var entry = getXPath(this);
          // do we have this entry in dropDownEntries?
          if (jQuery.inArray(entry, Object.keys(dropDownEntries)) == -1) {
              dropDownEntries[entry] = jQuery(this).text();
          }
          // ok if we have this already in the list, what do we need to display in this case?
          // is there a language setting?
          str = "";
          var re = /##([a-z]+)##([^(#)\2]*)##\/[a-z]+##/g;
          while(match = re.exec(dropDownEntries[entry])) {
              // we have now 
              //console.log("found language " + match[1] + " " + match[2]);
              if (match[1] == language) {
                  str = str + match[2];
              }
          }
          if (str != "") {
             jQuery(this).text(str);
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
        updateLanguage(); // first time as well
     });
  	 updateBackgrounds();
     updateLanguage(); // first time as well
  });
  
</script>