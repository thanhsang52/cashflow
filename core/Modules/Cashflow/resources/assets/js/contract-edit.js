
// Event delegation for calculating scheduled transactions
$(document).on('change', '.check-control input[type=checkbox], select.frequency-cycle, input.frequency-start-date, input.frequency-end-date', function() {
    var settingsContainer = $(this).closest('.billing-settings');
    calculateScheduledTransactions(settingsContainer);
});
// Function to calculate scheduled transactions
function calculateScheduledTransactions(container) {
    var startDate = new Date(container.find('input.frequency-start-date').val());
    var endDate = new Date(container.find('input.frequency-end-date').val());
    var frequency = container.find('select.frequency-cycle').val();
    var is_frequency = container.find('.check-control input[type=hidden]').val();

    var transactions = [];
    //reset
    container.find('input.scheduled-transactions').val('');
    var transactionsList = container.find('.scheduled-transactions-list');
    transactionsList.empty();

    if(is_frequency==1 && startDate && endDate && frequency){
        while (startDate <= endDate) {
            transactions.push(startDate);
            startDate = addFrequency(startDate, frequency);
        }
        container.find('input.scheduled-transactions').val(transactions.map(d => d.toISOString().slice(0,10)).join(','));
        var transactionsList = container.find('.scheduled-transactions-list');
        transactionsList.empty();
        transactions.forEach(function(transaction) {
            transactionsList.append('<li>' + transaction.toLocaleDateString('vi-VI') + '</li>');
        });
    }
}

// Function to add frequency to a date
function addFrequency(date, frequency) {
    var newDate = new Date(date);
    switch (frequency) {
        case 'P1M':
            newDate.setMonth(newDate.getMonth() + 1);
            break;
        case 'P3M':
            newDate.setMonth(newDate.getMonth() + 3);
            break;
        case 'P6M':
            newDate.setMonth(newDate.getMonth() + 6);
            break;
        case 'P1Y':
            newDate.setFullYear(newDate.getFullYear() + 1);
            break;
        default:
            break;
    }
    console.log(newDate);
    return newDate;
}
function onlyOne(checkbox) {
    
    var checkboxes = document.getElementsByName(checkbox.name)
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false
        $(item).next().val(item.checked?1:0);
    })
    //$('.group-container').hide();
    
}
$('.check-control input[type=checkbox]:checked').each(function(){
    $(this).parents(".modal-body").find('.check-nextpage').show();
})
$('.check-control input[type=checkbox]').on("change", function(){
    $(this).parents(".modal-body").find('.check-nextpage').toggle();
})
/*$('input.is-level-control[type=checkbox]:checked').each(function(){
    $(this).parents(".modal-body").find('.level-container').show();
})
$('input.is-level-control[type=checkbox]').on("change", function(){
    var toggle = this.checked?1:0;
    $(this).next().val(toggle);
    $(this).parents(".modal-body").find('.level-container').toggle(toggle);
    $(this).parents(".modal-body").find('.term-value').toggle(!toggle);
})
$('input.is-conditions-control[type=checkbox]:checked').each(function(){
    $(this).parents(".modal-body").find('.condition-container').show();
})
$('input.is-conditions-control[type=checkbox]').on("change", function(){
    var toggle = this.checked?1:0;
    $(this).next().val(toggle);
    $(this).parents(".modal-body").find('.condition-container').toggle(toggle);
    //$(this).parents(".modal-body").find('.term-value').toggle(!toggle);
})*/
$('input.type-control[type=radio]').on("change", function(){
    var term_type = $(this).val();
    $('.group-container').hide();
    if(term_type==2) $('.condition-container').show();
    if(term_type==1) $('.level-container').show();
    console.log(term_type==0);
    $(this).parents(".modal-content").find('.term-value').toggle(term_type==0);
})
$(document).on('change', '.float-field', function() {
    $(this).val(currency($(this).val(), {symbol: ""}).format());
});
$(document).on('change', '.min,.max,.amount', function() {
    $(this).val(currency($(this).val(), {symbol: "",precision: 0}).format());
});
$(document).on('change', '.percentage', function() {
    $(this).val(currency($(this).val(), {symbol: "",precision: 2}).format());
});
$(document).ready(function() {
    $('.min,.max,.amount').each(function() {
        $(this).val(currency($(this).val(), {symbol: "",precision: 0 }).format());
    });
    $('.percentage').each(function() {
        $(this).val(currency($(this).val(), {symbol: "",precision: 2 }).format());
    });
    $('.quaterly').each(function() {
        $(this).val( Number.parseInt($(this).val()));
    });
  });
