<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class cSimple_image {

   var $image;
   var $image_type;

   public $class_settings = array();

   function simple_image(){
     // print_r( $_GET );

     //if( $_GET[ 'todo' ] && isset($_GET[ 'todo' ]) ){ 
         //$this->$_GET[ 'todo' ]();
     // }
   }

   public function generate_id_card( $users, $params, $template_id ){

      $output = array();

      include $this->class_settings["calling_page"] . "classes/id-card-gen/phpqrcode/qrlib.php";
      //include "../id-card-gen/./phpqrcode/qrlib.php";

      if( is_array( $users ) && ! empty( $users ) && is_array( $params ) && ! empty( $params ) ){

         // ID Card Front View
         $dest_front = imagecreatefrompng( $params[ 'front_image' ] );
         imagealphablending($dest_front, false);
         imagesavealpha($dest_front, true);

         // ID Card Back View
         $dest_back = imagecreatefrompng( $params[ 'back_image' ] );
         imagealphablending( $dest_back, false );
         imagesavealpha( $dest_back, true );

         $text_company = strtoupper( $params["company"] );

         $issue_date = date("j/n/Y");
         $valid_till_date = date('j/n/Y', strtotime( $params[ 'validity' ] ));
         
         $font = $this->class_settings["calling_page"] ."classes/id-card-gen/OpenSans-Bold.ttf";
         $font4 = $this->class_settings["calling_page"] ."classes/id-card-gen/OpenSans-Bold.ttf";
         $font2 = $this->class_settings["calling_page"] ."classes/id-card-gen/OpenSans-Bold.ttf";
         $font3 = $this->class_settings["calling_page"] ."classes/id-card-gen/OpenSans-Regular.ttf";
         $font5 = $this->class_settings["calling_page"] ."classes/id-card-gen/segoe-ui-bold.ttf";

         $black = imagecolorallocate($dest_front, 0x15, 0x75, 0x0A);
         $black4 = imagecolorallocate($dest_front, 0x15, 0x75, 0x0A);
         $black2 = imagecolorallocate($dest_front, 0xFF, 0x6B, 0x09);
         $black3 = imagecolorallocate($dest_front, 0x22, 0x22, 0x22);
         $white = imagecolorallocate($dest_front, 255, 255, 255);
         $red = imagecolorallocate($dest_front, 204, 0, 0);

         $fontsize = 30;
         $fontsize4 = 22;
         $fontsize2 = 24;
         $fontsize3 = 22;
         $fontsize5 = 42;
               
         foreach( $users as $dv ){
            
   			$unique_id = $dv["unique_id"];
            $path1 = "files/ready/";
            $path = $this->class_settings["calling_page"] ."files/ready/";

            $text_fullname = strtoupper( $dv["firstname"]. ' ' .$dv["lastname"]);
            $text4 = '';

            $text_role = strtoupper( $dv["role"] );
            $text_id = strtoupper( $dv["id"] );
            $text_division = strtoupper( $dv["division"] );
            $text_department = strtoupper( $dv["department"] );

            $picture = $dv["photograph"];
            // print_r( $spicture ); exit;
			
            if( file_exists( $picture ) ){
   				$px = pathinfo( $picture );
   				if( isset( $px["extension"] ) && $px["extension"] == "png" ){
   					$this->image = imagecreatefrompng( $picture );
   				}else{
   					$this->image = imagecreatefromjpeg( $picture );
   				}
               $photograph_w = 492;
               $photograph_h = 463;
               
               $this->resizeToWidth( $photograph_w );
               $this->resizeToHeight( $photograph_h );

               $center_width = abs(imagesx( $dest_front ) - imagesx( $this->image ) ) / 2;

               imagecopymerge($dest_front, $this->image, $center_width, 248, 0, 0, $this->getWidth(), $this->getHeight(), 100); //have to play with these numbers for it to work for you, etc.
            }else{
               $path1 = "files/notready/";
               $path = $this->class_settings["calling_page"] ."files/notready/";
            }

            $show_signature = 0;
            $show_barcode = 0;

            switch( $template_id ){
            case 'food-handler':

               if( $text4 ){
                  $bbox4 = imageftbbox( $fontsize4 , 0, $font4, $text4);
                  $bbox = imageftbbox( $fontsize , 0, $font, $text_fullname);
                  $x = ( $bbox[0] + $bbox4[0] ) + (imagesx($dest_front) / 2) - ( ( $bbox[4] + $bbox4[4] ) / 2) - 5;
                  $x4 = $x + $bbox[4];
               }else{
                  // First we create our bounding box
                  $bbox = imageftbbox( $fontsize , 0, $font, $text_fullname);
                  $x = $bbox[0] + (imagesx($dest_front) / 2) - ($bbox[4] / 2) - 5;
                  //$y = $bbox[1] + (imagesy($dest_front) / 2) - ($bbox[5] / 2) - 5;
               }
               imagettftext($dest_front, $fontsize, 0, $x, 765, -$black, $font, $text_fullname);
               if( $text4 ){
                  imagettftext($dest_front, $fontsize4, 0, $x4, 704, -$black4, $font4, $text4);
               }

               // First we create our bounding box
               $bbox2 = imageftbbox( $fontsize2 , 0, $font2, $text_role);
               $x2 = $bbox2[0] + (imagesx($dest_front) / 2) - ($bbox2[4] / 2) - 5;
               //$y = $bbox2[1] + (imagesy($dest_front) / 2) - ($bbox2[5] / 2) - 5;

               $text_id_size = imageftbbox( $fontsize3 , 0, $font3, $text_id);
               $text_id_size = $text_id_size[0] + ( imagesx($dest_front) / 2 ) - ($text_id_size[4] / 2) - 5;
               imagettftext($dest_front, $fontsize2, 0, $text_id_size, 936, -$black2, $font2, $text_id);

               $text_department_size = imageftbbox( $fontsize3 , 0, $font4, $text_department);
               $text_department_size = $text_department_size[0] + ( imagesx($dest_front) / 2 ) - ($text_department_size[4] / 2) - 5;
               imagettftext($dest_front, $fontsize3, 0, $text_department_size, 877, -$red, $font4, $text_department);

               $text_company_size = imageftbbox( $fontsize5 , 0, $font5, $text_company);
               $text_company_size = $text_company_size[0] + ( imagesx($dest_front) / 2 ) - ($text_company_size[4] / 2) - 5;
               imagettftext($dest_front, $fontsize5, 0, $text_company_size, 197, -$red, $font5, $text_company);

               $text_division_size = imageftbbox( $fontsize5 , 0, $font5, $text_division);
               $text_division_size = $text_division_size[0] + ( imagesx($dest_front) / 2 ) - ($text_division_size[4] / 2) - 5;
               imagettftext($dest_front, $fontsize5, 0, $text_division_size, 830, -$black3, $font5, $text_division);

               imagettftext($dest_front, $fontsize3, 0, 183, 1007, -$black3, $font3, $issue_date);
               imagettftext($dest_front, $fontsize3, 0, 461, 1007, -$black3, $font3, $valid_till_date);

               $show_signature = 1;
               $sign2 = 1350;
               $sign3 = 0;
               $sign4 = 0;

               $show_barcode = 1;
               $barcode2 = 509;
               $barcode3 = 0;
               $barcode4 = 0;
            break;
            }

            $dest_backw = imagesx( $dest_back );

            if( $show_signature ){
               // $spicture = '../files/signature/signature.png';
               $spicture = $dv["signature"];
               // echo '<img src="'. $picture .'" width="100" height="100">';exit;
               if( file_exists( $spicture ) ){
                  $this->image = imagecreatefromjpeg( $spicture );
                  $signw = imagesx( $this->image );
                  $signh = imagesy( $this->image );
                  $center_width = abs($dest_backw - $signw) / 2;

                  imagecopymerge($dest_back, $this->image, $center_width, $sign2, $sign3, $sign4, $signw, $signh, 100); 
               }else{
                  $path = $this->class_settings["calling_page"] ."files/notready/";
               }
            }
			
            if( $show_barcode ){
               $barcode_file = $unique_id . '-barcode.png';
               $filename = $barcode_file;
               QRcode::png( $unique_id , $filename, "H", 7, 2);
               if( file_exists( $barcode_file ) ){
                  $barcode_size = 768;

                  $this->image = imagecreatefrompng( $barcode_file );   
                  $this->resizeToWidth( $barcode_size );
                  $this->resizeToHeight( $barcode_size );
                  $center_width = abs($dest_backw - $barcode_size) / 2;

                  imagecopymerge( $dest_back, $this->image, $center_width, $barcode2, $barcode3, $barcode4, $this->getHeight(), $this->getWidth(), 100 );
               }
            }
			
            // header('Content-type: image/jpg');

            ob_start();
            imagejpeg($dest_front);
            $image_data_front = ob_get_contents();
            ob_end_clean();

                  // print_r( $show_barcode );exit();
            ob_start();
            imagejpeg($dest_back);
            $image_data_back = ob_get_contents();
            ob_end_clean();

            //if( ! file_exists( '../files/users_id_card' ) ){
              // mkdir( '../files/users_id_card' );
            if( ! file_exists( $path ) ){
               create_folder( $path, '', '' );
            }
            file_put_contents( $path.$unique_id."-front.jpg", $image_data_front );
            file_put_contents( $path.$unique_id."-back.jpg", $image_data_back );

            $output["front"] = $path1 . $unique_id . '-front.jpg';
            $output["back"] = $path1 . $unique_id . '-back.jpg';

            imagedestroy($dest_front);
            imagedestroy($dest_back);
            imagedestroy($this->image);

            if( isset($signature) && $signature )
               imagedestroy($signature);

            if( isset( $src ) && $src )
               imagedestroy($src);

            unset($barcode);
            unset($src);
            unset($signature);

            if( $show_barcode && file_exists($barcode_file) ){
               unlink( $barcode_file );
            }

         }
      }

      // print_r( $output ); exit;
      return $output;

   }
 
   function load($filename) {
 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
 
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
	  return $this->image;
   }

   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
   	if( file_exists($filename) ){
   		unlink( $filename );
   	}
		
      if( $image_type == IMAGETYPE_JPEG ) {
        imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }      
 
}
?>