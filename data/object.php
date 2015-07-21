<?php
	function selectById($db, $id)
	{
		$object = new stdClass();
		$stmt = $db->prepare('SELECT a, b, c, id FROM object WHERE id = ? LIMIT 1;');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($object->a, $object->b, $object->c, $object->id);
		$stmt->fetch();
		echo json_encode($object);
		$stmt->free_result();
		$stmt->close();
	}
	function selectAll($db)
	{
		$stmt = $db->prepare('SELECT a, b, c, id FROM object;');
		$stmt->execute();
		$stmt->bind_result($a, $b, $c, $id);
		$objects = [];
		while($stmt->fetch())
		{
			$object = new stdClass();
			$object->a = $a;
			$object->b = $b;
			$object->c = $c;
			$object->id = $id;
			$objects[] = $object;
		}
		echo json_encode($objects);
		$stmt->free_result();
		$stmt->close();
	}
	function insert($db, $object)
	{
		var_dump($object);
		$stmt = $db->prepare('INSERT INTO object (a, b, c) VALUES (?, ?, ?);');
		$stmt->bind_param('sss', $object->a, $object->b, $object->c);
		$stmt->execute();
		echo $stmt->insert_id;
		$stmt->free_result();
		$stmt->close();
	}
	function update($db, $object)
	{
		$stmt = $db->prepare('UPDATE object SET a = ?, b = ?, c = ? WHERE id = ? LIMIT 1;');
		$stmt->bind_param('sssi', $object->a, $object->b, $object->c, $object->id);
		$stmt->execute();
		echo $stmt->affected_rows;
		$stmt->free_result();
		$stmt->close();
	}
	function delete($db, $id)
	{
		$stmt = $db->prepare('DELETE FROM object WHERE id = ? LIMIT 1;');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		echo $stmt->affected_rows;
		$stmt->free_result();
		$stmt->close();
	}

	try 
	{
		$host = 'localhost';
		$dbname = 'testrest';
		$user = 'root';
		$password = '';
		$db = new mysqli($host, $user, $password, $dbname);
		switch($_SERVER['REQUEST_METHOD'])
		{
			case 'GET':
				if(isset($_GET['id']))
				{
					selectById($db, $_GET['id']);
				}
				else
				{
					selectAll($db);
				}
				break;
			case 'POST':
				$object = json_decode(file_get_contents("php://input"));
				insert($db, $object);
				break;
			case 'PUT':
				$object = json_decode(file_get_contents("php://input"));
				update($db, $object);
				break;
			case 'DELETE':
				$object = json_decode(file_get_contents("php://input"));
				delete($db, $object->id);
				break;
		}
		$db->close();
	} 
	catch (Exception $e) 
	{
		http_response_code(500);
		die();
	}
?>