<?php
namespace Heliumframework;
use \Heliumframework\Session;
class Notifications 
{
    /**
     * Set the notification
     * @param string $message
     */
    public static function set( $msg, $type = 'default' )
    {
        $messages[] = ['msg' => $msg, 'type' => $type];

        // Get current messages
        if( Session::exists('notification_messages') ) {
            $messages = Session::get('notification_messages');
        }
        Session::put('notification_messages', $messages);
    }

    /**
     * Get notification messages
     * @return object
     */
    public static function get()
    {
        // Get all notifications
        if( Session::exists('notification_messages') ) {
            return json_decode(json_encode(Session::get('notification_messages')));
        }
    }

    /**
     * Clear notifications
     * @return void
     */
    public static function clear()
    {
        Session::delete('notification_messages');
    }

}