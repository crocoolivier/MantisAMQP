<?php
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

html_page_top( plugin_lang_get( 'plugin_title' ) );

print_manage_menu();

$reporter = plugin_config_get("exclude_reporter", ON);
$handler  = plugin_config_get("exclude_handler", ON);
$config   = plugin_config_get("config");

?>

<br />

<form action="<?php echo plugin_page( 'manage_config_edit' )?>" method="post">
<table align="center" class="width75" cellspacing="1">

<tr <?php echo helper_alternate_class( )?>>
	<td><?php echo plugin_lang_get( 'exclude_reporter' ) ?></td>
	<td>
		<label>
			<input type="radio" name="exclude_reporter" value="<?php echo ON ?>" <?php echo ( ( $reporter == ON )  ? ' checked="checked"' : NULL ) ?>/>
			<?php echo plugin_lang_get( 'yes' ) ?>
		</label>
	</td>
	<td>
		<label>
			<input type="radio" name="exclude_reporter" value="<?php echo OFF ?>"<?php echo ( ( $reporter == OFF ) ? ' checked="checked"' : NULL ) ?>/>
			<?php echo plugin_lang_get( 'no' ) ?>
		</label>
	</td>
</tr>
<tr <?php echo helper_alternate_class( )?>>
	<td><?php echo plugin_lang_get( 'exclude_handler' ) ?></td>
	<td>
		<label>
			<input type="radio" name="exclude_handler" value="<?php echo ON ?>" <?php echo ( ( $handler == ON )  ? ' checked="checked"' : NULL ) ?>/>
			<?php echo plugin_lang_get( 'yes' ) ?>
		</label>
	</td>
	<td>
		<label>
			<input type="radio" name="exclude_handler" value="<?php echo OFF ?>"<?php echo ( ( $handler == OFF ) ? ' checked="checked"' : NULL ) ?>/>
			<?php echo plugin_lang_get( 'no' ) ?>
		</label>
	</td>
</tr>
<tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'config' ) ?></td>
        <td colspan="2"><textarea name="config" cols="50" rows="50"><?php var_export($config)?></textarea></td>
</tr>
<tr>
	<td class="center" width="100%" colspan="3">
		<input type="submit" class="button" value="submit" />
	</td>
</tr>

</table>
</form>

<?php
html_page_bottom( __FILE__ );
?>
