<?php

namespace Wolfgang\Network\Uri;

use Wolfgang\Interfaces\Network\IDataUri;
use Wolfgang\Encoding\Base64;

/**
 *
* @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class DataUri extends Uri implements IDataUri {
	
	/**
	 *
	 * @var string
	 */
	protected $mime_type;
	
	/**
	 *
	 * @var string
	 */
	protected $extension;
	
	/**
	 *
	 * @var string
	 */
	protected $data;
	
	/**
	 *
	 * @param string $uri
	 */
	public function __construct ( string $uri ) {
		parent::__construct( $uri );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Network\Uri\Uri::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$path = explode( ';', $this->getPath() );
		$path[ 1 ] = str_replace( "base64,", '', $path[ 1 ] );
		
		$this->setMimeType( $path[ 0 ] );
		$this->setExtension( explode( '/', $path[ 0 ] )[ 1 ] );
		$this->setData( $path[ 1 ] );
	}
	
	/**
	 *
	 * @param string $mime_type
	 */
	private function setMimeType ( string $mime_type ) {
		$this->mime_type = $mime_type;
	}
	
	/**
	 *
	 * @param string $extension
	 */
	private function setExtension ( string $extension ) {
		$this->extension = $extension;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getExtension ( ): string {
		return $this->extension;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDataUri::getMediaType()
	 */
	public function getMediaType ( ): string {
		return $this->getMimeType();
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDataUri::getMimeType()
	 */
	public function getMimeType ( ): string {
		return $this->mime_type;
	}
	
	/**
	 *
	 * @param string $data
	 */
	private function setData ( string $data ) {
		$this->data = $data;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDataUri::getData()
	 */
	public function getData ( ): string {
		return $this->data;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Network\IDataUri::getBinaryData()
	 */
	public function getBinaryData ( ) {
		return Base64::decode( $this->getData() );
	}
}
