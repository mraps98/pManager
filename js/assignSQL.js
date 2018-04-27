$(document).ready(function(){
  $('.assignbtn').on('click',function(){
    var pno = $(this).attr('value');
    var status = $(this).text();
    $('#pnoinput').attr('value',pno);
    $('#assigninput').attr('value',status);
    $('#assignform').attr('action', 'assignProjectSQL.php');
    $('#assignform').attr('method', 'POST');
    $('#assignform').submit();
  });
});
