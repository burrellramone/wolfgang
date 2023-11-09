<?php

namespace Wolfgang\Interfaces\Application;

use Wolfgang\Interfaces\Model\ISkin;
use Wolfgang\Interfaces\Model\ISkinDomain;

/**
 *
 * @package Wolfgang\Interfaces
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
interface IContext {
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_DEFAULT = 'default';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_DEVELOPMENT = 'development';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_INTEGRATION = 'integration';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_LOCAL = 'local';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_PRODUCTION = 'production';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_STAGING = 'staging';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_UAT = 'uat';
	
	/**
	 *
	 * @var string
	 */
	const ENVIRONMENT_UNIT = 'unit';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_AOLSERVER = 'aolserver';
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_APACHE = 'apache';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_APACHE2FILTER = 'apache2filter';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_APACHE2_HANDLER = 'apache2handler';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_CAUDIUM = 'caudium';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_CGI = 'cgi';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_CGI_FCGI = 'cgi-fcgi';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_CLI = 'cli';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_CLI_SERVER = 'cli-server';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_CONTINUITY = 'continuity';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_EMBED = 'embed';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_FPM_FCGI = 'fpm-fcgi';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_ISAPI = 'isapi';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_LITESPEED = 'litespeed';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_MILTER = 'milter';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_NSAPI = 'nsapi';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_PHTTPD = 'phttpd';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_PI3WEB = 'pi3web';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_ROXEN = 'roxen';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_THTTPD = 'thttpd';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_TUX = 'tux';
	
	/**
	 *
	 * @var string
	 */
	const PHP_SAPI_WEBJAMES = 'webjames';
	
	/**
	 *
	 * @return string
	 */
	public function getEnvironment ( ): string;
	
	/**
	 *
	 * @return ISkin
	 */
	public function getSkin ( ): ISkin;
	
	/**
	 *
	 * @return ISkinDomain
	 */
	public function getSkinDomain ( ): ISkinDomain;
	
	/**
	 *
	 * @return bool
	 */
	public function isMobile ( ): bool;
	
	/**
	 *
	 * @return bool
	 */
	public function isProduction ( ): bool;
}