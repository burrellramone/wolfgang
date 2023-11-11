<?php

namespace Wolfgang\Encryption;

use Wolfgang\Component as BaseComponent;
use Wolfgang\Exceptions\Encryption\OpenSslException;
use Wolfgang\Exceptions\IllegalArgumentException;

/**
 *
 * @package Wolfgang\Encryption
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @see https://tools.ietf.org/html/rfc5246#page-24
 * @since Version 1.0.0
 */
abstract class OpenSsl extends BaseComponent {
	const OPEN_SSL_PASSWORD = '8d85a81fb6dcadef22e9c8a376c7c1d9755c3fb567c706f4d84290982ad0dbda';
	
	// Cipher Methods
	const CIPHER_METHOD_AES_128_CBC = 'AES-128-CBC';
	const CIPHER_METHOD_AES_128_CBC_HMAC_SHA1 = 'AES-128-CBC-HMAC-SHA1';
	const CIPHER_METHOD_AES_128_CBC_HMAC_SHA256 = 'AES-128-CBC-HMAC-SHA256';
	const CIPHER_METHOD_AES_128_CFB = 'AES-128-CFB';
	const CIPHER_METHOD_AES_128_CFB1 = 'AES-128-CFB1';
	const CIPHER_METHOD_AES_128_CFB8 = 'AES-128-CFB8';
	const CIPHER_METHOD_AES_128_CTR = 'AES-128-CTR';
	const CIPHER_METHOD_AES_128_ECB = 'AES-128-ECB';
	const CIPHER_METHOD_AES_128_OFB = 'AES-128-OFB';
	const CIPHER_METHOD_AES_192_CBC = 'AES-192-CBC';
	const CIPHER_METHOD_AES_192_CFB = 'AES-192-CFB';
	const CIPHER_METHOD_AES_192_CFB1 = 'AES-192-CFB1';
	const CIPHER_METHOD_AES_192_CFB8 = 'AES-192-CFB8';
	const CIPHER_METHOD_AES_192_ECB = 'AES-192-ECB';
	const CIPHER_METHOD_AES_192_OFB = 'AES-192-OFB';
	const CIPHER_METHOD_AES_256_CBC = 'AES-256-CBC';
	const CIPHER_METHOD_AES_256_CBC_HMAC_SHA1 = 'AES-256-CBC-HMAC-SHA1';
	const CIPHER_METHOD_AES_256_CBC_HMAC_SHA256 = 'AES-256-CBC-HMAC-SHA256';
	const CIPHER_METHOD_AES_256_CFB = 'AES-256-CFB';
	const CIPHER_METHOD_AES_256_CFB1 = 'AES-256-CFB1';
	const CIPHER_METHOD_AES_256_CFB8 = 'AES-256-CFB8';
	const CIPHER_METHOD_AES_256_ECB = 'AES-256-ECB';
	const CIPHER_METHOD_AES_256_OFB = 'AES-256-OFB';
	const CIPHER_METHOD_BF_CBC = 'BF-CBC';
	const CIPHER_METHOD_BF_CFB = 'BF-CFB';
	const CIPHER_METHOD_BF_ECB = 'BF-ECB';
	const CIPHER_METHOD_BF_OFB = 'BF-OFB';
	const CIPHER_METHOD_CAST5_CBC = 'CAST5-CBC';
	const CIPHER_METHOD_CAST5_CFB = 'CAST5-CFB';
	const CIPHER_METHOD_CAST5_ECB = 'CAST5-ECB';
	const CIPHER_METHOD_CAST5_OFB = 'CAST5-OFB';
	const CIPHER_METHOD_DES_CBC = 'DES-CBC';
	const CIPHER_METHOD_DES_CFB = 'DES-CFB';
	const CIPHER_METHOD_DES_CFB1 = 'DES-CFB1';
	const CIPHER_METHOD_DES_CFB8 = 'DES-CFB8';
	const CIPHER_METHOD_DES_ECB = 'DES-ECB';
	const CIPHER_METHOD_DES_EDE = 'DES-EDE';
	const CIPHER_METHOD_DES_EDE_CBC = 'DES-EDE-CBC';
	const CIPHER_METHOD_DES_EDE_CFB = 'DES-EDE-CFB';
	const CIPHER_METHOD_DES_EDE_OFB = 'DES-EDE-OFB';
	const CIPHER_METHOD_DES_EDE3 = 'DES-EDE3';
	const CIPHER_METHOD_DES_EDE3_CBC = 'DES-EDE3-CBC';
	const CIPHER_METHOD_DES_EDE3_CFB = 'DES-EDE3-CFB';
	const CIPHER_METHOD_DES_EDE3_OFB = 'DES-EDE3-OFB';
	const CIPHER_METHOD_DES_OFB = 'DES-OFB';
	const CIPHER_METHOD_DESX_CBC = 'DESX-CBC';
	const CIPHER_METHOD_IDEA_CBC = 'IDEA-CBC';
	const CIPHER_METHOD_IDEA_CFB = 'IDEA-CFB';
	const CIPHER_METHOD_IDEA_ECB = 'IDEA-ECB';
	const CIPHER_METHOD_IDEA_OFB = 'IDEA-OFB';
	const CIPHER_METHOD_RC2_40_CBC = 'RC2-40-CBC';
	const CIPHER_METHOD_RC2_64_CBC = 'RC2-64-CBC';
	const CIPHER_METHOD_RC2_CBC = 'RC2-CBC';
	const CIPHER_METHOD_RC2_CFB = 'RC2-CFB';
	const CIPHER_METHOD_RC2_ECB = 'RC2-ECB';
	const CIPHER_METHOD_RC2_OFB = 'RC2-OFB';
	const CIPHER_METHOD_RC4 = 'RC4';
	const CIPHER_METHOD_RC4_40 = 'RC4-40';
	
