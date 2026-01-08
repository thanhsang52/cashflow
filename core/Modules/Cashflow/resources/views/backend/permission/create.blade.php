@extends('dashboard.layouts.master')
@section('title', __('backend.permissions'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/themify-icons.css') }}">
@endpush
@section('content')
<style>
.checkmark{border-radius:10px;}
.c-container input:checked ~ .checkmark {background-color: #2ecc71;}
</style>
<div class="padding">
	<div class="box m-b-0"">
		<div class="box-header dker">
			<h3>{{ __('Permissions') }}</h3>
			<small>
				<a href="{{route('adminHome')}}">{{ __('Home') }}</a> /
				<a>{{ __('Cashflow') }}</a> 
			</small>
		</div>
	</div>
	<div class="box b-info">
		<div class="box-body p-a-2">
	
		<form method="post" id="permissions" class="validate" autocomplete="off" action="{{ route('cashflow.permission.store') }}">
		 
			<div class="row">
				<div class="col-md-12">
					<div class="">
						<div class="card-body">
						    <div class="col-md-4">
								<div class="form-group">
								   <label class="control-label">{{ __('Select Role') }}</label>						
								   <select class="form-control select2" id="role_id" name="role_id" required>
									<option value="">{{ __('Select One') }}</option>
									{{ create_option("permissions", "id", "name", $role_id) }}
								   </select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
				
			<div class="">
				<!-- <span class="d-none panel-title">{{ __('Permission Control') }}</span> -->

				<div class="card-body"> 
					{{ csrf_field() }}

					<div id="accordion">
					 @php $i = 1; @endphp
					 @foreach($permission as $key => $val)
					   <div class="card">
						<div class="card-header">
						  <h4>
							  <a class="card-link" data-toggle="collapse" href="#collapse-{{ explode("\\",$key)[5] }}">
								<i class="fa fa-angle-double-right" aria-hidden="true"></i>
								{{ str_replace("Controller","",explode("\\",$key)[5]) }}
							  </a>
						  </h4>
						</div>
						<div id="collapse-{{ explode("\\",$key)[5] }}" class="collapse">
						  <div class="card-body">
							  <table class="table">
								@foreach($val as $name => $url)
									<tr>
										<td>
											<div class="checkbox">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="" name="permissions[]" value="{{ $name }}" id="customCheck{{ $i + 1 }}" {{ array_search($name,$permission_list) !== FALSE ? "checked" : "" }}>
													<label class="custom-control-label" for="customCheck{{ $i + 1 }}">{{ str_replace("index","list",$name) }}</label>
												</div>
											</div>
										</td>
									</tr>
									@php $i++; @endphp
								@endforeach	
							</table>
						  </div>
						</div>
					   </div>
					 
					  @endforeach
					</div>
					

							
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<button type="submit" class="btn btn-lg btn-primary m-t">{{ __('Save Permission') }}</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		</div>
    </div>
</div>
@endsection


@push("after-scripts")

<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>

@endpush