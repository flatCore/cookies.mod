<style>
.ace_editor {
	height: 350px;
}
</style>
<script>
$(function() {
	
	var code_editor;
	$('.code_editor').each(function( index ) {
		
		var textarea = $(this);
		var editDiv = $('<div>', {
            position: 'absolute',
            'class': textarea.attr('class')+' aceCodeEditor'
          }).insertAfter(textarea);
    var HTMLtextarea = $('textarea[class*=code_editor]').hide();
    
    var code_editor = ace.edit(editDiv[0]);
    code_editor.$blockScrolling = Infinity;
		code_editor.$blockScrolling = Infinity;
		code_editor.getSession().setMode({ path:'ace/mode/html', inline:true });
		code_editor.getSession().setValue(textarea.val());
		code_editor.setTheme("ace/theme/" + ace_theme);
		code_editor.getSession().setUseWorker(false);
		code_editor.setShowPrintMargin(false);
		
		code_editor.getSession().on('change', function(){
			textarea.val(code_editor.getSession().getValue());
		});
		
	});

});
</script>
<?php




?>