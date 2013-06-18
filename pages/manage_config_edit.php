<?php
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
$f_gpc = array(
	'activate' => gpc_get_bool( 'activate' ),
	'host'	   => gpc_get_string( 'host' ),
        'port'     => gpc_get_string( 'port' ),
        'user'     => gpc_get_string( 'user' ),
        'password' => gpc_get_string( 'password' ),
        'vhost'    => gpc_get_string( 'vhost' ),
        'exchange' => gpc_get_string( 'exchange' )
);

foreach ( $f_gpc AS $t_key => $t_value )
{
echo "$t_key\n";
echo $t_value;
	if( plugin_config_get( $t_key ) != $t_value )
	{
		plugin_config_set( $t_key, $t_value );
		
	}
}

print_successful_redirect( plugin_page( 'manage_config', TRUE ) );
