<?php

namespace Wolfgang\Util;

use Wolfgang\Exceptions\InvalidArgumentException;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @package Wolfgang\Util
 * @since Version 1.0.0
 */
final class Strings extends Component {

	/**
	 *
	 * @param array $items
	 * @param string $glue
	 * @return string
	 */
	public static function quoteArray ( array $items, $glue = ",") {
		$str = '';
		foreach ( $items as $key => $item ) {
			$str .= "\"" . $item . "\"";
			if ( $key < (count( $items ) - 1) ) {
				$str .= $glue;
			}
		}
		return $str;
	}

	public static function ucwords ( ) {
		return call_user_func_array( 'ucwords', func_get_args() );
	}

	public static function filterVar ( ) {
		$parameters = func_get_args();

		if ( isset( $parameters[ 1 ] ) && $parameters[ 1 ] === FILTER_VALIDATE_PHONE ) {
			return preg_match( "/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/", $parameters[ 0 ] );
		}

		return call_user_func_array( 'filter_var', $parameters );
	}

	/**
	 *
	 * @param string $email
	 * @return number
	 */
	public static function validateEmail ( string $email ) {
		return self::filterVar( $email, FILTER_VALIDATE_EMAIL );
	}

	/**
	 *
	 * @param mixed $remove
	 * @param string $subject
	 * @param mixed|null $replacement
	 * @throws InvalidArgumentException
	 */
	public static function remove ( $remove, $subject, $replacement = null): string {
		if ( empty( $remove ) ) {
			throw new InvalidArgumentException( "String(s)/Expression(s) to remove not provided" );
		} else if ( ! is_array( $remove ) && ! is_string( $remove ) && ! is_numeric( $replacement ) ) {
			throw new InvalidArgumentException( "String(s)/Expression(s) to remove must be an array, a string or numeric" );
		} else if ( ! empty( $replacement ) && (! is_array( $replacement )) && ! is_string( $replacement ) && ! is_numeric( $replacement ) ) {
			throw new InvalidArgumentException( "Replacement must be an array, a string or numeric" );
		}

		if ( ! is_array( $remove ) ) {
			$remove = [ 
					$remove
			];
		}

		foreach ( $remove as $key => $r ) {
			if ( ($r instanceof Regex) ) {
			} else {
				if ( isset( $replacement ) ) {
					if ( is_array( $replacement ) ) {
						if ( ! isset( $replacement[ $key ] ) ) {
							throw new InvalidArgumentException( "Corresponding resplacement for string to remove '{$r}' not found" );
						}

						$rep = $replacement[ $key ];
						$subject = str_replace( $r, $rep, $subject );
					} else {
						$subject = str_replace( $r, $replacement, $subject );
					}
				} else {
					$subject = str_replace( $r, "", $subject );
				}
			}
		}

		return $subject;
	}

	public static function isInetIpAddress ( string $ip_address ): bool {
		return preg_match( "/^(192|127)/", $ip_address );
	}
}
