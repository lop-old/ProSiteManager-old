<?php namespace builder\builders;
global $ClassCount; $ClassCount++;
final class Builder_javac {
	private function __construct() {}


	public static function RunXML($data) {
		//[user@matt test]$ jar -cv0mf MANIFEST.MF test.jar test.class
		//added manifest
		//adding: test.class(in = 416) (out= 416)(stored 0%)
		//[user@matt test]$ java -jar test.jar
		//testing output
	}


}
?>