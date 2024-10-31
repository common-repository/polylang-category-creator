<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//BUILD NEW ADMIN PAGE
add_action('admin_menu', 'mk_mbc_build');

//FUNCTION TO CREATE THE ADMIN PAGE
function mk_mbc_build() { 
	add_menu_page( 'Multilang-Cat', 'Multilang-Cat', 'manage_options', 'Multilang-Cat', 'mk_mbc_admin', 'dashicons-exerpt-view');
}

//FUNCTION TO PRINT THE ADMIN PAGE
function mk_mbc_admin(){
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	
	}
	if (!function_exists('pll_default_language'))  {
		wp_die( __('Polylang not installed. Please install it to use this plugin. :(') );
	
	}
?><h1>Polylang Category Creator</h1><?php 
//GET THE DEFAULT LANGUAGE SLUG
$lang_default = pll_default_language();

//GET THE ARRAY OF LANGUAGE SLUGS
$langs = pll_languages_list();

$name_langs = pll_languages_list( array('0' ,'name') );

//BEGIN TO PROCESS THE FORM, START CHECKING SUBMITTED HIDDEN FORM AND NONCE
if(isset($_POST['submitted']) && isset($_POST['polylang-category-creator']) && wp_verify_nonce( $_POST['polylang-category-creator'], 'polylang-category-creator' )){
	
	//DEFAULTS
	$parent_cat = '0';
	$taxonomy = 'category';
	$get_descript = '';
	$get_slug = '';
	
	//CREATES DEFAULT ARRAYS
	$lang_and_id = array();
	
	$langs_poly = array();
	
	//TAXONOMY
	if(isset($_POST['taxonomy'])){
		//GET TAXONOMY
		$taxonomy = $_POST['taxonomy'];
		//CHECK IF EXIST AND THEN SANITIZE
		if(taxonomy_exists($taxonomy)){
			$taxonomy = sanitize_text_field($taxonomy);
		}else{
			echo '<p style="margin-top:5%; font-size:17px;">';
			esc_html_e("Taxonomy can't be empty, please select one before filling the form.",'mk-mbc');
			exit;
		}
		//GET THE FIELD NAMED WITH TAXONOMY NAME TO GET THE PARENT CAT(IF SET)
		if(isset($_POST[$taxonomy])){
			$parent_cat = $_POST[$taxonomy];
			$parent_cat = absint($parent_cat);
		}
		
	}
	
	//MAIN LOOP
	foreach ($langs as $lang){
		
		//GET THE FIELDS FOR THIS LANGUAGE
		$cat_name = $lang . '_name';
		$cat_slug = $lang . '_slug';
		$cat_descript = $lang . '_descript';
		
		//GET THE ID FOR THE LANGUAGE PARENT
		$parent_cat_trans = pll_get_term($parent_cat,$lang);
		
		//GET VARIABLES
		//CHECK IF IS EMPTY
		if(isset($_POST[$cat_name]) && $_POST[$cat_name] != ''){
			$get_name = $_POST[$cat_name];
		}else{
			echo '<p style="margin-top:5%; font-size:17px;">';
			esc_html_e("Error. Category Name cannot be empty, it's the only field that must be filled.",'mk-mbc');
			exit;
		}
		if(isset($_POST[$cat_name]) && $_POST[$cat_name] != ''){
			$get_slug = $_POST[$cat_slug];
		}
		if(isset($_POST[$cat_descript]) && $_POST[$cat_descript] != ''){
			$get_descript = $_POST[$cat_descript];
		}
		
		//SANITIZE BEFORE CREATING THE CATEGORY
		//CATEGORY NAME: FIRST VALIDATE INPUT FIELD AND THEN SANITIZE
		if(strlen($get_name) > 2 && strlen($get_name) < 26){
			$get_name = sanitize_text_field($get_name);
		}else{
			echo '<p style="margin-top:5%; font-size:17px;">';
			esc_html_e('Error. Category name too short or too big','mk-mbc');
			exit;
		}
		
		//CATEGORY SLUG: FIRST VALIDATE
		if(strlen($get_slug) < 26){
			$get_slug = sanitize_title($get_slug);
		}else{
			echo '<p style="margin-top:5%; font-size:17px;">';
			esc_html_e('Error. Category slug too short or too big','mk-mbc');
			exit;
		}
		if(strlen($get_descript) < 40){
			$get_descript = sanitize_text_field($get_descript);
		}else{
			echo '<p style="margin-top:5%; font-size:17px;">';
			esc_html_e('Error. Category name too short or too big','mk-mbc');
			exit;
		}
		
	
		//CREATE THE CATEGORY
		$new_cat = array(
				'cat_name' => $get_name,
				'category_description' => $get_descript,
				'category_nicename' => $get_slug,
				'category_parent' => $parent_cat_trans,
				'taxonomy' => $taxonomy,
		);
		
		$category_new_id = wp_insert_category($new_cat);
		
		//CHECK IF CATEGORY WAS CREATED
		if(!$category_new_id){
			echo '<p style="margin-top:5%; font-size:17px;">';
			esc_html_e('Error. Category probably exists, if not, try again.','mk-mbc');
			exit;
		}
	
		
		//ADD THIS LANGUAGE AND ID FOR POLYLANG ARRAYS
		$array_new = array(
				'LANG' => $lang,
				'ID' => $category_new_id,
		);
		array_push($lang_and_id,$array_new);
		
		$langs_poly[$lang] = $category_new_id;
		
		
	}
	
	//SET THE LANGUAGE FOR EACH CATEGORY
	foreach($lang_and_id as $polylang_config){
		pll_set_term_language($polylang_config['ID'], $polylang_config['LANG']);
	}
	
	//LINK THE TRANSLATIONS
	pll_save_term_translations($langs_poly);
	
	echo '<h2 style="margin-top:2%; margin-bottom:2%; padding:1%; color:black; background-color: lightgreen; border-left: 5px solid green;">' . __('Saved successfully') . '</h2>';
	
	
}

