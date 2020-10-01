jQuery(document).ready(function () {

jQuery.extend(jQuery.fn.dataTable.defaults, {
	searching: false,
	ordering: true,
	paging: true,
	responsive: true
});

//Modified By Vignesh on July 17 2020
var uoTable = jQuery('#uo-transcript-table').DataTable( {
    "language": {
        "emptyTable":     "No Course in Progress / Non-completed!!"
    }
} );

//Added By Vignesh on July 17 2020
if ( ! uoTable.data().count() ) {
	jQuery("#uo-t-print-button").hide();
}

jQuery('#uo-t-print-button').click(function () {
	showPrintDialog();
});

jQuery(document).bind("keyup", function (e) {
	if (e.ctrlKey && e.keyCode == 80) {
		showPrintDialog();
		return false;
	}
});

jQuery(document).bind("keydown", function (e) {
	if (e.ctrlKey && e.keyCode == 80) {
		return false;
	}
});

var showPrintDialog = function () {
	var button = jQuery('#uo-t-print-button');
	button.text('Loading..');

	window.frames["print_frame"].document.body.className = "printable-frame";
	window.frames["print_frame"].document.body.innerHTML = document.getElementById('uo-t-print').innerHTML;
	setTimeout(function () {
		window.frames["print_frame"].window.focus();
		window.frames["print_frame"].window.print();
		/*Added by Vignesh July 15 2020*/
		button.text('Print Transcript');
	}, 2000);
}

});
