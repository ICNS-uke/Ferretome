<?php if (!defined('BASEPATH')) exit('Wir haben Sie nicht verstanden!'); ?>
<?php  $this->load->view('header');  ?>



<?php if($action == 'index'):?>

<h2>Literature input</h2>


<?php if(isset($index_message)):?>
<p><?=$index_message?></p>
<?php endif;?>




<a href="index.php?c=literature&m=add">Add new Literature </a>
<a href="index.php?c=authors&m=add">Add new Authors </a>

<?php endif;?>



<?php if($action == 'add'):?>

<h2>Add new Literature</h2>

<?php if(isset($add_message)):?>
<p><?=$add_message?></p>
<?php endif;?>


<form method="post" id="frm" name="frm" action="index.php?c=literature&m=insert">

<table border="0" cellpadding="3" cellspacing="1">


<tr>
	<td>Search an author(s)<br/> to this pub. <br/> 
	Cant find? <a href="index.php?c=authors&m=add" target="_blank" >Add authors to DB first!</a> </td>
	<td><input title="Please, start to type a surname of an author" type="text" id="autocomplite_auth" class="input"/></td>
</tr>
<tr>
	<td>
		Authors list:<br/>
		<strong>
		Authors should be in <br/>
		the same order like in paper!
		</strong>
	</td>
	<td id="auth_list"></td>
</tr>


<tr id="abbrevaiture_blk_srch">

	<td >
		Search journal / book <br/> to this pub. <br/>
		Cant find? <a href="index.php?c=abbreviations&m=add" target="_blank">Add journal/ book to DB first!</a> 
	</td>
	<td><input title="Please, start to type title of journal" type="text" id="autocomplite_abbr" class="input"/></td>
</tr>
<tr id="abbrevaiture_blk_sel" style="display:none;">
	<td>Selected journal/ bool</td>
	<td id="abbr_block"></td>
</tr>


<?php foreach($fields as $field): ?>

<?php if (($field->primary_key == 1)  OR ($field->name == 'literature_state') OR ($field->name == 'literature_physicalCopy') OR ($field->name == 'literature_source') OR ($field->name == 'literature_tracingData') OR ($field->name == 'literature_mappingData') OR ($field->name == 'literature_index') ) continue; ?>

<tr>
	<td><?php $fname = explode("_", $field->name); foreach ($fname as $fn) { echo $fn." "; }; echo ' '.$field->default; ?></td>
	
	<?php if ($field->type == 'blob'): ?>
	<td><textarea class="textarea" id="<?php echo $field->name; ?>" name="<?php echo $field->name; ?>" cols="30" rows="10" ><?php echo form_prep($field->default); ?></textarea></td>
	<?php else : ?>
	<td><input class="input" id="<?php echo $field->name; ?>" name="<?php echo $field->name; ?>" value="<?php echo form_prep($field->default); ?>" size="30" /></td>
	<?php endif; ?>
	
</tr>
<?php endforeach; ?>


<tr id="upload_block_button">
	<td>
		Upload pdf file
		<br/>if exists
	</td>
	<td>
	<input type="button" id="uploader" value="Upload">
	</td>	
</tr>

<tr id="upload_block_file" style="display:none;">
	<td>
		Uploaded pdf file		
	</td>
	<td id="upload_block_file_name">
		<input type="hidden" id="literature_physicalCopy" name="literature_physicalCopy" value="">
	</td>	
</tr>

</table>

<input type="submit" class="submit" value="Insert" />

</form>


<script type="text/javascript">
//<![CDATA[
new Autocomplete('autocomplite_auth', { 
	serviceUrl:'index.php/authors/ajaxAtocomplit', 
	onSelect: function(value, data){
    	auth_id = data;
    	author_add();
	}
 });

new Autocomplete('autocomplite_abbr', { 
	serviceUrl:'index.php/abbreviations/ajaxAtocomplit', 
	onSelect: function(value, data){
    	abbr_id = data;
    	abbr_select();
	}
 });


var uploader = document.getElementById('uploader');

upclick(
  {
   element: uploader,
   action: 'index.php/literature/uploadPdfFile', 
   onstart:
     function(filename)
     {
       alert('Start upload: '+filename);
     },
   oncomplete:
     function(response_data) 
     {
       if(response_data != 'FAIL') {

           select_file(response_data);

       } else {

           alert('An Error has occured, try once again!');

       }
     }
  });




 $('frm').onsubmit = function () { return check_form(this)}






//]]>
</script>

<?php endif;?>


<?php if($action == 'search'):?>


