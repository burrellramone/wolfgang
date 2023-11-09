<?php

namespace Wolfgang\Extension\Model;

use Wolfgang\Extension\Model\Extension as WolfgangModelExtension;
use Wolfgang\Model\SkinDomainList;

/**
 *
 * @package Wolfgang\Extension\Model
 * @author Ramone Burrell <ramoneb@airportruns.ca>
 * @link http://airportruns.ca
 * @since Version 1.0.0
 */
final class WhiteLabel extends WolfgangModelExtension {
	
	/**
	 *
	 * @var SkinDomainList
	 */
	protected $skin_domain_list;
	
	/**
	 *
	 * @return SkinDomainList
	 */
	public function getSkinDomains ( ): SkinDomainList {
		if ( empty( $this->skin_domain_list ) || ! $this->skin_domain_list->count() ) {
			$this->skin_domain_list = new SkinDomainList();
			$this->skin_domain_list->where( [ 
					"white_label_id" => $this->getSubject()->getId()
			] )->limit( 50 );
		}
		return $this->skin_domain_list;
	}
}
