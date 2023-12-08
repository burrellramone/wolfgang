<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\Exception;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 */
final class LocalFile extends File {

	/**
	 *
	 * @throws Exception
	 * @return LocalFile.class|NULL
	 */
	public static function get ( string $file_path ): ?LocalFile {
		if ( ! $file_path ) {
			throw new Exception( "File path not provided" );
		} else if ( ! file_exists( $file_path ) ) {
			throw new Exception( "File '{$file_path}' does not exist" );
		}

		$name = basename( $file_path );

		$finfo = new \finfo();
		$type = $finfo->file( $file_path, FILEINFO_MIME_TYPE );

		$size = filesize( $file_path );

		if ( $type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ) {
			$extension = 'docx';
		} else if ( $type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) {
			$extension = 'xlsx';
		} else {
			$extension = preg_replace( "/^([a-z]+)\//", "", $type );
		}

		$file = new LocalFile( $name, $type, $extension, $file_path, $size );

		return $file;
	}
}
