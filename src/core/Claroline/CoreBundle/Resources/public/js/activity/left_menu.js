(function(){
    var currentStep = 0;
    var currentItem = 1;

    $('#item-'+1).css('font-weight', 'bold');
    var totalSteps = document.getElementById('twig-attributes').getAttribute('data-total-steps');
    var totalItems = document.getElementById('twig-attributes').getAttribute('data-total-items');

    $('#progress-bar').html(currentStep+'/'+totalSteps);

    $('.icon-arrow-right').live('click', function(){
        currentItem++;
        if (currentItem >= totalItems){currentItem = totalItems;}
        loadRightFrame(currentItem);
    });

    $('.icon-arrow-left').live('click', function(){
        currentItem--;
        if (currentItem <= 1){currentItem = 1;}
        loadRightFrame(currentItem);
    });

    var loadRightFrame = function(item){
        currentItem = item;

        if ($('#item-'+item).attr('class') == 'activity-step' && $('#item-'+item).css('font-weight') == 400){
             currentStep++;
             $('#progress-bar').html(currentStep+'/'+totalSteps);
        }

        $('#item-'+item).css('font-weight', 'bold');
        var route =  $('#item-'+item).attr('href');
        window.parent.document.getElementById('right-frame').src = route;
    }
})();


