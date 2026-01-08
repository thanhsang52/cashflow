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
            <input placeholder="Invoice ID" class="form-control" dir="ltr" name="invoice_id" type="number" />
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <input placeholder="Branch No" class="form-control" dir="ltr" name="branch_no" type="number" />
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <select name="invoice_type" id="invoice_type" class="form-control c-select">
                <option value="2">Tất cả</option>
                <option value="1">Tổng hợp</option>
                <option value="0">Chi tiết (bán lẻ)</option>
                <option value="3">Điều chỉnh (bán lẻ)</option>
            </select>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <select name="status" id="status" class="form-control c-select">
                <option value="">All status</option>
                <option value="Completed">Completed</option>
                <option value="Submitted">Submitted</option>
                <option value="Checked">Checked</option>
                <option value="temp">Temp</option>
                <option value="Cancelled">Cancelled</option>
            </select>
          </div>
          <div class="col-md-3 col-xs-6 m-b-5p">
            <select id="invoiceTemplateTypeSelect" name="invoice_template_type"  class="form-control c-select">
              <option selected="" value="">Tất cả mẫu hoá đơn</option>
              <option value="is_invoice_revenue_template">Hóa đơn doanh số</option>
              <option value="is_invoice_consigment_template">Hóa đơn ký gửi</option>
              <option value="is_invoice_transfer_template">PXK VCNB</option>
              <option value="is_invoice_internal_template">Hóa đơn nội bộ</option>
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