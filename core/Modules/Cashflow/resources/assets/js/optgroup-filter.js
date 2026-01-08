$.fn.filterGroups = function( options ) {
    var settings = $.extend( {}, options);

    return this.each(function(){
        
        var $select = $(this);

        // Clone the optgroups to data, then remove them from dom
        $select.data('fg-original-groups', $select.find('optgroup').clone()).children('optgroup').remove();

        $(settings.groupSelector).change(function(){
            
            var $this = $(this);
            var optgroup_label = $this.val();
            // Handle "all" case
            if (optgroup_label === "") {
                // Restore all optgroups
                var $allGroups = $select.data('fg-original-groups').clone();
                $select.children('optgroup').remove();
                $select.append($allGroups);
            } else {
                // Filter specific optgroup
                var $optgroup = $select.data('fg-original-groups').filter(function () {
                    return $(this).attr('label') === optgroup_label;
                }).clone();
                $select.children('optgroup').remove();
                $select.append($optgroup);
            }
            
        }).change();

    });
};