$(document).on('click', '.clear-all-level', function(e) {
    var contract_term_id = $(this).closest('.billing-settings').data('contract-term-id');
    $(this).closest('.level-container').html('');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({ 
        url: '/contract_term_level/'+contract_term_id, 
        type: 'DELETE', 
        success: function (result) { 
            console.log(result);
        } 
    }); 
});
$(document).on('click', '.remove-one-level', function(e) {
    var contract_term_id = $(this).closest('.billing-settings').data('contract-term-id');
    var level = $(this).data('level');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({ 
        url: '/contract_term_level/contract_term_id/'+contract_term_id+'/level/'+level, 
        type: 'DELETE', 
        success: function (result) { 
            console.log(result);
            
        } 
    }); 
    $(this).closest('.form-group').remove();
});
// $('input[type="checkbox"]').change(function(){
// 	this.value = (Number(this.checked));
// });
// $(document).ready(function () {
// 	$('form').submit(function() {
// 		$(this).find('input[type=checkbox]').each(function () {
// 			$(this).attr('value', $(this).is(':checked') ? '1' : '0');
// 			$(this).attr('checked', true);
// 		});
// 	});
// });
// Event delegation for adding new levels
$(document).on('click', '.add-level', function(e) {
    e.preventDefault(); // Prevent default link behavior

    var container = $(this).closest('.modal.billing-settings');
    var termId = container.data('term-id');
    var contractTermId = container.data('contract-term-id');
    var levelCount = container.find('.level-container .form-group').length; // Get current number of levels
    // Validate current levels
    if (!validateLevels($(this).closest('.level-container'))) {
        alert("Each level's target value must be less than the next level's target value.");
        return;
    }

    levelCount++; // Increment level counter
    var controlLabelValue = $lang_flat_rebate;
    var isPercentageControl = container.find('.is-percentage-control');
    var isPercentage = $(isPercentageControl).is(':checked');
    if(isPercentage==true) controlLabelValue = $lang_percentage_rebate;
    var newLevel = $(this).closest('.form-group').clone();
    $(newLevel).find("input").val("");
    $(newLevel).find('.control-label.font-weight-bold').html(levelCount+'/');
    $(newLevel).find('.add-level').remove();
    $(newLevel).append(' <a href="#" class="remove-level"><i class="ti-minus"></i>'+$lang_remove+'</a>');
    // Create new level input field
    /*var newLevel = `
        <div class="form-group" id="level-` + levelCount + `">	
            <label class="control-label font-weight-bold">` + levelCount + `/</label>	
            <label class="control-label">`+$lang_target +` >=</label>		
            <input type="text" class="target-input" name="levels[` + contractTermId + `][target][]" value="">		
            <label class="control-label">`+$lang_then+`</label> <label class="control-label control-label-value">`+controlLabelValue+`</label>	
            <input type="text" class="min-level-input" name="levels[` + contractTermId + `][value][]" value="">
            <a href="#" class="remove-level"><i class="ti-minus"></i> `+$lang_remove+`</a>
        </div>
    `;*/

    // Append the new level input field to the container
    container.find(".level-items").append(newLevel);
});
// Use event delegation to handle dynamically added remove links
$('.level-container').on('click', '.remove-level', function(e) {
    e.preventDefault(); // Prevent default link behavior
    $(this).closest('.form-group').remove(); // Remove the corresponding form group
    // Re-number the remaining levels
    $(this).parents('.level-items').find('.form-group').each(function(index) {
        var newIndex = index + 1;
        $(this).attr('id', 'level-' + newIndex);
        $(this).find('label.control-label').text('Level ' + newIndex);
        $(this).find('a.remove-level').html('<i class="ti-minus"></i> '+$lang_remove);
    });

    // Update level counter
    levelCount = $('.level-container .form-group').length;
});
// Function to validate the levels
function validateLevels(container) {
    var isValid = true;
    var previousValue = -Infinity; // Initialize with the smallest possible value

    container.find('.min-level-input').each(function() {
        var currentValue = parseFloat($(this).val());

        if (isNaN(currentValue) || currentValue <= previousValue) {
            isValid = false;
            return false; // Exit the each loop
        }

        previousValue = currentValue;
    });
    var previousValue = -Infinity; // Initialize with the smallest possible value

    container.find('.target-input').each(function() {
        var currentValue = parseFloat($(this).val().replaceAll(',', ''));
        //console.log('currentValue: '+$(this).val() + ' -> '+currentValue);
        if (isNaN(currentValue) || currentValue <= previousValue) {
            isValid = false;
            return false; // Exit the each loop
        }

        previousValue = currentValue;
    });

    return isValid;
}
function handlePercentage(obj){
    if(obj.checked==true){
        $(obj).closest('.billing-settings').find('.control-label-value').text($lang_percentage_rebate);
        $(obj).closest('.billing-settings').find('.control-label-term-value').text('(%)');
        $(obj).closest('.billing-settings').find('.discount-value').addClass('percentage');
        $(obj).closest('.billing-settings').find('.discount-value').removeClass('amount');
        $(obj).closest('.billing-settings').find('.min-level-input').addClass('percentage');
        $(obj).closest('.billing-settings').find('.min-level-input').removeClass('amount');
    }
    else{
        $(obj).closest('.billing-settings').find('.control-label-value').text($lang_flat_rebate);
        $(obj).closest('.billing-settings').find('.control-label-term-value').text('('+$lang_flat_amount+')');
        $(obj).closest('.billing-settings').find('.discount-value').addClass('amount');
        $(obj).closest('.billing-settings').find('.discount-value').removeClass('percentage');
        $(obj).closest('.billing-settings').find('.min-level-input').addClass('amount');
        $(obj).closest('.billing-settings').find('.min-level-input').removeClass('percentage');
    }
}
// Event delegation for adding new levels
$(document).on('click', '.add-condition', function(e) {
    e.preventDefault(); // Prevent default link behavior

    var container = $(this).closest('.modal.billing-settings');
    var termId = container.data('term-id');
    var contractTermId = container.data('contract-term-id');
    var levelCount = $(this).closest('.condition-items').find('.form-group').length; // Get current number of levels
    // Validate current levels
    /*if (!validateLevels($(this).closest('.condition-container'))) {
        alert("Each level's target value must be less than the next level's target value.");
        return;
    }*/

    levelCount++; // Increment level counter
    var controlLabelValue = $lang_flat_rebate;
    var isPercentageControl = container.find('.is-percentage-control');
    var isPercentage = $(isPercentageControl).is(':checked');
    if(isPercentage==true) controlLabelValue = $lang_percentage_rebate;
    // Create new condition group
    var objClone = $(this).closest(".form-group").clone();
    objClone.find('.control-label.font-weight-bold').html(levelCount+'/');
    objClone.find('.condition_eval').val('');
    objClone.find('.condition_id').val('');
    objClone.find('.add-condition').remove();
    objClone.append(' <a href="#" class="remove-condition"><i class="ti-minus"></i>'+'Remove'+'</a>');
    objClone.appendTo($(this).closest(".condition-items"));

});
// Use event delegation to handle dynamically added remove links
$('.condition-container').on('click', '.remove-condition', function(e) {
    e.preventDefault(); // Prevent default link behavior
    $(this).closest('.form-group').remove(); // Remove the corresponding form group
    // Re-number the remaining levels
    $(this).parents('.condition-items').find('.form-group').each(function(index) {
        var newIndex = index + 1;
        $(this).attr('id', 'condition-' + newIndex);
        $(this).find('label.control-label').text('Level ' + newIndex);
        $(this).find('a.remove-condition').html('<i class="ti-minus"></i> '+$lang_remove);
    });

    // Update level counter
    levelCount = $('.condition-container .form-group').length;
});
// Event delegation for adding new condition group
$(document).on('click', '.add-condition-items', function(e) {
    e.preventDefault(); // Prevent default link behavior
    // Create new condition group
    if($(this).closest(".condition-container").find(".conditions-group").length>0)
    {
        var objClone = $(this).closest(".condition-container").find(".conditions-group").first().clone();
        var count = $(this).closest('.condition-container').find('.conditions-group').length;

        //objClone.find('input,select').val('');
        objClone.find('input,select').each(function(){
            var $element = $(this);
            if($element.attr("type")!='hidden') $element.val(''); // Reset value
            var name = $element.attr("name");
            console.log(name);
            let newName = name.replace(/conditions\[(\d+)\]/g, "conditions[n"+count+"]");
            $element.attr("name",newName);
        });
        objClone.find(".condition-items").attr("data-condition-id",0);
        objClone.find("a.remove-condition-group").attr("data-term-condition-id",0);
        objClone.insertBefore($(this).closest(".condition-container").find('.add-condition-items'));
        count++;
    }else{
        var obj = $(this);
        var is_percentage = $(this).closest(".billing-settings").find(".is-percentage-control").is(":checked");
        var contract_term_id = $(this).closest(".billing-settings").data("contract-term-id");
        console.log("contract_term_id: "+contract_term_id);
        $.ajax({ 
            url: '/cashflow/contract_term_condition/get_data_term_condition', 
            type: 'POST', 
            data: JSON.stringify({"contract_term_id": contract_term_id, "is_percentage": is_percentage}),
            //dataType: 'json',
            contentType: 'application/json;charset=UTF-8',
            success: function (result) { 
                $(obj).closest(".condition-container").prepend(result);
            } 
        }); 
    }

});
$(document).on('click', '.remove-condition-group', function(e) {
    var term_condition_id = $(this).data('term-condition-id');
    var obj = $(this);
    if(term_condition_id==0) {
        obj.closest('.conditions-group').remove();
        return;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({ 
        url: '/cashflow/contract_term_condition/'+term_condition_id, 
        type: 'DELETE', 
        success: function (result) { 
            //console.log(result);
            $(obj).closest(".conditions-group").remove();
        } 
    }); 
});
$(document).on('click', '.save-term-and-edit', function(e) {
    e.preventDefault(); // prevent the form submit
    var objModal = $(this).closest(".billing-settings");
    var contract_id = $("#contract_id").val();
    var term_id = objModal.data("term-id");
    console.log(term_id);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({ 
        url: '/cashflow/contract/term-setting/'+contract_id, 
        type: 'POST', 
        data: $("#contract-edit-from").serialize(),
        contentType: 'application/x-www-form-urlencoded',
        processData: true,
        /*beforeSend: function(){
            Pace.on("done", function(){
                $("body").fadeIn(1000);
            });
        },*/
        success: function (result) { 
            window.location.reload();
            //$("#setting-modal-"+term_id).modal('show');
        },
        error: function (response) {
            // handle error response
            console.log(response.data);
        },
        
    }); 
    
});
$(document).on('change', 'select.condition_id', function() {
    var evalObj = $(this).closest(".form-group").find(".condition_eval");
    if($(this).val()=="min"){
        evalObj.addClass($(this).val());
    }else{
        evalObj.removeClass("min");
    }
    if($(this).val()=="max"){
        evalObj.addClass($(this).val());
    }else{
        evalObj.removeClass("max");
    }
    if($(this).val()=="quaterly"){
        evalObj.addClass($(this).val());
    }else{
        evalObj.removeClass("quaterly");
    }
    
});