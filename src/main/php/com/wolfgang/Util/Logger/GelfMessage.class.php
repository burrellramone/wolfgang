<?php

namespace Wolfgang\Util\Logger;

use Wolfgang\Interfaces\Logger\IGelfMessage;

/**
 *
 * @package Components
* @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
final class GelfMessage extends Component implements IGelfMessage {
	protected $version = '1.1';
	protected $host;
	protected $short_message;
	protected $full_message;
	protected $timestamp;
	protected $level;
	protected $additional_fields = [ ];

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\IGelfMessage::getVersion()
	 */
	public function getVersion ( ): string {
		return $this->version;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\IGelfMessage::getHost()
	 */
	public function getHost ( ): string {
		return $this->host;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\IGelfMessage::getShortMessage()
	 */
	public function getShortMessage ( ): string {
		return $this->short_message;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\IGelfMessage::getFullMessage()
	 */
	public function getFullMessage ( ): string {
		return $this->full_message;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\IGelfMessage::getTimestamp()
	 */
	public function getTimestamp ( ): string {
		return $this->timestamp;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Logger\IGelfMessage::getLevel()
	 */
	public function getLevel ( ): int {
		return $this->level;
	}
}