//GET TAXONOMIES FOR FORM
$args = array(
		'show_option_all'    => '',
		'show_option_none'   => '',
		'option_none_value'  => '-1',
		'orderby'            => 'ID',
		'order'              => 'ASC',
		'show_count'         => 0,
		'hide_empty'         => 1,
		'child_of'           => 0,
		'exclude'            => '',
		'include'            => '',
		'echo'               => 1,
		'selected'           => 0,
		'hierarchical'       => 0,
		'name'               => 'cat',
		'id'                 => '',
		'class'              => 'postform',
		'depth'              => 0,
		'tab_index'          => 0,
		'taxonomy'           => 'category',
		'hide_if_empty'      => false,
		'value_field'	     => 'term_id',
);


//==================================
//==========THE ADMIN PAGE==========
//==================================
?>
<p>

<script>
/*SCRIPTS TO HANDLE THE TAXONOMY AND CAT_PARENT*/

function update_parents(value){
	var x = document.getElementsByClassName("all_cat");
	for(i=0;i<x.length;i++){
		if(x[i].name = value){
			x[i].disabled = false;
			}else{
				x[i].disabled = true;
			}
	}
	document.getElementById("show_cats").innerHTML = '<p style="font-size: 13px;"><?php esc_html_e('Select first a Taxonomy', 'mk-mbc'); ?></p>';
	if(value !== ""){
		document.getElementById("show_cats").innerHTML = document.getElementById(value).innerHTML;
		document.getElementById(value).style.display = "block";
		document.getElementById("TaxonomySelector").style.color = "green";
	}else{
		document.getElementById("TaxonomySelector").style.color = "red";
	}
}
</script>

<p><?php esc_html_e('A tool to create multilanguage categories, easier. You can use custom taxonomies, product categories(woocommerce) and others.','mk-mbc'); ?></p>
<p><?php esc_html_e('Select first a Taxonomy, then you will be able to select a parent category, and then fill the form.','mk-mbc'); ?></p>
<p style="font-style:italic; font-size:14px;" ><?php esc_html_e('Please consider rating the plugin to know your opinion, and be free to use the support forum if you have any trouble.','mk-mbc'); ?></p>
<hr>


