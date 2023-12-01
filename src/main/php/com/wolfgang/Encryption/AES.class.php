<?php

namespace Wolfgang\Encryption;

/**
 *
 * @package Wolfgang\Encryption
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 1.0.0
 */
final class AES extends OpenSsl {
	const AES_PASSWORD = 'e57733e10d128a0e1d24875d6e7479af8af146d4bbc272e444e7c3f7a1e83eae';

	/**
	 *
	 * @param string $data
	 * @param string $method
	 * @param string $password
	 * @param int $options
	 * @param string $iv
	 * @param string $tag
	 * @param string $additional_authentication_data
	 * @param number $tag_length
	 * @return string
	 */
	public static function encrypt ( $data, $method = OpenSsl::CIPHER_METHOD_AES_256_ECB, $password = AES::AES_PASSWORD, $options = 0, $iv = '', &$tag = NULL, $additional_authentication_data = '', $tag_length = 16 ): string {
		return parent::encrypt( $data, $method, $password, $options, $iv, $tag, $additional_authentication_data, $tag_length );
	}

	/**
	 *
	 * @param string $data
	 * @param string $method
	 * @param string $password
	 * @param int $options
	 * @param string $iv
	 * @param string $tag
	 * @param string $additional_authentication_data
	 * @return string
	 */
	public static function decrypt ( $data, $method = OpenSsl::CIPHER_METHOD_AES_256_ECB, $password = AES::AES_PASSWORD, $options = 0, $iv = '', $tag = NULL, $additional_authentication_data = '' ): string {
		return parent::decrypt( $data, $method, $password, $options, $iv, $tag, $additional_authentication_data );
	}
}