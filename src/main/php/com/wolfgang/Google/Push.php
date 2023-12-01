<?php
namespace Wolfgang\Google;

//PHP
use Exception;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

//Google
use Google\Client;
use Google_Service_FirebaseCloudMessaging;
use Google_Service_FirebaseCloudMessaging_SendMessageRequest;
use Google_Service_FirebaseCloudMessaging_Message;
use Google_Service_Exception;

final class Push {
    
    /**
     * 
     * @var string
     */
    const APPLE_PUSH_ENDPOINT = "ssl://gateway.push.apple.com:2195";
    
    /**
     * 
     * @var string
     */
    const APPLE_SANDBOX_PUSH_ENDPOINT = "ssl://gateway.sandbox.push.apple.com:2195";
    
    /**
     * 
     * @var integer
     */
    const APN_MAX_PAYLOAD_LENGTH = 2048;
    
    /**
     * @see https://firebase.google.com/docs/cloud-messaging/http-server-ref#error-codes
     * @var integer
     */
    const FCM_MAX_PAYLOAD_LENGTH = 4096;
    
    /**
     * 
     * @var integer
     */
    const MAX_NOTIFICATION_BODY_LENGTH = 200;
    
    /***
     * 
     * @var array
     */
    private static $config = array();
    
    /**
     * 
     * @var array
     */
    private static $dataMessagePayloadTemplate = array (
        "message" => array (
            "android" => array(
                "priority" => "high",
            ),
            
            "apns" => array (
                "headers" => array (
                    "apns-push-type" => "background",
                    'apns-priority' => "5",
                ),
                "payload" => array(
                    "aps" => array(
                        "content-available" => 1,
                    ),
                )
            ),
            
            "data" => array(
                
            )
        )
    );

    
    protected static function init () {
        if (!empty(self::$config)) {
            return;
        }
        
        self::$config = [];
    }
    
