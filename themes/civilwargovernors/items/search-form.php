<?php
if (!empty($formActionUri)):
    $formAttributes['action'] = $formActionUri;
else:
    $formAttributes['action'] = url(array('controller'=>'items',
                                          'action'=>'browse'));
endif;
$formAttributes['method'] = 'GET';
?>

<script>
  // clear the search form when a user uses the browser back button to do another advanced search
  jQuery(window).load(function() {
    jQuery('form').get(0).reset();
  });
</script>

<form id="advanced-solr-search-form" action="/solr-search?" method="GET">
  <div class="field">
      <div class="label">
        <h3>Search within specific fields</h3>
      </div>
      <div class="inputs">
          <div class="search-entry">
            <select title="Search Field" id="advanced-solr-search-element-1">
              <option>Select a field</option>
              <option value="52_t:">Accession Number</option>
              <option value="59_t:">Collection</option>
              <option value="62_t:">Date of Creation</option>
              <option value="64_t:">Dates Mentioned</option>
              <option value="65_t:">Document Genre</option>
              <option value="53_t:">Document Title</option>
              <option value="54_t:">Editorial Note</option>
              <option value="60_t:">Item Location</option>
              <option value="63_t:">Place of Creation</option>
              <option value="58_t:">Repository</option>
              <option value="66_t:">Transcription</option>
            </select>
            <input type="text" title="Search Terms" class="advanced-solr-search-terms" name="q" value="" id="solr-advanced-term-1" placeholder="Enter keyword search"/>
          </div>
          <div class="search-entry">
            <select title="Search Field" id="advanced-solr-search-element-2">
              <option>Select a field</option>
              <option value="52_t:">Accession Number</option>
              <option value="59_t:">Collection</option>
              <option value="62_t:">Date of Creation</option>
              <option value="64_t:">Dates Mentioned</option>
              <option value="65_t:">Document Genre</option>
              <option value="53_t:">Document Title</option>
              <option value="54_t:">Editorial Note</option>
              <option value="60_t:">Item Location</option>
              <option value="63_t:">Place of Creation</option>
              <option value="58_t:">Repository</option>
              <option value="66_t:">Transcription</option>
            </select>
            <input type="text" title="Search Terms" class="advanced-solr-search-terms" name="" value="" id="solr-advanced-term-2" placeholder="Enter keyword search"/>
          </div>
          <div class="search-entry">
            <select title="Search Field" id="advanced-solr-search-element-3">
              <option>Select a field</option>
              <option value="52_t:">Accession Number</option>
              <option value="59_t:">Collection</option>
              <option value="62_t:">Date of Creation</option>
              <option value="64_t:">Dates Mentioned</option>
              <option value="65_t:">Document Genre</option>
              <option value="53_t:">Document Title</option>
              <option value="54_t:">Editorial Note</option>
              <option value="60_t:">Item Location</option>
              <option value="63_t:">Place of Creation</option>
              <option value="58_t:">Repository</option>
              <option value="66_t:">Transcription</option>
            </select>
            <input type="text" title="Search Terms" class="advanced-solr-search-terms" name="" value="" id="solr-advanced-term-3" placeholder="Enter keyword search"/>
          </div>
      </div>
  </div>
  <script>
  jQuery(document).ready(function(){
    // when click form submit button ('search') get the values of the search form and construct the search query
    jQuery("#submit_solr_search_advanced").click(function(){
      // 1st input values
          var select1 = jQuery("#advanced-solr-search-element-1").val();
          var input1raw = jQuery("#solr-advanced-term-1").val();
          //if query begins with quotes but lacks closing quotes, remove beginning quotes. otherwise an omeka error will occur
          if(input1raw.startsWith("\"") && !input1raw.endsWith("\"")){
            var input1 = input1raw.substring(1);
          }
          // if query contains a hyphen, it's probably a date or accession number. wrap it in quotes so the hyphen is not stripped by the tokenizer
          else if (input1raw.indexOf("-") != -1){
            var input1 = "\"" + input1raw + "\""
          }
          //if query contains more than one word and is not inside double quotes, wrap query in parens and add "+" before each term to create an AND search
          //within the field instead of an OR search
          else if(!input1raw.endsWith("\"") && !input1raw.startsWith("\"") && input1raw.indexOf(" ") != -1){
                var input1array = input1raw.split(" ");
                var input1array = input1array.map(function (i){
                    return '+' + i;
                });
                var input1string = input1array.join(" ");
                var input1 = "(" + input1string + ")";
          } else {
            //query is inside double quotes or it is a single word query. keep it as entered.
            var input1 = jQuery("#solr-advanced-term-1").val();
          }

      // 2nd input values
        var select2 = jQuery("#advanced-solr-search-element-2").val();
        var input2raw = jQuery("#solr-advanced-term-2").val();
        //if query begins with quotes but lacks closing quotes, remove beginning quotes. otherwise an omeka error will occur
        if(input2raw.startsWith("\"") && !input2raw.endsWith("\"")){
          var input2 = input2raw.substring(1);
        }
        // if query contains a hyphen, it's probably a date or accession number. wrap it in quotes so the hyphen is not stripped by the tokenizer
        else if (input2raw.indexOf("-") != -1){
          var input2 = "\"" + input2raw + "\""
        }
        //if query contains more than one word and is not inside double quotes, wrap query in parens and add "+" before each term to create an AND search
        //within the field instead of an OR search
        else if(!input2raw.endsWith("\"") && !input2raw.startsWith("\"") && input2raw.indexOf(" ") != -1){
              var input2array = input2raw.split(" ");
              var input2array = input2array.map(function (i){
                  return '+' + i;
              });
              var input2string = input2array.join(" ");
              var input2 = "(" + input2string + ")";
        } else {
          //query is inside double quotes or it is a single word query. keep it as entered.
          var input2 = jQuery("#solr-advanced-term-2").val();
        }

      // 3rd input values
        var select3 = jQuery("#advanced-solr-search-element-3").val();
        var input3raw = jQuery("#solr-advanced-term-3").val();
        //if query begins with quotes but lacks closing quotes, remove beginning quotes. otherwise an omeka error will occur
        if(input3raw.startsWith("\"") && !input3raw.endsWith("\"")){
          var input2 = input3raw.substring(1);
        }
        // if query contains a hyphen, it's probably a date or accession number. wrap it in quotes so the hyphen is not stripped by the tokenizer
        else if (input3raw.indexOf("-") != -1){
          var input3 = "\"" + input3raw + "\""
        }
        //if query contains more than one word and is not inside double quotes, wrap query in parens and add "+" before each term to create an AND search
        //within the field instead of an OR search
        else if(!input3raw.endsWith("\"") && !input3raw.startsWith("\"") && input3raw.indexOf(" ") != -1){
              var input3array = input3raw.split(" ");
              var input3array = input3array.map(function (i){
                  return '+' + i;
              });
              var input3string = input3array.join(" ");
              var input3 = "(" + input3string + ")";
        } else {
          //query is inside double quotes or it is a single word query. keep it as entered.
          var input3 = jQuery("#solr-advanced-term-3").val();
        }

      // construct the query
      // 1st input only
      if ((select1 != 'Select a field' && input1 != '') && (select2 == 'Select a field' || input2 == '') && (select3 == 'Select a field' || input3 == '')){
        jQuery("#solr-advanced-term-1").val(select1 + input1);
      }
      // 2nd input only
      else if ((select1 == 'Select a field' || input1 == '') && (select2 != 'Select a field' && input2 != '') && (select3 == 'Select a field' || input3 == '')){
        jQuery("#solr-advanced-term-1").val(select2 + input2);
      }
      // 1st and 2nd input only
      else if ((select1 != 'Select a field' && input1 != '') && (select2 != 'Select a field' && input2 != '') && (select3 == 'Select a field' || input3 == '')){
        jQuery("#solr-advanced-term-1").val(select1 + input1 + ' AND ' + select2 + input2);
      }
      // 2nd and 3rd input only
      else if ((select1 == 'Select a field' || input1 == '') && (select2 != 'Select a field' && input2 != '') && (select3 != 'Select a field' && input3 != '')){
        jQuery("#solr-advanced-term-1").val(select2 + input2 + ' AND ' + select3 + input3);
      }
      // 1st and 3rd input only
      else if ((select1 != 'Select a field' && input1 != '') && (select2 == 'Select a field' || input2 == '') && (select3 != 'Select a field' && input3 != '')){
        jQuery("#solr-advanced-term-1").val(select1 + input1 + ' AND ' + select3 + input3);
      }
      // all 3 inputs
      else if ((select1 != 'Select a field' && input1 != '') && (select2 != 'Select a field' && input2 != '') && (select3 != 'Select a field' || input3 != '')){
        jQuery("#solr-advanced-term-1").val(select1 + input1 + ' AND ' + select2 + input2 + ' AND ' + select3 + input3);
      }
      // default blank query
      else {
        jQuery("#solr-advanced-term-1").val('');
      }
    });
  });
  </script>
  <div class="submit_solr_search_advanced">
      <?php if (!isset($buttonText)) $buttonText = __('Search'); ?>
      <input type="submit" class="submit" id="submit_solr_search_advanced" value="<?php echo $buttonText ?>">
      <button id="simple-search-link"><a href="/solr-search?q=">Back to simple search</a></button>
  </div>

</form>





<?php echo js_tag('items-search'); ?>
<!--<script type="text/javascript">
    jQuery(document).ready(function () {
        Omeka.Search.activateSearchButtons();
    });
</script>-->
