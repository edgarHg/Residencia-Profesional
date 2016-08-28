<?php
	// PHP Document
	// Copyright(c) 2013, eHg @softekar
	
	class CNotificacion{
		public function __construct(){
			
		}
		public static function errorServidor($mensaje){
			return "
				<div style=\"margin:10px auto 10px auto; padding:0px;\">
					<div style=\"-moz-border-radius:2px 2px 2px 2px; background-color:#FDCAC9; border:1px solid #8B1717; min-height:10px; padding:5px 10px; margin:0px auto 0px auto; text-align:left;\">
						<div style=\"background-image:url('img/error.jpg'); display:block; float:left; height:18px; margin:0px 10px 0px 0px; width:17px; font-size:12px !important;\"></div>
						<p style=\"color:#424242; line-height:17px; margin:0; font-size:14px !important; padding-bottom:0px;\">$mensaje</p>
					</div>
				</div>
			";
		}
		public static function error($mensaje){
			return "<div class=\"notificacion\"><div class=\"error\"><div class=\"error_ima\"></div><p>$mensaje</p></div></div>";
		}
		public static function correcto($mensaje){
			return "<div class=\"notificacion\"><div class=\"correcto\"><div class=\"correcto_ima\"></div><p>$mensaje</p></div></div>";
		}
	}
?>