<?php if(isset($block_message)):?>
<p><?=$block_message?></p>
<?php endif;?>

<h2>Search publication</h2>

<form method="post" id="frm" name="frm" action="#">

<table border="0" cellpadding="3" cellspacing="1">

<tr id="auto_block">
	<td>
		<input title="Please, start to type literature title" type="text" id="autocomplite_1" class="input"/>
		<input title="Please, start to type a surname of an author" type="text" id="autocomplite_2" class="input" style="display:none"/>
		<br/>Search publication using:<span id="search_type_1"> Title</span><span id="search_type_2" style="display:none"> Authors</span> - 
		<a href="#" id="search_link_2"  onclick="switch_search(2); return false;">switch to Authors</a>
		<a href="#" id="search_link_1" onclick="switch_search(1); return false;" style="display:none">switch to Title</a>
		<br/><a href="index.php?c=literature&m=viewAll">All literature list</a>   
	</td>
	
</tr>

</table>
</form>

<p></p>

<br/>
<div id="search_result"></div>

<?php if(isset($last_inserted)):?>
<div id="last_inserted">
	<p>Recently inserted publications</p>
	
	<?php $data['lit_data'] = $last_inserted; $data['auth_data'] = $last_inserted_authors; $this->load->view('literature_search_view',$data); ?>

</div>
<?php endif;?>

<?php if(isset($last_updated)):?>
<div id="last_updated">
	<p>Recently updated publications</p>
	
	<?php $data['lit_data'] = $last_updated; $data['auth_data'] = $last_updated_authors;  $this->load->view('literature_search_view',$data); ?>

</div>
<?php endif;?>


<?php if(isset($for_proof_data)):?>
<div id="for_proof">
	<p>Ready for proofreading literature</p>
	
	<?php $data['lit_data'] = $for_proof_data; $data['auth_data'] = $for_proof_authors;  $this->load->view('literature_search_view',$data); ?>

</div>
<?php endif;?>


<script type="text/javascript">
//<![CDATA[
new Autocomplete('autocomplite_1', { 
	serviceUrl:'index.php/literature/ajaxAtocomplit',
	onSelect: function(value, data){
		sel_lit_num = data;
		
		search_do();
		$('autocomplite_1').value = '';
		
	} 
	
 });
new Autocomplete('autocomplite_2', { 
	serviceUrl:'index.php/authors/ajaxAtocomplit',
	onSelect: function(value, data){
		sel_auth_num = data;
		
		search_do();

		$('autocomplite_2').value = '';
		
	} 
	
 });




 

//]]>
</script>








<?php endif;?>



<?php if($action == 'edit'):?>

<div id="literature_block">

<h2>Edit Literature</h2>

<?php if(isset($block_message)):?>
<p><?=$block_message?></p>
<?php endif;?>


	<?php if(isset($lit_data)):?>


	<?php  $this->load->view('update_dialog_view');  ?>

<form method="post" id="frm" name="frm" action="index.php?c=literature&m=update&lid=<?=$lit_data->literature_id?>">

<table border="0" cellpadding="3" cellspacing="1">

<tr>
	<td>literature status</td>
	<td>
	<strong><span id="lit_status"><?=$liteature_types[$lit_data->literature_state]?></span></strong>
	<?php if(isset($lit_changeble_state)):?>
		
		<span id="lit_status_buttons"> 
		<?php foreach ($liteature_types as $ltype => $typeval): ?>
			<?php if ($ltype <= $lit_data->literature_state) continue;?>
			 change status to: <a href="#" onclick="change_literature_state(<?=$lit_data->literature_id?>,<?=$ltype?>); return false;"><?=$typeval?></a> 
		<?php endforeach; ?>
		</span>
	
	<?php else:?>
	
		You cannot change status, take this job first
	
	<?php endif;?>
	
	 
	</td>
</tr>


<tr>
	<td>
		Search an author(s)<br/> to this pub. <br/> Cant find?<a href="index.php?c=authors&m=add" target="_blank" >Add authors to DB first!</a> 
	</td>
	<td><input title="Please, start to type a surname of an author" type="text" id="autocomplite_auth" class="input"/></td>
</tr>
<tr>
	<td>Authors list:</td>
	<td id="auth_list">
		<?php $i=1; foreach ($auth_data->result() as $adata):?>	
		<span id="auth_id_<?=$adata->authors_id?>"><?=$i++?><input type="hidden" name="authors_id[]" value="<?=$adata->authors_id?>"> <?=$adata->authors_surname?> <?=$adata->authors_name?> <?=$adata->authors_middleName?> <a href="#" onclick="auth_del(<?=$adata->authors_id?>); return false;">X</a><br/></span>
		<?php endforeach; ?>
	</td>
