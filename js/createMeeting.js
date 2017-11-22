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

    /* ---------- !!!! different datepicker library !!!! ---------- */
    $('#startTime').datetimepicker({
        useCurrent: false, //datetimepicker - Important! See issue #1075
        format: "LT"
    });
    $('#endTime').datetimepicker({
        useCurrent: false, //datetimepicker - Important! See issue #1075
        format: "LT"
    });

    $("#startTime").on("dp.change", function (e) {
        $('#endTime').data("DateTimePicker").minDate(e.date);
    });
    $("#endTime").on("dp.change", function (e) {
        $('#startTime').data("DateTimePicker").maxDate(e.date);
    });
    /* ---------- !!!! different datepicker library !!!! ---------- */

    /* ---------- !!!! different datepicker library !!!! ---------- */
    $('#startDate').datetimepicker({
        useCurrent: false, //datetimepicker - Important! See issue #1075
        format: "ll"
    });
    $('#endDate').datetimepicker({
        useCurrent: false, //datetimepicker - Important! See issue #1075
        format: "ll"
    });

    $("#startDate").on("dp.change", function (e) {
        $('#endDate').data("DateTimePicker").minDate(e.date);
    });
    $("#endDate").on("dp.change", function (e) {
        $('#startDate').data("DateTimePicker").maxDate(e.date);
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

// jQueryValidate
$("#createMeetingForm").validate({
    // Specify validation rules
    rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        startDate: "required",
        endDate: "required",
        startTime: "required",
        endTime: "required",
        title: "required",
        description: "required",
        venue: "required",
        participant: "required"
    },
    // Specify validation error messages
    messages: {
        startDate: "Please enter a start date",
        endDate: "Please enter the end date",
        startTime: "Please enter the start time",
        endTime: "Please enter the end time",
        title: "Please enter the meeting title",
        description: "Please enter the description",
        venue: "Please enter a meeting venue",
        participant: "Please select at least one participant"
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function (form) {
        form.submit();
    }
});