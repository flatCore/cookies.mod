## cookies.mod - flatCore CMS Module

With this addon you can display a cookie consent banner on your website.


### Installing cookies.mod

1. Download the latest Version from flatCore.org or github.com
2. Unzip the file and upload the folder __cookies.mod__ into the directory *modules*.
3. Open the Backend and go to the Addons Section. The installation will run automatic if you open cookies.mod for the first time

#### Preferences

| URL to your Privacy Policy | Link to your Privacy Policy Page |
| --- | ---|
| Cookie Banner Intro | Intro of your Cookie Banner |
| Choose Snippet ... | If you select a snippet, the Intro will be overwritten by this snippet |
| Lifetime | one day = 86400 |
| Ignore inline css | use your own CSS, if you check this option |


If you want to Style the Cookie-Banner by yourself, check the Option __Ignore inline CSS__ and you can make use of your own Styles. If you're looking for the default CSS: `cookies.mod/global/styles.css`

#### Manage your Cookies

Enter the Title, Teaser and Text OR if you have a multilingual Page, choose a Snippet. Note: The list only shows entries with "cookie" as prefix in the name.

| status |   |
| --- | ---|
| Active | Check this, if you want to display this cookie in the cookie consent banner |
| Mandatory | Check this, if your visitors can't "uncheck" this cookie

Now there are four input fields for code

1. Cookie Accepted - `<head>` injection
2. Cookie Accepted - `<body>` injection
3. Cookie Declined - `<head>` injection
4. Cookie Declined - `<body>` injection

#### Requirement

* flatCore Version > 2.0 (1.5.3 should still work, but is no longer tested)
* Your Theme must support `$append_head_code` and `$append_body_code`


License: GNU GENERAL PUBLIC LICENSE Version 3
