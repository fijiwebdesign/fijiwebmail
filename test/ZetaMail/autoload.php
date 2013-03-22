<?php
/**
 * Autoload the Zeta Mail classes
 * @author gabe@fijiwebdesigncom
 */

spl_autoload_register('autoloadZetaMail');
 
/**
 * Autoload ezc classes 
 * 
 * @param string $className 
 */
function autoloadZetaMail( $className )
{
    static $classMap;
	
	if (!$classMap) {
		$classMap = include('../../ZetaMail/src/mail_autoload.php');
		$classMap = array_merge($classMap, include('../../ZetaBase/src/base_autoload.php'));
	}
	
	if (isset($classMap[$className])) {
		if (file_exists(('../../ZetaMail/src/' . (str_replace('Mail/', '', $classMap[$className]))))) {
			require_once('../../ZetaMail/src/' . (str_replace('Mail/', '', $classMap[$className])));
		} else {
			require_once('../../ZetaBase/src/' . (str_replace('Base/', '', $classMap[$className])));
		}
	}
		
		
}