    /**
     * Sends push notifications to a particular user to everywhere he is subscribed for it. This 
     * class method will also send corresponding badge update data messages to the user.
     * 
     * @param string $title The title of the push notification messages
     * @param $user The user to send the push notification messages to
     * @param array $otherOptions Other options to use in sending the push notification messages
     * @return void|boolean
     */
    public static function sendNotification ($title, $user, $otherOptions = array()) {
        /*if (!isset($title, $user->id, $user['notifications'])) {
            return false;
        } else if (!$user['notifications']) {
            return false;
        }
        
        if (empty($otherOptions['message'])) {
            throw new Exception("Notification body not provided");    
        }
        
        self::init();
        
        $pushServiceSubscriptionsModel = new PushServiceSubscriptions();
        $pushDeviceTokensModel = new PushDeviceTokens();
        $mobilePushRegistrationsModel = new MobilePushRegistrations();
        $linksModel = new Links();
        
        $webPushPublicKey = self::getWebPushPublicKey();
        $webPushPrivateKey = self::getWebPushPrivateKey();
        $maxNotificationBodyLength = self::MAX_NOTIFICATION_BODY_LENGTH;
        
        $subscriptions = $pushServiceSubscriptionsModel->loadByUserIds(array($user->getId()));
        $notifications = array();
        
        $icon = isset($otherOptions['icon']) ? $otherOptions['icon'] : null;
        $url = isset($otherOptions['url']) ? $otherOptions['url'] : '';
        $urlExernal = isset($otherOptions['url_external']) ? $otherOptions['url_external'] : false;
        $urlExernal = $urlExernal ? "true" : "false";
        
        if (!$icon) {
            $icon = '/img/icon-push.png';
        }

        if (mb_strlen($otherOptions['message'], 'UTF-8') > $maxNotificationBodyLength) {
            $otherOptions['message'] = mb_substr($otherOptions['message'], 0, $maxNotificationBodyLength, 'UTF-8') . ' ...';
        }
        
        $payload = json_encode(array(
            "title" => $title,
            "message" => $otherOptions['message'],
            "icon" => $icon,
            "url" => isset($otherOptions['url']) ? $otherOptions['url'] : null,
            "actions" => isset($otherOptions['actions']) ? $otherOptions['actions'] : null,
        ));
        
        $safariPayload = json_encode(array(
            "aps" => array (
                "alert" => array (
                    "title" => $title,
                    "body" => $otherOptions['message'],
                    "action" => isset($otherOptions['actions']) ? $otherOptions['actions'][0]["title"] : null,
                ),
                
                "url-args" => array(
                    isset($otherOptions['url']) ? str_replace("https:", "", $otherOptions['url']) : null,
                )
            )
        ));
        
        $mobilePayload = array(
            "message" => array (
                "notification" => array (
                    "title" => $title,
                    "body" => $otherOptions['message'],
                ),
                
                "android" => array(
                    "priority" => "high",
                    "notification" => array (
                        "sound" => "default",
                        "default_vibrate_timings" => "true"
                    )
                ),
                
                "apns" => array (
                    "headers" => array (
                        "apns-push-type" => "alert",
                        'apns-priority' => "10",
                    ),
                    "payload" => array(
                        "aps" => array(
                            "alert" => array (
                                "title" => $title,
                                "body" => $otherOptions['message'],
                            ),
                            "sound" => "default",
                        ),
                        
                        "url" => $url,
                        'url_external' => $urlExernal,
                    )
                ),

                "data" => array(
                    "notification_foreground" => "true",
                    "url" => $url,
                    "url_external" => $urlExernal,
                )
            )
        );
        
        if (isset($otherOptions["mobile"]["message"]["data"])) {
            $mobilePayload["message"]["data"] = array_merge($mobilePayload["message"]["data"], $otherOptions["mobile"]["message"]["data"]);
        }

        if (!empty($subscriptions)) {
            foreach ($subscriptions as $subscription) {
                $notifications[] = array(
                    'endpoint' => $subscription['endpoint'],
                    'payload' => $payload,
                    'userPublicKey' => $subscription['p256dh_key'], // base 64 encoded, should be 88 chars
                    'userAuthToken' => $subscription['auth_key'], // base 64 encoded, should be 24 chars
                );
            }
            
            $auth = [
                'VAPID' => [
                    'subject' => "mailto:{$linksModel->getLink(5)}", // can be a mailto: or your website address
                    'publicKey' => $webPushPublicKey, //uncompressed public key P-256 encoded in Base64-URL
                    'privateKey' => $webPushPrivateKey, //in fact the secret multiplier of the private key encoded in Base64-URL
                ],
            ];
            
            $webPush = new WebPush($auth);
            
            //Firefox on Android won't work without automatic padding 0
            //https://github.com/web-push-libs/web-push-php/issues/108
            $webPush->setAutomaticPadding(0);
            
            // send multiple notifications with payload
            foreach ($notifications as $notification) {
                $webPush->sendNotification(
                    Subscription::create([
                        'endpoint' => $notification['endpoint'],
                        'publicKey' => $notification['userPublicKey'], // optional (defaults null)
                        'authToken' => $notification['userAuthToken'] // optional (defaults null)
                    ]),
                    $notification['payload'] // optional (defaults null)
                );
            }
            
            $result = $webPush->flush();
            
            if ($result) {
                foreach ($result as $report) {
                    $endpoint = $report->getRequest()->getUri()->__toString();
                    
                    if ($report->isSuccess()) {
                        self::log("Message sent successfully for subscription {$endpoint}.");
                    } else {
                        self::log("Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
                        
                        if ($report->isSubscriptionExpired()) {
                            $pushServiceSubscriptionsModel->deleteByEndpoint($endpoint);
                        }
                    }
                }
            }
        }
        
        //Get device / registration tokens
        
        $safariDeviceTokens = array();
        $mobileRegistrationTokens = array();
        
        foreach ($pushDeviceTokensModel->loadByUserIds(array($user->getId())) as $pushDeviceToken) {
           $safariDeviceTokens[] = $pushDeviceToken['token'];
        }
        
        foreach ($mobilePushRegistrationsModel->loadByUserIds(array($user->getId())) as $mobilePushRegistration) {
            $mobileRegistrationTokens[] = $mobilePushRegistration['token'];
        }
        
        if ($safariDeviceTokens) {
            try {
                self::sendSafariPushNotification($safariDeviceTokens, $safariPayload);
            } catch (Exception $e) {
                self::log($e);
            }
        }
        
        if ($mobileRegistrationTokens) {
            try {
                self::sendMobilePushNotifications($mobileRegistrationTokens, $mobilePayload);
            } catch (Exception $e) {
                if ($e instanceof Google_Service_Exception) {
                    self::log($e->getMessage());
                } else {
                    self::log($e);
                }
            }
        }*/
    }
    
