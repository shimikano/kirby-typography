# Kirby-Typography

This is wrapper of [PHP-Typography](https://github.com/mundschenk-at/php-typography) for Kirby CMS. This plugin enhances the typography of you kirby-powered website. Think of it a more advanced alternative to the built-in `SmartyPants` parser.

Current version: `1.0.0-beta1`

***

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [1 Key Features](#1-key-features)
- [2 Download and Installation](#2-download-and-installation)
  - [2.1 Requirements](#21-requirements)
  - [2.2 Kirby CLI](#22-kirby-cli)
  - [2.3 Git Submodule](#23-git-submodule)
  - [2.4 Copy and Paste](#24-copy-and-paste)
- [3 Usage](#3-usage)
  - [3.1 CSS and JavaScript Setup](#31-css-and-javascript-setup)
- [4 Configuration](#4-configuration)
  - [4.1 Localization for Multilingual Sites](#41-localization-for-multilingual-sites)
- [5 Recommended Settings for Different Languages](#5-recommended-settings-for-different-languages)
- [6 License](#6-license)
- [7 Credits](#7-credits)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

***

## 1 Key Features

-	**Hyphenation:** Hyphenate text, very handy for justified text or languages with very long words, but also for English. Supports a large number of languages and offers fine-grained control over hyphenation.
-	**Smart Replacements:** Includes Smart quotes (i.e. „Curly Quotes“, «Guillemets», »Chevrons« or German „Gänsefüßchen“) and smart ordinal suffixes.
-	**CSS Hooks:** Add `<span class="[…]">` tags around characters like ampersands, CAPITALIZED WORDS etc.
-	**Hanging Punctuation:** Add the twinkle of the print-era to your site.
-	**Wraps URLs:** Prevents long URLs from overflowing their container element on small screens.
-	**Smart Math:** Converts mathematical symbols into their correct counterpart. E.g. `(2x6)/3=4` becomes `(2×6)÷3=4`.
-	**Caching:** Processing text like this plugin does is a very performance-hungry task. So the results are always cached, even if Kirby’s cache option is turned off. Cache will automatically be updated, if you change your content or update the plugin. It also comes with a *Dashboard Widget*, so you can flush the cache from your panel.

… and basically every other feature of PHP-Typography :-)

## 2 Download and Installation

### 2.1 Requirements

-	PHP 5.6.0 or above with multibyte extension (mbstring)
-	Kirby 2.3.0+

### 2.2 Kirby CLI

If you’re using the [Kirby CLI](https://github.com/getkirby/cli), you need to `cd` to the root directory of your Kirby installation and run the following command:

```
kirby plugin:install fabianmichael/kirby-typography
```

This will download and copy *Kirby-Typography* into `site/plugins/typography`.

### 2.3 Git Submodule

To install this plugin as a git submodule, execute the following command from the root of your kirby project:

```
$ git submodule add https://github.com/fabianmichael/kirby-typography.git site/plugins/typography
```

### 2.4 Copy and Paste

1. [Download](https://github.com/fabianmichael/kirby-typography/archive/master.zip) the contents of this repository as ZIP-file.
2. Rename the extracted folder to `typography` and copy it into the `site/plugins/` directory in your Kirby project.

## 3 Usage

The plugin is enabled by default and replaces the [SmartyPants](https://michelf.ca/projects/php-smartypants/) parser of your Kirby installation. That means, typographic enhancements are applied to *Kirbytext* by default.

The default typography settings of PHP-Typography are used. You can change any settings in your `config.php` file, cf. below.

### 3.1 CSS and JavaScript Setup

**CSS:** Not all features of the plugin need extra CSS rules, but for stuff like hanging punctuation, and character styling to work properly, it is recommended or even necessary to add the plugin’s CSS file to your site.
Add `<?= css('assets/plugins/typography/css/typography.css') ?>` to your template or – even better – copy the rules to your own stylesheet. Note, that some features like hanging punctuation should be tuned in accordance to the font you have chosen.

**JavaScript:** Hyphenation works by inserting invisible so-called »shy hyphens« (`&shy;`) into works, telling the browser where a word may be hyphenated. Unfortunately, these invisible characters may stay in the text, when it is copied from your site. To keep copied text easily editable, it is recommended (though not mandatory) to also include the supplied JavaScript file to your code. Just add `<?= js('assets/plugins/typography/js/dist/copyfix.min.js') ?>` into to your template.

## 4 Configuration

Use the `typography.settings` option in your `config.php` file to customize the PHP-Typography settings. Note that the value is a callback *function* rather than a simple string value.

```php
c::set('typography.settings' => function($settings) {
  $settings->set_french_punctuation_spacing(false);
  $settings->set_smart_dashes_style(\PHP_Typography\Settings\Dash_Style::INTERNATIONAL);

  $settings->set_smart_quotes_primary('doubleLow9Reversed');
  $settings->set_smart_quotes_secondary('singleLow9Reversed');

  $settings->set_hyphenation(true);
  $settings->set_hyphenation_language('de');
}
```

Please refer to the PHP-Typography documentation for the supported settings.

### 4.1 Localization for Multilingual Sites

If your Kirby installation is configured for multiple languages, you might want to use different settings for every language. In this case, you can define a common baseline of settings – shared by all languages – in your config file. Use the language files in `site/languages/` to define localized versions of your settings. Note, that in the language files, settings need to be configured with `l::set()`, because those localizations are treated as language variables:

```php
# site/config/config.php
c::set('typography.settings' => function($settings) { // settings shared across languages
  $settings->set_hyphenation(true);
}
```

```php
# site/languages/fr.php
l::set('typography.settings' => function($settings) { // language specific settings
  $settings->set_smart_quotes_primary('doubleGuillemetsFrench');
  $settings->set_smart_quotes_secondary('doubleCurled');

  $settings->set_hyphenation_language('fr');
}

# site/languages/de.php
l::set('typography.settings' => function($settings) {
  $settings->set_smart_quotes_primary('doubleLow9Reversed');
  $settings->set_smart_quotes_secondary('singleLow9Reversed');

  $settings->set_hyphenation_language('de');
}
```

## 5 Recommended Settings for Various Languages

The following recommendations are based on common typographic conventions of some specific languages. That does not mean that you have to stick to them, but they make a good starting point for your own settings. Is your language missing in this list? Feel free to [create an issue](https://github.com/fabianmichael/kirby-typography/issues/new) or pull request and I will add settings for your language to the list.

**Important:** If your site is multilingual, you need to define those settings in `site/languages/[language code].php`, using `l::set()` instead of `c::set`, if you want to use different settings per language.

**French (France):**

```php
c::set('typography.settings' => function($settings) {
  $settings->set_french_punctuation_spacing(true);
  $settings->set_smart_dashes_style(\PHP_Typography\Settings\Dash_Style::TRADITIONAL_US); // cadratin (INTERNATIONAL for demi-cadratin)
  $settings->set_smart_quotes_primary('doubleGuillemetsFrench');
  $settings->set_smart_quotes_secondary('doubleCurled');
  $settings->set_hyphenation_language('fr');
}
```

**German (Germany):**

```php
c::set('typography.settings' => function($settings) {
  $settings->set_french_punctuation_spacing(false);
  $settings->set_smart_dashes_style(\PHP_Typography\Settings\Dash_Style::INTERNATIONAL);
  $settings->set_smart_quotes_primary('doubleLow9Reversed');
  $settings->set_smart_quotes_secondary('singleLow9Reversed');
  $settings->set_hyphenation_language('de');
}
```
Note: In books, sometimes Chevrons [ »foo« ] are used as quotation marks instead. Use `doubleGuillemetsReversed` and `singleGuillemetsReversed` if you prefer that style.

## 6 License

*Kirby-Typography* is released under the GNU General Public License 3.0 or later. See `LICENSE` file for details (FYI: This is mandatory, because PHP-Typography is also released under GPL. I would have preferred the MIT license.).

## 7 Credits

**Kirby-Typography** is developed and maintained by [Fabian Michael](https://fabianmichael.de).

The plugin includes or is based on the following third-party libraries:

-	**PHP-Typography:** Copyright 2014-2016 Peter Putzer; 2012-2013 Marie Hogebrandt; 2009-2011 KINGdesk, LLC. Released under the [GPL 2.0](http://www.gnu.org/licenses/gpl-2.0.html) or later. Large parts of this documentation have also been copied and/or adapted from the original WordPress plugin.
-	**HTML5-PHP:** Released under the *HTML5Lib License* (see `vendors/masterminds/HTML5/LICENSE` for details and contributors)
-	**A List of all Top-Level-Domains**, maintained by the [Internet Assigned Numbers Authority (IANA)](http://www.iana.org) (automatically updated once a week)
- **JavaScript** (copyfix.js) based on [Hyphenator](https://github.com/mnater/Hyphenator) 5.2.0(devel). Copyright 2015 by Mathias Nater. Originally released under the MIT license.
