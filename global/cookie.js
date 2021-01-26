<script>
		
		$("#toggleCookies").click(function() {
			$( "div.cookie-box" ).removeClass( "cookie-box-hide" );
			$( "#toggleCookies" ).addClass( "toggle-hide" );
			$( "#toggleCookies" ).removeClass( "toggle-show" );
		});
		
		function getCookie(name) {
		    var cookie = document.cookie;
		    var prefix = name + "=";
		    var begin = cookie.indexOf("; " + prefix);
		    if (begin == -1) {
		        begin = cookie.indexOf(prefix);
		        if (begin != 0) return null;
		    } else {
		        begin += 2;
		        var end = document.cookie.indexOf(";", begin);
		        if (end == -1) {
		        end = cookie.length;
		        }
		    }
		    return unescape(cookie.substring(begin + prefix.length, end));
		}
				
    $(document).ready(function(){
	    
	    var cookie_consent = getCookie("cookie_consent");
	    
			if(cookie_consent == null) {
				$( "div.cookie-box" ).removeClass( "cookie-box-hide" );
			} else {
				$( "#toggleCookies" ).addClass( "toggle-show" );
			}
	     

      $('#cookie_form button[type="submit"]').on("click", function(e) {
      	e.preventDefault();
      	
				$( "#toggleCookies" ).addClass( "toggle-show" );
				var set_mode = $(this).attr('id');
					
        var disabled = $("#cookie_form").find(':input:disabled').removeAttr('disabled');
        var cookie_data = $("#cookie_form").serializeArray();
        disabled.attr('disabled','disabled');
            
        cookie_data.push({name: 'set_mode', value: set_mode});

        $.ajax({
        	type: "POST",
          url: '/modules/cookies.mod/global/cookies.php',
          data: cookie_data,
          success: function(data){
						//alert(data); // show response from the php script.
						$( "div.cookie-box" ).addClass( "cookie-box-hide" );
          }
         });
       });
    });	
</script>