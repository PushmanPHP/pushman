@if (Session::has('flash_notification.message'))

	<div class="row">
		<div class="col-lg-12">
	        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

	            {!! Session::get('flash_notification.message') !!}
	        </div>
		</div>
	</div>

@endif
