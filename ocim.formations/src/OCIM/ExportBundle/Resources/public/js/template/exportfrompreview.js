$(document).ready(function(){

  $('#export input[type=submit]').click(function(e){
    e.preventDefault();
    var input = $("<input>")
      .attr("type", "hidden")
      .attr("name", "content").val(tinyMCE.get('wrapper').getContent());
    $('#export').append($(input));
      $("#export").submit();
  });

});