</tr>


<tr id="abbrevaiture_blk_srch" style="display:none;">

	<td>
		Search journal / book <br/> to this pub. <br/>
		Cant find? <a href="index.php?c=abbreviations&m=add" target="_blank">Add journal/ book to DB first!</a> 
	</td>
	<td><input title="Please, start to type title of journal" type="text" id="autocomplite_abbr" class="input"/></td>
</tr>

<tr id="abbrevaiture_blk_sel" >
	<td>Selected journal/ bool</td>
	<td id="abbr_block">
	
	<span id="abbr_id_<?=$lit_data->literature_source?>"><input type="hidden" name="literature_source" value="<?=$lit_data->literature_source?>"> <?=$abbr_data->abbreviations_short?> - <?=$abbr_data->abbreviations_full?> <a href="#" onclick="abbr_replace('<?=$lit_data->literature_source?>'); return false;">Replace</a><br/></span>
	
	</td>
</tr>

<?php foreach($fields as $field): ?>

<?php if (($field->primary_key == 1) OR ($field->name == 'literature_state') OR ($field->name == 'literature_physicalCopy') OR ($field->name == 'literature_source') OR ($field->name == 'literature_index') ) continue; ?>

<tr>
	<td><?php $fname = explode("_", $field->name); foreach ($fname as $fn) { echo $fn." "; }; echo ' '.$field->default; ?></td>
	
	<?php if ($field->type == 'blob'): ?>
	<td><textarea class="textarea" id="<?php echo $field->name; ?>" name="<?php echo $field->name; ?>" cols="30" rows="10" ><?php $f=$field->name; echo form_prep($lit_data->$f); ?></textarea></td>
	<?php else : ?>
	<td><input class="input" name="<?php echo $field->name; ?>" id="<?php echo $field->name; ?>" value="<?php $f=$field->name; echo form_prep($lit_data->$f); ?>" size="30" /></td>
	<?php endif; ?>
	
</tr>

<?php endforeach; ?>



<?php if(!empty($lit_data->doi_id)):?>

<tr >
	<td>
		DOI web link
	</td>
	<td>
	<a target="_blank" href="http://dx.doi.org/<?=$lit_data->doi_id?>">Click here to open in new window</a>
	</td>	
</tr>


<?php endif;?>

<?php if(!empty($lit_data->pubmed_id)):?>

<tr >
	<td>
		Pub med web link
	</td>
	<td>
	<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/<?=$lit_data->pubmed_id?>">Click here to open in new window</a>
	</td>	
</tr>


<?php endif;?>



<tr id="upload_block_button">
	<td>
		Upload pdf file
		<br/>if exists
	</td>
	<td>
	<input type="button" id="uploader" value="Upload">
	</td>	
</tr>

<tr id="upload_block_file" style="display:none;">
	<td>
		Uploaded pdf file		
	</td>
	<td id="upload_block_file_name">
		<input type="hidden" id="literature_physicalCopy" name="literature_physicalCopy" value="">
	</td>	
</tr>

</table>

<input type="submit" class="submit" value="Update" />

</form>
<br/>
<h3>Mapping data:</h3>
<a target="_blank" href="index.php?c=brainmaps&m=add&id=<?=$lit_data->literature_id?>">Add new brain map</a>

<?php if(isset($bmaps_data)):?>
<table>
<tr><td><?=$bmaps_data->brain_maps_index?></td><td> <a target="_blank" href="index.php?c=brainmaps&m=edit&id=<?=$bmaps_data->brain_maps_id?>">Details</a></td></tr>
</table>
<?php endif;?>

<?php if(!isset($bmaps_data)):?>
<p>This Literature has no mapping data</p>

<?php endif;?>


<p><strong>Brain Sites:</strong></p>
<p><a href="index.php?c=brainsites&m=add&id=<?=$lit_data->literature_id?>" target="_blank"> Add new Brain Site </a></p>




<h3>Experimental data:</h3>
<p><strong>Injections:</strong></p>
<p><a href="index.php?c=injections&m=add&id=<?=$lit_data->literature_id?>" target="_blank"> Add new injection </a></p>

<?php if(isset($inj_data)):?>

<table>
	<?php foreach($inj_data->result() as $inj): ?>
	<tr><td><?=$inj->injections_index?> - <?=$inj->brain_sites_index?> - <?=$inj->acronym_full_name?> - <?=$inj->tracers_name?> </td><td><a target="_blank" href="index.php?c=injections&m=edit&id=<?=$inj->injections_id?>">Details</a></td></tr>
	<?php endforeach; ?>
