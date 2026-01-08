<div class="conditions-group">   
    <div class="condition-items">
        <div class="form-group">	
            <label class="control-label font-weight-bold">1/</label>		
            <label class="control-label">{{ __('Condition')}}</label>			
            
            <select class="condition_id form-control-sm" name="conditions[0][condition_id][]">
                <option value="0">{{ __('Select Condition') }}</option>
                @foreach((array)$term_conditions as $key=> $term_condition)
                <option value="{{ $key }}">{{ $term_condition }}</option>
                @endforeach
            </select>
            <label class="control-label">{{ __('Value')}}</label>	
            <input type="text" class="condition_eval form-control-sm" name="conditions[0][condition_eval][]" placeholder="{{ __('Value') }}"/>

            <a href="#" class="add-condition"><i class="ti-plus"></i>{{ __('Add') }}</a>
        </div>
    </div>
    <div class="form-group">			
        <label class="control-label">{{ __('Discount') }}</label> <label class="control-label control-label-term-value">{{ ($is_percentage==true)?'(%)':'('.__('Flat amount').')'}}</label>
        <input type="text" class="discount-value form-control-sm" name="conditions[0][discount]">	
        <input type="hidden" name="conditions[0][contract_term_id]" value="{{$contract_term_id}}" />
    </div>
    <a href="#" class="remove-condition-group" data-term-condition-id="0"><i class="ti-trash"></i></a>
</div>