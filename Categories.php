<?php 
function sub3() {
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
     
</script><?php
   global $studi_courses;
   global $studi_slides;
   global $studi_category;
   global $studi_categ_cours;

                               
?> 
  </br>&nbsp;&nbsp;<label style=" font-size:200%;">Categories</label>&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php?page=id_sub2"><input style="" type="submit" value="Add new" name="add_newcat" title="Add new category" class="button-primary" /></a></br></br>
      <?php  if ( isset($_POST['add_new_cat']) ) { 
	    $name = sanitize_text_field(stripslashes($_POST['cat_name']));
		$parent =NULL;
	    $desc = sanitize_text_field(stripslashes($_POST['cat_desc']));
		$nbre = 0;
		          		        global $wpdb;
            foreach( $wpdb->get_results($wpdb->prepare("select cat_parent from $studi_category where cat_id=%d",$_POST['cat_par'])) as $key => $row) {
                $cat_parent = sanitize_text_field($row-> cat_parent);}
		if($cat_parent!=NULL) {echo "</br><div style="; echo '"width:98%; height:4%;   border-width:thin; border-style:solid; border-color:rgb(255,153,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">sorry, you can not add a category to another that has a parent category, there are only two levels for categories</label>
				</div>';}
else {		

	    if($_POST['cat_par'] ==Neither){
		          		        global $wpdb;
                                $req5=$wpdb->prepare("INSERT INTO $studi_category (`cat_id`, `cat_name`, `cat_des`, `cat_parent`, `courses_nbr`) 
								VALUES (NULL, '$name', %s, NULL,%d)",$desc,$nbre);
                                $res5=$wpdb->query($req5);}
				 else {	    $parent = stripslashes($_POST['cat_par']);
				            global $wpdb;
                            $req5=$wpdb->prepare("INSERT INTO $studi_category (`cat_id`, `cat_name`, `cat_des`, `cat_parent`, `courses_nbr`) 
						    VALUES (NULL, '$name', %s, %d, %d)",$desc,$parent,$nbre);
                             $res5=$wpdb->query($req5);}}

		//if($_POST['cat_par'] ==Neither){$parent ='';} else{$parent = stripslashes($_POST['cat_par']);} echo $parent;
}

if ( isset($_POST['update_cat']) ){
		   global $wpdb;
            foreach( $wpdb->get_results($wpdb->prepare("select cat_parent from $studi_category where cat_id=%d",$_POST['cat_par'])) as $key => $row) {
                $cat_parent = sanitize_text_field($row-> cat_parent);}
		if($cat_parent!=NULL) {echo "</br><div style="; echo '"width:98%; height:4%;   border-width:thin; border-style:solid; border-color:rgb(255,153,0); background:rgb(255,153,0); float:left;">
                &nbsp;&nbsp;<label style="height:5%;">Sorry, you can'; echo "'t add category to another have parent (just 2 level of categories) ! </label>
				</div>";}
else {		
		$cat_name=sanitize_text_field($_POST['cat_name']);
		$cat_desc=sanitize_text_field($_POST['cat_desc']);
		$cat_par=sanitize_text_field($_POST['cat_par']);

	    if($_POST['cat_par'] ==Neither){


          $req11=$wpdb->prepare("UPDATE $studi_category SET cat_name = %s,cat_des=%s,cat_parent=NULL WHERE cat_id=%d",$cat_name,$cat_desc,$_GET['id_cat']);
	}
				 else {
          $req11=$wpdb->prepare("UPDATE $studi_category SET cat_name = %s,cat_des=%s,cat_parent=%d WHERE cat_id=%d",$cat_name,$cat_desc,$cat_par,$_GET['id_cat']);
            }             $res11=$wpdb->query($req11);		  }}


	  ?>
  </br></br></br><form id="monform4" name="form4" method="post" enctype="multipart/form-data" >
  <div style="float:left; width:98%;">
    <div style="float:left; width:30%;"><?php
	if(empty($_GET['id_cat'])or isset($_POST['add_newcat'])){
  ?>&nbsp;&nbsp;<label style=" font-size:120%;">Add a new category</label>&nbsp;&nbsp;</br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<label for="cat_name" title="The name of category" style=" font-size:110%;">Name</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" title="The name of category" value="" name="cat_name" size="50" tabindex="1" autocomplete="off" /></br></br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<label for="cat_par" title="The name of category" style=" font-size:110%;">Parent</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;&nbsp;&nbsp;<select name="cat_par" >
               <option value="Neither">Neither</option>
			   <?php     global $wpdb;
                         foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent is NULL",$studi_category)) as $key => $row) {
                // each column in your row will be accessible like this
                $nom = sanitize_text_field($row-> cat_name);
                $id = sanitize_text_field($row-> cat_id);
                $id_parent = sanitize_text_field($row-> cat_parent);
                     ?>
				  <option value="<?php echo $id; ?>" ><?php echo $nom;?></option><?php 
				   foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent=%d",$id)) as $key1 => $row1){
				    $nom1 = sanitize_text_field($row1-> cat_name);
                    $id1 = sanitize_text_field($row1-> cat_id);
                    $id_parent1 = sanitize_text_field($row1-> cat_parent); ?>
				    <option value="<?php echo $id1; ?>" >&nbsp;&nbsp;<?php echo $nom1;?></option><?php 
				                    }}
				                                                         ?>
				     
				                                                                                                                } ?>
           </select></br></br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<label for="cat_desc" title="The category description" style=" font-size:110%;">Description</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;&nbsp;&nbsp;<textarea title="The category description" value="" name="cat_desc" ROWS="6" cols="45"></textarea></br></br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<input style="" type="submit" value="Add a new category" name="add_new_cat" title="Add a new category" class="button-primary" />
    <?php }
	else {
     global $wpdb;
                         foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_des,cat_parent FROM $studi_category where cat_id=%d",$_GET['id_cat'])) as $key => $row) {
                // each column in your row will be accessible like this
                $cat_id = sanitize_text_field($row-> cat_id);
                $cat_name = sanitize_text_field($row-> cat_name);
                $cat_des = sanitize_text_field($row-> cat_des);
                $cat_parent = sanitize_text_field($row-> cat_parent);
				}
	?>
  &nbsp;&nbsp;<label style=" font-size:120%;">Update the category</label>&nbsp;&nbsp;

  &nbsp;&nbsp;&nbsp;&nbsp;<label for="cat_name" title="The name of category" style=" font-size:110%;">Name</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" title="The name of category" value="<?php echo $cat_name; ?>" name="cat_name" size="50" tabindex="1" autocomplete="off" /></br></br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<label for="cat_par" title="The name of category" style=" font-size:110%;">Parent</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;&nbsp;&nbsp;<select name="cat_par" value="<?php echo $cat_parent; ?>" >
               <option value="Neither">Neither</option>
			   <?php     global $wpdb;
                         foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent is NULL",$studi_category)) as $key => $row) {
                // each column in your row will be accessible like this
                $nom = sanitize_text_field($row-> cat_name);
                $id = sanitize_text_field($row-> cat_id);
                $id_parent = sanitize_text_field($row-> cat_parent);
                     ?>
				  <option value="<?php echo $id; ?>" ><?php echo $nom;?></option><?php 
				   foreach( $wpdb->get_results($wpdb->prepare("SELECT cat_id,cat_name,cat_parent FROM $studi_category where cat_parent=%d",$id)) as $key1 => $row1){
				    $nom1 = sanitize_text_field($row1-> cat_name);
                    $id1 = sanitize_text_field($row1-> cat_id);
                    $id_parent1 = sanitize_text_field($row1-> cat_parent); ?>
				    <option value="<?php echo $id1; ?>" >&nbsp;&nbsp;<?php echo $nom1;?></option><?php 
				                    }}
				                                                         ?>
				     
				                                                                                                                } ?>
           </select></br></br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<label for="cat_desc" title="The category description" style=" font-size:110%;">Description</label>&nbsp;&nbsp;</br>
  &nbsp;&nbsp;&nbsp;&nbsp;<textarea title="The category description"  name="cat_desc" ROWS="6" cols="45"><?php echo $cat_des; ?></textarea></br></br></br>
  &nbsp;&nbsp;&nbsp;&nbsp;<input style="" type="submit" value="Update" name="update_cat" title="Update the category" class="button-primary" />

    <?php 
	} ?>
    </div>
	<div style="float:right; width:69%;">
	  <input type="submit" id='delete_cat' name='delete_cat'  title="delete category" value="Delete"  /><?php
	    if(isset($_POST['delete_cat'])){
  $categ_list = implode(', ', $_POST['list_cat']);
  global $wpdb;
  $req5=$wpdb->prepare("UPDATE $studi_category SET cat_parent =NULL WHERE cat_parent IN (%d)",$categ_list);
  $res5=$wpdb->query($req5);
  $req=$wpdb->prepare("DELETE FROM $studi_categ_cours WHERE cat_id IN(%d)",$categ_list);
  $wpdb->query($req); 
  $req=$wpdb->prepare("DELETE FROM $studi_categ_quiz WHERE cat_id IN(%d)",$categ_list);
  $wpdb->query($req);   
  $req2=$wpdb->prepare("DELETE FROM $studi_category WHERE cat_id IN(%d)",$categ_list);
  $wpdb->query($req2);}
  ?>
	  <table cellpadding="5" cellspacing="0" border="1" class="widefat">
       <thead>
        <tr><th width="5px"><input type="checkbox"  onclick="if(this.checked){ cocher(); }else{decocher();}"> </th>
            <th width="100px">Name</th>
            <th width="200px">Description</th>
            <th width="50px">Courses</th>
			<th width="50px">Quizs</th>
          <?php //  <th width="50px">Quizs</th> ?>

        </tr>
       </thead>
      </table>

