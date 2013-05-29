<?php
function sub1() {
?>
<script type="text/javascript">

  function cocher(){
    var tags =  document.getElementsByTagName("input");
    for(var i=0; i<tags.length; i++)
     {tags[i].checked=true;}}
     
  function decocher(){
    var tags =  document.getElementsByTagName("input");
    for(var i=0; i<tags.length; i++)
     {tags[i].checked=false;}}
     
</script>
<?php
   global $studi_courses;
   global $studi_slides;
   global $studi_category;
   global $studi_categ_cours;
   global $studi_quiz;

  if(isset($_POST['delete_course'])){
  $cours_list = implode(', ', $_POST['list']);
  global $wpdb;
  $req3=$wpdb->prepare("DELETE FROM $studi_slides WHERE course_id IN(".$cours_list.")");
  $wpdb->query($req3);  
  $req=$wpdb->prepare("DELETE FROM $studi_categ_cours WHERE course_id IN(".$cours_list.")");
  $wpdb->query($req);  
  $req2=$wpdb->prepare("DELETE FROM $studi_courses WHERE course_id IN(".$cours_list.")");
  $wpdb->query($req2);}
?>
  </br>&nbsp;&nbsp;<label style=" font-size:200%;">Courses</label>&nbsp;&nbsp;
  <a href="admin.php?page=id_sub1"><input style=" font-size:100%;" type="submit" value="Add new" name="add_new" title="Add new course" class="button-primary" size="20" tabindex="1" autocomplete="off" /></a>&nbsp;&nbsp;</br></br>
  
  <form method="POST"  id="showcourse" name="showcourse" enctype="multipart/form-data" >    
    <input type="submit" id='delete_course' name='delete_course'  title="delete course" value="Delete"  />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<select name="filtrer_cat" >
		           <option value="All" >ALL Quizs</option>
			   <?php     global $wpdb;
                         foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent is NULL")) as $key => $row) {
                // each column in your row will be accessible like this
                $nom_cat = sanitize_text_field($row-> cat_name);
                $id = sanitize_text_field($row-> cat_id);
                $id_parent = sanitize_text_field($row-> cat_parent);
                     ?>
				  <option value="<?php echo $id; ?>" ><?php echo $nom_cat;?></option><?php 
				   foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent='".$id."'")) as $key1 => $row1){
				    $nom1 = sanitize_text_field($row1-> cat_name);
                    $id1 = sanitize_text_field($row1-> cat_id);
                    $id_parent1 = sanitize_text_field($row1-> cat_parent); ?>
				    <option value="<?php echo $id1; ?>" >&nbsp;&nbsp;<?php echo $nom1;?></option><?php 
				                    }}
				                                                         ?>
  &nbsp;&nbsp;<input type="submit" id='filtre_course' name='filtre_course'  title="course filtrer" value="Filter"  /></br></br>
																		 
    <table cellpadding="5" cellspacing="0" border="1" class="widefat">
      <thead>
        <tr><th width="5px"><input type="checkbox"  onclick="if(this.checked){ cocher(); }else{decocher();}"> </th>
            <th width="100px">Name</th>
            <th width="150px">Description</th>
            <th width="100px">Categories</th>
            <th width="100px">Shortcode</th>
            <th width="100px">Duration</th>
            <th width="100px">Author</th>
        </tr>
      </thead>
    </table>

<?php
  if(isset($_POST['filtre_course'])){
       if($_POST['filtrer_cat']=='All'){
  $req=$wpdb->get_results($wpdb->prepare("SELECT * FROM $studi_courses"));
   global $wpdb;
			 foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr 
			                              FROM $studi_courses")) 
								as $key => $row)
             {$cours_nbr = $row-> nbr;}
}
	else {
        $req=$wpdb->get_results($wpdb->prepare("SELECT * 
		       FROM $studi_courses
			   where course_id IN (select course_id
			                       from $studi_categ_cours
								   where cat_id='".$_POST['filtrer_cat']."')"));
			 foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr 
		       FROM $studi_courses
			   where course_id IN (select course_id
			                       from $studi_categ_cours
								   where cat_id='".$_POST['filtrer_cat']."')"))
								as $key => $row)
             {$cours_nbr = $row-> nbr;}	}}	
	else {
  $req=$wpdb->get_results($wpdb->prepare("SELECT * FROM $studi_courses"));
   global $wpdb;
			 foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr 
			                              FROM $studi_courses")) 
								as $key => $row)
             {$cours_nbr = $row-> nbr;}}
 if($cours_nbr==0){  ?>
  <table cellpadding="5" cellspacing="0"  border="2" class="widefat">
            <tr><th width="20px"><strong>No entries.</strong><th width="5px"></tr>
  </table>
<?php
 }
 else{
  foreach($req as $key => $row_each){$course_id= sanitize_text_field($row_each->course_id);
                                     $cours_desc= sanitize_text_field($row_each->cours_des);
                                     $nom= sanitize_text_field($row_each->nom);
									 $shortcode= sanitize_text_field($row_each->shortcode);
									 $duration= sanitize_text_field($row_each->duration);
									 $author= sanitize_text_field($row_each->author);
  
    echo '<table cellpadding="5" cellspacing="0"  border="2" class="widefat">
            <tr>
             <th width="5px"><input type="checkbox" name="list[]" value="',$course_id,'" /></th>
             <th width="100px"><a href="admin.php?page=id_sub1&id=';print_r ($course_id);echo'">';print_r ($nom);echo'</th>
             <th width="150px">';print_r ($cours_desc);echo'</th>
             <th width="100px">';          
			 foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_name 
                                          FROM $studi_categ_cours, $studi_category
		                                  where $studi_categ_cours.cat_id=$studi_category.cat_id
										  AND $studi_categ_cours.course_id='".$course_id."'")) as $key => $row)
                {$cat_name = sanitize_text_field($row-> cat_name);
				 print_r ('-'.$cat_name); echo'</br>';}echo'</th>
		 <th width="100px">';print_r ($shortcode);echo'</th>
	     <th width="100px">';print_r ($duration);echo' mn</th>
	     <th width="100px">';print_r ($author);echo'</th>
           </tr>
         </table>';?><?php
}}?>

  <table cellpadding="5" cellspacing="0" border="1" class="widefat">
    <thead>
      <tr>
        <th width="5px"><input type="checkbox"  onclick="if(this.checked){ cocher(); }else{decocher();}"> </th>
        <th width="100px">Name</th>
        <th width="150px">Description</th>
        <th width="100px">Categories</th>
        <th width="100px">Shortcode</th>
        <th width="100px">Duration</th>
        <th width="100px">Author</th>
     </tr>
   </thead>
 </table>
 </form>

  
