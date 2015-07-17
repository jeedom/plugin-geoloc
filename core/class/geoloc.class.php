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

class geoloc extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function event() {
		$cmd = geolocCmd::byId(init('id'));
		if (!is_object($cmd)) {
			throw new Exception(__('Commande ID geoloc inconnu : ', __FILE__) . init('id'));
		}
		if ($cmd->getEqLogic()->getEqType_name() != 'geoloc') {
			throw new Exception(__('Cette commande n\'est pas de type geoloc : ', __FILE__) . init('id'));
		}
		if ($cmd->getConfiguration('mode') != 'dynamic') {
			throw new Exception(__('Cette commande de géoloc n\'est pas dynamique : ', __FILE__) . init('id'));
		}
		$value = init('value');
		if (strpos($value, 'https://') !== false || strpos($value, 'http://') !== false) {
			$url = parse_url($value);
			parse_str($url['query'], $output);
			if (isset($output['q'])) {
				$value = $output['q'];
			}
			if (isset($output['ll'])) {
				$value = $output['ll'];
			}
		}
		$cmd->event($value);
		foreach ($cmd->getEqLogic()->getCmd('info') as $distance) {
			if ($distance->getConfiguration('mode') == 'distance' && ($distance->getConfiguration('from') == $cmd->getId() || $distance->getConfiguration('to') == $cmd->getId())) {
				$distance->event($distance->execute());
			}
		}
		$cmd->getEqLogic()->refreshWidget();
	}

	/*     * *********************Methode d'instance************************* */

	public function toHtml($_version = 'dashboard') {
		if ($this->getConfiguration('noSpecifyWidget', 0) == 1) {
			return parent::toHtml($_version);
		}
		if ($_version == '') {
			throw new Exception(__('La version demandée ne peut pas être vide (mobile, dashboard ou scénario)', __FILE__));
		}
		if (!$this->hasRight('r')) {
			return '';
		}

		$cmd_html = '';
		$version = jeedom::versionAlias($_version);
		$vcolor = 'cmdColor';
		if ($version == 'mobile') {
			$vcolor = 'mcmdColor';
		}
		if ($this->getPrimaryCategory() == '') {
			$cmdColor = '';
		} else {
			$cmdColor = jeedom::getConfiguration('eqLogic:category:' . $this->getPrimaryCategory() . ':' . $vcolor);
		}
		$maps = array();
		$dynamic = array();
		$cmd_html = '';
		if ($this->getIsEnable()) {
			foreach ($this->getCmd(null, null, true) as $cmd) {
				if ($cmd->getConfiguration('mode') == 'travelTime') {
					$from = $cmd->getConfiguration('from');
					$to = $cmd->getConfiguration('to');
					if (!isset($maps[$from . '_' . $to])) {
						$maps[$from . '_' . $to] = array();
					}
					$maps[$from . '_' . $to]['travelTime'] = $cmd->execCmd();
				}
				if ($cmd->getConfiguration('mode') == 'distance') {
					$from = $cmd->getConfiguration('from');
					$to = $cmd->getConfiguration('to');
					if (!isset($maps[$from . '_' . $to])) {
						$maps[$from . '_' . $to] = array();
					}
					$maps[$from . '_' . $to]['distance'] = $cmd->execCmd();
				}
				if ($cmd->getConfiguration('mode') == 'travelDistance') {
					$from = $cmd->getConfiguration('from');
					$to = $cmd->getConfiguration('to');
					if (!isset($maps[$from . '_' . $to])) {
						$maps[$from . '_' . $to] = array();
					}
					$maps[$from . '_' . $to]['travelDistance'] = $cmd->execCmd();
				}
				if ($cmd->getConfiguration('mode') == 'dynamic') {
					$dynamic[$cmd->getId()] = $cmd;
				}
			}
		}

		foreach ($maps as $key => $map) {
			$key = explode('_', $key);
			if (count($key) != 2) {
				continue;
			}
			foreach ($dynamic as $id => $cmd) {
				if (in_array($id, $key)) {
					unset($dynamic[$id]);
				}
			}

			$from_cmd = cmd::byId($key[0]);
			$to_cmd = cmd::byId($key[1]);
			if (!is_object($from_cmd) || !is_object($to_cmd)) {
				contine;
			}
			$from = $from_cmd->execCmd();
			$to = $to_cmd->execCmd();
			$replace = array(
				'#name#' => $from_cmd->getName() . ' <i class="fa fa-arrow-right"></i> ' . $to_cmd->getName(),
				'#from#' => $from,
				'#to#' => $to,
				'#travelDistance#' => (isset($map['travelDistance'])) ? $map['travelDistance'] : __('Inconnue', __FILE__),
				'#distance#' => (isset($map['distance'])) ? $map['distance'] : __('Inconnue', __FILE__),
				'#travelTime#' => (isset($map['travelTime'])) ? $map['travelTime'] : __('Inconnue', __FILE__),
			);
			$cmd_html .= template_replace($replace, getTemplate('core', $version, 'geoloc', 'geoloc'));
		}

		foreach ($dynamic as $id => $cmd) {
			$replace = array(
				'#state#' => $cmd->execCmd(),
				'#name#' => $cmd->getName(),
				'#collectDate#' => $cmd->getCollectDate(),
				'#id#' => $cmd->getId(),
			);
			$cmd_html .= template_replace($replace, getTemplate('core', $_version, 'geoloc_single', 'geoloc'));
		}

		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => $this->getName(),
			'#eqLink#' => ($this->hasRight('w')) ? $this->getLinkToConfiguration() : '#',
			'#category#' => $this->getPrimaryCategory(),
			'#background_color#' => $this->getBackgroundColor($version),
			'#cmd#' => $cmd_html,
			'#style#' => '',
			'#noResize#' => 1,
			'#max_width#' => '650px',
			'#logicalId#' => $this->getLogicalId(),
			'#battery#' => $this->getConfiguration('batteryStatus', -2),
			'#batteryDatetime#' => $this->getConfiguration('batteryStatusDatetime', __('inconnue', __FILE__)),
		);
		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowObjectNameOnView', 0) == 0) {
			$object = $this->getObject();
			$replace['#object_name#'] = (is_object($object)) ? '(' . $object->getName() . ')' : '';
		} else {
			$replace['#object_name#'] = '';
		}
		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowNameOnView') == 1) {
			$replace['#name#'] = '';
		}
		if (($_version == 'mobile' || $_version == 'dashboard') && $this->getDisplay('doNotShowNameOnDashboard') == 1) {
			$replace['#name#'] = '';
		}
		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotDisplayBatteryLevelOnView') == 1) {
			$replace['#battery#'] = -1;
		}
		if ($_version == 'dashboard' && $this->getDisplay('doNotDisplayBatteryLevelOnDashboard') == 1) {
			$replace['#battery#'] = -1;
		}

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		return template_replace($replace, getTemplate('core', $version, 'eqLogic'));
	}
}

