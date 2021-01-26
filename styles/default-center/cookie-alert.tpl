<a href="#" id="toggleCookies" class="toggle toggle-hide">Cookies</a>
<div class="cookie-box cookie-box-hide">
	<div class="cookie-box-inner">
		<div class="cookie-box-content">
			<p class="mb-0">{cookie_banner_intro}</p>
			{url_privacy_policy}
			<form id="cookie_form">
				{cookie_list}
				<div class="cookie-box-actions d-flex flex-column">
					<button type="submit" class="btn btn-success btn-sm w-100 ml-1 mr-1" id="cookies_accept_all">{btn_accept_all}</button>
					<button type="submit" class="btn btn-link btn-sm w-100" id="cookies_accept_selected">{btn_save}</button>
					<input type="hidden" name="cookie_lifetime" value="{cookie_lifetime}">
					{cookies_list_all}
				</div>
			</form>
		</div>
	</div>
</div>