    /**
     * Updates a user's app badge
     * 
     * @param $user The user for which all app badges should be updated
     * @return void
     */
    public static function sendMobileBadgeUpdateDataMessage($user, $badge) {
        /*$mobilePushRegistrationsModel = new MobilePushRegistrations();
        
        self::init();
        
        $mobilePayload = array (
            "message" => array (
                "android" => array(
                    "priority" => "high",
                ),
                
                "apns" => array (
                    "headers" => array (
                        "apns-push-type" => "alert",
                        'apns-priority' => "10",
                    ),
                    "payload" => array(
                        "aps" => array(
                            "badge" => $badge,
                        ),
                    )
                ),
                
                "data" => array(
                    "type" => 'badge-set',
                    "badge" => (string)$badge,
                    "datetime_sent" => date("Y-m-d H:i:s"),
                )
            )
        );

        $mobileRegistrationTokens = array();
        
        foreach ($mobilePushRegistrationsModel->loadByUserIds(array($user->getId())) as $mobilePushRegistration) {
            $mobileRegistrationTokens[] = $mobilePushRegistration['token'];
        }
        
        if ($mobileRegistrationTokens) {
            try {
                self::sendMobilePushNotifications($mobileRegistrationTokens, $mobilePayload);
            } catch (Exception $e) {
                if ($e instanceof Google_Service_Exception) {
                    self::log($e->getMessage());
                } else {
                    self::log($e);
                }
            }
        } else {
            self::log("Badge message not sent. No registration tokens.");
        }*/
    }
    
    /**
     * Sends a data message to a user or to a provided set of devices identified by their Firebase Cloud Messaging
     * (FCM) registration token.
     * 
     * @param array $options The options to use in sending the data message
     * @throws Exception
     */
    public static function sendDataMessage ($options) {
        /*if (empty($options)) {
            throw new Exception("Options not provided for sending data message");
        } else if (empty($options['data'])) {
            throw new Exception("Data not provided for sending data message");
        }
        
        $mobilePushRegistrationsModel = new MobilePushRegistrations();
        
        self::init();
        
        $mobilePayload = self::$dataMessagePayloadTemplate;
        $mobilePayload['message']['data'] = $options['data'];
        $user = isset($options['user']) ? $options['user'] : null;
        $mobileRegistrationTokens = isset($options['tokens']) ? $options['tokens'] : null;
        $mobilePayload['message']['data']['datetime_sent'] = date("Y-m-d H:i:s");
        
        if (isset($user)) {
            $mobileRegistrationTokens = array();
            
            foreach ($mobilePushRegistrationsModel->loadByUserIds(array($user->getId())) as $mobilePushRegistration) {
                $mobileRegistrationTokens[] = $mobilePushRegistration['token'];
            }
        } else if (empty($mobileRegistrationTokens)) {
            throw new Exception("User not mobile push registration tokens provided");
        }
        
        if ($mobileRegistrationTokens) {
            try {
                self::sendMobilePushNotifications($mobileRegistrationTokens, $mobilePayload);
            } catch (Exception $e) {
                if ($e instanceof Google_Service_Exception) {
                    self::log($e->getMessage());
                } else {
                    self::log($e);
                }
            }
        } else {
            self::log("Data message not sent. No registration tokens.");
        }*/
    }
    
