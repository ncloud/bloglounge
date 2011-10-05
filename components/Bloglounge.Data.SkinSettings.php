<?php
	class SkinSettings {
		var $_error;

		function SkinSettings() {
			global $database, $db;
			if ($data = $db->queryAll('SELECT * FROM '.$database['prefix'].'SkinSettings',MYSQL_ASSOC)) {
				foreach ($data as $item) {
					$this->$item['name'] = $item['value'];
				}
			}
			return true;
		}

		function get($name) {
			global $database, $db;
			list($result) = $db->pick('SELECT value FROM '.$database['prefix'].'SkinSettings WHERE name = "'.$name.'" LIMIT 1');
			return $result;
		}

		function gets($names) {
			global $database, $db;
		
			$names = explode(',',$names);
			if (!$data = $db->queryAll('SELECT name, value FROM '.$database['prefix'].'SkinSettings WHERE name IN ('.func::implode_string(',',$names).')',MYSQL_ASSOC))
				return false;
		
				
			$result = array();
		

			foreach($names as $name) {
	
				$result[trim($name)] = '';
	
			}

			foreach($data as $item) {
	
				$result[$item['name']] = $item['value'];
	
			}
			
				
			$data = array();
	
				
			foreach($result as $item) {
		
				array_push($data, $item);
		
			}
			
			
			return $data;
		}

		function getAsArray($names) {
			global $database, $db;
		
			
			$names = explode(',',$names);

			if (!$data = $db->queryAll('SELECT name, value FROM '.$database['prefix'].'SkinSettings WHERE name IN ('.func::implode_string(',',$names).')',MYSQL_ASSOC))
				return false;
		
				
			$result = array();
	
				
			foreach($names as $name) {
		
				$result[trim($name)] = '';
		
			}

			foreach($data as $item) {
			
				$result[$item['name']] = $item['value'];

			}
		
				
			return $result;
		}

		function set($name, $value) {
			global $database, $db;
			return $db->execute('UPDATE '.$database['prefix'].'SkinSettings SET value="'.$db->escape($value).'" WHERE name = "'.$name.'"');
		}

		function setWithArray($arg) {
			global $db;
			foreach ($arg as $field=>$value) {
				if(!$this->set($field, $value)) {
					$this->_error = 'Error: on setWithArray('.$field.','.$value.') : '.$db->error();
					return false;
				}
			}
			return true;
		}
	}
?>