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

    public static function cron() {
        if (strval(config::byKey('Refresh','PhilipsPuriAir','15')) == '1')
        {
            self::ExecuteCron();
        }
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
        
        $state = $this->getCmd(null, 'state');
		if (!is_object($state)) {
			$state = new PhilipsPuriAirCmd();
			$state->setName(__('Etat', __FILE__));
        }
        
		$state->setEqLogic_id($this->getId());
		$state->setLogicalId('state');
		$state->setType('info');
        $state->setSubType('binary');
        $state->setIsVisible(0);
        $state->setIsHistorized(1);
        $state->setTemplate('dashboard','prise');
        $state->setTemplate('mobile','prise');
        $state->setDisplay("generic_type","ENERGY_STATE");
        $state->save();

        $on = $this->getCmd(null, 'on');
		if (!is_object($on)) {
			$on = new PhilipsPuriAirCmd();
			$on->setName(__('On', __FILE__));
        }
        
		$on->setEqLogic_id($this->getId());
		$on->setLogicalId('on');
		$on->setType('action');
        $on->setSubType('other');
        $on->setIsVisible(1);
        $on->setValue($state->getId());
        $on->setTemplate('dashboard','prise');
        $on->setTemplate('mobile','prise');
        $on->setDisplay("generic_type","ENERGY_ON");
        $on->save();

        $off = $this->getCmd(null, 'off');
		if (!is_object($off)) {
			$off = new PhilipsPuriAirCmd();
			$off->setName(__('Off', __FILE__));
        }
        
		$off->setEqLogic_id($this->getId());
		$off->setLogicalId('off');
		$off->setType('action');
        $off->setSubType('other');
        $off->setIsVisible(1);
        $off->setValue($state->getId());
        $off->setTemplate('dashboard','prise');
        $off->setTemplate('mobile','prise');
        $off->setDisplay("generic_type","ENERGY_OFF");
        $off->save();

        // Values
        $pm25 = $this->getCmd(null, 'pm25');
		if (!is_object($pm25)) {
			$pm25 = new PhilipsPuriAirCmd();
			$pm25->setName(__('PM2.5', __FILE__));
        }
        
		$pm25->setEqLogic_id($this->getId());
		$pm25->setLogicalId('pm25');
		$pm25->setType('info');
        $pm25->setSubType('numeric');
        $pm25->setIsHistorized(1);
        $pm25->setIsVisible(1);
        $pm25->setConfiguration('maxValue', 100);
        $pm25->save();

        $iaql = $this->getCmd(null, 'iaql');
		if (!is_object($iaql)) {
			$iaql = new PhilipsPuriAirCmd();
			$iaql->setName(__('IQA Intérieur', __FILE__));
        }
        
		$iaql->setEqLogic_id($this->getId());
		$iaql->setLogicalId('iaql');
		$iaql->setType('info');
        $iaql->setSubType('numeric');
        $iaql->setIsHistorized(1);
        $iaql->setIsVisible(1);
        $iaql->setConfiguration('maxValue', 100);
        $iaql->save();
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
        $cmd = 'sudo airctrl --ipaddr '. $this->getConfiguration("IP") .' --protocol coap';
        $result = shell_exec($cmd);
        $onOffStatus = '';
        $pm25 = '';
        $iaql = '';
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $result) as $line){
            if(substr( $line, 0, 5 ) === '[pwr]')
            {
                $onOffStatus = str_replace("[pwr]                         Power: ", "", $line);
            }
            if(substr( $line, 0, 6 ) === '[pm25]')
            {
                $pm25 = str_replace("[pm25]                        PM25: ", "", $line);
            }
            if(substr( $line, 0, 6 ) === '[iaql]')
            {
                $iaql = str_replace("[iaql]                        Allergen index: ", "", $line);
            }
        }

        if($onOffStatus <> '')
        {
           $stateCmd = $this->getCmd(null, 'state');
           $stateCmd->event($onOffStatus === "ON" ? 1 : 0);
        }

        if($pm25 <> '')
        {
           $pm25Cmd = $this->getCmd(null, 'pm25');
           $pm25Cmd->event($pm25);
        }

        if($iaql <> '')
        {
           $iaqlCmd = $this->getCmd(null, 'iaql');
           $iaqlCmd->event($iaql);
        }
    }

    public function setState($state){              
        $cmd = 'sudo airctrl --ipaddr '. $this->getConfiguration("IP") .' --protocol coap --pwr '.$state;
        log::add('PhilipsPuriAir', 'debug', $cmd);
        for ($i = 1; $i <= 3; $i++) {
            shell_exec($cmd);
            sleep(1);
        }
        
        $this->updateData();
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
        if ($this->getLogicalId() == 'on') {
            $eqLogic->setState(1);
        }
        if ($this->getLogicalId() == 'off') {
            $eqLogic->setState(0);
        }
    }
}

?>
