// Select уже существующего у подписки товара
$('.product_id','#subscribe_consist_rows').select2();

// Добавление параметра в список
$('#add_subscribe_consist_button').click(function(){
    $('#subscribe_consist_rows').append($('#new_subscribe_consist_block').html());
    $('.product_id:last','#subscribe_consist_rows').select2();
    /*$('#subscribe_parameter_rows input[type="text"][name="value[]"]:last').focus();*/
    showFormActions();
});

// Удаление товара
$('#main_form').on('click','.delete_subscribe_consist_button',function(){
    $(this).closest('.row').remove();
});
