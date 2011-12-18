
jQuery.ajaxSetup({
	cache: false,
	dataType: 'json',
	success: function (payload) {
		if (payload.snippets) {
			for (var i in payload.snippets) {
				$('#' + i).html(payload.snippets[i]);
			}
		}
	}
});

// odesílání odkazů
$('a.ajax').live('click', function (event) {
	event.preventDefault();
	$.get(this.href);
});

// odesílání formulářů
$('form.ajax').live('submit', function (event) {
	event.preventDefault();
	$.post(this.action, $(this).serialize());
});