<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Filesystem\Exception as FilesystemException;
use Wolfgang\Exceptions\Filesystem\FileNotExistException;
use Wolfgang\Exceptions\IllegalArgumentException;
use Wolfgang\Date\DateTime;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Network\Uri\DataUri;

/**
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @package Wolfgang\Util
 * @since Version 1.0.0
 */
final class Filesystem extends Component {

	/**
	 *
	 * @param string $filepath
	 * @throws FilesystemException
	 * @throws FileNotExistException
	 * @return string
	 */
	public static function getContents ( $filepath ) {
		if ( empty( $filepath ) ) {
			throw new FilesystemException( "File path not provided" );
		} else if ( is_string( $filepath ) && ! self::exists( $filepath ) ) {
			throw new FileNotExistException( "File '{$filepath}' does not exist" );
		}

		if ( ($contents = @file_get_contents( $filepath )) === FALSE ) {
			throw new FilesystemException( "Unable to read contents from file '{$filepath}'" );
		}

		return $contents;
	}

	/**
	 *
	 * @param string $filepath
	 * @param string $contents
	 * @param number $flags
	 * @throws IllegalargumentException
	 * @throws FilesystemException
	 * @return bool
	 */
	public static function putContents ( $filepath, $contents, $flags = null): bool {
		if ( empty( $filepath ) ) {
			throw new IllegalargumentException( "File path not provided" );
		} else if ( empty( $contents ) ) {
			throw new FilesystemException( "Contents not provided" );
		}

		if ( ! self::exists( $filepath ) ) {
			return self::create( $filepath, $contents, $flags );
		}

		if ( file_put_contents( $filepath, $contents, $flags ) === false ) {
			throw new FilesystemException( "Could not put contents to file '{$filepath}'" );
		} else if ( ! self::exists( $filepath ) ) {
			throw new FileNotExistException( "Could not put contents to file '{$filepath}'" );
		}

		return true;
	}

	/**
	 * Writes a string to a file. This method will force the flag LOCK_EX onto the $flags arguments
	 * passed.
	 *
	 * @param string $filepath A relative or absolute path to the file to write to
	 * @param string|array|resource $data The data to write to the file
	 * @param int|null $flags The flags to use in writing to the file. The list of acceptable flags
	 *        are FILE_USE_INCLUDE_PATH, FILE_APPEND and LOCK_EX
	 * @param int|null $mode The mode the file should be changed to after creation, null if the mode
	 *        should not be changed
	 * @throws FilesystemException Throws a FilesystemException if the file already exists or if
	 *         there was an error creating the physical file
	 * @return boolean
	 */
	public static function create ( string $filepath, $data = '', $flags = null, $mode = null) {
		if ( self::exists( $filepath ) ) {
			throw new FilesystemException( "File '{$filepath}' already exists." );
		}

		$flags = $flags | LOCK_EX;

		if ( file_put_contents( $filepath, $data, $flags ) === false ) {
			throw new FilesystemException( "Could not put contents to file '{$filepath}'" );
		}

		if ( $mode !== null ) {
			self::chmod( $filepath, $mode );
		}

		return true;
	}

	/**
	 *
	 * @param string $filepath
	 * @param int $mode
	 * @throws FileNotExistException
	 * @throws FilesystemException
	 * @return boolean
	 */
	public static function chmod ( string $filepath, int $mode ) {
		if ( ! self::exists( $filepath ) ) {
			throw new FileNotExistException( "The file '{$filepath}' does not exist." );
		}

		if ( ! chmod( $filepath, $mode ) ) {
			throw new FilesystemException( "Error while changing mode of file '{$filepath}'" );
		}

		return true;
	}

	/**
	 *
	 * @param string $data_uri
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public static function extFromDataURI ( string $data_uri ): string {
		if ( empty( $data_uri ) ) {
			throw new InvalidArgumentException( "Data URI not provided" );
		}

		$data_uri = new DataUri( $data_uri );

		return $data_uri->getExtension();
	}

	/**
	 *
	 * @param string $filepath
	 * @throws IllegalArgumentException
	 * @throws FilesystemException
	 * @return DateTime
	 */
	public static function getModifiedDateTime ( string $filepath ): DateTime {
		if ( empty( $filepath ) ) {
			throw new IllegalArgumentException( "File path not provided" );
		} else if ( file_exists( $filepath ) ) {
			throw new FilesystemException( "File '{$filepath}' does not exist" );
		}

		$timestamp = filemtime( $filepath );

		if ( ! $timestamp ) {
			return $timestamp;
		}

		$modfied_date_time = new DateTime( date( 'Y-m-d H:i:s', $timestamp ) );

		return $modfied_date_time;
	}

	/**
	 *
	 * @param string $filepath
	 * @return boolean
	 */
	public static function unlink ( string $filepath ) {
		if ( file_exists( $filepath ) ) {
			return unlink( $filepath );
		}

		return false;
	}

	/**
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public static function exists ( string $filename ): bool {
		return file_exists( $filename );
	}

	/**
	 *
	 * @link http://www.php.net/manual/en/function.mkdir.php
	 * @param string $pathname
	 * @param int $mode
	 * @param bool $recursive
	 * @param resource $context
	 */
	public static function makeDirectory ( $pathname, $mode = 0777, $recursive = true, $context = null) {
		if ( self::exists( $pathname ) ) {
			return true;
		}

		if ( ($context) ) {
			if ( ! mkdir( $pathname, $mode, $recursive, $context ) ) {
				throw new FilesystemException( "Unable to create directory '{$pathname}'" );
			}
		} else {
			if ( ! mkdir( $pathname, $mode, $recursive ) ) {
				throw new FilesystemException( "Unable to create directory '{$pathname}'" );
			}
		}
	}

	/**
	 *
	 * @see http://php.net/manual/en/function.glob.php Documentation of PHP's 'glob' function
	 * @param string $pattern
	 * @param $flags
	 */
	public static function glob ( string $pattern, int $flags = 0) {
		return glob( $pattern, $flags );
	}
}
