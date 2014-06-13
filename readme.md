# JT Lang

This is a quick plugin for testing various languages in a WordPress install.  I use this for theme dev and plan to expand it to do some cooler stuff in the future.

## Usage

The following will guide you in using this plugin.  It doesn't currently have any admin options or anything.  It's in the early dev stages.

### Installing languages

You should install the WordPress translation files as well as any theme or plugin translation files for the language you are attempting to test.

You can find WP translations here: http://wpcentral.io/internationalization/

### Testing languages

If you want to test a specific language, create a new post in your dev environment or edit an old post.  For the best testing, this post's content should be written in the target language (though not required for this plugin to work).

Add a new custom field called `lang` in the Custom Fields meta box.  For the value, enter the language code for the target language and save.

What this does is change the post's permalink to something like `http://localhost/post-name/?lang=xx_XX`.  The plugin will use that to determine the language to test the post in.

### Supported languages

Mostly because I'm lazy, I've only added a few languages to support for general testing.  You can use these for testing at the moment.  Here's the list:

	$languages = array(
		'en_US', // U.S. English
		'ko_KR', // Korean
		'ja',    // Japanese
		'fi',    // Finnish
		'fr_FR', // French
		'zh_CN', // Chinese
		'ro_RO', // Romanian
		'th',    // Thai
		'ar',    // Arabic (RTL)
		'sv_SE'  // Swedish
	);

For RTL testing, be sure to use Arabic (`ar`).