<?php
require_once(config_get('class_path') . 'MantisPlugin.class.php');
require_once('amqp.php');

class MantisAmqpPlugin extends MantisPlugin
{
    function register()
    {
        $this->name        = plugin_lang_get('plugin_title');
        $this->description = plugin_lang_get('plugin_description');
        $this->page        = '';
        $this->version     = '0.1';
        $this->requires    = array(
            'MantisCore' => '1.2.0'
        );
        
        $this->author  = plugin_lang_get('plugin_author');
        $this->contact = 'webmaster@croc-informatique.com';
        $this->url     = 'http://www.croc-informatique.com';
        $this->page    = 'manage_config';
    }
    
    function hooks()
    {
        return array(
            'EVENT_REPORT_BUG' => 'report_bug'
        );
    }
    
    function config()
    {
        return array(
            'exclude_reporter' => ON,
            'exclude_handler' => ON,
            'config' => array()
        );
    }
    
    function report_bug($p_event, $bug_data, $bug_id)
    {
        
        
        // Debug
        $fp = fopen("compteur.txt", "w+");
        
        //Récupération du nom du projet
        $t_project_table  = db_get_table('mantis_project_table');
        $query            = "SELECT name
		          FROM $t_project_table
				      WHERE id = $bug_data->project_id";
        $result           = db_query_bound($query);
        $t_projects_found = array();
        
        while ($row = db_fetch_array($result)) {
            $project_name = $row['name'];
        }
        
        //Reporter
        $t_user_table = db_get_table('mantis_user_table');
        $query        = "SELECT username
		          FROM $t_user_table
				      WHERE id = $bug_data->reporter_id";
        $result       = db_query_bound($query);
        $t_user_found = array();
        
        while ($row = db_fetch_array($result)) {
            $user_name = $row['username'];
        }
        
        // Variables
        $category_id     = $bug_data->category_id;
        $bug_id          = $bug_data->id;
        $bug_title       = $bug_data->summary;
        $bug_description = $bug_data->description;
        $bug_priority    = $bug_data->priority;
        $bug_severity    = $bug_data->severity;
        $bug_state       = $bug_data->status;
        $Message         = "Category_id:$category_id,bug_id:$bug_id,bug_title:$bug_title,bug_description:$bug_description,bug_priority:$bug_priority,bug_severity:$bug_severity,bug_state:$bug_state";
        //fputs ($fp, $Message);
        
        // State
        if ($bug_severity < 60) {
            $state_amqp = 0;
        } else {
            $state_amqp = 2;
        }
        
        //Type
        if ($bug_state != "") {
            $status_amqp = get_enum_element('status', $bug_state);
        }
        
        amqp("Mantis", gethostname(), $status_amqp, $state_amqp, 1, "$user_name - $bug_title", "Mantis - $project_name");
    }
}
?>
