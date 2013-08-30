<?php

require_once 'CRM/Core/Form.php';

class CRM_Optionsimporter_Form_OptionsImporter extends CRM_Core_Form
{
  CONST VALUE_LABEL = 1, LABEL_VALUE = 2, VALUE = 3, NOT_CHECK = 1, SKIP_OPTION = 2, OVERWRITE_LABEL = 3;
  
  protected $_gid;
  protected $_ogid;
  protected $_fid;
  
  /**
   * build all the data structures needed to build the form
   *
   * @return void
   * @access public
   */
  function preProcess()
  {
    parent::preProcess();
  }
  
  /**
   * Build the form
   *
   * @access public
   * @return void
   */
  function buildQuickForm()
  {
    civicrm_initialize();
    $this->_fid = CRM_Utils_Request::retrieve('fid', 'Positive', $this);
    $this->_gid = CRM_Utils_Request::retrieve('gid', 'Positive', $this);
    
    //Get group    
    $field_name = "";
    $results    = civicrm_api("CustomField", "getsingle", array(
      'version' => '3',
      'id' => $this->_fid
    ));
    
    if(!isset($results["is_error"])) {
      $this->_ogid = $results["option_group_id"];
    } else {
      CRM_Core_Error::fatal(ts('Wrong Custom Field!!'));
      return;
    }
    // Get Custom Field Label
    $result = civicrm_api("CustomField", "getsingle", array(
      'version' => '3',
      'sequential' => '1',
      'id' => $this->_gid
    ));
    if(!isset($result['is_error'])) {
      $field_name = $result["label"];
    } else {
      CRM_Core_Error::fatal(ts('Wrong Custom Field!!'));
      return;
    }
    
    CRM_Utils_System::setTitle("$field_name - " . ts('Import Option Values'));
    
    //Setting Upload File Size
    $config = CRM_Core_Config::singleton();
    if($config->maxImportFileSize >= 8388608) {
      $uploadFileSize = 8388608;
    } else {
      $uploadFileSize = $config->maxImportFileSize;
    }
    $uploadSize = round(($uploadFileSize / (1024 * 1024)), 2);
    $this->assign('uploadSize', $uploadSize);
    
    $this->add('file', 'uploadFile', ts('Import Data File'), 'size=30 maxlength=255', TRUE);
    
    $this->addRule('uploadFile', ts('A valid file must be uploaded.'), 'uploadedfile');
    $this->addRule('uploadFile', ts('File size should be less than %1 MBytes (%2 bytes)', array(
      1 => $uploadSize,
      2 => $uploadFileSize
    )), 'maxfilesize', $uploadFileSize);
    $this->setMaxFileSize($uploadFileSize);
    $this->addRule('uploadFile', ts('Input file must be in CSV format'), 'utf8File');
    
    $this->addElement('checkbox', 'skipColumnHeader', ts('First row contains column headers'));
    
    $this->addElement('text', 'fieldSeparator', ts('Import Field Separator'), array(
      'size' => 2,
      'maxlength' => 1
    ));
    $this->addElement('text', 'textEnclosure', ts('Field Text Enclosure'), array(
      'size' => 1,
      'maxlength' => 1
    ));    
    
    $overrideOptions[self::NOT_CHECK]       = $this->createElement('radio', null, ts("Don't check"), "Don't check", self::NOT_CHECK);
    $overrideOptions[self::SKIP_OPTION]     = $this->createElement('radio', null, ts('Skip Option'), "Skip Option", self::SKIP_OPTION);
    $overrideOptions[self::OVERWRITE_LABEL] = $this->createElement('radio', null, ts('Overwrite Label'), "Overwrite Label", self::OVERWRITE_LABEL);
    
    $this->addGroup($overrideOptions, 'overrideimport', ts('If Option Value Exists'));    
    
    $colOrder = array(
      self::VALUE_LABEL => ts("2 columns (value, label)"),
      self::LABEL_VALUE => ts("2 columns (label, value)"),
      self::VALUE => ts("only 1 column (label will be same as value)")
    );
    $this->add('select', 'colOrder', ts('Columns Order'), $colOrder, TRUE);    
    
    $this->addButtons(array(
      array(
        'type' => 'upload',
        'name' => ts('Import >>'),
        'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        'isDefault' => TRUE
      ),
      array(
        'type' => 'cancel',
        'name' => ts('Cancel')
      )
    ));
    
  }
  
  function setDefaultValues()
  {
    $config   = CRM_Core_Config::singleton();
    $defaults = array(
      'fieldSeparator' => $config->fieldSeparator,
      'textEnclosure' => "'",
      'overrideimport' => '2'
    );
    
    return $defaults;
  }
  
  public function insertValue_customfield($value, $label)
  {
    $params = array(
      'version' => 3,
      'name' => strtolower(CRM_Utils_String::munge($label, '_', 64)),
      'label' => $label,
      'value' => $value,
      'option_group_id' => $this->_ogid,
      'weight' => ++$this->weight
    );
    $result = civicrm_api("OptionValue", "Create", $params);
    $this->_lineCount++;
  }
  
