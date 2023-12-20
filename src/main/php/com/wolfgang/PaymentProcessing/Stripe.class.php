<?php

namespace Wolfgang\PaymentProcessing;

// Stripe
use Stripe\Plan as StripePlan;
use Stripe\Card as StripeCard;
use Stripe\Stripe as StripeLib;
use Stripe\Refund as StripeRefund;
use Stripe\Charge as StripeCharge;
use Stripe\Customer as StripeCustomer;
use Stripe\Error\Card as StripeCardError;
use Stripe\BankAccount as StripeBankAccount;
use Stripe\Subscription as StripeSubscription;
use Stripe\Error\InvalidRequest as InvalidStripeRequest;
// Wolfgang
use Wolfgang\Traits\TSingleton;
use Wolfgang\Interfaces\ISingleton;
use Wolfgang\Interfaces\IStripeCustomer;
use Wolfgang\Exceptions\InvalidStateException;
use Wolfgang\Exceptions\InvalidArgumentException;
use Wolfgang\Exceptions\Exception as WolfgangException;
use Wolfgang\Config\PaymentProcessing as PaymentProcessingConfig;
use Wolfgang\Exceptions\PaymentProcessing\Exception as PaymentProcessingException;
use Wolfgang\Exceptions\PaymentProcessing\Stripe\Exception as StripePaymentProcessingException;

/**
 *
 * @uses \Stripe\Stripe()
 * @author Ramone Burrell <ramone@ramoneburrell.com>
 * @see Version 1.0.0
 */
final class Stripe extends Component implements ISingleton {
	use TSingleton;
	
	/**
	 *
	 * @var StripeLib
	 */
	private $stripe;
	
	/**
	 *
	 * @var array
	 */
	private $stripe_config = [ ];
	
	/**
	 *
	 * {@inheritdoc}
	 * @see \Wolfgang\BaseObject::init()
	 */
	protected function init ( ) {
		parent::init();
		
		$this->setConfig( PaymentProcessingConfig::get( 'stripe' ) );
		
		$this->stripe = new \Stripe\Stripe();
		$this->stripe->setApiKey( $this->config[ 'secret_key' ] );
	}
	
	/**
	 *
	 * @param array $config
	 */
	private function setConfig ( array $config ) {
		$this->validateConfig( $config );
		$this->config = $config;
	}
	
	/**
	 *
	 * @param array $config
	 * @throws InvalidArgumentException
	 */
	private function validateConfig ( array $config ): void {
		if ( empty( $config ) ) {
			throw new InvalidArgumentException();
		} else if ( empty( $config[ 'secret_key' ] ) ) {
			throw new InvalidArgumentException( "Stripe secret key not defined in configuration" );
		} else if ( empty( $config[ 'publishable_key' ] ) ) {
			throw new InvalidArgumentException( "Stripe publishable key not defined in configuration" );
		} else if ( empty( $config[ 'trial_end' ] ) ) {
			throw new InvalidArgumentException( "Stripe trial duration not defined in configuration" );
		} else if ( ! strtotime( $config[ 'trial_end' ] ) ) {
			throw new InvalidArgumentException( "Stripe trial duration specified in configuration is not valid" );
		}
	}
	
