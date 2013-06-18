<?php
require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );
require_once('amqp.php');

class MantisAmqpPlugin extends MantisPlugin
{
	function register() {
		$this->name = plugin_lang_get( 'plugin_title' );
		$this->description = plugin_lang_get( 'plugin_description' );
		$this->page        = '';
		$this->version     = '0.2';
		$this->requires    = array(
			'MantisCore' => '1.2.0', 
			);		
		
		$this->author = plugin_lang_get( 'plugin_author' );
		$this->contact = 'webmaster@croc-informatique.com';
		$this->url     = 'http://www.croc-informatique.com';
		$this->page    = 'manage_config';
	}
	
	function hooks() {
		return array(
			'EVENT_REPORT_BUG' => 'report_bug',
		);
	}
	
	function config() {
		return array( 'exclude_reporter' => ON,
					  'exclude_handler'  => ON,
					  'config'           => array(), );
	}
	
	function report_bug($p_event, $bug_data, $bug_id) {
		
		$exclude_reporter = plugin_config_get('exclude_reporter', OFF);
		$exclude_handler  = plugin_config_get('exclude_handler' , OFF);
		$config           = plugin_config_get('config', OFF);
		
		// Debug
		$fp = fopen ("compteur.txt", "w+");
		
		//Récupération du nom du projet
		$t_project_table = db_get_table( 'mantis_project_table' );
    $query = "SELECT name
		          FROM $t_project_table
				      WHERE id = $bug_data->project_id";
	  $result = db_query_bound( $query );
    $t_projects_found = array();
	    
	  while( $row = db_fetch_array( $result ) ) {
	     $project_name=$row['name'];
	  }
	  
	  //Reporter
	  $t_user_table = db_get_table( 'mantis_user_table' );
    $query = "SELECT username
		          FROM $t_user_table
				      WHERE id = $bug_data->reporter_id";
	  $result = db_query_bound( $query );
    $t_user_found = array();
	    
	  while( $row = db_fetch_array( $result ) ) {
	     $user_name=$row['username'];
	  }

    // Variables
    $category_id = $bug_data->category_id;
    $bug_id      = $bug_data->id;
    $bug_title   = $bug_data->summary;
    $bug_description = $bug_data->description;
    $bug_priority = $bug_data->priority;
    $bug_severity = $bug_data->severity;
    $bug_state    = $bug_data->status;
    $Message= "Category_id:$category_id,bug_id:$bug_id,bug_title:$bug_title,bug_description:$bug_description,bug_priority:$bug_priority,bug_severity:$bug_severity,bug_state:$bug_state";
    //fputs ($fp, $Message);
    
    // State
      if ($bug_severity<60)
      {
        $state_amqp=0;
      }
      else {
        $state_amqp=2;
      }
      
      //Type
      if ($bug_state!= ""){
        $status_amqp=get_enum_element( 'status', $bug_state ) ;
      }
      
      amqp("Mantis",gethostname(),$status_amqp,$state_amqp,1,"$user_name - $bug_title","Mantis - $project_name");
      
      fputs ($fp, "$user_name");
    /*
    function amqp($connector_name,$component,$resource,$state,$state_type,$output,$display_name){
    //Status :
    $s_status_enum_string = '10:nouveau,20:commentaire,30:acceptÃ©,40:confirmÃ©,50:ecours,80:rÃ©solu,90:annulÃ©';
    // Standard
    $s_priority_enum_string = '10:none,20:low,30:normal,40:high,50:urgent,60:immediate';
    $s_severity_enum_string = '10:feature,20:trivial,30:text,40:tweak,50:minor,60:major,70:crash,80:block';
    
    //Prod
    $s_severity_enum_string = '10:mineur,20:serieux,30:texte,40:cosmÃ©tique,50:norma,60:majeur,70:critique,80:bloquant';
    
    
    
    

    $msg_body = array(
	"timestamp"		=> time(),            ok
	"connector"		=> "cli",               ok
	"connector_name"	=> "Mantis",       ok
	"event_type"		=> "log",            ok
	"source_type"		=> "resource",        ok
	"component"		=> gethostname(),    ok--> Url à récupérer
	"resource"		=> "",                     OK --> Type de changement ( Nouveau/ Commentaire / clôture)
	"state"			=> 0,                        ok --> State (0 (Ok), 1 (Warning), 2 (Critical), 3 (Unknown)),
	"state_type"		=> 1,                    ok --> State type (O (Soft), 1 (Hard)),
	"output"		=> "MESSAGE",                ok --> $user_name - $bug_title 
	"display_name"		=>"Mantis"             ok --> Mantis - $project_name
);
*/
			
	// Instruction 1

// Instruction 2  
// Instruction 3
$visible_bug_data = email_build_visible_bug_data( $user_id, $bug_id, $message_id );
$nb_visites = "$bug_data->id, $bug_data->category_id, $bug_data->project_id,$bug_data->summary , $bug_data->description\n";  
// Instruction 4
// Instruction 5
  
// Instrcution 6
fclose ($fp);  
// Instrcution 7
	
		if(!array_key_exists($bug_data->project_id, $config))
			return;
		
		$diff = array(false);
		if(exclude_reporter == ON)
			$diff[] = $bug_data->reporter_id;
		if(exclude_handler  == ON)
			$diff[] = $bug_data->handler_id;
		
		$users = array_map(user_get_id_by_name, $config[$bug_data->project_id]);
		$users = array_diff($users, $diff);
		$message_id = 'email_notification_title_for_action_bug_submitted'; 

		
		ignore_user_abort(true);
		
		foreach( $users as $user_id ) {
			lang_push( user_pref_get_language( $user_id, $bug_data->project_id ) );
			$visible_bug_data = email_build_visible_bug_data( $user_id, $bug_id, $message_id );
			email_bug_info_to_one_user( $visible_bug_data, $message_id, $bug_data->project_id, $user_id);
			lang_pop();
		}

		email_send_all();
	}
}
?>
