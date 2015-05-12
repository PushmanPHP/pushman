@if (Session::has('flash_notification.message'))
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
			    @if (Session::has('flash_notification.overlay'))
			        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
			    @else
			        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
			            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

			            {{ Session::get('flash_notification.message') }}
			        </div>
			    @endif
			</div>
		</div>
	</div>
@endif
