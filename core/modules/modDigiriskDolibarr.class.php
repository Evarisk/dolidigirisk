<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2019  Nicolas ZABOURI         <info@inovea-conseil.com>
 * Copyright (C) 2019-2020  Frédéric France         <frederic.france@netlogic.fr>
 * Copyright (C) 2019-2020 Eoxia <dev@eoxia.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   digiriskdolibarr     Module DigiriskDolibarr
 *  \brief      DigiriskDolibarr module descriptor.
 *
 *  \file       htdocs/custom/digiriskdolibarr/core/modules/modDigiriskDolibarr.class.php
 *  \ingroup    digiriskdolibarr
 *  \brief      Description and activation file for module DigiriskDolibarr
 */
include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module DigiriskDolibarr
 */
class modDigiriskdolibarr extends DolibarrModules
{
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;

		$this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 501500; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module
		$this->rights_class = 'digiriskdolibarr';
		// Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
		// It is used to group modules by family in module setup page
		$this->family          = "other";
		$this->module_position = '90';
		$this->name            = preg_replace('/^mod/i', '', get_class($this));
		$this->description     = $langs->trans('DigiriskDolibarrdDescription');
		$this->descriptionlong = "DigiriskDolibarr";
		$this->editor_name     = 'Evarisk';
		$this->editor_url      = 'https://evarisk.com';
		$this->version         = '1.0.0';
		$this->const_name      = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->picto           ='digiriskdolibarr@digiriskdolibarr';

		$this->module_parts = array(
			// Set this to 1 if module has its own trigger directory (core/triggers)
			'triggers' => 1,
			// Set this to 1 if module has its own login method file (core/login)
			'login' => 0,
			// Set this to 1 if module has its own substitution function file (core/substitutions)
			'substitutions' => 1,
			// Set this to 1 if module has its own menus handler directory (core/menus)
			'menus' => 0,
			// Set this to 1 if module overwrite template dir (core/tpl)
			'tpl' => 0,
			// Set this to 1 if module has its own barcode directory (core/modules/barcode)
			'barcode' => 0,
			// Set this to 1 if module has its own models directory (core/modules/xxx)
			'models' => 1,
			// Set this to 1 if module has its own theme directory (theme)
			'theme' => 0,
			// Set this to relative path of css file if module has its own css file
			'css' => array(
				//    '/digiriskdolibarr/css/digiriskdolibarr.css.php',
			),
			// Set this to relative path of js file if module must load a js on all pages
			'js' => array(
				//   '/digiriskdolibarr/js/digiriskdolibarr.js.php',
			),
			// Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
			'hooks' => array(
				'completeTabsHead',
				'admincompany',
				'globaladmin'
			),
			'tabs' => array(
				'mycompany_admin'
			),
			// Set this to 1 if features of module are opened to external users
			'moduleforexternal' => 0,
		);

		$this->dirs = array("/digiriskdolibarr/temp", "/ecm/digiriskdolibarr", "/ecm/digiriskdolibarr/legaldisplay", "/ecm/digiriskdolibarr/informationssharing");

		// Config pages.
		$this->config_page_url = array("setup.php@digiriskdolibarr");
		// Dependencies

		$this->hidden                  = false;
		$this->depends                 = array('modAgenda', 'modECM');
		$this->requiredby              = array();
		$this->conflictwith            = array();
		$this->langfiles               = array("digiriskdolibarr@digiriskdolibarr");
		$this->phpmin                  = array(5, 5); // Minimum version of PHP required by module
		$this->need_dolibarr_version   = array(11, -3); // Minimum version of Dolibarr required by module
		$this->warnings_activation     = array(); // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		$this->warnings_activation_ext = array(); // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
		//$this->automatic_activation = array('FR'=>'DigiriskDolibarrWasAutomaticallyActivatedBecauseOfYourCountryChoice');
		//$this->always_enabled = true;								// If true, can't be disabled

