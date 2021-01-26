<script>
		if (document.cookie.indexOf("cookie_consent=") >= 0) {
			$( "div.cookie-box" ).addClass( "cookie-box-hide" );
		}
		
		$("#toggleCookies").click(function() {
			$( "div.cookie-box" ).removeClass( "cookie-box-hide" );
		});
		
    $(document).ready(function(){

	    
      $('#cookie_form button[type="submit"]').on("click", function(e) {
      	e.preventDefault();

				var set_mode = $(this).attr('id');
					
            var disabled = $("#cookie_form").find(':input:disabled').removeAttr('disabled');
            var cookie_data = $("#cookie_form").serializeArray();
            disabled.attr('disabled','disabled');
            
            cookie_data.push({name: 'set_mode', value: set_mode});

            $.ajax({
                type: "POST",
                url: 'modules/cookies.mod/global/cookies.php',
                data: cookie_data,
                success: function(data){
									//alert(data); // show response from the php script.
									$( "div.cookie-box" ).addClass( "cookie-box-hide" );
           			}
            });
        });
    });
				
</script>