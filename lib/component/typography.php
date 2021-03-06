<?php

/**
 *  This file is part of Kirby-Typography. A wrapper of PHP-Typography
 *  for Kirby CMS (https://getkirby.com).
 *
 *  Copyright of Kirby-Typography:
 *  2016 Fabian Michael.
 *
 *  Copyright of PHP-Typography (included in this package):
 *	2014-2016 Peter Putzer.
 *	2012-2013 Marie Hogebrandt.
 *	2009-2011 KINGdesk, LLC.
 *
 *	This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 *  ***
 *
 *  @package Kirby\Plugings\Typography
 *  @author Fabian Michael <hallo@fabianmichael.de>
 *  @license http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Kirby\Plugins\Typography\Component;
use Kirby\Plugins\Typography\Cache;
use C;
use L;

class Typography extends \Kirby\Component\Smartypants {

  private $typo;

  private $settingsAndHash;

  function __construct(\Kirby $kirby) {
    parent::__construct($kirby);

    $this->typo = new \PHP_Typography\PHP_Typography();
  }

  private function configureSettings($callable, $settings) {
    if (is_callable($callable)) {
      $callable($settings);
    }
  }

  private function settingsAndHash() {
    // we have to construct the settings lazily since the languages are not loaded at construction/configuration time of this component

    if (!$this->settingsAndHash) {
      $settings = new \PHP_Typography\Settings();

      $this->configureSettings(c::get('typography.settings'), $settings);

      if ($this->kirby->site->multilang()) {
        $this->configureSettings(l::get('typography.settings'), $settings);
      }

      $this->settingsAndHash = new SettingsAndHash($settings);
    }

    return $this->settingsAndHash;
  }

  private function hash($text) {
    return md5($this->settingsAndHash()->hash . $text);
  }

  private function processText($text) {
    return $this->typo->process($text, $this->settingsAndHash()->settings);
  }

  public function parse($text, $force = false, \Field $field = null) {
    if (!c::get('typography', true)) {
      return $text;
    } else {
      if (c::get('typography.debug', false)) {
        // Skip caching when in debug mode
        return $this->processText($text);
      }

      $cache         = Cache::instance();
      $cacheKey      = $this->hash($text);
      $processedText = $cache->get($cacheKey, false);

      if ($processedText === false) {
        $processedText = $this->processText($text);
        $cache->set($cacheKey, $processedText);
      }

      return $processedText;
    }
  }
}

class SettingsAndHash {

  public $settings;

  public $hash;

  function __construct($settings) {
    $this->settings = $settings;
    $this->hash = $settings->get_hash(); // we used to run into the now fixed https://github.com/mundschenk-at/php-typography/issues/27
  }

}