	/**
	 *
	 * @param array $options
	 * @throws WolfgangException
	 * @throws PaymentProcessingException
	 * @return string
	 */
	public function addCustomer ( array $options ): string {
		if ( empty( $options ) ) {
			throw new WolfgangException( "Options to create new stripe customer not provided" );
		}
		
		try {
			$stripe_cus_object = StripeCustomer::create( $options );
			return $stripe_cus_object->id;
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to add stripe customer", 0, $e );
		}
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 * @throws PaymentProcessingException
	 * @return StripeCustomer|NULL
	 */
	public function getCustomer ( IStripeCustomer $wolfgang_stripe_customer ): ?StripeCustomer {
		if ( ! $wolfgang_stripe_customer->getStripeCustomerId() ) {
			throw new InvalidArgumentException( "Stripe customer provided does not have an id" );
		}
		
		$stripe_cus_object = null;
		
		try {
			$stripe_cus_object = StripeCustomer::retrieve( $wolfgang_stripe_customer->getStripeCustomerId() );
			
			if ( ! $stripe_cus_object ) {
				throw new InvalidStateException( "Unable to find stripe customer with id '{$wolfgang_stripe_customer->getStripeCustomerId()}'" );
			}
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to retrieve stripe customer", 0, $e );
		}
		
		return $stripe_cus_object;
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @throws InvalidStateException
	 * @throws PaymentProcessingException
	 * @return boolean
	 */
	public function deleteCustomer ( IStripeCustomer $wolfgang_stripe_customer ): StripeCustomer {
		if ( ! $wolfgang_stripe_customer->getStripeCustomerId() ) {
			throw new InvalidArgumentException( "Stripe customer provided does not have an id" );
		}
		
		$customer = null;
		
		try {
			
			$customer = $this->getCustomer( $wolfgang_stripe_customer->getStripeCustomerId() );
			$customer->delete();
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( $e->getMessage(), 0, $e );
		}
		
		return $customer;
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @return string
	 */
	public function addTrialCustomer ( IStripeCustomer $wolfgang_stripe_customer ): string {
		return $this->addCustomer( array (
				"description" => $wolfgang_stripe_customer->getName(),
				"email" => $wolfgang_stripe_customer->getEmail(),
				"trial_end" => strtotime( $this->config[ 'trial_end' ] )
		) );
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @param string $stripe_token
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 * @throws PaymentProcessingException
	 * @return StripeCard
	 */
	public function addCard ( IStripeCustomer $wolfgang_stripe_customer, string $stripe_token ): StripeCard {
		if ( ! $wolfgang_stripe_customer->getStripeCustomerId() ) {
			throw new InvalidArgumentException( "Stripe customer provided does not have an id" );
		} else if ( ! $stripe_token ) {
			throw new InvalidArgumentException( "Stripe token not provided " );
		}
		
		$stripe_card_object = null;
		
		try {
			
			$cus = $this->getCustomer( $wolfgang_stripe_customer->getStripeCustomerId() );
			
			if ( ! $cus ) {
				throw new InvalidStateException( "Cannot add card for non-existent customer" );
			}
			
			$stripe_card_object = $cus->sources->create( array (
					"card" => $stripe_token
			) );
		} catch ( StripeCardError $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to add a new card for a customer", 0, $e );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to add a new card for a customer", 0, $e );
		}
		
		return $stripe_card_object;
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @param string $stripe_card_id
	 * @param string $name
	 * @param int $exp_year
	 * @param int $exp_month
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 * @throws PaymentProcessingException
	 * @return StripeCard
	 */
	public function updateCard ( IStripeCustomer $wolfgang_stripe_customer, string $stripe_card_id, string $name, int $exp_year, int $exp_month ): StripeCard {
		if ( ! $wolfgang_stripe_customer->getStripeCustomerId() ) {
			throw new InvalidArgumentException( "Stripe customer provided does not have an id" );
		} else if ( ! $stripe_card_id ) {
			throw new InvalidArgumentException( "Stripe card id not provided" );
		}
		
		$stripe_card_object = null;
		
		try {
			$customer = $this->getCustomer( $wolfgang_stripe_customer->getStripeCustomerId() );
			
			if ( ! $customer ) {
				throw new InvalidStateException( "Unable to find stripe customer with id '{$wolfgang_stripe_customer->getStripeCustomerId()}'" );
			}
			
			$stripe_card_object = $customer->cards->retrieve( $stripe_card_id );
			
			if ( ! $stripe_card_object ) {
				throw new InvalidStateException( "Unable to find stripe card with id '{$stripe_card_id}'" );
			}
			
			$stripe_card_object->name = $name;
			$stripe_card_object->exp_year = $exp_year;
			$stripe_card_object->exp_month = $exp_month;
			
			$stripe_card_object = $stripe_card_object->save();
		} catch ( StripeCardError $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to update stripe card", 0, $e );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to update stripe card", 0, $e );
		}
		
		return $stripe_card_object;
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @param string $stripe_card_id
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 * @throws PaymentProcessingException
	 * @return StripeCard
	 */
	public function deleteCard ( IStripeCustomer $wolfgang_stripe_customer, string $stripe_card_id ): StripeCard {
		if ( ! $wolfgang_stripe_customer->getStripeCustomerId() ) {
			throw new InvalidArgumentException( "Stripe customer provided does not have an id" );
		} else if ( ! $stripe_card_id ) {
			throw new InvalidArgumentException( "Stripe card id not provided" );
		}
		
		$stripe_card_object = null;
		
		try {
			$customer = $this->getCustomer( $wolfgang_stripe_customer->getStripeCustomerId() );
			
			if ( ! $customer ) {
				throw new InvalidStateException( "Unable to find stripe customer with id '{$wolfgang_stripe_customer->getStripeCustomerId()}'" );
			}
			
			$stripe_card_object = $customer->cards->retrieve( $stripe_card_id );
			
			if ( ! $stripe_card_object ) {
				throw new InvalidStateException( "Unable to find stripe card with id '{$stripe_card_id}'" );
			}
			
			$stripe_card_object = $stripe_card_object->delete();
		} catch ( StripeCardError $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to delete stripe card", 0, $e );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to delete stripe card", 0, $e );
		}
		
		return $stripe_card_object;
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @param array $params
	 * @throws InvalidArgumentException
	 * @throws PaymentProcessingException
	 * @return StripeCharge
	 */
	public function charge ( IStripeCustomer $wolfgang_stripe_customer, array $params ): StripeCharge {
		if ( ! $wolfgang_stripe_customer->getStripeCustomerId() ) {
			throw new InvalidArgumentException( "Stripe customer provided does not have an id" );
		} else if ( empty( $params ) ) {
			throw new InvalidArgumentException( "Stripe charge params not provided" );
		}
		
		try {
			$stripe_charge_object = StripeCharge::create( $params );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to create a stripe charge", 0, $e );
		}
		
		return $stripe_charge_object;
	}
	
	/**
	 *
	 * @param string $stipe_charge_id
	 * @param string $reason
	 * @return boolean
	 */
	public function refund ( string $stripe_charge_id, string $reason = "requested_by_customer"): StripeRefund {
		if ( ! $stripe_charge_id ) {
			throw new InvalidArgumentException( "Stripe charge id not provided" );
		} else if ( ! $reason ) {
			throw new InvalidArgumentException( "Stripe refund reason not provided" );
		}
		
		$refund = null;
		$stripe_charge_object = null;
		
		try {
			$stripe_charge_object = StripeCharge::retrieve( $stripe_charge_id );
			
			if ( ! $stripe_charge_object ) {
				throw new InvalidStateException( "Unable to find tripe charge with id '{$stripe_charge_id}'" );
			}
			
			$refund = $stripe_charge_object->refunds->create( array (
					"reason" => $reason
			) );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to create a stripe refund", 0, $e );
		}
		
		return $refund;
	}
	
	/**
	 *
	 * @param array $options
	 * @throws InvalidArgumentException
	 * @throws PaymentProcessingException
	 * @return StripePlan
	 */
	public function createPlan ( array $options ): StripePlan {
		if ( empty( $options ) ) {
			throw new InvalidArgumentException( "Params to create new stripe plan not provided" );
		}
		
		$plan = null;
		
		try {
			$plan = StripePlan::create( $options );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to create a stripe plan", 0, $e );
		}
		
		return $plan;
	}
	
	/**
	 *
	 * @param string $id
	 * @throws InvalidArgumentException
	 * @return StripePlan
	 */
	public function getPlan ( string $id ): StripePlan {
		if ( ! $id ) {
			throw new InvalidArgumentException( "Stripe plan id not provided" );
		}
		
		$plan = null;
		
		try {
			$plan = StripePlan::retrieve( $id );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to create a stripe plan", 0, $e );
		}
		
		return $plan;
	}
	
	/**
	 *
	 * @param IStripeCustomer $wolfgang_stripe_customer
	 * @param int $tax_percentage
	 * @param string $stripe_plan_id
	 * @throws PaymentProcessingException
	 * @return StripeSubscription
	 */
	public function createSubscription ( IStripeCustomer $wolfgang_stripe_customer, int $tax_percentage, string $stripe_plan_id ): StripeSubscription {
		$subscription = null;
		
		try {
			
			$subscription = StripeSubscription::create( array (
					"customer" => $wolfgang_stripe_customer->getStripeCustomerId(),
					"plan" => $stripe_plan_id,
					"quantity" => 1,
					"tax_percent" => $tax_percentage
			) );
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to create stripe subscription", 0, $e );
		}
		
		return $subscription;
	}
	
	/**
	 *
	 * @param string $stripe_subscription_id
	 * @throws InvalidArgumentException
	 * @throws PaymentProcessingException
	 * @return StripeSubscription
	 */
	public function cancelSubscription ( string $stripe_subscription_id ): StripeSubscription {
		if ( ! $stripe_subscription_id ) {
			throw new InvalidArgumentException( "Stripe subscription id not provided" );
		}
		
		$subsscription = null;
		
		try {
			
			$subsscription = StripeSubscription::retrieve( $stripe_subscription_id );
			$subsscription->cancel();
		} catch ( InvalidStripeRequest $e ) {
			throw new PaymentProcessingException( "Error occured while attempting to cancel stripe subscription", 0, $e );
		}
		
		return $subsscription;
	}
	
	/**
	 *
	 * @link https://stripe.com/docs/api?lang=php#customer_create_bank_account
	 * @param array $data
	 * @throws InvalidArgumentException
	 * @throws StripePaymentProcessingException
	 * @return StripeBankAccount
	 */
	public function addBankAccount ( array $data ): StripeBankAccount {
		try {
			if ( empty( $data[ 'account_number' ] ) ) {
				throw new InvalidArgumentException( "Account number not provided" );
			} else if ( empty( $data[ 'country' ] ) ) {
				throw new InvalidArgumentException( "Country code not provided" );
			} else if ( empty( $data[ 'currency' ] ) ) {
				throw new InvalidArgumentException( "Currency code not provided" );
			} else if ( empty( $data[ 'account_holder_name' ] ) ) {
				throw new InvalidArgumentException( "Account holder name not provided" );
			} else if ( empty( $data[ 'account_holder_type' ] ) ) {
				throw new InvalidArgumentException( "Account holder type not provided" );
			} else if ( empty( $data[ 'customer_id' ] ) ) {
				throw new InvalidArgumentException( "Account holder type not provided" );
			} else {
				$customer = $this->stripe->getCustomer( $data[ 'customer_id' ] );
				$bank_account = $customer->sources->create( array (
						"external_account" => array (
								// The type of external account. Should be "bank_account".
								"object" => "bank_account",
								// The account number for the bank account in string form. Must be a
								// checking account.
								"account_number" => $data[ 'account_number' ],
								// The country the bank account is in.
								"country" => $data[ 'country' ],
								// The currency the bank account is in. This must be a
								// country/currency
								// pairing that Stripe supports.
								"currency" => $data[ 'currency' ],
								// The name of the person or business that owns the bank account.
								// This field is required when attaching the bank account to a
								// customer
								// object.
								"account_holder_name" => $data[ 'account_holder_name' ],
								// The type of entity that holds the account. This can be either
								// "individual" or "company". This field is required when attaching
								// the bank account to a customer object.
								"account_holder_type" => $data[ 'account_holder_type' ],
								// If you set this to true (or if this is the first bank account
								// being
								// added in this currency) this bank account will become the default
								// bank account for its currency.
								"default_for_currency" => true
						)
				) );
				
				return $bank_account;
			}
		} catch ( InvalidStripeRequest $e ) {
			throw new StripePaymentProcessingException( "Unable to add stripe bank account:", 0, $e );
		}
	}
}
