<div class="dker b-b displayNone" id="filter_div" style="display: block;">
  <div class="p-a">
    <form method="GET" action="{{ route('inventory.get_inventory_table_data') }}" accept-charset="UTF-8" id="filter_form" target="">

      <div class="filter_div">
        <div class="row">
          <div class="col-md-3 col-xs-6 m-b-5p">
                <div class="form-group m-b-0">
                    <div class="input-group date" ui-jp="datetimepicker" ui-options="{
                                format: 'DD MMM YYYY',
                                icons: {
                                time: 'fa fa-clock-o',
                                date: 'fa fa-calendar',
                                up: 'fa fa-chevron-up',
                                down: 'fa fa-chevron-down',
                                previous: 'fa fa-chevron-left',
                                next: 'fa fa-chevron-right',
                                today: 'fa fa-screenshot',
                                clear: 'fa fa-trash',
                                close: 'fa fa-remove'
                                },
                            allowInputToggle: true,
                            locale:'en'
                            }">
                        <input placeholder="From" class="form-control datepicker" name="from_date" type="text" value="">
                        <span class="input-group-addon">
                        <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <div class="form-group m-b-0">
              <div class="input-group date" ui-jp="datetimepicker" ui-options="{
                        format: 'DD MMM YYYY',
                        icons: {
                        time: 'fa fa-clock-o',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-screenshot',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                        },
                    allowInputToggle: true,
                    locale:'en'
                    }">
                <input placeholder="To date" class="form-control" name="to_date" type="text" value="">
                <span class="input-group-addon">
                  <span class="fa fa-calendar"></span>
                </span>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <input placeholder="Item Code" class="form-control" dir="ltr" name="item_code" type="text" />
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <input placeholder="Branch No" class="form-control" dir="ltr" name="branch_no" type="number" />
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <select name="division"  id="division" class="form-control c-select">
              <option value="">All</option>
              <option value="A">A-HEALTH</option>
              <option value="B">B-BEAUTY</option>
              <option value="C">C-PERSONAL CARE</option>
              <option value="D">D-FASHION</option>
              <option value="G">G-FOOD & DRINK</option>
              <option value="NS">NS Non Stock Items</option>	
            </select>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            
            <select name="area" class="form-control c-select">
              <option value="all">All</option>
              <option value="A2">A2 (Bich Lieu)</option>
              <option value="A3">A3 (Thu Linh)</option>
              <option value="A4">A4 (Lan Ngoc)</option>
              <option value="A5">A5 (Thi Hoa)</option>
              <option value="A7">A7 (Thi Tien)</option>
              <option value="A8">A8 (Bich Ngoc)</option>
              <option value="CDC">CDC (CDC)</option>
            </select>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
          <select name="equalStatus" id="equalStatus" class="form-control c-select">
            <option value="all">Status Item (All)</option>
            <option value="ongoing">On Going</option>
            <option value="discontinued">Discontinued</option>	
            <option value="pickedVsPacked">Picked &lt;&gt; Packed</option>	
            <option value="packedVsTransfered">Packed &lt;&gt; Transfered</option>	
            <option value="receivedVsTransfered">Received &lt;&gt; Transfered</option>	
            <option value="movement">SOH movement</option>	
          </select>
          </div>
          <div class="col-md-1 col-xs-6 m-b-5p">
            <button class="btn white w-full" id="search-btn" type="button">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>