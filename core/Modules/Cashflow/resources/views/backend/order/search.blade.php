<div class="dker b-b displayNone" id="filter_div" style="display: block;">
  <div class="p-a">
    <form method="GET" action="https://cashflow.local/18/topics" accept-charset="UTF-8" id="filter_form" target="">
      <input type="hidden" name="stat" id="search_submit_stat" value="">
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
            <input placeholder="Invoice" class="form-control" dir="ltr" name="invoice" type="number" />
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <input placeholder="Coupon" class="form-control" dir="ltr" name="coupon" />
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <select name="status" id="status" class="form-control c-select">
                <option value="">{{__('All Status')}}</option>
                <option value="pending_confirm">{{__('Pending')}}</option>
                <option value="pending_receive">{{__('Pending Receive')}}</option>
                <option value="cancelled">{{__('Cancelled')}}</option>
                <option value="finished">{{__('Finished')}}</option>
            </select>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <select name="payment_status" id="payment_status" class="form-control c-select">
                <option value="">{{__('All Payment Status')}}</option>
                <option value="pending">{{__('Pending')}}</option>
                <option value="authorized">{{__('Authorized')}}</option>
                <option value="refunded">{{__('Refunded')}}</option>
            </select>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
          <select class="form-control c-select" name="payment_method" style="">
              <option value="">{{__('All Payment Method')}}</option>
              <option value="cod">COD</option>
              <option value="vnpay">VNPAY</option>
              <option value="momopay">MomoPay</option>
              <option value="zalopay">ZaloPay</option>
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