class geolocCmd extends cmd {
	/*     * *************************Attributs****************************** */

/*     * ***********************Methode static*************************** */
	function get_driving_information($start, $finish, $highways = true) {
		if (strcmp($start, $finish) == 0) {
			return array('distance' => 0, 'time' => 0);
		}
		$start = urlencode($start);
		$finish = urlencode($finish);
		$distance = __('Inconnue', __FILE__);
		$time = __('Inconnue', __FILE__);
		$url = 'http://maps.googleapis.com/maps/api/directions/xml?origin=' . $start . '&destination=' . $finish . '&sensor=false';
		if (!$highways) {
			$url .= '&avoid=highways';
		}
		if ($data = file_get_contents($url)) {
			$xml = new SimpleXMLElement($data);
			if (isset($xml->route->leg->duration->value) AND (int) $xml->route->leg->duration->value > 0) {
				$distance = (int) $xml->route->leg->distance->value / 1000;
				$distance = round($distance, 1);
				$time = (int) $xml->route->leg->duration->value;
				$time = floor($time / 60);
			} else {
				throw new Exception(__('Impossible de trouver une route', __FILE__));
			}
			return array('distance' => $distance, 'time' => $time);
		} else {
			throw new Exception(__('Impossible de resoudre l\'url', __FILE__));
		}
	}
	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		switch ($this->getConfiguration('mode')) {
			case 'fixe':
				$this->setSubType('string');
				$this->setEventOnly(0);
				break;
			case 'dynamic':
				$this->setSubType('string');
				$this->setEventOnly(1);
				break;
			case 'distance':
				$this->setSubType('numeric');
				$this->setUnite('Km');
				$this->setEventOnly(0);
				break;
			case 'travelDistance':
				$this->setSubType('numeric');
				$this->setUnite('Km');
				$this->setEventOnly(0);
				break;
			case 'travelTime':
				$this->setSubType('numeric');
				$this->setUnite('min');
				$this->setEventOnly(0);
				break;
		}
	}

	function distance($lat1, $lng1, $lat2, $lng2) {
		$earth_radius = 6378.137; // Terre = sphère de 6378km de rayon
		$rlo1 = deg2rad($lng1);
		$rla1 = deg2rad($lat1);
		$rlo2 = deg2rad($lng2);
		$rla2 = deg2rad($lat2);
		$dlo = ($rlo2 - $rlo1) / 2;
		$dla = ($rla2 - $rla1) / 2;
		$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
		$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return round(($earth_radius * $d), 2);
	}

	public function execute($_options = array()) {
		switch ($this->getConfiguration('mode')) {
			case 'fixe':
				$result = $this->getConfiguration('coordinate');
				foreach ($this->getEqLogic()->getCmd('info') as $cmd) {
					if ($cmd->getConfiguration('mode') == 'distance') {

					}
				}
				return $result;
			case 'distance':
				$from = cmd::byId($this->getConfiguration('from'));
				$to = cmd::byId($this->getConfiguration('to'));
				if (!is_object($from)) {
					throw new Exception(__('Commande point de départ introuvable : ', __FILE__) . $this->getConfiguration('from'));
				}
				if (!is_object($to)) {
					throw new Exception(__('Commande point d\'arrivé introuvable : ', __FILE__) . $this->getConfiguration('to'));
				}
				$to = explode(',', $to->execCmd(null, 0));
				$from = explode(',', $from->execCmd(null, 0));
				if (count($to) > 2) {
					$to[2] = implode(',', array_slice($to, 1));
				}
				if (count($from) > 2) {
					$from[2] = implode(',', array_slice($from, 1));
				}
				if (count($to) == 2 && count($from) == 2) {
					return self::distance($from[0], $from[1], $to[0], $to[1]);
				}
				return 0;
			case 'travelTime':
				$from = cmd::byId($this->getConfiguration('from'));
				$to = cmd::byId($this->getConfiguration('to'));
				try {
					$highways = true;
					if ($this->getConfiguration('noHighways', 0) == 1) {
						$highways = false;
					}
					$result = self::get_driving_information($from->execCmd(null, 0), $to->execCmd(null, 0), $highways);
					return $result['time'];
				} catch (Exception $e) {
					return 0;
				}
			case 'travelDistance':
				$from = cmd::byId($this->getConfiguration('from'));
				$to = cmd::byId($this->getConfiguration('to'));
				try {
					$highways = true;
					if ($this->getConfiguration('noHighways', 0) == 1) {
						$highways = false;
					}
					$result = self::get_driving_information($from->execCmd(null, 0), $to->execCmd(null, 0), $highways);
					return $result['distance'];
				} catch (Exception $e) {
					return 0;
				}
		}
	}

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */
}

?>