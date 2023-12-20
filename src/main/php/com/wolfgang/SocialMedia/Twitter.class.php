<?php

namespace Wolfgang\SocialMedia;

use Abraham\TwitterOAuth\TwitterOAuth;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Config\SocialMedia;
use Wolfgang\Traits\TSingleton;

/**
 *
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @since Version 0.1.0
 */
final class Twitter extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var TwitterOAuth
	 */
	private $connection;
	
	protected function __construct ( ) {
		parent::__construct();
		
		$this->connection = new TwitterOAuth( SocialMedia::get( 'twitter.consumer_key' ), SocialMedia::get( 'twitter.consumer_secret' ), SocialMedia::get( 'twitter.access_token' ), SocialMedia::get( 'twitter.access_token_secret' ) );
	}
	
	/**
	 *
	 * @param array $options
	 */
	public function getTweets ( array $options = array()) {
		$tweets = [ ];
		try {
			$tweets = $this->connection->get( "statuses/user_timeline", [ 
					"count" => 5,
					"screen_name" => SocialMedia::get( 'twitter.user' )
			] );
		} catch ( \Exception $e ) {
		}
		return $tweets;
	}
}
