<?php echo $gmaps_js; ?>

<h1>Datasel</h1>

This demo shows data entered by using the eMOCHA demo form
in an Android phone. The form data has previously been sent
to the server in the form of XML files.

<table>
	<tr>
		<td>
			<div id="map_canvas"></div>
		</td>
		<td>
			<form method="post" action="<?php echo Url::site('main/datasel') ?>">
				Gender<br/>
				<input type="radio" name="gender" value="m" />Male<br/>
				<input type="radio" name="gender" value="f" />Female<br/>
				<input type="radio" name="gender" value="" />Both<br/>
				<br/>
				TB<br/>
				<input type="radio" name="tb" value="y" />yes<br/>
				<input type="radio" name="tb" value="n" />no<br/>
				<input type="radio" name="tb" value="" />both<br/>
				<br/>
				HIV<br/>
				<input type="radio" name="hiv" value="y" />yes<br/>
				<input type="radio" name="hiv" value="n" />no<br/>
				<input type="radio" name="hiv" value="" />both<br/>
				<br/>
				Age between<br/>
				<input type="text" name="age_min" size="2" class="minmax" /> and
				<input type="text" name="age_max" size="2" class="minmax" /><br/>
				<br/>
				Temp between<br/>
				<input type="text" name="temp_min" size="2" class="minmax" /> and 
				<input type="text" name="temp_max" size="2" class="minmax" /><br/>
				<br/>
				<input type="submit" value="search" />
			</form>
		</td>
	</tr>
</table>

<script src="<?php echo Kohana::config('assets.javascript_folder'); ?>/slimbox2.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Kohana::config('assets.css_folder'); ?>/slimbox2.css">