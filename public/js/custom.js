$(document).ready(function($){
  let data = [];
  $("#invite-emails").email_multiple({
      data: data
  });
});

$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});