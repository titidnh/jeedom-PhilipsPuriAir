<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class PhilipsPuriAir extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

	public static function dependancy_info() {
		$return = array();
        $return['log'] = __CLASS__ . '_update';
        $return['progress_file'] = jeedom::getTmpFolder('PhilipsPuriAir') . '/dependance';
		$return['state'] = 'ok';
		if (exec('which airctrl | wc -l') == 0){
			$return['state'] = 'nok';
        }
        
		return $return;
    }
    
    public static function dependancy_install() {
        log::remove(__CLASS__ . '_update');
        return array('script' => dirname(__FILE__) . '/../../resources/install.sh ' . jeedom::getTmpFolder('PhilipsPuriAir') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
    }

    public static function cron5() {
        if (strval(config::byKey('Refresh','PhilipsPuriAir','15')) == '5')
        {
            self::ExecuteCron();
        }
    }

    public static function cron15() {
        if (strval(config::byKey('Refresh','PhilipsPuriAir','15')) == '15')
        {
            self::ExecuteCron();
        }
    }

    public static function cron30() {
        if (strval(config::byKey('Refresh','PhilipsPuriAir','15')) == '30')
        {
            self::ExecuteCron();
        }
    }

    public static function cronHourly() {
        if (strval(config::byKey('Refresh','PhilipsPuriAir','15')) == '60')
        {
            self::ExecuteCron();
        }
    }

    private static function ExecuteCron(){
        foreach (self::byType('PhilipsPuriAir') as $eqLogic) 
        {	
            if ($eqLogic->getIsEnable() == 1) 
            {
                $eqLogic->updateData();
            }
        }
    }

    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        $refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new PhilipsPuriAirCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
        }
        
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    public function updateData(){
    }
}

class PhilipsPuriAirCmd extends cmd {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */
    public function dontRemoveCmd() {
      return true;
    }

    public function execute($_options = array()) {
        log::add('PhilipsPuriAir', 'debug', "Cmd");
        $eqLogic = $this->getEqLogic();
        log::add('PhilipsPuriAir', 'debug', print_r($eqLogic,true));
        if ($this->getLogicalId() == 'refresh') {
			$eqLogic->updateData();
		}
    }
}

?>
