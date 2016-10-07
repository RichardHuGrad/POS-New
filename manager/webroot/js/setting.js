$(document).ready(function () {
    $("#submit_button").click(function () {
        if ($.trim($("#firstname").val()) == "") {
            $("#firstname-error").html("Please specify first name");
            $("#firstname").parent('div').addClass('has-error');
            $("#firstname").focus();
            return false;
        } else {
            $("#firstname").parent('div').removeClass('has-error');
            $("#firstname-error").html("");
        }
    });
});