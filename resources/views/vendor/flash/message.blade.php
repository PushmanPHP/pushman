@if (Session::has('flash_notification.message'))
	@if(!$cover)
		<div class="container">
	@endif

	<div class="row @if($cover) bottom40 @endif" style="margin-top:{{$top or ''}};">
		<div class="col-lg-12">
	        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

	            {!! Session::get('flash_notification.message') !!}
	        </div>
		</div>
	</div>

	@if(!$cover)
		</div>
	@endif
@endif
