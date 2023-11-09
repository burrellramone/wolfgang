<?php

namespace Wolfgang\Test\Mailing;

use Wolfgang\Test\Test;
use Wolfgang\Model\SkinDomainList;
use Wolfgang\ORM\SchemaManager;

/**
 *
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 */
final class SkinDomainListTest extends Test {
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\Test\Test::setUp()
	 */
	protected function setUp ( ) {
		parent::setUp();
	}
	
	public function testCanFindAndIdentifyAll ( ) {
		$statement = "SELECT * FROM skin_domain";
		$result = SchemaManager::getInstance()->getDefaultSchema()->getConnection()->exec( $statement );
		
		$skin_domain_list = new SkinDomainList();
		$skin_domain_list->limit( 100 );
		
		foreach ( $result as $record ) {
			$skin_domain_matched = false;
			foreach ( $skin_domain_list as $skin_domain ) {
				if ( $skin_domain->id == $record[ 'id' ] ) {
					$skin_domain_matched = true;
					break;
				}
			}
			
			$this->assertTrue( $skin_domain_matched, "Could not find matching skin domain with id {$record['id']} in skin domain model list" );
		}
	}
}