<?php
			 foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr FROM $studi_category",$studi_category)) as $key => $row)
             {$cat_nbr = $row-> nbr;} 
 if($cat_nbr==0){   ?>
<table cellpadding="5" cellspacing="0"  border="2" class="widefat">
            <tr><th width="20px"><strong>No entries.</strong><th width="5px"></tr>
</table>
<?php
 }
 else{
  global $wpdb;
  $req1=$wpdb->prepare("SELECT cat_id, cat_name, cat_parent, cat_des
         FROM $studi_category
         WHERE cat_parent IS NULL",$studi_category);		 
  $res1=$wpdb->get_results($req1);
  foreach($res1 as $key => $row ){ 
                           $cat_id = sanitize_text_field($row-> cat_id);
						   $cat_name = sanitize_text_field($row-> cat_name);
						   $cat_parent = sanitize_text_field($row-> cat_parent);
						   $cat_des = $row-> cat_des;
  foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr
                               FROM $studi_categ_cours
                               WHERE cat_id = %d",$cat_id)) as $key2 => $row2)
                               {$cat_cours_nbr = $row2-> nbr;} 
   foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr
                               FROM $studi_categ_quiz
                               WHERE cat_id = %d",$cat_id)) as $key2 => $row2)
                               {$cat_quiz_nbr = $row2-> nbr;}							   
    echo '<table cellpadding="5" cellspacing="0"  border="2" class="widefat">
            <tr>
             <th width="5px"><input type="checkbox" name="list_cat[]" value="',$cat_id,'" /></th>
             <th width="100px"><a href="admin.php?page=id_sub2&id_cat=';print_r ($cat_id);echo'">';print_r ($cat_name);echo'</th>
             <th width="200px">';print_r ($cat_des);echo'</th>
	     <th width="50px">'; print_r ($cat_cours_nbr);  echo'</th>
	     <th width="50px">'; print_r ($cat_quiz_nbr);echo'</th>
          </tr>
         </table>';
		   $req2=$wpdb->prepare("SELECT cat_id, cat_name, cat_parent, cat_des, courses_nbr
                  FROM wp_studi_category
                  WHERE cat_parent=%d",$cat_id);
  $res2=$wpdb->get_results($req2);
  foreach($res2 as $key1 => $row1 ){
                           $cat_id1 = sanitize_text_field($row1-> cat_id);
						   $cat_name1 = sanitize_text_field($row1-> cat_name);
						   $cat_parent1 = sanitize_text_field($row1-> cat_parent);
						   $cat_des1 = sanitize_text_field($row1-> cat_des);
    foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*)as nbre
                               FROM $studi_categ_cours
                               WHERE cat_id=%d",$cat_id1)) as $key => $row)
                               {$catf_cours_nbr = $row-> nbre;}
  foreach( $wpdb->get_results($wpdb->prepare("SELECT count(*) as nbr
                               FROM $studi_categ_quiz
                               WHERE cat_id = %d",$cat_id1)) as $key2 => $row2)
                               {$cat_quiz_nbr = $row2-> nbr;}
          echo '<table cellpadding="5" cellspacing="0"  border="2" class="widefat">
            <tr>
             <th width="5px"><input type="checkbox" name="list_cat[]" value="',$cat_id1,'" /></th>
             <th width="100px"><a href="admin.php?page=id_sub2&id_cat=';print_r ($cat_id1);echo'">';echo '---';print_r ($cat_name1);echo'</th>
             <th width="200px">';print_r ($cat_des1);echo'</th>
	     <th width="50px">';print_r ($catf_cours_nbr);echo'</th>
	     <th width="50px">';print_r ($cat_quiz_nbr);echo'</th>

          </tr>
         </table>';}
}}?>
	  <table cellpadding="5" cellspacing="0" border="1" class="widefat">
       <thead>
        <tr><th width="5px"><input type="checkbox"  onclick="if(this.checked){ cocher(); }else{decocher();}"> </th>
            <th width="100px">Name</th>
            <th width="200px">Description</th>
            <th width="50px">Courses</th>
			<th width="50px">Quizs</th>
          <?php //  <th width="50px">Quizs</th> ?>
	    </tr>
       </thead>
      </table>

    </div>
  </div>
</form>
	
  <?php


  }
  
?>