if (!Seasons) {
    var Seasons = {};
}

(function ($) {
    Seasons.sortLinks = function () {
        $("#sort-links span").click(function(){
          $("#sort-links ul").slideToggle("slow");
        })
    }

    Seasons.emDash = function() {
      $("#tei_wrapper p").html(function(index, html) {
      return html.replace(/\--/g, '&mdash;');

      });
      $("#tei_wrapper p").html(function(index, html) {
        return html.replace('&amp;#9756;', '&#9756;');
      });
      $("#tei_wrapper p").html(function(index, html) {
        return html.replace('&amp;#9758;', '&#9758;');
      });
      $("#tei_wrapper p").html(function(index, html) {
        return html.replace('&amp;#x2713;', '&#x2713;');
      });
    }

    Seasons.mobileSelectNav = function () {
        // Create the dropdown base
        $("<select class=\"mobile\" />").appendTo("nav.top");

        // Create default option "Go to..."
        $("<option />", {
           "selected": "selected",
           "value"   : "",
           "text"    : "Menu"
        }).appendTo("nav select");

        // Populate dropdown with menu items
        $("nav.top a").each(function() {
            var el = $(this);
            if (el.parents('ul ul').length) {
                var parentCount = el.parents("ul").length;
                var dashes = new Array(parentCount).join('- ');
                $("<option />", {
                    "value": el.attr("href"),
                    "text":  dashes + el.text()
                }).appendTo("nav select");
            } else {
                $("<option />", {
                    "value": el.attr("href"),
                    "text": el.text()
                }).appendTo("nav.top select");
            }
            $("nav.top select").change(function() {
              window.location = $(this).find("option:selected").val();
            });
        });
    }

})(jQuery);
