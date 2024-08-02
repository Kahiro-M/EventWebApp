$('.search-container .search-detail .reset-button').click(function(){
    $('.search-container .search-detail  input[type="text"]').val('');
    $('.search-container .search-detail  input[type="date"]').val('');
    $('.search-container .search-detail  input[type="datetime-local"]').val('');
    $('.search-container .search-detail  input.member-code-null-checkbox').removeAttr('checked')
    $('.search-container .search-detail  select').val('')
});

$('.search-container .search-title').click(function(){
    $('.search-detail').slideToggle(100);
});

$('input.dl-checkbox').click(function(){
    $('.csv-download-button').slideDown(100);
});
