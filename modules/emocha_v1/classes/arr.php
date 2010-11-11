<?php


class Arr extends Kohana_Arr{

	/*
	 * catch all xss sanitization for $_POST or $_GET arrays
	 */
	public static function xss(array $array){

		foreach ($array as &$value){
			if(is_array($value)){
				$value = self::xss($value);
			}else{
				$value = Security::xss_clean($value);
			}
		}

		return $array;
	}

}