		// Constants
		$this->const = array(
			// CONST CONFIGURATION
			1 => array('DIGIRISK_GENERAL_MEANS','chaine','','General means', 1),
			2 => array('DIGIRISK_GENERAL_RULES','chaine','','General rules', 1),
			// CONST LEGAL DISPLAY
			4 => array('DIGIRISK_LOCATION_OF_DETAILED_INSTRUCTION','chaine','','Location of detailed instruction', 1),
			5 => array('DIGIRISK_DEROGATION_SCHEDULE_PERMANENT','chaine','','Permanent exceptions to working hours', 1),
			6 => array('DIGIRISK_DEROGATION_SCHEDULE_OCCASIONAL','chaine','','Occasional exceptions to working hours', 1),
			7 => array('DIGIRISK_COLLECTIVE_AGREEMENT_TITLE','chaine','','Title of the collective agreement', 1),
			8 => array('DIGIRISK_COLLECTIVE_AGREEMENT_LOCATION','chaine','','Location of the collective agreement', 1),
			9 => array('DIGIRISK_DUER_LOCATION','chaine','','Location of risks evaluation', 1),
			10 => array('DIGIRISK_RULES_LOCATION','chaine','','Location of rules of procedure', 1),
			11 => array('DIGIRISK_PARTICIPATION_AGREEMENT_INFORMATION_PROCEDURE','chaine','','Information procedure of participation agreement', 1),
			// CONST INFORMATIONS SHARING
			12 => array('DIGIRISK_IDCC_DICTIONNARY','chaine','','IDCC of company', 1),
			13 => array('MAIN_AGENDA_ACTIONAUTO_LEGALDISPLAY_CREATE','chaine',1,'', 1),
			14 => array('MAIN_AGENDA_ACTIONAUTO_INFORMATIONSSHARING_CREATE','chaine',1,'', 1),
			15 => array('DIGIRISKDOLIBARR_LEGALDISPLAY_ADDON_ODT_PATH','chaine', DOL_DOCUMENT_ROOT . '/custom/digiriskdolibarr/documents/doctemplates/legaldisplay/' ,'', 1),
			16 => array('DIGIRISKDOLIBARR_LEGALDISPLAY_CUSTOM_ADDON_ODT_PATH','chaine', DOL_DATA_ROOT . '/ecm/digiriskdolibarr/legaldisplay/' ,'', 1),
			17 => array('DIGIRISKDOLIBARR_INFORMATIONSSHARING_ADDON_ODT_PATH','chaine', DOL_DOCUMENT_ROOT . '/custom/digiriskdolibarr/documents/doctemplates/informationssharing/' ,'', 1),
			18 => array('DIGIRISKDOLIBARR_INFORMATIONSSHARING_CUSTOM_ADDON_ODT_PATH','chaine', DOL_DATA_ROOT . '/ecm/digiriskdolibarr/informationssharing/' ,'', 1),
			19 => array('DIGIRISKDOLIBARR_INFORMATIONSSHARING_ADDON','chaine', 'mod_informationssharing_standard' ,'', 1),
			20 => array('DIGIRISKDOLIBARR_LEGALDISPLAY_ADDON','chaine', 'mod_legaldisplay_standard' ,'', 1)
		);

		if ( ! isset($conf->digiriskdolibarr ) || ! isset( $conf->digiriskdolibarr->enabled ) ) {
			$conf->digiriskdolibarr          = new stdClass();
			$conf->digiriskdolibarr->enabled = 0;
		}

		// Array to add new pages in new tabs
		$this->tabs = array();
		// Rajouter les onglets ici
		// Example:
		$this->tabs[] = array('data'=>'mycompany_admin:+security:Sécurité:@digiriskdolibarr:1:/custom/digiriskdolibarr/admin/securityconf.php');  					// To add a new tab identified by code tabname1
		$this->tabs[] = array('data'=>'mycompany_admin:+social:Social:@digiriskdolibarr:1:/custom/digiriskdolibarr/admin/socialconf.php');  					// To add a new tab identified by code tabname1
		$this->tabs[] = array('data'=>'thirdparty:+openinghours:Horaires:@digiriskdolibarr:1:/custom/digiriskdolibarr/openinghours_card.php?id=__ID__');  					// To add a new tab identified by code tabname1

