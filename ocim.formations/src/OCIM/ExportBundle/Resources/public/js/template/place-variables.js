$(".variable").on('click', function() {
    var caretPos = document.getElementById("ocim_exportbundle_template_filename").selectionStart;
    var textAreaTxt = $("#ocim_exportbundle_template_filename").val();
    var txtToAdd = "{{" + $(this).attr('data-var') + "}}";
    $("#ocim_exportbundle_template_filename").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
});
