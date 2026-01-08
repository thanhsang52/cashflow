<div class="modal fade billing-settings" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-term-id="{{ $term->id }}" data-contract-term-id="{{ $term->pivot->id }}" id="setting-modal-{{ $term->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title mt-0 text-white">{{ __('Billing Setting') }} {{ $term->code }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body overflow-hidden">
                <div class="col-12 row check-control">
                    <div class="col-md-12">
                        <div class="form-group">			
                            <input type="checkbox" onclick="$(this).next().val(this.checked?1:0)" {{($term->pivot->billing_frequency==1?'checked':'')}} /> <input type="hidden" name="params[billing_frequency][{{ $term->id }}]" value="{{($term->pivot->billing_frequency==1?1:0)}}"/>
                            <label class="control-label">{{ __('This is billing frequency') }}</label>		
                        </div>
                    </div>
                </div>
                <div class="col-12 row check-nextpage" style="display:none">
                    

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{ __('Frequency cycle') }}</label>						
                            <!-- <input type="number" class="form-control" name="params[frequency_cycle][{{ $term->id }}]" value="" > -->
                            <select class="form-control select2 frequency-cycle" name="params[frequency_cycle][{{ $term->id }}]">
                                <option value="">{{ __('Select One') }}</option>
                                <!-- <option {{$term->pivot->frequency_cycle=="P1W"?"selected":""}} value="P1W">Every 7 days (weekly)</option> -->
                                <option {{$term->pivot->frequency_cycle=="P1M"?"selected":""}} value="P1M">Every month (monthly)</option>
                                <option {{$term->pivot->frequency_cycle=="P3M"?"selected":""}} value="P3M">Every 3 months (quarterly)</option>
                                <option {{$term->pivot->frequency_cycle=="P6M"?"selected":""}} value="P6M">Every 6 months (semiannually)</option>
                                <option {{$term->pivot->frequency_cycle=="P1Y"?"selected":""}} value="P1Y">Every year (annually)</option>
                                <option {{$term->pivot->frequency_cycle=="P2Y"?"selected":""}} value="P2Y">Every 2 years (biennially)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{ __('The First Transaction Start In') }}</label>						
                            <input type="text" class="form-control datepicker frequency-start-date" name="params[frequency_start_date][{{ $term->id }}]" value="{{!empty($term->pivot->frequency_start_date)?$term->pivot->frequency_start_date:$date_from}}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{ __('End In') }}</label>						
                            <input type="text" class="form-control datepicker frequency-end-date" name="params[frequency_end_date][{{ $term->id }}]" value="{{!empty($term->pivot->frequency_end_date)?$term->pivot->frequency_end_date:$date_to}}">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">{{ __('Scheduled transactions list') }}</label>		
                            @php
                            $arr=array();
                            if($term->pivot->billing_frequency && $term->pivot->frequency_cycle){
                                $running = $term->pivot->frequency_start_date;
                                $start = new DateTime($term->pivot->frequency_start_date);
                                $arr[] = $start;
                                while($running<=$term->pivot->frequency_end_date){
                                    $date = new DateTime($running);
                                    $date->add(new DateInterval($term->pivot->frequency_cycle));
                                    $running = $date->format('Y-m-d');
                                    if($running<= $term->pivot->frequency_end_date)
                                        $arr[] = $date;
                                }
                            }
                            @endphp
                            <input type="hidden" name="scheduled_transaction_date[{{ $term->pivot->id }}]" class="form-control scheduled-transactions" readonly="true" value="{{implode(',',array_map(function($item) {return $item->format('Y-m-d');}, $arr))}}">
                            <ul class="scheduled-transactions-list">
                                @foreach($arr as $key => $value)
                                    <li>{{ $value->format('d/m/Y') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                   
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">			
                        <input type="checkbox" onclick="$(this).next().val(this.checked?1:0);handlePercentage(this)" class="is-percentage-control" {{$term->pivot->is_percentage==1?'checked':''}}/> <input type="hidden" name="params[is_percentage][{{ $term->id }}]"/>
                        <label class="control-label">{{ __('This is calculated by percentage') }}</label>		
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">			
                        <input type="radio" class="type-control" name="params[type][{{ $term->id }}]" value="0" checked/>
                        <label class="control-label">{{ __('Default no condition') }}</label>
                        <span>&nbsp;</span>
                        <input type="radio" class="type-control" name="params[type][{{ $term->id }}]" value="1" {{$term->pivot->type==1?'checked':''}}/>
                        <label class="control-label">{{ __('Level') }}</label>			
                        <span>&nbsp;</span>
                        <input type="radio" class="type-control" name="params[type][{{ $term->id }}]" value="2" {{$term->pivot->type==2?'checked':''}}/>
                        <label class="control-label">{{ __('Combined conditions') }}</label>	
                        
                    </div>
                </div>
                @php
                $levels = \Modules\Cashflow\App\Models\ContractTermLevel::where('contract_term_id',$term->pivot->id)->orderBy('level', 'ASC')->get();
                @endphp
                
                <div class="col-md-12 level-container group-container" {{$term->pivot->type!=1?'style=display:none':''}}>
                    <div class="level-items">
                        @if(isset($levels) && count($levels))
                        @foreach($levels as $key => $level)
                        <div class="form-group">	
                            <label class="control-label font-weight-bold">{{$level->level}}/ </label>	
                            <label class="control-label">{{ __('Target')}} >=</label>				
                            <input type="text" class="target-input amount form-control-sm" name="levels[{{ $level->contract_term_id }}][target][]" value="{{money_format_2($level->target) }}" placeholder="{{ __('Target') }}">		
                            <label class="control-label">{{ __('Then')}}</label> <label class="control-label control-label-value">{{ $term->pivot->is_percentage==1?__('% Rebate/Incentive'):__('Flat Rebate/Incentive')}}</label>	
                            <input type="text" class="min-level-input {{$term->pivot->is_percentage?'percentage':'amount' }} form-control-sm" name="levels[{{ $term->pivot->id }}][value][]" value="{{$term->pivot->is_percentage?money_format_2($level->value):decimalPlace($level->value) }}" placeholder="{{ __('Value') }}">
                            @if($level->level==1) 
                            <a href="#" class="add-level"><i class="ti-plus"></i>{{ __('Add') }}</a>
                            @else
                            <a href="#" data-contract-term-id="{{$level->contract_term_id}}" data-level="{{$level->level}}" class="remove-one-level"><i class="ti-minus"></i>{{ __('Remove') }}</a>
                            @endif
                           
                        </div>
                        @endforeach
                        
                        @else
                        <div class="form-group">	
                            <label class="control-label font-weight-bold">1/</label>		
                            <label class="control-label">{{ __('Target')}} >=</label>			
                            <input type="text" class="target-input amount form-control-sm" name="levels[{{ $term->pivot->id }}][target][]" value="0" placeholder="{{ __('Target') }}"/>		
                            <label class="control-label">{{ __('Then')}}</label> <label class="control-label control-label-value">{{ $term->pivot->is_percentage==1?__('% Rebate/Incentive'):__('Flat Rebate/Incentive')}}</label>	
                            <input type="text" class="min-level-input {{$term->pivot->is_percentage?'percentage':'amount' }} form-control-sm" name="levels[{{ $term->pivot->id }}][value][]" value="0" placeholder="{{ __('Value') }}"/>  
                            <a href="#" class="add-level"><i class="ti-plus"></i>{{ __('Add') }}</a>
                            <!-- <a href="#" class="remove-level"><i class="ti-minus"></i>{{ __('Remove') }}</a> -->
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-info btn-xs clear-all-level"><i class="ti-eraser"></i> {{ __('Clear all level') }}</button>
                </div>
                @php
                $conditions = \Modules\Cashflow\App\Models\ContractTermCondition::where('contract_term_id',$term->pivot->id)->orderBy('id', 'ASC')->get();
                @endphp
                <div class="col-md-12 condition-container group-container" {{$term->pivot->type!=2?'style=display:none':''}}>
                    
                        @if(isset($conditions) && count($conditions))
                       
                        @foreach($conditions as $i => $condition)
                        <div class="conditions-group">
                            <div class="condition-items" data-condition-id="{{ $condition->id  }}">
                                @php
                                    $attributes = json_decode($condition->attributes)
                                    
                                @endphp
                                @foreach($attributes as $key => $attribute)
                                <div class="form-group">	
                                    <label class="control-label font-weight-bold">{{ $key+1 }}/</label>		
                                    <label class="control-label">{{ __('Condition')}}</label>			
                                    
                                    <select class="condition_id form-control-sm" name="conditions[{{ $condition->id }}][condition_id][]">
                                        <option value="0">{{ __('Select Condition') }}</option>
                                        @foreach((array)$term_conditions as $key3=> $term_condition)
                                        <option value="{{ $key3 }}" {{ $attribute->condition_id  == $key3?'selected':'' }}>{{ $term_condition }}</option>
                                        @endforeach
                                    </select>
                                    <label class="control-label">{{ __('Value')}}</label>	
                                    <input type="text" class="condition_eval {{ $attribute->condition_id  }} form-control-sm" name="conditions[{{ $condition->id  }}][condition_eval][]" value="{{$attribute->condition_eval}}" placeholder="{{ __('Value') }}"/>
                                    @if($key !=0) 
                                    <a href="#" class="remove-condition"><i class="ti-minus"></i>{{ __('Remove') }}</a>
                                    @else
                                    <a href="#" class="add-condition"><i class="ti-plus"></i>{{ __('Add') }}</a>
                                    @endif
                                </div>
                                @endforeach
                            
                            </div>
                            <div class="form-group">			
                                <label class="control-label">{{ __('Discount') }}</label> <label class="control-label control-label-term-value">{{ $term->pivot->is_percentage==1?'(%)':'('.__('Flat amount').')'}}</label>
                                <input type="text" class="discount-value {{ $term->pivot->is_percentage==1?'percentage':'amount'}} form-control-sm" name="conditions[{{ $condition->id  }}][discount]" value="{{$condition->discount}}">	
                            </div>
                            <a href="#" class="remove-condition-group" data-term-condition-id="{{$condition->id}}"><i class="ti-trash"></i></a>
                        </div>
                        @endforeach
                        @else
                        <!-- <div class="conditions-group">
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
                                <label class="control-label">{{ __('Discount') }}</label> <label class="control-label control-label-term-value">{{ $term->pivot->is_percentage==1?'(%)':'('.__('Flat amount').')'}}</label>
                                <input type="text" class="discount-value form-control-sm" name="conditions[0][discount]">	
                            </div>
                            <a href="#" class="remove-condition-group" data-term-condition-id="0"><i class="ti-trash"></i></a>
                        </div> -->
                        @endif
                        <a href="#" class="add-condition-items btn-secondary"><i class="ti-plus"></i>{{ __('Add group') }}</a>
                    </div>
                    <!-- <label class="control-label">{{ __('Then')}}</label> <label class="control-label control-label-value">{{ __('Flat Rebate/Incentive')}}</label>	
                    <input type="text" class="min-level-input float-field" name="conditions[{{ $term->pivot->id }}][discount]" value="0" placeholder="{{ __('Discount') }}"/>  -->
                </div>
                <div class="col-md-12 term-value" {{$term->pivot->type!=0?'style=display:none':''}}>
                    <div class="form-group">			
                        <label class="control-label">{{ __('Term value') }}</label> <label class="control-label control-label-term-value">{{ $term->pivot->is_percentage==1?'(%)':'('.__('Flat amount').')'}}</label>
                        <input type="text" class="form-control term-value {{ $term->pivot->is_percentage==1?'percentage':'amount'}} form-control" name="params[term_value][{{ $term->id }}]" value="{{$term->pivot->term_value?money_format_2($term->pivot->term_value):decimalPlace($term->pivot->term_value) }}">	
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> -->
                <div class="col-md-12">
                    <div class="form-group text-center m-t">
                    <button type="button" value="" class="btn btn-primary save-term-and-edit">{{ __('Save & Continue Edit') }}</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>