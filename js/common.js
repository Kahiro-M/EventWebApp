function blockSubmit(elementName){
  var obj = document.getElementsByName(elementName);
  var ret = false;
    obj.forEach(
      function(element){
        if(element.disabled){
          return false;
        }else{
          element.disabled = true;
          ret = true;
        }
      }
    );
  if(ret){
    return true;
  }else{
    return false;
  }
    //ボタンがdisabledでなければ、ボタンをdisabledにした上でsubmitする
}

$('.dbg-button').click(function(){
  $('.dbg-detail').toggleClass('d-flex');
  $('.dbg-detail').toggleClass('d-none');
});

$(document).ready(function(){
  $('input.timepicker').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '06:00',
    maxTime: '22:00',
    dynamic: false,
    scrollbar: true,
  });
});

