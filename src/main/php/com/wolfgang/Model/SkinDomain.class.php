<?php

namespace Wolfgang\Model;

use Wolfgang\Interfaces\Model\ISkinDomain;
use Wolfgang\Exceptions\IllegalStateException;
use Wolfgang\Traits\Model\TSkinDomain;
use Wolfgang\Date\DateTime;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Model
 * @since Version 1.0.0
 */
final class SkinDomain extends Model implements ISkinDomain {
	use TSkinDomain;

	/**
	 *
	 * @var string
	 */
	public $skin_id;

	/**
	 *
	 * @var string
	 */
	public $white_label_id;

	/**
	 *
	 * @var string
	 */
	public $domain;

	/**
	 *
	 * @var string
	 */
	public $url;

	/**
	 *
	 * @var string
	 */
	public $api_domain;

	/**
	 *
	 * @var string
	 */
	public $api_url;

	/**
	 *
	 * @var boolean
	 */
	public $open;

	/**
	 *
	 * @var DateTime
	 */
	public $last_updated;
	/**
	 *
	 * @var DateTime
	 */
	public $create_date;

	/**
	 *
	 * @return bool
	 */
	public function isOpen ( ): bool {
		return $this->open;
	}

	/**
	 *
	 * @return string
	 */
	public function getDomain ( ): string {
		return $this->domain;
	}

	/**
	 *
	 * @return string
	 */
	public function getUser ( ): ?string {
		return $this->user;
	}

	/**
	 *
	 * @return string
	 */
	public function getUrl ( ) {
		return $this->url;
	}

	/**
	 *
	 * @throws IllegalStateException::
	 * @return string
	 */
	public function getApiDomain ( ): string {
		if ( ! $this->api_domain ) {
			throw new IllegalStateException( "An API domain has not been defined for this skin domain" );
		}

		return $this->api_domain;
	}

	/**
	 *
	 * @throws IllegalStateException
	 * @return string
	 */
	public function getApiUrl ( ): string {
		if ( ! $this->api_url ) {
			throw new IllegalStateException( "An API URL has not been defined for this skin domain" );
		}
		return $this->api_url;
	}
}