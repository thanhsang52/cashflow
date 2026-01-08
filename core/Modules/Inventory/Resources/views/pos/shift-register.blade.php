@extends('inventory::pos.layouts.master')

@section('content')
<!-- <div class="modal-backdrop fade show"></div> -->
<div role="dialog" aria-modal="true" class="">
<div class="modal-dialog modal-md modal-dialog-centered">
    
    <div class="modal-content">
        <div class="py-4 pt-5 modal-header"><div class="modal-title h4" id="contained-modal-title-vcenter"><h4>{{__('inventory::pos.shift_register')}}</h4></div></div>
        {{Form::open(['route'=>['pos.register-shift'],'method'=>'POST', 'files' => true])}}
        <div class="modal-body">
            @if($shifts->count() > 0)
            <p>{{__('inventory::pos.continue_with_shift')}}</p>
            <div class="row">
                <div class="col-md-12">
                    <ul>
                    @foreach($shifts as $shift)
                    <li><input type="radio" name="shift_id" class="" value="{{$shift->id}}" > <label class="form-label">{{$shift->created_at}} {{__('inventory::pos.store')}} {{$shift->branch_no}}</label></li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">{{__('inventory::pos.cash_in_hand')}}: </label>
                    <input type="text" name="cash_in_hand" class="form-control money" value="0" >
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">{{__('inventory::pos.branch_no')}}: </label>
                    <input type="text" {{isset($branch_no)?'readonly=\"true\"':''}} name="branch_no" class=" form-control" value="{{$branch_no}}" >
                </div>
            </div>
        </div>
        <div class="py-4 pb-5 modal-footer"><button type="submit" class="btn btn-primary">{{__('inventory::pos.open')}}</button></div>
        {{Form::close()}}
    </div>
</div>
</div>
@endsection