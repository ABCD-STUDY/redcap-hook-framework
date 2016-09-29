### Highlight finished rows

Rows in a survey or data entry form that have not been filled out are displayed in gray. This plugin will keep the green bar active for all rows that have entered values. Only rows that are not filled in will have a gray background.

### Add a barcode

Displays a barcode version of the participant id if a tag with the name "barcode_id_redcap" exists on the current data entry form.

### Multi-language support

Several ways to implement multi-language support for data entry forms have been proposed for REDCap in the past. Most of them require branching logic and calculated field to represent similar entries across different languages in a common set of variables.

Here we use a very simply javascript extension that does not require a duplication of fields nor branching logic. The extension disables parts of Field Labels based on the value of variable defined in the data entry form (Spanish Version?) with a variable name that ends with 'select_language' (checkbox type). Parts of the field labels on the page that have an html tag of "lang=en" are disabled if the language setting is "es", e.g. different from the language requested.
