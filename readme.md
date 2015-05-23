# JT Lang

This is a quick plugin for testing various languages in a WordPress install.  I use this for theme dev and plan to expand it to do some cooler stuff in the future.

## Live demo

You can see this plugin in action on the [Stargazer theme demo](http://locallylost.com/stargazer).  Select a language from the "Languages" menu.  

## Usage

The following will guide you in using this plugin.  It's in the early dev stages, so don't expect miracles.

### Installing languages

You should install the WordPress translation files as well as any theme or plugin translation files for the language you are attempting to test.

You can find WP translations here: http://wpcentral.io/internationalization/

You can also install a language for core WordPress via the "Settings > General" screen in the WordPress admin.

### Testing languages

If you want to test a specific language, create a new post in your dev environment or edit an old post.  For the best testing, this post's content should be written in the target language (though not required for this plugin to work).

Look for the "Language" meta box.  From there, you can select a language for testing.

What this does is change the post's permalink to something like `http://localhost/post-name/?lang=xx_XX`.  The plugin will use that to determine the language to test the post in.

FWIW, you can also attach `?lang=xx_XX` to any URL on your install to test a particular page in a language.

### RTL Testing

This plugin can also be used for RTL testing if you install a RTL language.  All of that is automatically taken care of when you change to a specific language.