jQuery(document).ready(function($) {

    $( "#pb_app_box_example_pn" ).keypress(function(e) {
        if (e.keyCode == 13) {
            pb_get_shortcode_data();
            return false; // prevent the button click from happening
        }
    });

    $('#pb_app_box_generate_pn').click(pb_get_shortcode_data);

    function pb_get_shortcode_data(){
        $('#pb_app_box_generate_pn').text('Loading..');
        var get_shortcode = {
            url:'https://playboard.me/api/apps/search.json?q=' + $('#pb_app_box_example_pn').val() + '&num=1&mode=addapp',
            method:'get',
            dataType:'jsonp',
            success:function (data) {

                if (data && data.items && data.items.length > 0){
                    var shortcode = "[pb-app-box pname='" + data.items[0].package_name + "' name='" + data.items[0].name +  "' theme='" + $("#pb_app_box_themes_select").val() + "' lang='" + $("#pb_app_box_lang_select" ).val() + "']";
                    $('#pb_app_box_shortcode_textarea').text(shortcode);
                }else{
                    var pn = $('#pb_app_box_example_pn').val();
                    if (pn.indexOf(".") !== -1){
                        var shortcode = "[pb-app-box pname='" + pn + "' name='" + pn + "' theme='" + $("#pb_app_box_themes_select").val() + "' lang='" + $("#pb_app_box_lang_select" ).val() + "']";
                        $('#pb_app_box_shortcode_textarea').text(shortcode);
                    }else{
                        $('#pb_app_box_shortcode_textarea').text('Shortcode generation failed, please go to http://playboard.me/widgets to get a shortcode');
                    }

                }
                $('#pb_app_box_generate_pn').text('Generate');
            },
            error:function (xhr, ajaxOptions, thrownError) {

                var pn = $('#pb_app_box_example_pn').val();
                if (pn.indexOf(".") !== -1){
                    var shortcode = "[pb-app-box pname='" + pn + "' name='Android App " + pn + " on Playboard' theme='" + $("#pb_app_box_themes_select").val() + "' lang='" + $("#pb_app_box_lang_select" ).val() + "']";
                    $('#pb_app_box_shortcode_textarea').text(shortcode);
                }else{
                    $('#pb_app_box_shortcode_textarea').text('Shortcode generation failed, please go to http://playboard.me/widgets to get a shortcode');
                }
                $('#pb_app_box_generate_pn').text('Generate');
            }
        };
        $.ajax(get_shortcode);
    }




});
