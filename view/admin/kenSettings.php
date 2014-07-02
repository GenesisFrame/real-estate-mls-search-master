<?php
/*
Plugin Name: Real Estate Cloud
Plugin URI: http://www.realestatecloud.co
Description: Real Estate MLS IDX Plugin
Version: 1.5.1
Author: Real Estate Cloud LLC
Author URI: http://www.realestatecloud.co
License: Real Estate Cloud LLC
*/
?>

<?php
 $states = array(
     'demo'         => 'DEMO',
     'baltimore'    => 'Baltimore',
     'boston'       => 'Boston',
     'chicago'      => 'Chicago',
     'dallas'       => 'Dallas',
     'dc'           => 'DC',
     'denver'       => 'Denver',
     'ftlauder'     => 'Florida',
     'la'           => 'Los Angeles',
     'miami'        => 'Miami',
     'nj'           => 'New Jersey',
     'sf'           => 'San Francisco'
 );
?>

<div class="wrap">
<h2>Real Estate Cloud Wordpress Plugin Settings</h2>
<form method="post" action="">
<?php //wp_nonce_field('wp-paginate-update-options'); ?>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="api-address">Real Estate Cloud API key: </label></th>
			<td>
                <input name="apiKey" type="text" id="api-address" size="100" value="<?php echo $this->options['apiKey']?>"/>
                <span class="description">API Key for access to server </span>
                <p>If you don't have API key please contact Real Estate Cloud.</p>
            </td>
		</tr>

        <tr valign="top">
			<th scope="row"><label for="default-state">MLS area: </label></th>
			<td>
                <select id="default-state" name="defaultState" style="width: 205px;">
                    <?php foreach($states as $key => $value): ?>
                        <option <?php if ($key==$this->options['defaultState']): ?>selected="selected"<?php endif; ?> value="<?php echo $key ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
			    <span class="description">Your location</span>

                <?php if ($this->errorCode == 8): ?>
                    <div class="error" style="margin: 3px 0px; padding: 10px; background-color: #fffbfa; border: 1px solid #fff2f2; ">
                        This area is not allowed for this API key
                    </div>
                <?php endif; ?>
            </td>
		</tr>
	</table>

    <table class="form-table">
        <tr valign="top">
			<th scope="default-city">Default city: </th>
			<td>
                <input name="defaultCity" type="text" id="default-city" size="33" value="<?php echo $this->options['defaultCity']?>"/>

                <span class="description"></span>
            </td>
		</tr>
	</table>
	
	<table class="form-table">
        <tr valign="top">
			<th scope="default-area">Default area: </th>
			<td>
                <input name="defaultArea" type="text" id="default-area" size="33" value="<?php echo $this->options['defaultArea']?>"/>

                <span class="description"></span>
            </td>
		</tr>
	</table>

    <table class="form-table">
        <tr valign="top">
			<th scope="row">MLS Search Page: </th>
			<td>
                <select  name="pageProperty" style="width: 205px;">
                    <?php reParentDropdown($this->options['pageProperty']); ?>
                </select>

                <span class="description"></span>
            </td>
		</tr>
	</table>

	<p class="submit">
		<input type="submit" value="Save Changes" name="wp_property_save" class="button-primary" />
	</p>
</form>
</div>
