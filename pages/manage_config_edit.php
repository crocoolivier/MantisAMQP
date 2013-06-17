<?php
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_gpc = array(
	'exclude_reporter' => gpc_get_bool( 'exclude_reporter' ),
	'exclude_handler'  => gpc_get_bool( 'exclude_handler' ),
);

foreach ( $f_gpc AS $t_key => $t_value )
{
	if( plugin_config_get( $t_key ) != $t_value )
	{
		plugin_config_set( $t_key, $t_value );
	}
}

$t_config = @eval( 'return ' . gpc_get_string( 'config' ) . ';' );
if( is_array( $t_config) )
{
	if ( plugin_config_get( 'config' ) != $t_config )	{
		plugin_config_set( 'config', $t_config );
	}
}
else
{
	html_page_top( plugin_lang_get( 'plugin_title' ) );
	
	echo '<br /><div class="center">';
	echo plugin_lang_get( 'config_no_array' );
	print_bracket_link( plugin_page( 'manage_config', TRUE ), lang_get( 'proceed' ) );
	echo '</div>';

	$t_notsuccesfull = TRUE;

	html_page_bottom( __FILE__ );
}

if ( !isset( $t_notsuccesfull ) )
{
	print_successful_redirect( plugin_page( 'manage_config', TRUE ) );
}
