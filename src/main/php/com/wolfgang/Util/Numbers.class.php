<?php

namespace Wolfgang\Util;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.com>
 * @since Version 0.1.0
 */
final class Numbers extends Component {

	/**
	 *
	 * @param float $old_value
	 * @param float $new_value
	 * @return int|NULL
	 */
	public static function calcPercentChange ( float $old_value, float $new_value ): ?int {
		$percent_change = null;

		$old_value = ( float ) $old_value;
		$new_value = ( float ) $new_value;

		if ( $old_value && $new_value === 0.0 ) {
			$percent_change = - 100;
		} else if ( $old_value == 0.0 && $new_value ) {
			$percent_change = 100;
		} else if ( $old_value == $new_value ) {
			$percent_change = 0;
		} else {
			$percent_change = round( ((($new_value / $old_value) * 100) - 100), 0 );

			if ( $old_value < 0 || $new_value < 0 ) {
				if ( $new_value > $old_value ) {
					if ( $percent_change < 0 ) {
						$percent_change = abs( $percent_change );
					}
				} else if ( $new_value < $old_value ) {
					if ( $percent_change > 0 ) {
						$percent_change = $percent_change - ($percent_change * 2);
					}
				}
			}
		}

		return $percent_change;
	}
}