		// To remove an existing tab identified by code tabname
		// Dictionaries
		$this->dictionaries=array(
			'langs'=>'digiriskdolibarr@digiriskdolibarr',
			// List of tables we want to see into dictonnary editor
			'tabname'=>array(MAIN_DB_PREFIX."c_conventions_collectives"),
			// Label of tables
			'tablib'=>array("CollectiveAgreement"),
			// Request to select fields
			'tabsql'=>array('SELECT f.rowid as rowid, f.code, f.libelle, f.active FROM '.MAIN_DB_PREFIX.'c_conventions_collectives as f'),
			// Sort order
			'tabsqlsort'=>array("libelle ASC"),
			// List of fields (result of select to show dictionary)
			'tabfield'=>array("code,libelle"),
			// List of fields (list of fields to edit a record)
			'tabfieldvalue'=>array("code,libelle"),
			// List of fields (list of fields for insert)
			'tabfieldinsert'=>array("code,libelle"),
			// Name of columns with primary key (try to always name it 'rowid')
			'tabrowid'=>array("rowid"),
			// Condition to show each dictionary
			'tabcond'=>array($conf->digiriskdolibarr->enabled, $conf->digiriskdolibarr->enabled, $conf->digiriskdolibarr->enabled)
		);

		// Boxes/Widgets
		$this->boxes = array(
			//  0 => array(
			//      'file' => 'digiriskdolibarrwidget1.php@digiriskdolibarr',
			//      'note' => 'Widget provided by DigiriskDolibarr',
			//      'enabledbydefaulton' => 'Home',
			//  ),
			//  ...
		);

		// Cronjobs (List of cron jobs entries to add when module is enabled)
		$this->cronjobs = array();

		// Permissions provided by this module
		$this->rights = array();
		$r            = 0;

		/* LEGAL DISPLAY PERMISSIONS */
		$this->rights[$r][0] = 1050;
		$this->rights[$r][1] = $langs->trans('ReadLegalDisplay');
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'legaldisplay';
		$this->rights[$r][5] = 'read';
		$r++;
		$this->rights[$r][0] = 1050 + $r;
		$this->rights[$r][1] = $langs->trans('CreateLegalDisplay');
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'legaldisplay';
		$this->rights[$r][5] = 'write';
		$r++;
		$this->rights[$r][0] = 1050 + $r;
		$this->rights[$r][1] = $langs->trans('DeleteLegalDisplay');
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'legaldisplay';
		$this->rights[$r][5] = 'delete';
		$r++;

		/* INFORMATIONS SHARING PERMISSIONS */

		$this->rights[$r][0] = 1050 + $r;
		$this->rights[$r][1] = $langs->trans('ReadInformationsSharing');
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'informationssharing';
		$this->rights[$r][5] = 'read';
		$r++;
		$this->rights[$r][0] = 1050 + $r;
		$this->rights[$r][1] = $langs->trans('CreateInformationsSharing');
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'informationssharing';
		$this->rights[$r][5] = 'write';
		$r++;
		$this->rights[$r][0] = 1050 + $r;
		$this->rights[$r][1] = $langs->trans('DeleteInformationsSharing');
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'informationssharing';
		$this->rights[$r][5] = 'delete';

