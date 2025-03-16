<?php

namespace Wolfgang\I18N;

use Wolfgang\Component as BaseComponent;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Interfaces\Application\IApplication;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class I18N extends BaseComponent {
	
	private static $site = null;
	private static $appKind = IApplication::KIND_SITE;
	private static $path = null;
	private static $language = DEFAULT_LANGUAGE_CODE;
	private static $languages = array(
        'de-DE',
        'en-CA',
        'fr-FR',
        'he-IL',
		'es-ES',
        'pt-PT',
    );
    
    /**
     * @static
     * @var array
     */
    private static $copies = array();
    private static $translations = [];

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/API/Navigator/language
     * @see https://html.spec.whatwg.org/multipage/system-state.html#dom-navigator-language-dev
     * 
     * @param string $text
     * @param string|null $language
     * @param array $replacements
     * @return string
     */
    public static function __(string $text, string|null $language = null, array $replacements = array()): string {
        if (!empty($language) && !self::isValidLanguage($language)) {
            throw new InvalidArgumentException("Invalid language '{$language}' provided.");
        }

		$language = $language??self::$language;

        if (!isset(self::$translations[$language])) {
            self::$translations[$language] = self::loadTranslations($language);
        }

        if (array_key_exists($text, self::$translations[$language])){
            $text = self::$translations[$language][$text];
        }

        foreach($replacements as $key => $value){
            $text = str_replace($key, $value, $text);
        }

        return $text;
    }

    /**
     * 
     * 
     * @param string $language
     * @throws \Wolfgang\Exceptions\InvalidStateException
     * @return array
     */
    private static function loadTranslations(string $language): array {
        $translation = [];
        $site = self::$site;
		$appKind = self::$appKind;
		$path = self::$path;

        //General translation for for all sites
        $file = I18N_DIRECTORY . "{$language}.php";

        if (file_exists($file)) {
            $translations = include($file);

            if (!is_array($translations)) {
                throw new InvalidStateException("Translation file '{$file}' does not return an array.");
            }
        }

        //load site specific translations
        if (!empty($site) && !empty($path)) {
            //@TODO: General translation file for the site


            //General translation file for the site and app kind
            $siteFile = I18N_DIRECTORY . "{$site}/{$appKind}/{$language}.php";

            if (file_exists($siteFile)) {
                $siteTranslations = include($siteFile);

				if (!is_array($siteTranslations)) {
					throw new InvalidStateException("Translation file '{$siteFile}' does not return an array.");
				}

                $translations = array_merge($translations, $siteTranslations);
            }


            //Path specific translations
            $pathFile = I18N_DIRECTORY . "{$site}/{$appKind}/{$path}/{$language}.php";

            if (file_exists($pathFile)) {
                $pathTranslations = include($pathFile);

				if (!is_array($pathTranslations)) {
					throw new InvalidStateException("Translation file '{$pathFile}' does not return an array.");
				}

                $translations = array_merge($translations, $pathTranslations);
            }
        }

        return $translations;
    }

    /**
     * 
     
     * @param string $site
     * @param string $appKind
     * @param string $path
     * @param string $language
     * @return void
     */
	public static function setLoadContext(string $site, string $appKind, string $path, string $language):void {
		self::setSite($site);
		self::setAppKind($appKind);
		self::setPath($path);
		self::setLanguage($language);
	}

    /**
     * 
     * 
     * @param string $site
     * @return void
     */
	public static function setSite(string $site):void {
		self::$site = strtolower($site);
	}

    /**
     * 
     
     * @param string $appKind
     * @return void
     */
	public static function setAppKind(string $appKind):void {
		self::$appKind = strtolower($appKind);
	}
	

    /**
     * 
     * 
     * @param string $path
     * @return void
     */
	public static function setPath(string $path):void {
		self::$path = strtolower($path);
	}

    /**
     * 
     * 
     * @param string $language
     * @throws \Wolfgang\Exceptions\InvalidArgumentException
     * @return void
     */
	public static function setLanguage(string $language):void {
		if (!self::isValidLanguage($language)) {
            throw new InvalidArgumentException("Invalid language '{$language}' provided.");
        }

		self::$language = $language;
	}

    /**
     * Gets the translations that have been loaded
     * 
     * @return array
     */
    public static function getTranslations():array {
        return self::$translations[self::$language] ?? [];
    }

    /**
     * 
     * 
     * @param string $language
     * @return bool
     */
    public static function isValidLanguage(string $language):bool{
        return in_array($language, self::$languages);
    }
}