<form method="post" id="create-multilang" name="create-multilang" action="">
<div id="test"></div>
<table style="width:40%">

	<tr>
	<th width="35%">
	<p style="font-size: 16px; margin-bottom:4px; margin-top:3px;"><?php esc_html_e('Taxonomy','mk-mbc'); ?>
	</th><th width="35%">
	<p style="font-size: 16px; margin-bottom:4px; margin-top:3px;"><?php esc_html_e('Parent Category ID','mk-mbc'); ?>
	</th></tr>
	<tr><td width="35%">
	<center><select id="TaxonomySelector" name="taxonomy" style="width:80%;color:red;" onchange="update_parents(this.value)">
		<option value="" style="color:red;" selected>Select a Category</option>
		<?php 
		$tax_list = get_taxonomies();
		foreach ($tax_list as $tax){
			if(pll_is_translated_taxonomy($tax)){
				echo '<option value="'.  $tax . '" style="color:black;" >'. $tax . '</option>';
			}
		} ?>
		 </select></center>
		 
<?php 
		foreach ($tax_list as $tax){
			if(pll_is_translated_taxonomy($tax)){
				$args = array(
						'show_option_all'    => '',
						'show_option_none'   => 'No Parent',
						'option_none_value'  => 0,
						'orderby'            => 'ID',
						'order'              => 'ASC',
						'show_count'         => 0,
						'hide_empty'         => 0,
						'child_of'           => 0,
						'exclude'            => '',
						'include'            => '',
						'echo'               => 1,
						'selected'           => 0,
						'hierarchical'       => 0,
						'id'                 => 'cat_id',
						'name'               => 'catasdac',
						'class'              => 'all_cat',
						'depth'              => 0,
						'tab_index'          => 0,
						'taxonomy'           => $tax,
						'hide_if_empty'      => false,
						'value_field'	     => 'term_id',
				);
				echo '<p><div style="display:none"> <div id="' . $tax . '">';
				wp_dropdown_categories($args);
				echo '</div></div>';
			}
		} 
		?>

	</td><td id="corrector" width="35%">
	<center><div id="show_cats"><p style="font-size: 13px; text-align:center;"><?php esc_html_e('Select first a Taxonomy', 'mk-mbc'); ?></p></div></center>
		
	</td></tr>

</table>

<table id="tableCat" style="width:60%">

	<tr >
	<th width="5%"><?php esc_html_e('Language','mk-mbc'); ?></th>
	<th width="20%"><?php esc_html_e('Category Name','mk-mbc'); ?></th>
	<th width="20%"><?php esc_html_e('Slug','mk-mbc'); ?></th>
	<th width="40%"><?php esc_html_e('Description','mk-mbc'); ?></th>
	</tr>
	
	<?php foreach($langs as $lang){ ?>
		<tr>
		<td><?php echo $lang; ?></td>
		<td><input type="text" name="<?php echo $lang; ?>_name" placeholder="<?php esc_html_e('Category Name','mk-mbc'); ?>" maxlength="20" required/></td>
		<td><input type="text" name="<?php echo $lang; ?>_slug" placeholder="<?php esc_html_e('Slug','mk-mbc'); ?>" maxlength="15" /></td>
		<td><textarea style="width:100%;" name="<?php echo $lang; ?>_descript" maxlength="30" ></textarea></td>
		<td></td>
		</tr>
		
	<?php } ?>
	
	
</table>
<?php wp_nonce_field( 'polylang-category-creator','polylang-category-creator' ); ?>
<input type="hidden" id="parent_cat" name="parent_cat" value="0" />
<input type="hidden" name="submitted" value="1" />
<p>
<input type="submit" class="button-primary" value="<?php esc_html_e('Create Categories','mk-mbc'); ?>" />
</form>


<style>
#tableCat th{
border-bottom: 2px solid lightgreen !important;
margin-bottom: 2% !important;
padding-bottom: 1%;

}
#tableCat tr{
margin-top: 1% !important;
}
#cat_id{
width:80%;
}
td{
padding-top:10px !important;

}
#corrector{
padding-top: 0px !important;
}

</style>



<?php }?>