    /**
     * 
     * @throws Exception
     * @param array $safariDeviceTokens
     * @param string $payload
     */
    private static function sendSafariPushNotification ($deviceTokens, $payload) {
        /*if (empty($deviceTokens)) {
            return;
        } else if (empty($payload)) {
            throw new Exception("Safari push notification payload not provided");
        }
        
        $certificate = self::getPemCertificatePath();
        $passphrase = self::getPemCertificatePassphrase();
        
        $streamContext = stream_context_create();
        
        if (!$streamContext) {
            throw new Exception("Error creating stream context");
        }

        $result = stream_context_set_option( $streamContext, 'ssl', 'local_cert', $certificate);
        
        if (!$result) {
            throw new Exception("Error setting stream context option 'local_cert'");
        }

        $result = stream_context_set_option( $streamContext, 'ssl', 'passphrase', $passphrase );
        
        if (!$result) {
            throw new Exception("Error setting stream context option 'passphrase'");
        }

        // Open a connection to the APNS server
        $err = "";
        $errstr = "";
        
        $filePointer = stream_socket_client(self::APPLE_PUSH_ENDPOINT, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $streamContext );
        
        if (!$filePointer) {
            throw new Exception("Failed to connect to remote socket: {$err} {$errstr}");
        }
            
        $failedTokens = array(); 
        
        foreach ($deviceTokens as $deviceToken) {
            // Build the binary notification
            $message = chr( 0 ).pack( 'n', 32 ).pack( 'H*', $deviceToken ).pack( 'n', strlen( $payload ) ).$payload;
            
            if (strlen($message) > self::APN_MAX_PAYLOAD_LENGTH) {
                throw new Exception("Payload exceeds APN max payload length");
            }
            
            // Send it to the server
            $result = fwrite( $filePointer, $message, strlen( $message ) );
            
            if (!$result) {
                $failedTokens[] = $deviceToken;
            }
        }
        
        $result = fclose( $filePointer );
        
        if ($failedTokens) {
            throw new Exception("Failed to deliver to device id(s) \"" . implode(', ', $failedTokens). "\"");
        }*/
    }
    
    /**
     * @see https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages/send
     * @param array $registrationTokens
     * @param array $payload
     * @throws Exception
     * @return void|mixed
     */
    private static function sendMobilePushNotifications ($registrationTokens, $payload) {
        /*if (empty($registrationTokens)) {
            throw new Exception("Registration tokens not provided");
        } else if (empty($payload)) {
            throw new Exception("Push notification payload not provided");
        }
        
        $registrationTokens = self::filterMobileRegistrationIdsWhereSessionInvalid($registrationTokens);
        
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $parent = self::getCloudMessagingParent();
        
        $client->addScope(Google_Service_FirebaseCloudMessaging::CLOUD_PLATFORM);
        $service = new Google_Service_FirebaseCloudMessaging($client);
        $message = new Google_Service_FirebaseCloudMessaging_Message($payload['message']);

        //Throw exception with these towards the end
        $invalidTokens = array();

        foreach ($registrationTokens as $registrationToken) {
            $message->setToken($registrationToken);
            
            $postBody = new Google_Service_FirebaseCloudMessaging_SendMessageRequest();
            $postBody->setMessage($message);
            
            try {
                $service->projects_messages->send($parent, $postBody);
            } catch (Exception $e) {
                if (($e instanceof Google_Service_Exception)) {
                    $emessage = json_decode($e->getMessage(), true);
                    
                    self::log($emessage);
                    
                    if ($emessage['error']['status'] == 'INVALID_ARGUMENT') {
                        if ($emessage['error']['message'] == 'The registration token is not a valid FCM registration token') {
                            $invalidTokens[] = $registrationToken;
                        }
                    }
                } else {
                    self::log($e);
                }
            }
        }
        
        if ($invalidTokens) {
            //Delete these from data source
            $mobilePushRegistrationsModel = new MobilePushRegistrations();
            
            foreach ($invalidTokens as $invalidToken) {
                $mobilePushRegistrationsModel->deleteByToken($invalidToken);    
            }
            //Throw exception to log error
            throw new Exception("Registration token(s) '" . implode(', ', $invalidTokens) . "' are no longer valid an have been removed.");
        }*/
    }
    
