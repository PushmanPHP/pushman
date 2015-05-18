$(document).ready(function() {

	$('a.swal').on('click', function(event) {
		event.preventDefault();

		var self = $(this);
		var url = self.attr('href');

		var title = "Warning";
		var text = "Are you sure? This could be bad.";
		var type = "warning";
		var btn = "btn-warning";

		if(self.hasClass('btn-danger')) {
			title = "Danger";
			text = "Are you sure? This will be bad.";
			type = "error";
			btn = "btn-danger";
		}

		swal({
			title: title,
			text: text,
			type: type,
			showCancelButton: true,
			confirmButtonClass: btn,
			confirmButtonText: "Do it!",
			closeOnConfirm: false
		}, function() {
			window.location = url;
		});

	});

});