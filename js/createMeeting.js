/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var dateFormat = "dd/mm/yy";

$(document).ready(function () {
    // conlog("#__________ CreateMeeting:  ___________#");
    console.log("#__________ CreateMeeting: Start ___________#");

    main();

    console.log("#__________ CreateMeeting: End ___________#");

});



function main() {
    // console.log("#__________ CreateMeeting: Main ___________#");

    initDatePicker();



}

function initDatePicker() {
    
    //    Standard datepicker
    //    var from = $("#fromdate").datetimepicker({
    //        defaultDate: "+1w",
    //        numberOfMonths: 1,
    //        format: "dd/mm/yyyy"
    //    }).on("change", function () {
    //        to.datepicker("option", "minDate", getDate(this));
    //    });
    //    var to = $("#todate").datetimepicker({
    //        defaultDate: "+1w",
    //        numberOfMonths: 1,
    //        format: "dd/mm/yyyy"
    //    }).on("change", function () {
    //        from.datepicker("option", "maxDate", getDate(this));
    //    });

    /* ---------- !!!! different datepicker library !!!! ---------- */
    $('#todate').datetimepicker({
        useCurrent: false, //datetimepicker - Important! See issue #1075
        format: "llll"
    });
    $('#fromdate').datetimepicker({
        useCurrent: false, //datetimepicker - Important! See issue #1075
        format: "llll"
    });

    $("#fromdate").on("dp.change", function (e) {
        $('#todate').data("DateTimePicker").minDate(e.date);
    });
    $("#todate").on("dp.change", function (e) {
        $('#fromdate').data("DateTimePicker").maxDate(e.date);
    });
    /* ---------- !!!! different datepicker library !!!! ---------- */

    // Block input on datetimepicker except pastes, copy
    $('#todate').keypress(function (e) {
        e.preventDefault();
        return false;
    });

    $('#fromdate').keypress(function (e) {
        e.preventDefault();
        return false;
    });

}


function getDate(element) {
    var date;
    try {
        date = $.datepicker.parseDate(dateFormat, element.value);
    } catch (error) {
        date = null;
    }

    return date;
}

// jQuery 
$("#createMeetingForm").validate({
    // Specify validation rules
    rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        meetingtitle: "required",
        meetingvenue: "required",
        meetingfrom: "required",
        meetingto: "required",
        description: "required",
        "participants[]": "required"
    },
    // Specify validation error messages
    messages: {
        meetingtitle: "Please enter the meeting title",
        meetingvenue: "Please enter the meeting venue",
        meetingfrom: "Please enter the start date/time",
        meetingto: "Please enter the end date/time",
        description: "Please enter the meeting description",
        "participants[]": "Please select at least one participant for the meeting"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function (form) {
        form.submit();
    }
})

// jQuery 
$("#counter-proposes").validate({
    // Specify validation rules
    rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        meetingfrom: "required",
        meetingto: "required",
    },
    // Specify validation error messages
    messages: {
        meetingfrom: "Please enter the start date/time",
        meetingto: "Please enter the end date/time",
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function (form) {
        form.submit();
    }
});