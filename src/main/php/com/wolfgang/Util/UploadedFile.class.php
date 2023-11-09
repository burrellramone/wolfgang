<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <rburrell@altmedia.ca>
 * @package Wolfgang\Util
 */
class UploadedFile extends File {
	private static $files = [ ];

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_OK = 0;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_INI_SIZE = 1;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_FORM_SIZE = 2;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_PARTIAL = 3;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_NO_FILE = 4;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_NO_TMP_DIR = 6;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_CANT_WRITE = 7;

	/**
	 *
	 * @var integer
	 */
	const UPLOAD_ERR_EXTENSION = 8;

	/**
	 *
	 * @var string
	 */
	protected $tmp_name;

	/**
	 *
	 * @var int
	 */
	protected $error;

	/**
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $extension
	 * @param string $tmp_name
	 * @param string $error
	 * @param string $size
	 */
	protected function __construct ( string $name, string $type, string $extension, string $tmp_name, string $error, string $size ) {
		parent::__construct( $name, $type, $extension, $tmp_name, $size );

		if ( ! file_exists( $tmp_name ) ) {
			throw new Exception( "File '{$tmp_name}' does not exist" );
		}

		$this->tmp_name = $tmp_name;
		$this->error = $error;
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
	 * @return string
	 */
	public function getTmpName ( ): string {
		return $this->tmp_name;
	}

	/**
	 *
	 * @return int
	 */
	public function getSize ( ): int {
		return $this->size;
	}

	/**
	 *
	 * @return UploadedFile|array|NULL
	 */
	public static function get ( string $input_name ) {
		$return_array = true;

		if ( empty( self::$files ) ) {
			// Initialize self::$files with contents of $_FILES
			foreach ( $_FILES as $file_input_name => $element ) {
				if ( ! is_array( $element[ 'name' ] ) ) {
					self::$files[ $file_input_name ] = [ 
							$element
					];

					$return_array = false;
				} else if ( ! is_array( $element[ 'name' ][ 0 ] ) ) {
					// seconday level
					//@formatter:off
					// 					Array
					// 					(
							// 						[0] => 1fe964f9-80b4-5e40-a539-ac18fcf4ecbf.png
							// 						[1] => RIM_Q3Results_2008_A.png
							// 					)
					//@formmatter:on
					
					foreach ( $element as $attribute => $element2 ) {
						foreach ( $element2 as $key => $value ) {
							self::$files [ $file_input_name ] [ $key ] [ $attribute ] = $value;
						}
					}
				} else {
					//tertiary level
					//@formatter:off
					// 					Array
					// 					(
							// 							[0] => Array
							// 							(
									// 									[0] => -.js
									// 									[1] => 1fe964f9-80b4-5e40-a539-ac18fcf4ecbf.png
									// 									)
					
									// 							[1] => Array
									// 							(
											// 									[0] => 584fc4a4-e918-5fba-817d-042c7486d67d.png
									// 									[1] => RIM_Q3Results_2008_A.png
									// 									)
											
							// 							)
					// 					)
											// @formatter:on
					foreach ( $element as $attribute => $element2 ) {
						foreach ( $element2 as $key => $values ) {
							foreach ( $values as $key2 => $value ) {
								self::$files[ $file_input_name ][ $key ][ $key2 ][ $attribute ] = $value;
							}
						}
					}
				}
			}
		}

		if ( ! array_key_exists( $input_name, self::$files ) ) {
			return null;
		}

		$files = self::$files[ $input_name ];

		self::toUploadedFile( $files );

		if ( $return_array ) {
			return $files;
		}

		if ( count( $files ) == 1 && ($files[ 0 ] instanceof UploadedFile) ) {
			return $files[ 0 ];
		}
	}

	/**
	 *
	 * @param array $array
	 */
	private static function toUploadedFile ( array &$array ) {
		if ( array_key_exists( 'name', $array ) && array_key_exists( 'type', $array ) && array_key_exists( 'tmp_name', $array ) && array_key_exists( 'error', $array ) && array_key_exists( 'size', $array ) ) {

			if ( $array[ 'error' ] ) {
				$code = $array[ 'error' ];
				throw new Exception( "Error uploading file '{$array['name']}'. " . self::getErrorMessageFromCode( $code ) );
			}

			$name = $type = $tmp_name = $error = $size = null;
			extract( $array );

			if ( $type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) {
				$extension = 'docx';
			} else if ( $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) {
				$extension = 'xlsx';
			} else {
				$extension = preg_replace( "/^([a-z]+)\//", "", $type );
			}

			$array = new UploadedFile( $name, $type, $extension, $tmp_name, $error, $size );
			return;
		}

		foreach ( $array as &$element ) {
			if ( is_array( $element ) ) {
				self::toUploadedFile( $element );
			}
		}
	}

	/**
	 *
	 * @return int
	 */
	public function getError ( ): int {
		return $this->error;
	}

	/**
	 *
	 * @return string
	 */
	public function getErrorMessage ( ): ?string {
		return self::getErrorMessageFromCode( $this->error );
	}

	public static function getErrorMessageFromCode ( int $code ) {
		switch ( $code ) {
			case self::UPLOAD_ERR_OK :
				return 'There is no error, the file uploaded with success.';
				break;

			case self::UPLOAD_ERR_INI_SIZE :
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
				break;

			case self::UPLOAD_ERR_FORM_SIZE :
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
				break;

			case self::UPLOAD_ERR_PARTIAL :
				return 'The uploaded file was only partially uploaded.';
				break;

			case self::UPLOAD_ERR_NO_FILE :
				return 'No file was uploaded.';
				break;

			case self::UPLOAD_ERR_NO_TMP_DIR :
				return 'Missing a temporary folder.';
				break;

			case self::UPLOAD_ERR_CANT_WRITE :
				return 'Failed to write file to disk. Introduced in PHP 5.1.0.';
				break;

			case self::UPLOAD_ERR_EXTENSION :
				return 'A PHP extension stopped the file upload.';
				break;

			default :
				throw new Exception( "Unknown error code {$code}" );
				break;
		}
	}
}
