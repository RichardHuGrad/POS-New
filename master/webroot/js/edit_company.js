$(document).ready(function () {
    $("#submit_button").click(function () {
        if ($.trim($("#company_id").val()) == "") {
            $("#company_id-error").html("Please specify company id");
            $("#company_id").parent('div').addClass('has-error');
            $("#company_id").focus();
            return false;
        } else {
            $("#company_id").parent('div').removeClass('has-error');
            $("#company_id-error").html("");
        }

        if ($.trim($("#company_name").val()) == "") {
            $("#company_name-error").html("Please specify company name");
            $("#company_name").parent('div').addClass('has-error');
            $("#company_name").focus();
            return false;
        } else {
            $("#company_name").parent('div').removeClass('has-error');
            $("#company_name-error").html("");
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
        
        if ($.trim($("#owner_name").val()) == "") {
            $("#owner_name-error").html("Please specify owner name");
            $("#owner_name").parent('div').addClass('has-error');
            $("#owner_name").focus();
            return false;
        } else {
            $("#owner_name").parent('div').removeClass('has-error');
            $("#owner_name-error").html("");
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

        if ($.trim($("#car_type").val()) == "") {
            $("#car_type-error").html("Please select car type");
            $("#car_type").parent('div').addClass('has-error');
            $("#car_type").focus();
            return false;
        } else {
            $("#car_type").focus();
            $("#car_type").parent('div').removeClass('has-error');
            $("#car_type-error").html("");
        }

        if ($.trim($("#car_maker").val()) == "") {
            $("#car_maker-error").html("Please select car maker");
            $("#car_maker").parent('div').addClass('has-error');
            $("#car_maker").focus();
            return false;
        } else {
            $("#car_maker").focus();
            $("#car_maker").parent('div').removeClass('has-error');
            $("#car_maker-error").html("");
        }

        if ($.trim($("#car_model").val()) == "") {
            $("#car_model-error").html("Please specify car model");
            $("#car_model").parent('div').addClass('has-error');
            $("#car_model").focus();
            return false;
        } else {
            $("#car_model").focus();
            $("#car_model").parent('div').removeClass('has-error');
            $("#car_model-error").html("");
        }

        if ($.trim($("#car_no").val()) == "") {
            $("#car_no-error").html("Please specify car maker");
            $("#car_no").parent('div').addClass('has-error');
            $("#car_no").focus();
            return false;
        } else {
            $("#car_no").focus();
            $("#car_no").parent('div').removeClass('has-error');
            $("#car_no-error").html("");
        }

        if ($.trim($("#driving_license").val()) == "") {
            $("#driving_license-error").html("Please specify driving license no");
            $("#driving_license").parent('div').addClass('has-error');
            $("#driving_license").focus();
            return false;
        } else {
            $("#driving_license").focus();
            $("#driving_license").parent('div').removeClass('has-error');
            $("#driving_license-error").html("");
        }

        if ($.trim($("#fair").val()) == "") {
            $("#fair-error").html("Please specify fair");
            $("#fair").parent('div').addClass('has-error');
            $("#fair").focus();
            return false;
        } else {
            $("#fair").focus();
            $("#fair").parent('div').removeClass('has-error');
            $("#fair-error").html("");
        }

    });
});