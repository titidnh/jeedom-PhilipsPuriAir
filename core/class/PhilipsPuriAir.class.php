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
        $on->setValue('state');
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
        $off->setValue('state');
        $off->setTemplate('dashboard','prise');
        $off->setTemplate('mobile','prise');
        $off->setDisplay("generic_type","ENERGY_OFF");
        $off->save();

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
        

        // $filename = dirname(__FILE__) . '/../config/pureCmd.json';
		// if (!is_file($filename)) {
		//     throw new \Exception("File $filename does not exist");
        // }
        
		// $device = is_json(file_get_contents($filename), array());        
        // foreach($device['commands'] as $key => $cmd)
		// {
		// 	if (array_key_exists('logicalId',$cmd))
		// 		$id = $cmd['logicalId'];
		// 	else
		// 	{
		// 		if (array_key_exists('name',$cmd))
		// 			$id = $cmd['name'];
		// 		else {
		// 			$id = '';
		// 		}
		// 	}
            
        //     $curCmd = $this->getCmd(null, $id);

        //     if (is_object($curCmd)) {
		// 		unset($device['commands'][$key]);
		// 		continue;
        //     }
            
		// 	if (array_key_exists('name',$cmd))
		// 		$cmd['name'] = __($cmd['name'],__FILE__);
        // }

        // $cmd_order = 0;
        // foreach($this->getCmd() as $liste_cmd)
        // {
        //     if ($liste_cmd->getOrder()>$cmd_order)
        //         $cmd_order = $liste_cmd->getOrder()+1;
        // }
        // $link_cmds = array();
        // $link_actions = array();
        // $arrayToRemove = [];
        // foreach ($device['commands'] as $command) {
        //     $cmd = null;
        //     foreach ($this->getCmd() as $liste_cmd) {
        //         if ((isset($command['logicalId']) && $liste_cmd->getLogicalId() == $command['logicalId'])
        //         || (isset($command['name']) && $liste_cmd->getName() == $command['name'])) {
        //             $cmd = $liste_cmd;
        //             break;
        //         }
        //     }
        //     try 
        //     {
        //         if ($cmd === null || !is_object($cmd)) {
        //             $cmd = new PhilipsPuriAirCmd();
        //             $cmd->setOrder($cmd_order);
        //             $cmd->setEqLogic_id($this->getId());
        //         } else {
        //             $command['name'] = $cmd->getName();
        //             if (isset($command['display'])) {
        //                 unset($command['display']);
        //             }
        //         }
        //         utils::a2o($cmd, $command);
        //         $cmd->setConfiguration('logicalId', $cmd->getLogicalId());
        //         $cmd->save();
        //         if (isset($command['value'])) {
        //             $link_cmds[$cmd->getId()] = $command['value'];
        //         }
        //         if (isset($command['configuration']) && isset($command['configuration']['updateCmdId'])) {
        //             $link_actions[$cmd->getId()] = $command['configuration']['updateCmdId'];
        //         }
        //         $cmd_order++;
        //     } catch (Exception $exc) {
        //         log::error('kkasa','error','Error importing '.$command['name']);
        //         throw $exc;
        //     }
        //     $cmd->event('');
        // }

        $this->save();
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

    public function setState($state){
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
