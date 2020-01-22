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


		$addon_id = 'addonIDcookies';
		$modal_template_file = file_get_contents("templates/bs-modal.tpl");
		$btn_help_text = '<button type="button" class="btn btn-sm btn-fc float-right" data-toggle="modal" data-target="#'.$addon_id.'">'.$icon['question'].'</button>';
		
		$modal_body_text = file_get_contents('../modules/cookies.mod/readme.md');
		$Parsedown = new Parsedown();
		$modal_body = $Parsedown->text($modal_body_text);
		
		$modal = $modal_template_file;
		$modal = str_replace('{modalID}', $addon_id, $modal);
		$modal = str_replace('{modalTitle}', $mod['name'], $modal);
		$modal = str_replace('{modalBody}', $modal_body, $modal);
		echo $modal;



?>