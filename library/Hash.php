<?php
/**
 * HASHING
 * @author Ahmed Shan (@thaanu16)
 */
namespace Heliumframework;
class Hash 
{
	
	public static function make( $str, $salt = '' )
	{
		return hash('sha256', $str . $salt);
	}

	public static function salt( $length )
	{
		// return mcrypt_create_iv($length);
		return uniqid().md5($length);
		
	}

	public function unique()
	{
		return self::make(uniqid());
	}

}