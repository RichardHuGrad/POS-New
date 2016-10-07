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

        if ($.trim($("#lastname").val()) == "") {
            $("#lastname-error").html("Please specify last name");
            $("#lastname").parent('div').addClass('has-error');
            $("#lastname").focus();
            return false;
        } else {
            $("#lastname").parent('div').removeClass('has-error');
            $("#lastname-error").html("");
        }

        if ($.trim($("#email").val()) == "") {
            $("#email-error").html("Please specify email address");
            $("#email").parent('div').addClass('has-error');
            $("#email").focus();
            return false;
        } else {
            var string = $("#email").val()
            var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (filter.test(string)) {
                testresult = true;
                $("#email-error").html("");
                $("#email").parent('div').removeClass('has-error');
            } else {
                $("#email-error").html('Please enter a valid email address');
                $("#email").parent('div').addClass('has-error');
                $("#email").val("");
                $("#email").focus();
                return false
            }
        }

        if ($.trim($("#contact_no").val()) == "") {
            $("#contact_no-error").html("Please specify contact no");
            $("#contact_no").parent('div').addClass('has-error');
            $("#contact_no").focus();
            return false;
        } else {
            $("#contact_no").parent('div').removeClass('has-error');
            $("#contact_no-error").html("");
        }


        if (!$("input[name='gender']").is(":checked")) {
            $("#gender-error").html("Please select gender");
            $(".Gender").children('div').addClass('has-error');
            return false;
        } else {
            $(".Gender").children('div').removeClass('has-error');
            $("#gender-error").html("");
        }

    });
});