new Vue({
	
	el: "#ban_container",

	data: {

		bans: {},

		visibleEditor: false,
		ban_id: 0,
		ip: '',
		duration: '',
		active: ''

	},

	methods: {


		editBan: function(id, ip, duration, active) {
			this.ban_id = id;
			this.visibleEditor = true;
			this.ip = ip;
			this.duration = duration;
			this.active = active;
		},

		submitEditForm: function(event) {
			event.preventDefault();

			$.post('/ban/update', {
				id: this.ban_id,
				ip: this.ip,
				duration: this.duration,
				active: this.active
			}, function(data) {
				console.log(data);
			});

			this.visibleEditor = false;

			setTimeout(function() {
				location.reload()
			}, 500);

		}

	}


});