	// Digest Methods
	const DIGEST_METHOD_DSA = 'DSA';
	const DIGEST_METHOD_DSA_SHA = 'DSA-SHA';
	const DIGEST_METHOD_MD2 = 'MD2';
	const DIGEST_METHOD_MD4 = 'MD4';
	const DIGEST_METHOD_MD5 = 'MD5';
	const DIGEST_METHOD_RIPEMD160 = 'RIPEMD160';
	const DIGEST_METHOD_SHA = 'SHA';
	const DIGEST_METHOD_SHA1 = 'SHA1';
	const DIGEST_METHOD_SHA224 = 'SHA224';
	const DIGEST_METHOD_SHA256 = 'SHA256';
	const DIGEST_METHOD_SHA384 = 'SHA384';
	const DIGEST_METHOD_SHA512 = 'SHA512';
	
	/**
	 *
	 * @param string $data The data that is to be encrypted
	 * @param string $method The cipher method to use in encrypting the data
	 * @param string $password
	 * @param int $options
	 * @param string $iv
	 * @param string &$tag
	 * @param string $additional_authentication_data
	 * @param number $tag_length
	 * @throws IllegalArgumentException
	 * @throws OpenSslException
	 * @return string
	 */
	public static function encrypt ( $data, $method = OpenSsl::CIPHER_METHOD_AES_256_ECB, $password = OpenSsl::OPEN_SSL_PASSWORD, $options = 0, $iv = '', &$tag = NULL, $additional_authentication_data = '', $tag_length = 16): string {
		if ( empty( $data ) ) {
			throw new IllegalArgumentException( 'Data to be encrypted must be provided' );
		}
		
		//ecb mode does not use/require an IV
		if ( empty( $iv ) && !preg_match("/ecb/i", $method) ) {
			$iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( $method ) );
		}
		
		$password = openssl_digest( $password, OpenSsl::DIGEST_METHOD_SHA256 );
		
		// Counter with CBC-MAC and Galois Counter Mode
		if ( preg_match( "/(CCM|GCM)/i", $method ) ) {
			$encrypted_data = openssl_encrypt( $data, $method, $password, $options, $iv, $tag, $additional_authentication_data, $tag_length );
		} else {
			$encrypted_data = openssl_encrypt( $data, $method, $password, $options, $iv );
		}
		
		if ( empty( $encrypted_data ) ) {
			throw new OpenSslException( "Could not encrypt data using method '{$method}'" );
		}
		
		return $encrypted_data;
	}
	
	/**
	 *
	 * @param string $encrypted_data
	 * @param string $method
	 * @param string $password
	 * @param number $options
	 * @param string $iv
	 * @param string $tag
	 * @param string $additional_authentication_data
	 * @throws IllegalArgumentException
	 * @throws OpenSslException
	 * @return string
	 */
	public static function decrypt ( $encrypted_data, $method = OpenSsl::CIPHER_METHOD_AES_256_ECB, $password = OpenSsl::OPEN_SSL_PASSWORD, $options = 0, $iv = '', $tag = '', $additional_authentication_data = ''): string {
		if ( empty( $encrypted_data ) ) {
			throw new IllegalArgumentException( 'Data to be decrypted must be provided' );
		}
		
		//ecb mode does not use/require an IV
		if ( empty( $iv ) && !preg_match("/ecb/i", $method) ) {
			$iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( $method ) );
		}
		
		$password = openssl_digest( $password, OpenSsl::DIGEST_METHOD_SHA256 );
		
		// Counter with CBC-MAC and Galois Counter Mode
		if ( preg_match( "/(CCM|GCM)/i", $method ) ) {
			$decrypted_data = openssl_decrypt( $encrypted_data, $method, $password, $options, $iv, $tag, $additional_authentication_data );
		} else {
			$decrypted_data = openssl_decrypt( $encrypted_data, $method, $password, $options, $iv );
		}
		
		if ( empty( $decrypted_data ) ) {
			throw new OpenSslException( "Could not decrypt data using method '{$method}'" );
		}
		
		return $decrypted_data;
	}
	
	public static function randomPseudoBytes ( int $length = 32, &$crypto_strong = ""): string {
		return openssl_random_pseudo_bytes( $length, $crypto_strong );
	}
}
