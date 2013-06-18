<?php
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$t_activate = plugin_config_get( 'activate' );
$t_host = plugin_config_get( 'host' );
$t_port = plugin_config_get( 'port' );
$t_user = plugin_config_get( 'user' );
$t_password = plugin_config_get( 'password' );
$t_vhost = plugin_config_get( 'vhost' );
$t_exchange = plugin_config_get( 'exchange' );

html_page_top( plugin_lang_get( 'plugin_title' ) );

print_manage_menu();
?>

<br />

<form action="<?php echo plugin_page( 'manage_config_edit' )?>" method="post">
<table align="center" class="width75" cellspacing="1">

<tr <?php echo helper_alternate_class( )?>>
	<td><?php echo plugin_lang_get( 'activate' ) ?></td>
	<td>
		<label>
			<input type="radio" name="activate" value="<?php echo ON ?>" <?php echo ( ( $t_activate == ON )  ? ' checked="checked"' : NULL ) ?>/>
			<?php echo plugin_lang_get( 'yes' ) ?>
		</label>
	</td>
	<td>
		<label>
			<input type="radio" name="activate" value="<?php echo OFF ?>"<?php echo ( ( $t_activate == OFF ) ? ' checked="checked"' : NULL ) ?>/>
			<?php echo plugin_lang_get( 'no' ) ?>
		</label>
	</td>
</tr>
<tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'host' ) ?></td>
        <td colspan="2"><label><input type="text" name="host" cols="10" rows="1" value="<?php echo string_attribute( $t_host ) ?>"></label></td>
</tr>
<tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'port' ) ?></td>
        <td colspan="2"><input type="text" name="port" cols="10" rows="1" value="<?php echo string_attribute( $t_port ) ?>"></td>
</tr><tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'user' ) ?></td>
        <td colspan="2"><input type="text" name="user" cols="10" rows="1" value="<?php echo string_attribute( $t_user ) ?>"></td>
</tr><tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'password' ) ?></td>
        <td colspan="2"><input type="text" name="password" cols="10" rows="1" value="<?php echo string_attribute( $t_password ) ?>"></td>
</tr><tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'vhost' ) ?></td>
        <td colspan="2"><input type="text" name="vhost" cols="10" rows="1" value="<?php echo string_attribute( $t_vhost ) ?>"></td>
</tr><tr <?php echo helper_alternate_class( )?>>
        <td><?php echo plugin_lang_get( 'exchange' ) ?></td>
        <td colspan="2"><input type="text" name="exchange" cols="10" rows="1" value="<?php echo string_attribute( $t_exchange ) ?>"></td>
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
