<?php
/**
 * SESSIONS
 * @author Ahmed Shan (@thaanu16)
 */
namespace Heliumframework;
class Session
{

	// Check if the session exists
	public static function exists( $name )
	{
		return ( isset($_SESSION[_env('APP_ID')][$name]) ? true : false );
	}

	// Put a value to the session variable
	public static function put($sessionName, $value)
	{
		return $_SESSION[_env('APP_ID')][$sessionName] = $value;
	}

	// Get a session value
	public static function get( $name ) 
	{
		return $_SESSION[_env('APP_ID')][$name];
	}

	// Delete a session value
	public static function delete( $name )
	{
		if( self::exists($name) ) {
			unset($_SESSION[_env('APP_ID')][$name]);
		}
	}

	// Just a simple method to flash a message to the user
	public static function flash( $name, $string = '' )
	{
		if( self::exists($name) ) {
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}
	}


}