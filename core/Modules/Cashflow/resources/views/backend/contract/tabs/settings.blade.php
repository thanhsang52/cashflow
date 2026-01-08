<div class="tab-pane" id="tab_settings">
    <div class="p-a-2">
        {{Form::open(['route'=>['BannersUpdate',$contract->id],'method'=>'POST', 'files' => true])}}
        <div class="form-group row">
            
            
            <div class="col-sm-4">
                <label
                    class="form-control-label">{!!  __('cashflow::backend.linkTopic') !!} </label>
                <select name="settings[contract_link_topic_id]" class="form-control c-select">
                    @foreach($WebmasterSection->topics as $topic)
                    <option value="{{$topic->id}}" {{ (@$contract->linked_topic_id == $topic->id)?"selected":"" }}>{!!  $topic->title_en !!}</option>
                    @endforeach
                    
                </select>
            </div>
            
        </div>
       
        <div class="form-group">
            <button type="submit" class="btn btn-lg btn-primary m-t" name="action" value="save_and_close"><i class="material-icons">
            &#xe31b;</i>{!! __('cashflow::backend.update_exit') !!}</button>
            <!-- <button type="submit" class="btn btn-lg btn-primary m-t" name="action" value="save">{!! __('backend.update') !!}</button>
            <a href="{{ route('cashflow.contract.index') }}"
            class="btn btn-lg btn-default m-t"><i class="material-icons">
                    &#xe5cd;</i> {!! __('backend.cancel') !!}</a> -->
        </div>
       
       {{Form::close()}}
        
    </div>
</div>