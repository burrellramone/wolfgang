<?php

namespace Wolfgang\SQL;

use Wolfgang\ORM\Component;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Cache\Cacher;
use Wolfgang\Interfaces\SQL\Statement\IStatement;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @package Wolfgang\SQL
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @since Version 1.0.0
 */
final class StatementManager extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var array
	 */
	public $statements = [ ];
	
	/**
	 *
	 * @var string
	 */
	private $statement_cache_key = 'statements.<statement_sha1>';
	
	/**
	 *
	 * @param IStatement $statement
	 * @return string A string representation of the satatement that was stored in this statement
	 *         manager
	 */
	public function put ( IStatement $statement ): string {
		ob_start();
		var_dump( $statement );
		$ob = ob_get_clean();
		$ob = preg_replace( "/:[\d]+:/", "", $ob );
		// echo "<br/><br/>PUTTING AS " . $ob;
		$key = sha1( $ob );
		$key = str_replace( "<statement_sha1>", $key, $this->statement_cache_key );
		
		// echo "PUT KEY WAS " . $key . "\n";
		$this->statements[ $key ] = ( string ) $statement;
		// echo "Stored value {$this->statements[$key]}\n";
		Cacher::getInstance()->set( $key, $this->statements[ $key ] );
		
		return $this->statements[ $key ];
	}
	
	/**
	 *
	 * @param IStatement $statement
	 * @return string|null
	 */
	public function get ( IStatement $statement ): ?string {
		ob_start();
		var_dump( $statement );
		$ob = ob_get_clean();
		
		$ob = preg_replace( "/:[\d]+:/", "", $ob );
		// echo "<br/><br/>GETTING AS " . $ob;
		$key = sha1( $ob );
		$key = str_replace( "<statement_sha1>", $key, $this->statement_cache_key );
		
		// echo "GET KEY IS " . $key . "\n";
		// if ( $key == 'statements.cd012b3a3bcc1d3c2442c086f3639139e8b9b7ae' ) {
		// print_r( $this->statements );
		// }
		
		if ( ! empty( $this->statements[ $key ] ) ) {
			echo "FOUND {$this->statements[ $key ]}\n";
			// return $this->statements[ $key ];
		} else {
			// echo "NOT FOUND {$key}\n";
		}
		
		// Look in cache for the statement
		$value = Cacher::getInstance()->get( $key );
		
		if ( $value ) {
			$this->statements[ $key ] = $value;
		}
		
		return $value;
	}
}