<?php
 }

function sub2() {
   global $studi_courses;
   global $studi_slides;
   global $studi_category;
   global $studi_categ_cours;

  global $wpdb;
  foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_id,slides_name,slides_content,slides_order
                                              FROM $studi_slides                                              
                                              WHERE slides_id='".$_GET['id_slide']."'")) as $key1 => $row1) {
                                                                                            $content = $row1-> slides_content;
                                                                                            $slides_id = sanitize_text_field($row1-> slides_id);
                                                                                            $slides_name = sanitize_text_field($row1-> slides_name);
                                                                                            $slides_order = sanitize_text_field($row1-> slides_order);}
                                                       
  global $wpdb;
  foreach( $wpdb->get_results($wpdb->prepare("SELECT nom,duration,author,cours_des,cours_picture
                                              FROM $studi_courses
                                               where course_id='".$_GET['id']."'")) as $key => $row) {
                                                                                     $nom = sanitize_text_field($row-> nom);
                                                                                     $duration = sanitize_text_field($row-> duration);
                                                                                     $author = sanitize_text_field($row-> author);
																					 $cours_picture = sanitize_text_field($row-> cours_picture);
																					 $cours_desc = sanitize_text_field($row-> cours_des);}

  global $wpdb;
  
  $req111=$wpdb->get_results($wpdb->prepare("select slides_id,slides_name,slides_order
                                             FROM $studi_slides
                                             WHERE course_id='".$_GET['id']."'
                                             ORDER BY slides_order ASC"));
  $req112=$wpdb->get_results($wpdb->prepare("select slides_id,slides_name,slides_order
                                             FROM $studi_slides
                                             WHERE course_id >= ALL(SELECT course_id 
											                        FROM $studi_courses)
                                             ORDER BY slides_order ASC"));
?>  <form id="monform" name="form1" method="post" enctype="multipart/form-data" >
  </br>&nbsp;&nbsp;<label style=" font-size:200%;">Course Creation</label>&nbsp;&nbsp;</br></br></br>
<?php global $new;    
  if ( isset($_POST['add']) ) {
      $nom = sanitize_text_field(stripslashes($_POST['post_title']));
      $author = sanitize_text_field(stripslashes($_POST['author']));
      $duration = sanitize_text_field(stripslashes($_POST['duration']));
	  $cours_desc = sanitize_text_field(stripslashes($_POST['cours_desc']));
      $cours_picture = esc_url(stripslashes($_POST['cours_picture']), $protocols = null);
      global $wpdb;
			  if(empty($nom) or empty($author) or empty($duration)){?>
                <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%; ">The courses has'n been created or modified,  filled all the fields !!</label></div></br></br><?php           
		   }
	else{
	if(empty($_POST['cat_list'])){
	    ?>
                <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">Please, choose one or more categories !</label></div></br></br><?php }
				else{if(!filter_var($duration,FILTER_VALIDATE_INT)){					    ?>
                <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">The duration that you entered is not an integer!</label></div></br></br><?php }
		/*		if(filter_var($cours_picture,FILTER_VALIDATE_URL)===false){					    ?>
                <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">The picture URL that you entered is not an URL!</label></div></br></br><?php } */
				else{

      if(!empty($_GET['id'])){ 

		  
          global $wpdb;
          $req5=$wpdb->prepare("UPDATE $studi_courses 
		                        SET nom = '".$nom."',`duration` = '".$duration."',`author` = '".$author."', cours_des='".$cours_desc."',`cours_picture` = '".$cours_picture."' 
								WHERE course_id ='".$_GET['id']."'");
          $wpdb->query($req5);
		  
	      $catList = implode(', ', $_POST['cat_list']);
	      $req12=$wpdb->get_results($wpdb->prepare("select  cat_id
		                                            from $studi_category
			                                        where cat_id IN(".$catList.")"));
		  
		  foreach($req12 as $key => $row ){$cat_id = $row-> cat_id; 
		    $data11 = array( 'course_id' => $_GET['id'], 'cat_id' => $cat_id);  
          $result =$wpdb->prepare($wpdb->insert( $studi_categ_cours, $data11));
		}
		  $req4=$wpdb->prepare("DELETE FROM $studi_categ_cours 
		                        WHERE course_id='".$_GET['id']."' 
								and cat_id  NOT IN(".$catList.")");
          $wpdb->query($req4);


?>
              <div style="width:98%; height:4%;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,255,204); float:left;">
              &nbsp;&nbsp;<label style="height:5%;">The course has been modified : <?php print_r ($nom); ?></label>
              </div></br></br>
              <?php  }
              else{
          global $wpdb; $ids=stripslashes($_POST['ids_cours']); 
          foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id          
		                                              FROM $studi_courses 
													  where nom = '".$ids."'")) as $key => $row)
                {$id = sanitize_text_field($row-> course_id); } 
		if(!empty($id)){
          $req11=$wpdb->prepare("UPDATE $studi_courses 
		                         SET nom = '".$nom."',`duration` = '".$duration."',`author` = '".$author."', cours_des='".$cours_desc."',`cours_picture` = '".$cours_picture."'
                                 WHERE course_id='".$id."'");
			$wpdb->query($req11);
				      $catList = implode(', ', $_POST['cat_list']);
	      $req12=$wpdb->get_results($wpdb->prepare("select  cat_id
		                                            from $studi_category
			                                        where cat_id IN(".$catList.")"));
		  
		  foreach($req12 as $key => $row ){$cat_id = sanitize_text_field($row-> cat_id); 
		    $data11 = array( 'course_id' => $id, 'cat_id' => $cat_id);  
          $result =$wpdb->prepare($wpdb->insert( $studi_categ_cours, $data11));
		}
		  $req4=$wpdb->prepare("DELETE FROM $studi_categ_cours 
		                        WHERE course_id='".$id."' 
								and cat_id  NOT IN(".$catList.")");
          $wpdb->query($req4);
													 
																 ?>
			
		   <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,255,204); float:left;">
           &nbsp;&nbsp;<label style="height:5%;">The course has been modified : <?php print_r ($nom); ?></label>
           </div></br></br><input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r($nom); ?>" />
                  <?php   }
         else{

          $data = array( 'nom' => $nom, 'duration' => $duration, 'author' => $author, 'cours_des' => $cours_desc,'shortcode' =>  $short, 'cours_picture'=> $cours_picture ); 
          global $wpbd;
          $result =$wpdb->prepare($wpdb->insert( $studi_courses, $data));
          foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id 
		                                              FROM $studi_courses 
													  where course_id >= ALL(SELECT course_id 
													                         FROM $studi_courses)")) as $key => $row)
                {$id = sanitize_text_field($row-> course_id);}
          $short= '[stps_shortcode id="'.$id.'"]';
          $req11=$wpdb->prepare("UPDATE $studi_courses 
		                         SET shortcode = '".$short."' 
								 WHERE course_id='".$id."'");
		  $wpdb->query($req11);

	  $catList = implode(', ', $_POST['cat_list']);
	                                $req12=$wpdb->get_results($wpdb->prepare("select  cat_id
									       from $studi_category
										   where cat_id IN(".$catList.")"));
		foreach($req12 as $key => $row ){$cat_id=sanitize_text_field($row-> cat_id); 
		          $data11 = array( 'course_id' => $id, 'cat_id' => $cat_id);  
          global $wpbd;
          $result =$wpdb->prepare($wpdb->insert( $studi_categ_cours, $data11));
		}
          foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id FROM $studi_courses where nom = '".$nom."' ")) as $key => $row)
                {$id = sanitize_text_field($row-> course_id);} 
		  if($id ==''){?>
                <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">The course has'n been created : <?php print_r ($nom); ?></label></div></br></br>
				<input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r($nom); ?>" />
				<?php

		  }
		  else{?>
                <div style="width:98%; height:25px;   border-width:thin; border-style:solid; border-color:rgb(0,0,0); background:rgb(255,255,204); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">The course has been created : <?php print_r ($nom); ?></label>
				</div></br></br><input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r($nom); ?>" />
				<?php 				 
				}
					 }}}
}}}
          
      if ( isset($_POST['delete_slide']) ) {
	  				                  global $wpdb;
	    if (empty($_POST['id_rep'])){} else {
              $slideList = implode(', ', $_POST['id_rep']); 
               $req=$wpdb->get_results($wpdb->prepare("SELECT slides_id,course_id,slides_order
			                                           FROM $studi_slides 
					                                   where slides_id IN(".$slideList.")"));
			foreach($req as $key => $row ){ $slides_id=sanitize_text_field($row-> slides_id);
			                                $course_id=sanitize_text_field($row-> course_id);
											$slides_order=sanitize_text_field($row-> slides_order);
                  $req2=$wpdb->prepare("DELETE FROM $studi_slides WHERE slides_id='".$slides_id."'");
                  $wpdb->query($req2);
			  $req3=$wpdb->prepare("UPDATE $studi_slides SET slides_order=slides_order-1 
			                                where course_id = '".$course_id."' 
											and slides_order >= '".$slides_order."'");
              $wpdb->query($req3);
} 

			  ?><input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r($_POST['post_title']); ?>" />
				<?php
}}
        


          ?>

		
  <div style="float:left; width:98%;">
	   <div style="float:right; width:25%;">
	   <div style="float:right; width:100%;">
       <table class="widefat">
        <thead>
          <tr>
            <th>Categories</th>
          </tr>
		</thead>
		<tr> <?php 			 
		     foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr 
			                                             FROM $studi_category")) as $key => $row)
             {$cat_nbr = $row-> nbr;} 
          if($cat_nbr==0){  ?>          	
		    <th width="5px">
			  <label style="font-size:80%; text-decoration:underline;">No categories !, add new category </label></br></br></th></tr>
			  <?php } else { ?>		
          	<th width="5px">
			  <label style="font-size:80%; text-decoration:underline;">Choice one or more categories</label></br></br></th></tr>
			  <?php 
			  if(empty($_GET['id'])){
			                foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id 
							                                            FROM $studi_courses 
																		where course_id >= ALL(SELECT course_id 
																		                       FROM $studi_courses)")) as $key => $row)
                           {$id = $row-> course_id;}
						   $cat_check=sanitize_text_field($id);}
				else{$cat_check=$_GET['id'];}
			        global $wpdb;
			        $req=$wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent is NULL"));
			        $req1=$wpdb->get_results($wpdb->prepare("SELECT cat_name, cat_id
                                                             FROM $studi_category
                                                             WHERE cat_id IN ( SELECT cat_id
                                                                               FROM $studi_categ_cours
                                                                               WHERE course_id = '".$cat_check."')"));
					 foreach($req1 as $key => $row_cat ){$List_cat[]= sanitize_text_field($row_cat-> cat_name);} 

foreach( $wpdb->get_results($wpdb->prepare("SELECT count(cat_id) as number
                                            FROM $studi_category
                                            WHERE cat_id IN ( SELECT cat_id
                                                              FROM $studi_categ_cours
                                                              WHERE course_id = '".$cat_check."')")) as $key => $row)
                    {				    $number = $row-> number;} 
                    foreach($req as $key => $row_each){ $cat_id= sanitize_text_field($row_each-> cat_id);
					                                    $cat_name= sanitize_text_field($row_each-> cat_name);
														$cat_parent= sanitize_text_field($row_each-> cat_parent);
	
				                echo '<tr>
                                         <td>
										  <input type="checkbox"';
for ($i = 0; $i < $number; $i++){if($cat_name==$List_cat[$i]){echo 'checked="checked"';}}										  
                                       echo 'name="cat_list[]" value="',$cat_id,'" />&nbsp;&nbsp;';
                                            print_r ($cat_name);
                                     echo'</td>
                                        </tr>';
	   
						   
			          $req2=$wpdb->get_results($wpdb->prepare("SELECT cat_id, cat_name, cat_parent
                                                               FROM wp_studi_category
                                                               WHERE cat_parent='".$cat_id."'"));
                         foreach($req2 as $key => $row_each1){$cat_id1= sanitize_text_field($row_each1-> cat_id);
					                                    $cat_name1= sanitize_text_field($row_each1-> cat_name);
														$cat_parent1= sanitize_text_field($row_each1-> cat_parent);
                           echo '<tr>
                              <td>&nbsp;&nbsp;&nbsp;<input type="checkbox"';
                              for ($i = 0; $i < $number; $i++){
							     if($cat_name1==$List_cat[$i]){
								    echo 'checked="checked"';    }   }										  
							 echo 'name="cat_list[]" value="',$cat_id1,'" />&nbsp;&nbsp;';
                                 print_r ($cat_name1);

                        echo'</td>
                           </tr>';
                						 }}}
									 ?>
		<thead><tr>
		  <th>
  <label style="font-size:80%; text-decoration:underline;">Add new category </label></br>
  <label for="cat_new" title="The name of category" style=" font-size:80%;">Name</label>&nbsp;&nbsp;</br>
  <input type="text" title="The name of category" value="" name="cat_new" size="17" tabindex="1" autocomplete="off" /></br>
  <label for="cat_par_new" title="The name of category" style=" font-size:80%;">Parent</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;<select style=" font-size:80%;" name="cat_par_new" >
               <option style=" font-size:80%;" value="Neither">Neither</option>
			   <?php     global $wpdb;
                         foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent 
						                                             FROM $studi_category 
																	 where cat_parent is NULL")) as $key => $row) {
                $nom_cat = sanitize_text_field($row-> cat_name);
                $id = sanitize_text_field($row-> cat_id);
                $id_parent = sanitize_text_field($row-> cat_parent);
                     ?>
				  <option value="<?php echo $id; ?>" ><?php echo $nom_cat;?></option><?php 
				   foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent 
				                                               FROM $studi_category 
															   where cat_parent='".$id."'")) as $key1 => $row1){
				    $nom1 = sanitize_text_field($row1-> cat_name);
                    $id1 = sanitize_text_field($row1-> cat_id);
                    $id_parent1 = sanitize_text_field($row1-> cat_parent); ?>
				    <option value="<?php echo $id1; ?>" >&nbsp;&nbsp;<?php echo $nom1;?></option><?php 
				                    }}
				                                                         ?>
				     				                                                                                                                } ?>
           </select></br></br>
           <input style="" style="font-size:80%;" type="submit" value="&nbsp;&nbsp;Add a new category" name="add_cat" title="Add a new category" class="button-primary" />		   
		  </th>
		</tr></thead>
<?php  if ( isset($_POST['add_cat']) ) { 
	    $name = sanitize_text_field(stripslashes($_POST['cat_new']));
		$parent =NULL;
	    $desc = NULL;
		$nbre = 0;
	    if($_POST['cat_par_new'] ==Neither){
		          		        global $wpdb;
                                $req5=$wpdb->prepare("INSERT INTO $studi_category (`cat_id`, `cat_name`, `cat_des`, `cat_parent`, `courses_nbr`) 
								                                            VALUES (NULL, '$name', '$desc', NULL, '$nbre')");
                                $wpdb->query($req5);}
				 else {	    $parent = stripslashes($_POST['cat_par_new']);
				            global $wpdb;
                            $req5=$wpdb->prepare("INSERT INTO $studi_category (`cat_id`, `cat_name`, `cat_des`, `cat_parent`, `courses_nbr`) 
						                                                VALUES (NULL, '$name', '$desc', '$parent', '$nbre')");
                             $wpdb->query($req5);}
				?> <input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r($_POST['post_title']); ?>" /> <?php
                                   }?>         
	   </table>
</div>
       </div>
     <div style="float:left; width:74%;">
    <div style="width:100%; float:left;">
	<table class="widefat">
      <thead>
        <tr>
          <th>Course creation<input class="button-primary" style="float:right;" type="submit" value="&nbsp;&nbsp;save&nbsp;&nbsp;" name="add" ></th>
        </tr>
		</thead>
        <tr>
          <th> 
    <div style="width:35%; float:left;">		  
		  <?php if ( isset($_POST['add']) or isset($_POST['new_slide']) or isset($_POST['delete_slide']) or isset($_POST['save_slide']) or isset($_POST['add_cat']) ) {
		                                    $nom = sanitize_text_field(stripslashes($_POST['post_title']));
                                            $author = sanitize_text_field(stripslashes($_POST['author']));
                                            $duration = sanitize_text_field(stripslashes($_POST['duration']));
											$cours_desc = sanitize_text_field(stripslashes($_POST['cours_desc']));
                                            $cours_picture = esc_url(stripslashes($_POST['cours_picture']), $protocols = null);} ?>
        <label for="post_title" title="The name of course" style=" font-size:110%;">Course name</label></br>
        <input type="text" value="<?php print_r ($nom);?>" style=" font-size:100%; height:25px;" name="post_title" title="The name of course" size="45" tabindex="1" autocomplete="off" />
        <?php
  foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id
                                              FROM $studi_courses
                                              where nom='".$nom."'")) as $key => $row) {$course_id = sanitize_text_field($row-> course_id);} ?>
        </br></br>
      

      
        <label for="author" title="course author" style=" font-size:110%;">Author</label></br>
        <input type="text" id="author" value="<?php print_r ($author);?>" style=" font-size:100%; height:25px;" name="author" name="author" title="course author" size="45" tabindex="1"   /></br></br>

		<label for="cours_picture" title="Cours picture" style=" font-size:110%;">Picture URL</label></br>
        <input type="text"  style=" font-size:100%; height:25px;" value="<?php print_r ($cours_picture);?>" name="cours_picture" title="The cours picture" size="45" tabindex="1" autocomplete="off" /></br></br>     

      <div style="float:left; width:100%;">
	   <div style="float:left; width:40%;">
        <label for="duration" title="course duration" style=" font-size:110%;">Duration (mn)</label></br>
        <input type="text" id="duration" value="<?php print_r ($duration);?>" style=" font-size:100%; height:25px;" name="duration" title="course duration" size="8" tabindex="1" autocomplete="off"  />
		</div>
	<?php
     if(empty($_GET['id'])) {
	 if ( isset($_POST['add']) or isset($_POST['delete_slide']) or isset($_POST['new_slide']) or isset($_POST['save_slide']) or isset($_POST['add_cat'])  ) {
   if(!empty($nom) && !empty($author) && !empty($duration)  && !empty($_POST['cat_list'])){
	 foreach( $wpdb->get_results($wpdb->prepare("SELECT shortcode
                                                 FROM $studi_courses
                                                 where nom='".$nom."'")) as $key => $row){$shortcode = sanitize_text_field($row-> shortcode);}
       ?><div style="float:right; width:60%;">
	     <label  title="course shortcode" style=" font-size:110%;">Shortcode</label></br>
	     <label  title="course shortcode" style=" font-size:110%;"><?php echo $shortcode; ?></label>
	 </div><?php }}}
         else {
	      foreach( $wpdb->get_results($wpdb->prepare("SELECT shortcode
                                                      FROM $studi_courses
                                                      where course_id='".$_GET['id']."'")) as $key => $row){$shortcode = sanitize_text_field($row-> shortcode);}
       ?><div style="float:right; width:60%;">
	     <label  title="course shortcode" style=" font-size:110%;">Shortcode</label></br>
	     <label  title="course shortcode" style=" font-size:110%;"><?php echo $shortcode; ?></label>
	 </div><?php } ?>							   
</div>
</div>
			<div style="float:left; width:25%; margin-left:20px;">
              <label for="post_title" title="cours description " style=" font-size:110%;">Description (optional)</label></br>
			  <textarea title="The cours description"  name="cours_desc" ROWS="8" cols="30"><?php print_r ($cours_desc);?></textarea>
			</div>	
<?php 
     if($_GET['id']){ 
          $pict=$cours_picture; }
		  else {$pict=esc_url(stripslashes($_POST['cours_picture']), $protocols = null);}
		  if(!empty($pict)){	$k=2;
             if ( isset($_POST['add']) or isset($_POST['new_slide']) or isset($_POST['delete_slide']) or isset($_POST['save_slide']) or isset($_POST['add_cat']) or $_GET['id'] ) {
               if(!$_GET['id']){if(empty($nom) or empty($author) or empty($duration)  or empty($_POST['cat_list'])){$k=1;}}
             if($k=='2'){	 
		       ?>
		       <div style="float:right; width:25%; margin-right:90px;">

                <img src="<?php echo $pict; ?>" id="image_quiz"  style="margin:17px 20px 10px 40px; border-width:medium; border-style:solid; border-color:grey; " width="200" height="200" >

		       </div>
		       <input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r(sanitize_text_field($_POST['post_title'])); ?>" /><?php }}} ?>	
          </th>
		</tr>
		        
      </table>

	  </div>



	<div style="float:left; width:100%; margin-top:10px;"> <?php
	$k=2;
if ( isset($_POST['add']) or isset($_POST['new_slide']) or isset($_POST['delete_slide']) or isset($_POST['save_slide']) or isset($_POST['add_cat']) or $_GET['id'] ) {
if(!$_GET['id']){if(empty($nom) or empty($author) or empty($duration)  or empty($_POST['cat_list'])){$k=1;}}
if($k=='2'){
   ?>
     <div style="float:left; width:20%;">
      <table class="widefat" style="">
        <thead>
          <tr>
            <th>Sliders</th>
          </tr>
          <tr>
            <th><input type="submit" style="margin-left:30px;" name='new_slide' title="new slide" value="New"  />
<?php 
   foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id
                                               FROM $studi_courses
                                               where nom='".$_POST['post_title']."'")) as $key => $row) {$course_id = sanitize_text_field($row-> course_id);}  ?>
            <input type="submit" name='delete_slide' title="delete slide" value="Delete"  /></br>
			<label  style="font-size:80%; margin-left:27px; text-decoration:underline;" onclick="cocher();">Mark</label>/<label style="font-size:80%; text-decoration:underline;" onclick="decocher();">Unmark</label><label style="font-size:80%;">&nbsp;&nbsp;All</label></th>
          </tr>

<?php 			
                 foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr 
                                                             FROM $studi_slides 
															 where course_id='".$course_id."' ")) as $key => $row)
             {$slide_nbr = $row-> nbr;}
 if($slide_nbr==0){ 
 ?> 
             <tr>
                        <td>&nbsp;&nbsp;Add new slide</td></tr>
       <?php }
 else{if($_GET['id'] ){ 
	   foreach($req111 as  $key => $row111){$slides_id= sanitize_text_field($row111-> slides_id);
	                                        $slides_name= sanitize_text_field($row111-> slides_name);
											$slides_order= sanitize_text_field($row111-> slides_order);                   
            echo '<tr>
                    <td>
                      <input type="checkbox" name="id_rep[]" value="',$slides_id,'" />&nbsp;&nbsp;';
                                                       print_r ($slides_order);
                      echo ' - <a href="admin.php?page=id_sub1&id=';
                               print_r ($_GET['id']);
                               echo'&id_slide=';
                               print_r ($slides_id);
                               echo'&name_slide=';
                               print_r ($slides_name);            
                               echo'">';
                        print_r ($slides_name);
              echo'</a></td>
                 </tr>';}}
				 else{if(isset($_POST['add']) or isset($_POST['delete_slide']) or isset($_POST['add_cat'])){  
				 foreach($req112 as  $key => $row112){ 
				                            $slides_id= sanitize_text_field($row112-> slides_id);
	                                        $slides_name= sanitize_text_field($row112-> slides_name);
											$slides_order= sanitize_text_field($row112-> slides_order);                  
            echo '<tr>
                    <td>
                      <input type="checkbox" name="id_rep[]" value="',$slides_id,'" />&nbsp;&nbsp;';
                                                       print_r ($slides_order);
                      echo ' - <a href="admin.php?page=id_sub1&id=';
                               print_r ($course_id);
                               echo'&id_slide=';
                               print_r ($slides_id);
                               echo'&name_slide=';
                               print_r ($slides_name);            
                               echo'">';
                        print_r ($slides_name);
              echo'</a></td>
                 </tr>';}}}}
                 
      if ( isset($_POST['new_slide']) ){
        $slides_name='';
        $slides_order='';
        $content='';

						
            if(!$_GET['id'] ){
            global $wpdb;
            foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id 
			                                            FROM $studi_courses 
														where course_id >= ALL(SELECT course_id 
														                       FROM $studi_courses)")) as $key => $row)
                {$id = sanitize_text_field($row-> course_id);}
            foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_order 
			                                            FROM $studi_slides 
														where course_id='".$id."' 
														and slides_order >= ALL(SELECT slides_order 
														                        FROM $studi_slides 
																				where course_id='".$id."')")) as $key => $row1)
             {$slides_order = sanitize_text_field($row1-> slides_order); }
			if(empty($slides_order)){$max_order=1;} else{$max_order=$slides_order+1;}
                $data = array( 'course_id' => $id, 'slides_name' => 'New slide', 'slides_content' => '', 'slides_order' => $max_order );
                $result =$wpdb->prepare($wpdb->insert( $studi_slides, $data));
                              
                $req=$wpdb->get_results($wpdb->prepare("select slides_id,slides_name,slides_order
                                                        FROM $studi_slides,$studi_courses
                                                        WHERE $studi_slides.course_id=$studi_courses.course_id
                                                        AND $studi_courses.course_id='".$id."'
                                                        ORDER BY slides_order ASC"));
                foreach($req as $key => $row){$slides_id = sanitize_text_field($row-> slides_id);
				                            $slides_name = sanitize_text_field($row-> slides_name);
											$slides_order= sanitize_text_field($row-> slides_order);
                      echo '<tr>
                              <td><input type="checkbox" name="id_rep[]" value="',$slides_id,'" />&nbsp;&nbsp;';
                                 print_r ($slides_order);
                                echo ' - <a href="admin.php?page=id_sub1&id=';
                                       print_r ($id);
                                       echo'&id_slide=';
                                       print_r ($slides_id);
                                       echo'&name_slide=';
                                       print_r ($slides_name);            
                                       echo'">';
                                print_r ($slides_name);
                        echo'</a></td>
                           </tr>';}
                     }
              else {
             foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_order 
			                                             FROM $studi_slides 
														 where course_id='".$_GET['id']."' 
														 and slides_order >= ALL(SELECT slides_order 
														                         FROM $studi_slides 
																				 where course_id='".$_GET['id']."')")) as $key => $row1)
             {$slides_order = sanitize_text_field($row1-> slides_order); }
			 if(empty($slides_order)){$max_order=1;} else{$max_order=$slides_order+1;}
                  $data = array( 'course_id' => $_GET['id'], 'slides_name' => 'New slide', 'slides_content' => '', 'slides_order' => $max_order );
                $result =$wpdb->prepare($wpdb->insert( $studi_slides, $data));
				global $wpdb;
                foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_id 
				                                            FROM $studi_slides 
															where slides_id >= ALL(SELECT slides_id 
															                       FROM $studi_slides)")) as $key => $row1)
                            {$ids1 = sanitize_text_field($row1-> slides_id); 
		     echo '
                                    <input type="hidden" name="ids_new" value="',$ids1,'" />';	
                       echo '<tr>
                              <td><input type="checkbox" name="id_rep[]" value="',$ids1,'" />&nbsp;&nbsp;';
                                 print_r ($data['slides_order']);
                                echo ' - <a href="admin.php?page=id_sub1&id=';
                                       print_r ($_GET['id']);
                                       echo'&id_slide=';
                                       print_r ($ids1);
                                       echo'&name_slide=';
                                       print_r ('New slide');            
                                       echo'">';
                                print_r ('New slide');
                        echo'</a></td>
                           </tr>';}
      }
}

      if ( isset($_POST['save_slide']) ) {
	  		$idss = sanitize_text_field(stripslashes($_POST['ids_new']));
	  $ids_test=sanitize_text_field(stripslashes($_POST['ids_new_test']));
	  if($ids_test !=''){$idss=$ids_test;}
	  
        $nom = sanitize_text_field(stripslashes($_POST['post_title']));
        $slide_name = sanitize_text_field(stripslashes($_POST['name_slide']));
        $contenue = stripslashes($_POST['content']);
        $order = sanitize_text_field(stripslashes($_POST['order']));
		            global $wpdb;
                    foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_id 
					                                            FROM $studi_slides 
																where slides_id >= ALL(SELECT slides_id 
																                       FROM $studi_slides)")) as $key => $row1)
                            {$ids2 = sanitize_text_field($row1-> slides_id); }

    
        if(!$_GET['id_slide']){
          if(!$_GET['id']){
            global $wpdb;
            foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id 
			                                            FROM $studi_courses 
														where course_id >= ALL(SELECT course_id 
														                       FROM $studi_courses)")) as $key => $row)
                {$id = sanitize_text_field($row-> course_id);}
            foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_id 
			                                            FROM $studi_slides 
														where slides_id >= ALL(SELECT slides_id 
														                       FROM $studi_slides)")) as $key => $row1)
                {$ids = sanitize_text_field($row1-> slides_id); }
            global $wpdb;
                                $req5=$wpdb->prepare("UPDATE $studi_slides
                                      SET slides_order = slides_order+1
                                      WHERE slides_order >= '".$order."'
                                      AND course_id='".$id."'");
                                $wpdb->query($req5);
            
               $req=$wpdb->prepare("UPDATE $studi_slides
                                    SET slides_name = '".$slide_name."',`slides_content` = '".$contenue."', slides_order = '".$order."'
                                    WHERE slides_id = '".$ids."'");
               $wpdb->query($req);                                              
                $req=$wpdb->get_results($wpdb->prepare("select slides_id,slides_name,slides_order
                                                        FROM $studi_slides,$studi_courses
                                                        WHERE $studi_slides.course_id=$studi_courses.course_id
                                                        AND $studi_courses.course_id='".$id."'
                                                        ORDER BY slides_order ASC"));
                foreach($req as $key => $row){$slides_id = sanitize_text_field($row-> slides_id);
				                            $slides_name = sanitize_text_field($row-> slides_name);
											$slides_order = sanitize_text_field($row-> slides_order);
                      echo '<tr>
                              <td><input type="checkbox" name="id_rep[]" value="',$slides_id,'" />&nbsp;&nbsp;';
                                 print_r ($slides_order);
                                echo ' - <a href="admin.php?page=id_sub1&id=';
                                       print_r ($id);
                                       echo'&id_slide=';
                                       print_r ($slides_id);
                                       echo'&name_slide=';
                                       print_r ($slides_name);            
                                       echo'">';
                                print_r ($slides_name);
                        echo'</a></td>
                           </tr>';
                     }}
          else {
                       global $wpdb;        
                       foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_id 
					                                               FROM $studi_slides 
																   where slides_id >= ALL(SELECT slides_id 
																                          FROM $studi_slides)")) as $key => $row1)
                            {$ids = sanitize_text_field($row1-> slides_id); }
                       global $wpdb;
                       $req5=$wpdb->prepare("UPDATE $studi_slides
                                             SET slides_order = slides_order+1
                                             WHERE slides_order >= '".$order."'
                                             AND course_id='".$_GET['id']."'");
                       $wpdb->query($req5);

               $req=$wpdb->prepare("UPDATE $studi_slides
                                    SET slides_name = '".$slide_name."',`slides_content` = '".$contenue."', slides_order = '".$order."'
                                    WHERE slides_id ='".$ids."' 
									AND course_id='".$_GET['id']."'");
                $wpdb->query($req);
}}
       else {
                  global $wpdb;
                  $req5=$wpdb->prepare("UPDATE $studi_slides
                                      SET slides_order = slides_order+1
                                      WHERE slides_order >= '".$order."' AND slides_order < '".$slides_order."'
                                      AND course_id='".$_GET['id']."'");
                   $wpdb->query($req5);
 
                 if ($idss == $ids2){ 
                         $req=$wpdb->prepare("UPDATE $studi_slides
                                             SET slides_name = '".$slide_name."',`slides_content` = '".$contenue."', slides_order = '".$order."'
                                             WHERE slides_id ='".$idss."'");
                         $wpdb->query($req);
						echo '<input type="hidden" name="ids_new_test" value="',$idss,'" />';
}
                 if($idss != $ids2)  {                               
                      global $wpdb;
                      $req5=$wpdb->prepare("UPDATE $studi_slides
                                            SET slides_name = '".$slide_name."',`slides_content` = '".$contenue."', slides_order = '".$order."'
                                            WHERE slides_id ='".$_GET['id_slide']."'");
                      $wpdb->query($req5);
                               }}                                   

          unset($_GET);
        $slides_name=$slide_name;
        $slides_order=$order;
        $content=$contenue;}  ?>

        </thead>
      </table>&nbsp;&nbsp;
    </div> <?php 
 ?> <input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r($_POST['post_title']); ?>" /> <?php }}
 			if($_GET['id_slide']){
			 			   foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_name,slides_order 
						                                               FROM $studi_slides 
																	   where slides_id='".$_GET['id_slide']."'")) as $key => $row1)
                           {$slides_name = sanitize_text_field($row1-> slides_name);
                            $slides_order = sanitize_text_field($row1-> slides_order);				}}
	if (isset($_POST['new_slide']) or isset($_POST['delete_slide']) or isset($_POST['save_slide']) or isset($_POST['add_cat']) or $_GET['id_slide'] ) { ?>
    <div style="float:right; width:79%;"  >
      <table class="widefat">
        <thead>
          <tr>
<?php
            global $wpdb;
            foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id
                                                        FROM $studi_courses
                                                        where course_id >= ALL(SELECT course_id
                                                                               FROM $studi_courses)")) as $key => $row)
            {$id = sanitize_text_field($row-> course_id);}
			if(!$_GET['id']){$id_cours=$id;} else{$id_cours=$_GET['id'];}

              if ( isset($_POST['new_slide']) ){ 
			   foreach( $wpdb->get_results($wpdb->prepare("SELECT slides_order 
			                                               FROM $studi_slides 
														   where course_id='".$id_cours."'
														   and slides_order >= ALL(SELECT slides_order 
														                           FROM $studi_slides 
																				   where course_id='".$id_cours."')")) as $key => $row1)
                {$max_order = sanitize_text_field($row1-> slides_order); } 
				$slides_order=$max_order; $slides_name='';}
			?>
           <th>Add new slide :&nbsp;&nbsp;
             <input type="text" value="<?php print_r($slides_name);?>"id="name_slide" name="name_slide" title="slide_name" size="20" tabindex="1" autocomplete="on"  />
            &nbsp;&nbspOrder:&nbsp;
			   <?php
			     if(!$_GET['id']){$id_cours=$id;} else{$id_cours=$_GET['id'];}

				?>
			<input type="text" id="order" value="<?php print_r ($slides_order);?>" name="order" title="Slide order" size="3" tabindex="1" autocomplete="off"  /></h3>

 <?php  foreach( $wpdb->get_results($wpdb->prepare("SELECT course_id
                                                    FROM $studi_courses
                                                    where nom='".$nom."'")) as $key => $row) {$course_id = sanitize_text_field($row-> course_id);} ?>
     
        <input class="button-primary" type="submit" name='save_slide' value="&nbsp;&nbsp;Save&nbsp;&nbsp;" title="save slide" style="float:right;"  />
           </th>
         </tr>
         <tr>
           <th>
            <div id="poststuff" >
              <?php  
			  the_editor($content, $id ='content', $prev_id = 'post_title', $media_buttons = true, $tab_index = 2) ?>
            </div>
           </th>
         </tr>
		</table>
       </div>
	   <input type="hidden" name="ids_cours" id="ids_cours" value="<?php print_r(sanitize_text_field($_POST['post_title'])); ?>" />
<?php } ?>	   </div>
</div>
</div>





 
    </form>
<?php
} ?>