<?php

require_once 'CRM/Core/Page.php';

class CRM_Optionsimporter_Page_DeleteImporter extends CRM_Core_Page {
  protected $_gid;
  protected $_ogid;
  protected $_fid;



  function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml

    civicrm_initialize();
    $this->_fid = CRM_Utils_Request::retrieve('fid', 'Positive', $this);
    $this->_gid = CRM_Utils_Request::retrieve('gid', 'Positive', $this);
    
    //Get group 

    $field_name = "";   
    $results = civicrm_api("CustomField","getsingle", array ('version' => '3', 'id' => $this->_fid)); 

    if(!isset($results["is_error"])){
      $this->_ogid = $results["option_group_id"];
    }
    else{
      CRM_Core_Error::fatal(ts('Wrong Custom Field!!'));
      return;
    }
    $result = civicrm_api("CustomField", "getsingle", array ('version' => '3','sequential' =>'1', 'id' => $this->_gid));  
    if (!isset($result['is_error'])){
      $field_name = $result["label"];
    }
    else{
      CRM_Core_Error::fatal(ts('Wrong Custom Field!!'));
      return;
    }

    CRM_Utils_System::setTitle(ts('Deleted Values'));

   $values_field=civicrm_api("OptionValue","get", array (version => '3','sequential' =>'1', 'option_group_id' =>$results["option_group_id"]));
   $number_elements_deleted=0;
   $number_elements_not_deleted=0;
   $value_deleted=array();
   $value_not_deleted=array();

   foreach ($values_field['values'] as $key => $value) {        
      //CRM_Core_Error::debug($value, $variable = null, $log = true, $html = true);      
      $results_error=civicrm_api("OptionValue","delete", array (version => '3','sequential' =>'1', 'id' =>$value['id']));
      if($results_error['is_error']==0){
        $number_elements_deleted++;
        $value_deleted[$value['label']]=$value['value'];
      }
      else{
        $number_elements_not_deleted++;
        $value_not_deleted[$value['label']]=$value['value'];
      }
      CRM_Core_Error::debug($results_error, $variable = null, $log = true, $html = true);      
    }     
    $this->assign('gid', $this->_gid);
    $this->assign('value_deleted', $value_deleted);
    $this->assign('value_not_deleted', $value_not_deleted);
    $this->assign('number_elements_deleted', $number_elements_deleted);
    $this->assign('number_elements_not_deleted', $number_elements_not_deleted);

    parent::run();
    CRM_Core_Session::setStatus( $statusMsg, false );
  }


}
