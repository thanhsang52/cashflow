@extends('dashboard.layouts.master')
@section('title', __('cashflow::backend.setting'))
@push("after-styles")
    <link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('core/Modules/Cashflow/resources/assets/css/themify-icons.css') }}">
@endpush
@section('content')
<div class="padding">
	<div class="box">
        <div class="col-sm-12">
			<ul class="nav flex-column nav-tabs settings-tab" role="tablist">
				 <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general"><i class="ti-settings"></i> {{ __('General Settings') }}</a></li>
				  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#currency_settings"><i class="ti-money"></i> {{ __('Currency Settings') }}</a></li>
				 <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#email"><i class="ti-email"></i> {{ __('Email Settings') }}</a></li>
				 <!-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logo"><i class="ti-image"></i> {{ __('Logo and Favicon') }}</a></li>
				 <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cache"><i class="ti-server"></i> {{ __('Cache Control') }}</a></li> -->
			</ul>
		</div>
		  
		@php $settings = \Modules\Cashflow\App\Models\Setting::all(); @endphp
		  
		<div class="col-sm-12">
			<div class="tab-content">
				<div id="general" class="tab-pane active">
					<div class="box b-info">

						<div class="card-header">
							<span class="panel-title">{{ __('General Settings') }}</span>
						</div>

						<div class="box-body p-a-2">
							 <form method="post" class="settings-submit params-panel" autocomplete="off" action="{{ route('cashflow.settings.update_settings','store') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Company Name') }}</label>				
										<input type="text" class="form-control" name="company_name" value="{{ get_setting($settings, 'company_name') }}" required>
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Site Title') }}</label>						
										<input type="text" class="form-control" name="site_title" value="{{ get_setting($settings, 'site_title') }}" required>
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Phone') }}</label>						
										<input type="text" class="form-control" name="phone" value="{{ get_setting($settings, 'phone') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Email') }}</label>						
										<input type="email" class="form-control" name="email" value="{{ get_setting($settings, 'email') }}">
									  </div>
									</div>

									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Timezone') }}</label>						
										<select class="form-control select2" name="timezone" required>
										<option value="">{{ __('-- Select One --') }}</option>
										{{ create_timezone_option(get_setting($settings, 'timezone')) }}
										</select>
									  </div>
									</div>
									
													
									

									 <div class="col-md-6">
									  	<div class="form-group">
											<label class="control-label">{{ __('Backend Direction') }}</label>						
											<select class="form-control auto-select" data-selected="{{ get_setting($settings, 'backend_direction','ltr') }}" name="backend_direction" required>
												<option value="ltr">{{ __('LTR') }}</option>
												<option value="rtl">{{ __('RTL') }}</option>
											</select>
									  	</div>
									</div>
								
									
									<div class="col-md-6">
									  	<div class="form-group">
											<label class="control-label">{{ __('Date Format') }}</label>						
											<select class="form-control auto-select" name="date_format" data-selected="{{ get_setting($settings, 'date_format','Y-m-d') }}" required>
												<option value="Y-m-d">{{ date('Y-m-d') }}</option>
												<option value="d-m-Y">{{ date('d-m-Y') }}</option>
												<option value="d/m/Y">{{ date('d/m/Y') }}</option>
												<option value="m-d-Y">{{ date('m-d-Y') }}</option>
												<option value="m.d.Y">{{ date('m.d.Y') }}</option>
												<option value="m/d/Y">{{ date('m/d/Y') }}</option>
												<option value="d.m.Y">{{ date('d.m.Y') }}</option>
												<option value="d/M/Y">{{ date('d/M/Y') }}</option>
												<option value="d/M/Y">{{ date('M/d/Y') }}</option>
												<option value="d M, Y">{{ date('d M, Y') }}</option>
											</select>
									  	</div>
									</div>
									
									<div class="col-md-6">
									  	<div class="form-group">
											<label class="control-label">{{ __('Time Format') }}</label>						
											<select class="form-control auto-select" name="time_format" data-selected="{{ get_setting($settings, 'time_format',24) }}" required>
												<option value="24">{{ __('24 Hours') }}</option>
												<option value="12">{{ __('12 Hours') }}</option>
											</select>
									  	</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
										  <label class="control-label">{{ __('Contract types') }}</label>						
										  <input class="form-control" name="contract_types" readonly
											value="{{ get_setting($settings, 'contract_types','a:2:{s:4:"main";s:13:"Main Contract";s:3:"sub";s:11:"Subcontract";}') }}"
										   />
										</div>
								  	</div>

									  <div class="col-md-6">
										<div class="form-group">
										  <label class="control-label">{{ __('Term conditions') }}</label>						
										  <input class="form-control" name="term_conditions" readonly
											value="{{ get_setting($settings, 'term_conditions','a:4:{i:1;s:9:"Min Sales";i:2;s:9:"Max Sales";i:3;s:9:"Quarterly";i:4;s:5:"Brand";}') }}"
										   />
										</div>
								  	</div>
										
									<div class="col-md-12">
									  <div class="form-group">
										<button type="submit" class="btn btn-lg btn-primary m-t">{{ __('Save Settings') }}</button>
									  </div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>


				<div id="currency_settings" class="tab-pane fade">
					<div class="box b-info">
						<div class="card-header">
							<span class="panel-title">{{ __('Currency Settings') }}</span>
						</div>

						<div class="box-body p-a-2"> 
						   <form method="post" class="settings-submit params-panel" autocomplete="off" action="{{ route('cashflow.settings.update_settings','store') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="row">
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Currency Position') }}</label>	
										<select class="form-control auto-select" data-selected="{{ get_setting($settings, 'currency_position','left') }}" name="currency_position" required>
											<option value="left">{{ __('Left') }}</option>
											<option value="right">{{ __('Right') }}</option>
										</select>
									  </div>
									</div>


									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Thousand Seperator') }}</label>	
										<input type="text" class="form-control" name="thousand_sep" value="{{ get_setting($settings, 'thousand_sep',',') }}">
									  </div>
									</div>

									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Decimal Seperator') }}</label>	
										<input type="text" class="form-control" name="decimal_sep" value="{{ get_setting($settings, 'decimal_sep','.') }}">
									  </div>
									</div>

									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Decimal Places') }}</label>	
										<input type="text" class="form-control" name="decimal_places" value="{{ get_setting($settings, 'decimal_places',2) }}">
									  </div>
									</div>

									<div class="col-md-12">
									  <div class="form-group">
										<button type="submit" class="btn btn-lg btn-primary m-t">{{ __('Save Settings') }}</button>
									  </div>
									</div>	
								</div>							
							</form>
						</div>
					</div>
				</div>
				 
				
				<div id="email" class="tab-pane fade">
					<div class="box b-info">
						<div class="card-header">
							<span class="panel-title">{{ __('Email Settings') }}</span>
						</div>

					    <div class="box-body p-a-2">
							<form method="post" class="settings-submit params-panel" autocomplete="off" action="{{ route('cashflow.settings.update_settings','store') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('Mail Type') }}</label>		
										<select class="form-control auto-select" data-selected="{{ get_setting($settings, 'mail_type','mail') }}" name="mail_type" id="mail_type" required>
										  <option value="mail">{{ __('PHP Mail') }}</option>
										  <option value="smtp">{{ __('SMTP') }}</option>
										  <option value="sendmail">{{ __('Sendmail') }}</option>
										</select>
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('From Email') }}</label>						
										<input type="text" class="form-control" name="from_email" value="{{ get_setting($settings, 'from_email') }}" required>
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('From Name') }}</label>						
										<input type="text" class="form-control" name="from_name" value="{{ get_setting($settings, 'from_name') }}" required>
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('SMTP Host') }}</label>						
										<input type="text" class="form-control smtp" name="smtp_host" value="{{ get_setting($settings, 'smtp_host') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('SMTP Port') }}</label>						
										<input type="text" class="form-control smtp" name="smtp_port" value="{{ get_setting($settings, 'smtp_port') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('SMTP Username') }}</label>						
										<input type="text" class="form-control smtp" autocomplete="off" name="smtp_username" value="{{ get_setting($settings, 'smtp_username') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('SMTP Password') }}</label>						
										<input type="password" class="form-control smtp" autocomplete="off" name="smtp_password" value="{{ get_setting($settings, 'smtp_password') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ __('SMTP Encryption') }}</label>					
										<select class="form-control smtp auto-select" data-selected="{{ get_setting($settings, 'smtp_encryption','ssl') }}" name="smtp_encryption">
										   <option value="">{{ __('None') }}</option>
										   <option value="ssl">{{ __('SSL') }}</option>
										   <option value="tls">{{ __('TLS') }}</option>
										</select>
									  </div>
									</div>
									
									<div class="col-md-12">
									  <div class="form-group">
										<button type="submit" class="btn btn-lg btn-primary m-t">{{ __('Save Settings') }}</button>
									  </div>
									</div>
								</div>						
							</form>
					    </div>
					</div>
				</div>
							  				  
				


				
		</div>
	</div>
	
</div>
@endsection
@push("after-scripts")
<script src="{{ URL::asset('core/Modules/Cashflow/resources/assets/js/app.js') }}"></script>
@endpush