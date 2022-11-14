$( document ).ready(function() {

    $('.toggle-password').click(function(){
    console.log('test passs')
    $(this).children().toggleClass('mdi-eye-outline mdi-eye-off-outline');
    let input = $(this).prev();
    input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
    });
});