  /**
   * process the form after the input has been submitted and validated
   *
   * @access public
   * @return None
   */
  public function postProcess()
  {    
    civicrm_initialize();
    require_once 'CRM/Utils/String.php';
    
    $skipColumnHeader = $this->controller->exportValue($this->_name, 'skipColumnHeader');
    $separator        = $this->controller->exportValue($this->_name, 'fieldSeparator');
    $colOrder         = $this->controller->exportValue($this->_name, 'colOrder');
    $fileName         = $this->controller->exportValue($this->_name, 'uploadFile');
    $textEnclosure    = $this->controller->exportValue($this->_name, 'textEnclosure');
    $override_import  = $this->controller->exportValue($this->_name, 'overrideimport');    
    
    if(empty($separator)) {
      $config    = CRM_Core_Config::singleton();
      $separator = $config->fieldSeparator;
    }
    
    if(!is_array($fileName)) {
      CRM_Core_Error::fatal();
    }
    $fileName = $fileName['name'];
    
    $fd = fopen($fileName, "r");
    if(!$fd) {
      CRM_Core_Error::fatal();
      return FALSE;
    }
    
    /* ToDo:
    1.     API doesn't sets the right weight if not passed in params. I get the last weight in the options. 
    */
    $this->weight = 0;
    $params       = array(
      'version' => '3',
      'option_group_id' => $this->_ogid,
      'options' => array(
        'sort' => 'weight DESC',
        'limit' => 1
      ),
      'return' => 'weight'
    );
    $results      = civicrm_api("OptionValue", "getvalue", $params);
    
    if(isset($results)) {
      $weight = intval($results);
    }
    if($skipColumnHeader)
      fgets($fd);
    
    while(($values = fgetcsv($fd, 8192, $separator)) !== FALSE) {
      if(CRM_Utils_System::isNull($values)) {
        continue;
      }
      
      $label = $value = "";
      if($colOrder == self::VALUE_LABEL) {
        $value = $this->_encloseAndTrim($values[0], $textEnclosure);
        $label = $this->_encloseAndTrim($values[1], $textEnclosure);
      } elseif($colOrder == self::LABEL_VALUE) {
        $label = $this->_encloseAndTrim($values[0], $textEnclosure);
        $value = $this->_encloseAndTrim($values[1], $textEnclosure);
      } elseif($colOrder == self::VALUE) {
        $label = $value = $this->_encloseAndTrim($values[0], $textEnclosure);
      } else {
      }
      
      /* ToDo:
      2.    API Doesn't sets the "normalized" name from label as in the Web Form UI (Users insert label not name in UI)
      3.    If option value already exists API inserts it anyway. Should that be an error?
      */
      
      /*Not check*/
      if($override_import == self::NOT_CHECK) {
        $this->insertValue_customfield($value, $label);
      }
      
      /*Skip Option*/
      if($override_import == self::SKIP_OPTION) {
        $values_cutom_field = civicrm_api("OptionValue", "get", array(
          version => '3',
          'sequential' => '1',
          'option_group_id' => $this->_ogid
        ));
        $notexist           = true;
        foreach($values_cutom_field["values"] as $key => $value_comp) {
          if($value_comp["value"] == $value) {
            $notexist = false;
            break;
          }
        }
        if($notexist) {
          $this->insertValue_customfield($value, $label);
        }
      }
      
      /*Overwrite Label*/
      if($override_import == self::OVERWRITE_LABEL) {
        $values_cutom_field = civicrm_api("OptionValue", "get", array(
          'version' => '3',
          'sequential' => '1',
          'option_group_id' => $this->_ogid
        ));
        $notexist           = true;
        foreach($values_cutom_field["values"] as $key => $value_comp) {
          if($value_comp["value"] == $value) {
            civicrm_api("OptionValue", "update", array(
              'version' => '3',
              'sequential' => '1',
              'id' => $value_comp['id'],
              'label' => $label
            ));
            $notexist = false;
            $this->_lineUpdate++;
            break;
          }
        }
        if($notexist) {
          $this->insertValue_customfield($value, $label);
        }
      }
    }
    fclose($fd);
    
    /* ToDo:
    4. Would be nice to display a summary page here, right?
    */
    $this->_lineCount  = ($this->_lineCount == NULL) ? 0 : $this->_lineCount;
    $this->_lineUpdate = ($this->_lineUpdate == NULL) ? 0 : $this->_lineUpdate;
    $statusMsg         = "Options Inserted:" . $this->_lineCount . ", Options Updated:" . $this->_lineUpdate;
    CRM_Core_Session::setStatus($statusMsg, false);
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/admin/custom/group/field/option', "reset=1&action=browse&gid=" . $this->_gid . "&fid=" . $this->_fid));
  }
  
  private function _encloseAndTrim($value, $enclosure = "'")
  {
    if(empty($value)) {
      return;
    }
    // Replace enclosures and trim blank chars
    return preg_replace("/^$enclosure(.*)$enclosure$/", '$1', trim($value, " \t\r\n"));
  }
}