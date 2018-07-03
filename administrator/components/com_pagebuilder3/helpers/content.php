<?php
/**
 * @version    $Id$
 * @package    JSN_PageBuilder3
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
include_once JPATH_ROOT . '/components/com_content/helpers/route.php';

/**
 * JSN PageBuilder3 content helper.
 *
 * @package  JSN_PageBuilder3
 * @since    1.0.0
 */
class JSNPageBuilder3ContentHelper
{
	public static function fetchHttp($url)
	{
		$http = new JHttp();
		$resp = $http->get($url);
		return $resp->body;
	}

	public function delete($value, $key, $table)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$condition = $db->quoteName($key) . ' = ' . $db->quote($value);
		$query->delete($db->quoteName($table));
		$query->where($condition);
		$db->setQuery($query);

		return $db->execute();
	}

	public function create($columns, $values, $table)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->quoteName($table))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));
		$db->setQuery($query);
		return array(
			$db->execute(),
			$db->insertid()
		);
	}

	public function update($fields, $conditions, $table)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->quoteName($table))
			->set($fields)
			->where($conditions);
		$db->setQuery($query);

		return $db->execute();
	}

	public function select($fields, $table, $where = null, $selectOne = false, $order = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($fields);
		$query->from($db->quoteName($table));
		!empty($where) and $query->where($where);
		!empty($order) and $query->order($order);
		$db->setQuery($query);
		return $selectOne ? $db->loadObject() : $db->loadObjectList();
	}
}
