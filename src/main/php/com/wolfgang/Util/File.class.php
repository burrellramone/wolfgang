<?php

namespace Wolfgang\Util;

use Wolfgang\Interfaces\Util\IFile as IFileUtility;
use Wolfgang\BaseObject;
use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell
 * @package Wolfgang\Util
 */
abstract class File extends BaseObject implements IFileUtility {

	/**
	 *
	 * @var string
	 */
	protected $name;

	/**
	 *
	 * @var string
	 */
	protected $type;

	/**
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 *
	 * @var string
	 */
	protected $path;

	/**
	 *
	 * @var int
	 */
	protected $size = 0;

	/**
	 *
	 * @var int
	 */
	protected $width = 0;

	/**
	 *
	 * @var int
	 */
	protected $height = 0;

	/**
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $extension
	 * @param string $path
	 * @param string $size
	 * @throws Exception
	 */
	protected function __construct ( string $name, string $type, string $extension, string $path, string $size ) {
		parent::__construct();

		if ( ! $name ) {
			throw new Exception( "File name not provided" );
		} else if ( ! $type ) {
			throw new Exception( "File type not provided" );
		} else if ( ! $extension ) {
			throw new Exception( "File extension not provided" );
		}
		if ( ! $path ) {
			throw new Exception( "File path not provided" );
		} else if ( ! file_exists( $path ) ) {
			throw new Exception( "File '{$path}' does not exist" );
		}

		$this->name = $name;
		$this->type = $type;
		$this->extension = $extension;
		$this->path = $path;
		$this->size = $size;

		if ( preg_match( "/(image\/)/", $this->type ) ) {
			$size = getimagesize( $this->path );
			$this->width = $size[ 0 ];
			$this->height = $size[ 1 ];
		}
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getName()
	 */
	public function getName ( ): string {
		return $this->name;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getType()
	 */
	public function getType ( ): string {
		return $this->type;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getPath()
	 */
	public function getPath ( ): string {
		return $this->path;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getSize()
	 */
	public function getSize ( ): int {
		return $this->size;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getExtension()
	 */
	public function getExtension ( ): string {
		return $this->extension;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getWidth()
	 */
	public function getWidth ( ): int {
		return $this->width;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Interfaces\Util\IFile::getHeight()
	 */
	public function getHeight ( ): int {
		return $this->height;
	}
}