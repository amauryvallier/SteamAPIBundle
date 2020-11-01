$(document).ready(function() {

    /* Accordions management */
    function open_accordion(accordion) {
        var currentAttrValue = accordion.closest('.accordion-section-title');
        if(currentAttrValue.hasClass('active')) {
            close_accordion_section();
        }else {
            close_accordion_section();
            currentAttrValue.addClass('active');
            $('.accordion ' + currentAttrValue.attr('href')).slideDown(300).addClass('open');
            $(this).parent().find('.accordion-section-content .highchart').each(function() {
                // Resizing highcharts if there are ones
                $(this).highcharts().reflow();
            });
        }
    }
    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
    // Open corresponding accordion on page load if anchor in URL
    if(window.location.hash) {
        open_accordion($('a[href*='+ window.location.hash + ']'));
    }
    // Open/Close events triggers
    var activeopen = $('.accordion .accordion-section-title.active');
    $('.accordion ' + activeopen.attr('href')).show().addClass('open');
    $('.accordion-section-title').click(function(e) {
        open_accordion($(this));
        e.preventDefault();
    });

    /* Forms management */
    $('select').each(function(){
        $(this).chosen({allow_single_deselect: true});
    });

    /* Specific search actrices by initial letter */
    $('.letter-filter').on('click', function(){
        $('#actrice_search_noms').val($(this).data('value'));
        $('#form_search_actress').submit();
    });

    /* Specific search films by initial letter */
    $('.letter-filter').on('click', function(){
        $('#film_search_titre').val($(this).data('value'));
        $('#form_search_films').submit();
    });

    /* Scene page : generic forms management */
    $('.ajax-element-block').each(function(){
        var block = $(this);
        block.on('submit', '.add-element-block form', function (evt) {
            evt.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                method: form.attr('method'),
                success: function (data) {
                    block.html(data);
                    block.find('select').each(function () {
                        $(this).chosen({allow_single_deselect: true})
                    });
                    if( block.hasClass('force-page-reload') ) {
                        window.location.reload();
                    }
                },
                error: function () {
                    console.log('error');
                }
            });
        });
        block.on('submit', '.add-subelement-block form', function (evt) {
            evt.preventDefault();
            var subBlock = $(this).closest('.ajax-subelement-block');
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                method: form.attr('method'),
                success: function (data) {
                    subBlock.html(data);
                    subBlock.find('select').each(function () {
                        $(this).chosen({allow_single_deselect: true})
                    });
                },
                error: function () {
                    console.log('error');
                }
            });
        });
        block.on('click', '.ajax-link', function(evt) {
            evt.preventDefault();
            if($(this).parents('.ajax-subelement-block').length != 0) {
                block = $(this).parents('.ajax-subelement-block');
            }
            $.ajax({
                url: $(this).attr('href'),
                method: 'GET',
                success: function (data) {
                    block.html(data);
                    block.find('select').each(function () {
                        $(this).chosen({allow_single_deselect: true})
                    });
                },
                error: function () {
                    console.log('error');
                }
            });
        });
    });
});