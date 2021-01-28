<?php
/* Copyright (C) 2017  Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *
 * Need to have following variables defined:
 * $object (invoice, order, ...)
 * $action
 * $conf
 * $langs
 *
 * $keyforbreak may be defined to key to switch on second column
 */

// Protection to avoid direct call of template
if (empty($conf) || !is_object($conf))
{
	print "Error, template page can't be called as URL";
	exit;
}
if (!is_object($form)) $form = new Form($db);

?>
	<!-- BEGIN PHP TEMPLATE digiriskdolibarr_preventionplanfields_view.tpl.php -->
<?php

$preventionplan = json_decode($object->json, false, 512, JSON_UNESCAPED_UNICODE)->PreventionPlan;

//Creation User

print '<tr>';
print '<td class="titlefield">'.$langs->trans("CreatedBy").'</td>';
print '<td>';

if ($object->fk_user_creat > 0)
{
	$usercreat = new User($db);
	$result = $usercreat->fetch($object->fk_user_creat);
	if ($result < 0) dol_print_error('', $usercreat->error);
	elseif ($result > 0) print $usercreat->getNomUrl(-1);
}

//Creation Date
print '</td></tr>';

print '<tr>';
print '<td class="titlefield">'.$langs->trans("CreatedOn").'</td>';
print '<td>';

print dol_print_date($object->date_creation);

print '</td></tr>';

// Médecin du travail

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("LabourDoctor").'</td>';
	print '<td>';
		print $preventionplan->occupational_health_service->name;
		print '</td></tr>';

// Inspecteur du travail
print '<tr>';
	print '<td class="titlefield">'.$langs->trans("LabourInspector").'</td>';
	print '<td>';
		print $preventionplan->detective_work->name;
		print '</td></tr>';

// SAMU

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("SAMU").'</td>';
	print '<td>';
		print $preventionplan->emergency_service->samu;
		print '</td></tr>';

// Pompiers

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("Pompiers").'</td>';
	print '<td>';
		print $preventionplan->emergency_service->pompier;
		print '</td></tr>';

// Police

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("Police").'</td>';
	print '<td>';
		print $preventionplan->emergency_service->police;
		print '</td></tr>';

// Urgences

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("AllEmergencies").'</td>';
	print '<td>';
		print $preventionplan->emergency_service->emergency;
		print '</td></tr>';

// Défenseur du droit du travail

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("RightsDefender").'</td>';
	print '<td>';
		print $preventionplan->emergency_service->right_defender;
		print '</td></tr>';

// Antipoison

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("Antipoison").'</td>';
	print '<td>';
		print $preventionplan->emergency_service->poison_control_center;
		print '</td></tr>';

// Responsable de prévention

print '<tr>';
	print '<td class="titlefield">'.$langs->trans("ResponsibleToNotify").'</td>';
	print '<td>';
		print $preventionplan->safety_rule->responsible_for_preventing;
		print '</td></tr>';


?>
<!-- END PHP TEMPLATE digiriskdolibarr_preventionplanfields_view.tpl.php -->