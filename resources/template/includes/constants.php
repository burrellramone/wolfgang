<?php
/**
 * The root of the application on the server on which it runs
 *
 * @var string
 */
define("DOCUMENT_ROOT", realpath(dirname(__FILE__, 2)) . "/");

/**
 * The root of the application on the server on which it runs
 *
 * @var string
 */
define("PUBLIC_DIRECTORY", DOCUMENT_ROOT . 'public/');

/**
 * The root directory where all images for front-end use are stored
 *
 * @var string
 */
define("IMG_DIRECTORY", PUBLIC_DIRECTORY . 'img/');

/**
 * The top-level directory where classes are stored
 *
 * @var string
 */
define('CLASSES_DIRECTORY', DOCUMENT_ROOT . 'src/main/php/com/__project__/');

/**
 * The top-level directory where the composer vendor packages are stored
 *
 * @var string
 */
define('VENDOR_DIRECTORY', DOCUMENT_ROOT . 'vendor/');

/**
 *
 * @var string
 */
define('WOLFGANG_DIRECTORY', CLASSES_DIRECTORY . 'Wolfgang/');

/**
 * The top-level directory where PHPUnit test classes are stored
 *
 * @var string
 */
define('TESTS_CLASSES_DIRECTORY', DOCUMENT_ROOT . 'src/tests/php/com/__project__/');

/**
 *
 * @var string
 */
define('MODELS_DIRECTORY', CLASSES_DIRECTORY . 'Model/');

/**
 *
 * @var string
 */
define('RESOURCES_DIRECTORY', DOCUMENT_ROOT . "resources/");

/**
 *
 * @var string
 */
define('TEMPLATE_DIRECTORY', DOCUMENT_ROOT . 'src/main/php/com/__project__/Views/');

/**
 *
 * @var string
 */
define('I18N_DIRECTORY', DOCUMENT_ROOT . 'i18n/');

/**
 *
 * @var string
 */
define('STORAGE_DIRECTORY', DOCUMENT_ROOT . 'storage');

/**
 *
 * @var string
 */
define('TEMP_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . '.tmp/');

/**
 *
 * @var string
 */
define('XHPROF_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . 'xhprof');

/**
 *
 * @var string
 */
define('UPLOADS_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . 'uploads');

/**
 *
 * @var string
 */
define('LOGS_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . 'logs');

/**
 *
 * @var string
 */
define('SESSION_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . 'session');

/**
 *
 * @var string
 */
define('SMARTY_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . 'smarty');

/**
 *
 * @var string
 */
define('CURL_DIRECTORY', STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . 'curl');

/**
 *
 * @var string
 */
define('CONFIGURATION_DIRECTORY', DOCUMENT_ROOT . 'config/');

/**
 *
 * @var string
 */
define('SITES_FILE_PATH', DOCUMENT_ROOT . 'sites.php');


if(!defined('APPLICATION_ENVIRONMENT')) {
	/**
	 *
	 * @var string
	 */
	define('APPLICATION_ENVIRONMENT', getenv('APPLICATION_ENV'));
}


/**
 *
 * @var string
 */
define('DEFAULT_LANGUAGE_CODE', 'en-CA');

/**
 *
 * @var string
 */
define('DATE_FORMAT', 'Y-m-d');

/**
 *
 * @var string
 */
define('DATE_TIME_FORMAT', 'Y-m-d H:i:s');

/**
 *
 * @var string
 */
define('DB_LOCK_FILE', DOCUMENT_ROOT . '/db.lock');