</table>
<?php endif;?>

<?php if(!isset($inj_data)):?>
<p>This Literature has no injections data</p>
<?php endif;?>


<p><strong>Labeling outcomes:</strong></p>
<p><a href="index.php?c=labelingoutcome&m=add&id=<?=$lit_data->literature_id?>" target="_blank"> Add new outcome </a></p>
<?php if(isset($outcomes_data)):?>
<p>This paper has labeling outcomes <a target="_blank" href="index.php?c=labelingoutcome&m=add&id=<?=$lit_data->literature_id?>">Show</a><p>	
<?php endif;?>

<?php if(!isset($outcomes_data)):?>
<p>This paper has no labeling outcomes </p>
<?php endif;?>

<p><strong>Labeling Sites:</strong></p>
<p><a href="index.php?c=labelledsites&m=add&id=<?=$lit_data->literature_id?>" target="_blank"> Add new Labeled Site </a></p>

<p><strong>Injections And Outcomes:</strong></p>
<p><a href="index.php?c=injectionsoutcomes&id=<?=$lit_data->literature_id?>" target="_blank"> Add new relation of injections and outcomes</a></p>


<?php if(isset($relation_data)):?>
<p>This paper has relations of labeling outcomes and injections <a target="_blank" href="index.php?c=injectionsoutcomes&id=<?=$lit_data->literature_id?>">Show</a><p>	
<?php endif;?>

<?php if(!isset($relation_data)):?>
<p>This paper has no relations of labeling outcomes and injections </p>
<?php endif;?>


</div>
<h3>Maps relation data:</h3>
<p><a href="index.php?c=mapsrelations&m=add&id=<?=$lit_data->literature_id?>" target="_blank"> Add new maps relations </a></p>

<?php if(isset($mr_data)):?>
<table>
<tr>
	<td>This paper describes <strong><?=$mr_data_num?></strong> maps relations </td>
	<td>
	<span id="show_all_mr_a"><a href="#" onclick="show_all_mapsrel('<?=$mr_data?>')">Show all</a></span> 
	<span id="hide_all_mr_a" style="display:none;" >
		<a href="#" onclick="hide_all_mapsrel()">Hide all</a> &nbsp; 
		<a href="#" onclick="show_all_mapsrel('<?=$mr_data?>')">Refresh all</a>
	</span>
	</td>
</tr>
</table>
<?php endif;?>

<?php if(!isset($mr_data)):?>
<p>This Literature has no Maps relation data</p>
<?php endif;?>
<br/>
<div id="mrel_block"></div>





<script type="text/javascript">
//<![CDATA[
new Autocomplete('autocomplite_auth', { 
	serviceUrl:'index.php/authors/ajaxAtocomplit', 
	onSelect: function(value, data){
    	auth_id = data;
    	
	}
 });

new Autocomplete('autocomplite_abbr', { 
	serviceUrl:'index.php/abbreviations/ajaxAtocomplit', 
	onSelect: function(value, data){
    	abbr_id = data;
    	abbr_select();
	}
 });
 

auth_num = auth_num+<?=$auth_data_numr?>; 

abbr_id = <?=$lit_data->literature_source?>; 


<?php if(!empty($lit_data->literature_physicalCopy)):?>
select_file('<?=$lit_data->literature_physicalCopy?>');
<?php endif;?>




var uploader = document.getElementById('uploader');

upclick(
  {
   element: uploader,
   action: 'index.php/literature/uploadPdfFile', 
   onstart:
     function(filename){
       alert('Start upload: '+filename);
     },
   oncomplete:
     function(response_data){
       if(response_data != 'FAIL') {

           select_file(response_data);

       } else {

           alert('An Error has occured, try once again!');

       }
     }
  });



$('frm').onsubmit = function () { return check_form(this);}


$('upd_dialog_frm').onsubmit = function () { return send_update_reason(this);}

//]]>
</script>


<?php endif;?>




<?php endif;?>



<?php if($action == 'all_literature'):?>

<h2>All Literature</h2>

<?php if(isset($block_message)):?>
<p><?=$block_message?></p>
<?php endif;?>


<?php if(isset($block_data)):?>

<?php $local_data = array(); $local_data['output_data'] =$block_data; $local_data['fields_data'] = $block_fields; $this->load->view('standart_table_view',$local_data);?>

<?php else: ?>
<p> No papers</p>
<?php endif;?>



<?php endif;?>





<?php $this->load->view('footer');