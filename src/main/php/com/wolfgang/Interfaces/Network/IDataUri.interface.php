<?php

namespace Wolfgang\Interfaces\Network;

/**
 *
 * @author ramoneb
 */
interface IDataUri extends IUri {

	/**
	 *
	 * @return string
	 */
	public function getMediaType ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getMimeType ( ): string;

	/**
	 *
	 * @return string
	 */
	public function getData ( ): string;

	/**
	 */
	public function getBinaryData ( );
}