    /**
     * Receives a set of FCM tokens to which notifications will be sent and removes those
     * which are linked to devices that have signed in but have been overly inactive or where the
     * user does not have an active session.
     * 
     * @param array $mobileRegistrationTokens
     */
    private static function filterMobileRegistrationIdsWhereSessionInvalid ($mobileRegistrationTokens) {
        /*$mobilePushRegistrationsModel = new MobilePushRegistrations();
        $sessions = new Sessions();
        $userModel = new User();
        
        foreach ($mobileRegistrationTokens as $key => $token) {
            $mobilePushRegistration = $mobilePushRegistrationsModel->loadByToken($token);
            
            if ($mobilePushRegistration) {
                $user = $userModel->load($mobilePushRegistration['user_id']);
                $owner = $userModel->get_owner($user);
                $deviceUuid = $mobilePushRegistration['device_uuid'];
                
                //Find opened sessions with $deviceUuid
                //Specify id of user since users could be sharing devices
                $openedSessions = $sessions->filter("user_id = ? AND device_uuid = ? AND logout_datetime IS NULL", array($user->getId(), $deviceUuid), false, 'last_active_time desc');
                
                //If is not signed in on the device identified by $deviceUuid
                //We should not send him notifications
                if (empty($openedSessions)) {
                    self::log("User {$user->getId()} is not signed in. Cannot send message.");
                    
                    unset($mobileRegistrationTokens[$key]);
                    continue;
                }
                
                //User is signed in on the devices identified by $deviceUuid but 
                //may or may not be overly inactive
                $openedSession = array_shift($openedSessions);

                //User overly inactive on the device sign in
                //We should not send him notifications
                if ($sessions->isOverlyInactive($openedSession['php_session_id'], $owner)) {
                    self::log("User {$user->getId()} overly inactive. Cannot send message, session id {$openedSession['php_session_id']}, using token {$token}.");
                    
                    unset($mobileRegistrationTokens[$key]);
                    continue;
                }
            }
        }
        
        $mobileRegistrationTokens = array_values($mobileRegistrationTokens);
        
        return $mobileRegistrationTokens;*/
    }

    /**
     * 
     * @return string
     */
    public static function getPemCertificatePath ($fileProtocol = false) {
        if ($fileProtocol) {
            $fileProtocol = "file://";
        }
        
        return $fileProtocol . DOCUMENT_ROOT . "resources/certs/PushCertKey.pem"; 
    }
    
    /**
     * 
     * @return NULL|string
     */
    public static function getPemCertificatePassphrase () {
        self::init();
        
        return self::$config['certificate_passphrase'];
    }
    
    /**
     * @see https://developer.apple.com/support/expiration/
     * @return string
     */
    public static function  getAppleWWDRCAPath () {
        return DOCUMENT_ROOT . 'resources/certs/AppleWWDRCA.pem';
    }
    
    /**
     * @return string
     */
    public static function getWebPushPublicKey () {
        self::init();
        
        return self::$config['web_push_public_key'];
    }
    
    /**
     * @return string
     */
    public static function getWebPushPrivateKey () {
        self::init();
        
        return self::$config['web_push_private_key'];
    }
    
    /**
     * @return string
     */
    public static function getWebsitePushId () {
        self::init();
        
        return self::$config['website_push_id'];
    }
    
    public static function getCloudMessagingParent () {
        self::init();
        
        return self::$config['cloud_messaging_parent'] ?? null;
    }
    
    /**
     * Log Push related error messages to their own file.
     * 
     * @param array|Exception $errorRecord
     */
    public static function log($errorRecord) {
        if (!isset($errorRecord)) {
            return false;
        }
        
        $message = date('Y-m-d H:i:s') . ' - ' . print_r($errorRecord, true) . "\n";
        
        error_log($message, 3, DOCUMENT_ROOT . '/log/push.log');
    }
}