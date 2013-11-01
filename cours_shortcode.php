<?php
add_action( 'init', 'courses_shortcodes' );
function courses_shortcodes() {
	add_shortcode( 'stps_shortcode', 'courses_slides_shortcode' );
}
function courses_slides_shortcode($atts, $content=null){
   global $studi_courses;
   global $studi_slides;
   global $studi_category;
   global $studi_categ_cours;
//On d�marre la temporisation de sortie

ob_start();

//Fonction wordpress de listage des pages

wp_list_pages();

//On enregistre dans une variable le contenu du tampon, et l�efface.

$cont=ob_get_clean();
?> 	<form method="POST"  id="showcours" name="showcours" enctype="multipart/form-data" > <?php
echo "</br></br></br><div id="; echo '"slider_c'; echo $atts['id']; echo'">
      <div id="mask_c'; echo $atts['id']; echo'">
        <ul id="image_container_c'; echo $atts['id']; echo'">';
               global $wpdb;
               foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id,nom,author, duration 
				                             FROM $studi_courses 
											 where course_id=%d",$atts['id'])) as $key => $row_name) {
				$course_id = sanitize_text_field($row_name-> course_id);
                $course_name = sanitize_text_field($row_name-> nom);
				$course_author = sanitize_text_field($row_name->author);
				$course_duration = sanitize_text_field($row_name-> duration);}
				echo '<li>
				        <div style=" width:700px; background:rgb(252,252,227); height:400px;">
						  <div style="width:90%;">
						     <h2 style="text-align: center;">
							   <div style="margin: 120px 80px 40px 100px  ;  width:480px; height:50px; 	font-size:150%;">
							     <span style="text-align:center;">   ';  echo $course_name;  
					        echo'</span>
							   </div>
							 </h2></br>
							 <div style="width:100%;">
							  <div style="width:40%; float:left;">
						       <label style="  font-size:150%;">Author : ';  echo $course_author;  echo'</label></br></br>
							   <label style="  font-size:150%;">Duration : ';  echo $course_duration;  echo' mn</label></br></br>
							  
					            <label style=" font-size:150%;">Categories</label></br>';
						  $req_cat_cours=$wpdb->get_results($wpdb->prepare("SELECT cat_name
                                                                            FROM $studi_categ_cours, $studi_category
                                                                            WHERE $studi_categ_cours.cat_id = $studi_category.cat_id
                                                                            AND $studi_categ_cours.course_id = '".$atts['id'] ."'"));
                          foreach($req_cat_cours as $key => $row){$cat_name = sanitize_text_field($row-> cat_name);                    						  
					           echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>- '; echo $cat_name; echo '</label></br>'; }
					  echo ' </div></div>

                           
						  </div>
						</div>
					  </li>';

                foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_content 
				                                            FROM $studi_slides 
											                where course_id=%d
											                ORDER BY slides_order ASC",$atts['id'])) as $key => $row) {
                $nom = $row-> slides_content;
                
               echo '<li><div style=" width:700px; background:rgb(252,252,227); height:400px;"><div style=" left:-40%;    width:93%;">'; echo $nom; echo '</div></div></li>';}
				echo '<li>
				        <div style=" width:700px; background:rgb(252,252,227); height:400px;">
						  <div style="width:90%;">                             <div align="center"><img src="' . plugins_url( 'images/zarkhrafaaa.png' , __FILE__ ) . '"></div>
						     <h2 style="text-align: center;"><div style="margin: 150px 80px 40px 100px  ;  width:480px; height:50px;  	font-size:150%;"><span style="text-align:center;">   ';  echo 'The end';  echo'</span></div></h2>

						  </div>
						</div>
					  </li>';
			   

        echo '</ul>
      </div>

      <ul id="dots_c'; echo $atts['id']; echo'">';
                      
               global $wpdb;
                foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nb 
				                                            FROM $studi_slides 
															where course_id=%d",$atts['id'])) as $key => $row) {
                $nom = $row-> nb;
				$nbre_slide=$nom+2;}
                for ($i = 1; $i <= $nbre_slide; $i++){
                            echo'<li class="button_c'; echo $atts['id']; echo '_'; print_r($i); echo '" onClick="changeImage_c'; echo $atts['id']; echo'('; print_r($i); echo')" ></li>'; } echo '

      </ul>
	  

      <img src="' . plugins_url( 'images/fleche-gauche.png' , __FILE__ ) . '" id="fleche_gauche_c'; echo $atts['id']; echo'" class="fleche_c'; echo $atts['id']; echo'" onClick="prevImage_c'; echo $atts['id']; echo'()" width="45px" height="90px" >
      <img src="' . plugins_url( 'images/fleche-droite.png' , __FILE__ ) . '" id="fleche_droite_c'; echo $atts['id']; echo'" class="fleche_c'; echo $atts['id']; echo'" onClick="nextImage_c'; echo $atts['id']; echo'()" width="45px" height="90px" >
    </div><script type="text/javascript">
      var secDuration = 5;
      var image_c'; echo $atts['id']; echo' = 1;
      var maxImages_c'; echo $atts['id']; echo' = '; echo $nbre_slide; echo '
      var slider_c'; echo $atts['id']; echo' = document.getElementById('; echo "'slider_c"; echo $atts['id']; echo"');
      var timeout;
      
      function changeImage_c"; echo $atts['id']; echo"(requiredImage) {
      
        if (!requiredImage && requiredImage != 0){ 
          if(image_c"; echo $atts['id']; echo" < maxImages_c"; echo $atts['id']; echo"){
            image++;
          }
          else{
            image_c"; echo $atts['id']; echo" = 1; 
          }
        }
        else{ 
          if(requiredImage > maxImages_c"; echo $atts['id']; echo"){
            image_c"; echo $atts['id']; echo" = 1;
          }
          else if(requiredImage < 1){ 
            image_c"; echo $atts['id']; echo" = maxImages_c"; echo $atts['id']; echo";
          }
          else{
            image_c"; echo $atts['id']; echo" = requiredImage; 
          }
        }
        slider_c"; echo $atts['id']; echo".className = "; echo'"image_c'; echo $atts['id']; echo '"+image_c'; echo $atts['id']; echo ';
        
        clearTimeout(timeout)
        timeout = setTimeout("changeImage_c'; echo $atts['id']; echo'()",secDuration*1000);
      }
      
      function nextImage_c'; echo $atts['id']; echo'(){
        changeImage_c'; echo $atts['id']; echo'(image_c'; echo $atts['id']; echo'+1);
      }
      function prevImage_c'; echo $atts['id']; echo'(){
        changeImage_c'; echo $atts['id']; echo'(image_c'; echo $atts['id']; echo'-1);
      }
      
      changeImage_c'; echo $atts['id']; echo'(1);

</script>    <style>
      *{        
        padding:0;
        margin:0%;
        list-style-type:none;
      }
      #slider_c'; echo $atts['id']; echo'{ border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(252,252,227);
        width:700px;
        height:400px;
        margin: auto ;
        position:relative;
      }
      #mask_c'; echo $atts['id']; echo'{
        
        width:100%;
        height:100%;
        position:relative;
        overflow:hidden;
      }
      .fleche_c'; echo $atts['id']; echo'{
        position:absolute;
        top:145px;
        cursor:pointer;
      }
      #fleche_gauche_c'; echo $atts['id']; echo'{
        left:-17px;
      }
      #fleche_droite_c'; echo $atts['id']; echo'{
        right: -17px;
      }
      #image_container_c'; echo $atts['id']; echo'{
        position:absolute;
        
        width:'; echo $nbre_slide; echo '00%;
        height:100%;
        /* La transition sur tout les navigateurs */
           /* Chrome */ 
          -webkit-transition-property:all;
          -webkit-transition-duration:1s;

           /* Firefox */ 
          -moz-transition-property:all;
          -moz-transition-duration:1s;
          
           /* Opera */ 
          transition-property:all;
          transition-duration:1s;

      }
      
      /* Les diff�rentes positions du slider */ ';
                      for ($i = 1; $i <= $nbre_slide; $i++){ $j=($i-1)*100;
                      echo '.image_c'; echo $atts['id']; echo $i; echo ' #image_container_c'; echo $atts['id']; echo'{
        left:-'; echo $j; echo '%;}'; }
        echo "

      
      /* Les images */
      #image_container_c"; echo $atts['id']; echo " li{
        float:left;
      }
      
   
      

      
      /* Les points de navigation */
      #dots_c"; echo $atts['id']; echo "{
	    position:absolute;
        width:"; echo 16*($nbre_slide); echo "px;
        height:30px;
        top:380px;
        left:"; echo (680-(16*($nbre_slide)))/2; echo "px;
        bottom:-25px;

      }
      
      /* les points, avec leur background non selectionn� */
      #dots_c"; echo $atts['id']; echo" li{
        float:left;
		text-align: center;
        margin: 0px 2px;
        width:12px;
        height:12px; ";
        echo "background: url('"; echo plugins_url(); echo "/studypress/images/empty-dot.png');";
        echo "cursor:pointer;
      }
      /* Point au survol */
      #dots_c"; echo $atts['id']; echo " li:hover{";
        echo "background: url('"; echo plugins_url(); echo "/studypress/images/selected-dot.png');}";
      
      for ($i = 1; $i < $nbre_slide; $i++){ 
      echo '.image_c'; echo $atts['id'];  echo $i; echo' #dots_c'; echo $atts['id']; echo' li.button_c'; echo $atts['id']; echo '_'; echo $i; echo', ';}
      echo ".image_c"; echo $atts['id']; echo $nbre_slide; echo" #dots_c"; echo $atts['id']; echo " li.button_c"; echo $atts['id']; echo '_'; echo $nbre_slide; echo "
      {";
        echo "background: url('"; echo plugins_url(); echo "/studypress/images/selected-dot.png');";
        echo "cursor:normal;
      }

      /* Un eyecandy */
      #glass{
        position:absolute;
        top:0px;
        left:0px;
      }
    </style>";
	 
  if (in_array( 'buddypress/bp-loader.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	?>

		<input style="float:right; font-size:140%; margin-right:160px; margin-top:5px;" type="submit" value="Share"  name="share_course" title="share the course" class="button-primary" /></br></br>
<?php
if ( isset($_POST['share_course']) ) {

     global $current_user;
               global $wpdb;
                foreach( $wpdb->get_results($wpdb->prepare("SELECT nom,cours_picture,cours_des FROM $studi_courses where course_id='".$atts['id'] ."'")) as $key => $row) {
                $name = sanitize_text_field($row-> nom);
				$cours_des=sanitize_text_field($row-> cours_des);
				$cours_picture =esc_url($row-> cours_picture, $protocols = null);}
    bp_activity_add( array(
        'user_id' => $current_user->ID,
        'action' => '<a href="/members/'.$current_user->user_login.'/" title="'.$current_user->user_login.'">'.$current_user->user_login.'</a> shared with you the course <a href="http://'. $_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'].'">'.$name.'</a>',
        'component' => 'Course',
        'type' => 'activity_update',
		'content' => '<a href="http://'. $_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'].'"><img src="'.$cours_picture.'" id="image_quiz"  style=" border-width:medium; border-style:solid; border-color:grey; " width="50" height="50" ></a>   '.$cours_des,
    ) );




}} ?> </form>
<?php
}
?>