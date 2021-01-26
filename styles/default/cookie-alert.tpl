<div class="cookie-box cookie-box-hide">
	<a href="#" id="toggleCookies">Cookies</a>
	<div class="cookie-box-inner">
		<p class="mb-0">{cookie_banner_intro}</p>
		{url_privacy_policy}
		<form id="cookie_form">
			{cookie_list}
			<div class="cookie-box-actions d-flex">
				<button type="submit" class="btn btn-success btn-sm w-100 mr-1" id="cookies_accept_all">{btn_accept_all}</button>
				<button type="submit" class="btn btn-outline-success btn-sm" id="cookies_accept_selected">{btn_save}</button>
				<input type="hidden" name="cookie_lifetime" value="{cookie_lifetime}">
				{cookies_list_all}
			</div>
		</form>
	</div>
</div>