		// Main menu entries to add
		$this->menu = array();
		$r          = 0;
		/* BEGIN MODULEBUILDER TOPMENU */
		$this->menu[$r++] = array(
			'fk_menu'  => '', // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'     => 'top', // This is a Top menu entry
			'titre'    => 'Digirisk',
			'mainmenu' => 'digiriskdolibarr',
			'leftmenu' => '',
			'url'      => '/digiriskdolibarr/digiriskdolibarrindex.php',
			'langs'    => 'digiriskdolibarr@digiriskdolibarr', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position' => 48520 + $r,
			'enabled'  => '$conf->digiriskdolibarr->enabled', // Define condition to show or hide menu entry. Use '$conf->digiriskdolibarr->enabled' if entry must be visible if module is enabled.
			'perms'    => '1', // Use 'perms'=>'$user->rights->digiriskdolibarr->digiriskconst->read' if you want your menu with a permission rules
			'target'   => '',
			'user'     => 2, // 0=Menu for internal users, 1=external users, 2=both
		);
		/* END MODULEBUILDER TOPMENU */

		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=digiriskdolibarr',	    // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',			                // This is a Left menu entry
			'titre'=>'Documents Légaux',
			'mainmenu'=>'digiriskdolibarr',
			'leftmenu'=>'documents',
			'url'=>'/digiriskdolibarr/legaldocuments_list.php',
			'langs'=>'digiriskdolibarr@digiriskdolibarr',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>48520+$r,
			'enabled'=>'$conf->digiriskdolibarr->enabled',  // Define condition to show or hide menu entry. Use '$conf->digiriskdolibarr->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
			'perms'=>'1',			                // Use 'perms'=>'$user->rights->digiriskdolibarr->level1->level2' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=digiriskdolibarr,fk_leftmenu=documents',      // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',                          // This is a Top menu entry
			'titre'=>'Liste des affichages légaux',
			'mainmenu'=>'digiriskdolibarr',
			'leftmenu'=>'legaldisplay',
			'url'=>'/digiriskdolibarr/legaldisplay_list.php',
			'langs'=>'digiriskdolibarr@digiriskdolibarr',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>48520+$r,
			'enabled'=>'$conf->digiriskdolibarr->enabled',  // Define condition to show or hide menu entry. Use '$conf->digiriskdolibarr->enabled' if entry must be visible if module is enabled.
			'perms'=>'1',			                // Use 'perms'=>'$user->rights->digiriskdolibarr->level1->level2' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=digiriskdolibarr,fk_leftmenu=legaldisplay',      // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',                          // This is a Top menu entry
			'titre'=>'Nouveau',
			'mainmenu'=>'digiriskdolibarr',
			'leftmenu'=>'createlegaldisplay',
			'url'=>'/digiriskdolibarr/legaldisplay_card.php?action=create',
			'langs'=>'digiriskdolibarr@digiriskdolibarr',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>48520+$r,
			'enabled'=>'$conf->digiriskdolibarr->enabled',  // Define condition to show or hide menu entry. Use '$conf->digiriskdolibarr->enabled' if entry must be visible if module is enabled.
			'perms'=>'1',			                // Use 'perms'=>'$user->rights->digiriskdolibarr->level1->level2' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=digiriskdolibarr,fk_leftmenu=documents',      // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',			                // This is a Left menu entry
			'titre'=>'Diffusion d\'informations',
			'mainmenu'=>'digiriskdolibarr',
			'leftmenu'=>'informations',
			'url'=>'/digiriskdolibarr/informationssharing_list.php',
			'langs'=>'digiriskdolibarr@digiriskdolibarr',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>48520+$r,
			'enabled'=>'$conf->digiriskdolibarr->enabled',  // Define condition to show or hide menu entry. Use '$conf->digiriskdolibarr->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
			'perms'=>'1',			                // Use 'perms'=>'$user->rights->digiriskdolibarr->level1->level2' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);
		$this->menu[$r++]=array(
			'fk_menu'=>'fk_mainmenu=digiriskdolibarr,fk_leftmenu=informations',      // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'type'=>'left',			                // This is a Left menu entry
			'titre'=>$langs->trans('New'),
			'mainmenu'=>'digiriskdolibarr',
			'leftmenu'=>'informationsreleasecreate',
			'url'=>'/digiriskdolibarr/informationssharing_card.php' . '?action=create',
			'langs'=>'digiriskdolibarr@digiriskdolibarr',	        // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'position'=>48520+$r,
			'enabled'=>'$conf->digiriskdolibarr->enabled',  // Define condition to show or hide menu entry. Use '$conf->digiriskdolibarr->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
			'perms'=>'1',			                // Use 'perms'=>'$user->rights->digiriskdolibarr->level1->level2' if you want your menu with a permission rules
			'target'=>'',
			'user'=>2,				                // 0=Menu for internal users, 1=external users, 2=both
		);

