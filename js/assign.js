$(document).ready(function(){
  $('.assignbtn').on('click',function(){
    var id = $(this).attr('value');
    $('#ssninput').attr('value',id);
    $('#assign').attr('action','pages/projectsByEmp.php');
    $('#assign').attr('method', 'GET');
    $('#assign').submit();


  });

});
