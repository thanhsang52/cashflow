<table class="table table-bordered">
	<tr><td>{{ __('Term code') }}</td><td>{{ $term->code }}</td></tr>
	<tr><td>{{ __('Term name') }}</td><td>{{ $term->name }}</td></tr>
	<tr><td>{{ __('Category') }}</td><td>{{ $term->category->name }}</td></tr> 
	
	<tr>
		<td>{{ __('Attachment') }}</td>
			<td>
			  @if($term->attachment != "")
			   <a href="{{ asset('public/uploads/terms/'.$term->attachment) }}" target="_blank" class="btn btn-link btn-xs">{{ $term->attachment }}</a>
			  @else
				  <label class="badge badge-warning">
					<strong>{{ __('No Atachment Availabel !') }}</strong>
				  </label>
			  @endif
			</td>
		</tr>
	<tr><td>{{ __('Note') }}</td><td>{{ $term->note }}</td></tr>
</table>