		// Exports profiles provided by this module
		$r = 1;
		/* BEGIN MODULEBUILDER EXPORT DIGIRISKCONST */
		/*
		$langs->load("digiriskdolibarr@digiriskdolibarr");
		$this->export_code[$r]=$this->rights_class.'_'.$r;
		$this->export_label[$r]='DigiriskConstLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
		$this->export_icon[$r]='digiriskconst@digiriskdolibarr';
		// Define $this->export_fields_array, $this->export_TypeFields_array and $this->export_entities_array
		$keyforclass = 'DigiriskConst'; $keyforclassfile='/digiriskdolibarr/class/digiriskconst.class.php'; $keyforelement='digiriskconst@digiriskdolibarr';
		include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
		//$this->export_fields_array[$r]['t.fieldtoadd']='FieldToAdd'; $this->export_TypeFields_array[$r]['t.fieldtoadd']='Text';
		//unset($this->export_fields_array[$r]['t.fieldtoremove']);
		//$keyforclass = 'DigiriskConstLine'; $keyforclassfile='/digiriskdolibarr/class/digiriskconst.class.php'; $keyforelement='digiriskconstline@digiriskdolibarr'; $keyforalias='tl';
		//include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
		$keyforselect='digiriskconst'; $keyforaliasextra='extra'; $keyforelement='digiriskconst@digiriskdolibarr';
		include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
		//$keyforselect='digiriskconstline'; $keyforaliasextra='extraline'; $keyforelement='digiriskconstline@digiriskdolibarr';
		//include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
		//$this->export_dependencies_array[$r] = array('digiriskconstline'=>array('tl.rowid','tl.ref')); // To force to activate one or several fields if we select some fields that need same (like to select a unique key if we ask a field of a child to avoid the DISTINCT to discard them, or for computed field than need several other fields)
		//$this->export_special_array[$r] = array('t.field'=>'...');
		//$this->export_examplevalues_array[$r] = array('t.field'=>'Example');
		//$this->export_help_array[$r] = array('t.field'=>'FieldDescHelp');
		$this->export_sql_start[$r]='SELECT DISTINCT ';
		$this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'digiriskconst as t';
		//$this->export_sql_end[$r]  =' LEFT JOIN '.MAIN_DB_PREFIX.'digiriskconst_line as tl ON tl.fk_digiriskconst = t.rowid';
		$this->export_sql_end[$r] .=' WHERE 1 = 1';
		$this->export_sql_end[$r] .=' AND t.entity IN ('.getEntity('digiriskconst').')';
		$r++; */
		/* END MODULEBUILDER EXPORT DIGIRISKCONST */

		// Imports profiles provided by this module
		$r = 1;
		/* BEGIN MODULEBUILDER IMPORT DIGIRISKCONST */
		/*
		 $langs->load("digiriskdolibarr@digiriskdolibarr");
		 $this->export_code[$r]=$this->rights_class.'_'.$r;
		 $this->export_label[$r]='DigiriskConstLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
		 $this->export_icon[$r]='digiriskconst@digiriskdolibarr';
		 $keyforclass = 'DigiriskConst'; $keyforclassfile='/digiriskdolibarr/class/digiriskconst.class.php'; $keyforelement='digiriskconst@digiriskdolibarr';
		 include DOL_DOCUMENT_ROOT.'/core/commonfieldsinexport.inc.php';
		 $keyforselect='digiriskconst'; $keyforaliasextra='extra'; $keyforelement='digiriskconst@digiriskdolibarr';
		 include DOL_DOCUMENT_ROOT.'/core/extrafieldsinexport.inc.php';
		 //$this->export_dependencies_array[$r]=array('mysubobject'=>'ts.rowid', 't.myfield'=>array('t.myfield2','t.myfield3')); // To force to activate one or several fields if we select some fields that need same (like to select a unique key if we ask a field of a child to avoid the DISTINCT to discard them, or for computed field than need several other fields)
		 $this->export_sql_start[$r]='SELECT DISTINCT ';
		 $this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'digiriskconst as t';
		 $this->export_sql_end[$r] .=' WHERE 1 = 1';
		 $this->export_sql_end[$r] .=' AND t.entity IN ('.getEntity('digiriskconst').')';
		 $r++; */
		/* END MODULEBUILDER IMPORT DIGIRISKCONST */
	}

	/**
	 *  Function called when module is enabled.
	 *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *  It also creates data directories
	 *
	 *  @param      string  $options    Options when enabling module ('', 'noboxes')
	 *  @return     int             	1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		$sql = array();

		$this->_load_tables('/digiriskdolibarr/sql/');

		return $this->_init($sql, $options);
	}

	/**
	 *  Function called when module is disabled.
	 *  Remove from database constants, boxes and permissions from Dolibarr database.
	 *  Data directories are not deleted
	 *
	 *  @param      string	$options    Options when enabling module ('', 'noboxes')
	 *  @return     int                 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();
		return $this->_remove($sql, $options);
	}
}
