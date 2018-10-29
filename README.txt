**Important:**
This repo has been moved to https://lab.civicrm.org/extensions/optionsimporter


---

Options Importer - CiviCRM Extension



Introduction
============
Options Importer Extension allows you to view import automatically custom field's options within a CSV file, Also you can Delete all options in a custom field (Use with care!!)

Installation
============
1. Move the downloaded extension to your extensions folder.
2. Goto civicrm/admin/extensions&reset=1  -- install the extension
 
If you are new to CiviCRM Extension you can get help about extension from

http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions

Usage
=====
1. Go to the Custom Field Sectio by the top menu in Administer / Customiza Data and Screens / Custom Fields
2. Select the custom fiueld you want to import / delete the options
3. Click on Edit Multiple Choice Optionsmore link
4. Options list will displayed (if any already created). Below the list you will see two buttons. "Import Options" and "Delete Options"

4.1 "Delete Options" will delete alloptions in the custom field. USE IT WITH CARE!!
4.2 "Import Options" will go to the Import Form. There select:
* The csv file to import containing the options
* Check if first row contains column headers
* Select the csv field separator (by default is comma)
* Select the text field enclosure (if necesary, depending on the csv format)
* Select the order of the columns in the file:
	2 columns (value, label)
	2 columns (label, value)
	only 1 column (label will be same as value)
* Click on Import, and save loads of typing time !!!


Bugs & Support
==============

For bugs regarding this demo extension, please post on the github project page:
https://github.com/ixiam/com.ixiam.modules.optionsimporter/issues

LICENSE AND REDISTRIBUTION:

(C) 2013 Luciano Spiegel
IXIAM GLOBAL SOLUTIONS S.L.U.
http://www.ixiam.com/en
info@ixiam.com

Redistributed under the AGPL license:
http://civicrm.org/licensing
