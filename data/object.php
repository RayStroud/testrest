<?php
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
	function insert($db, $data)
	{
		var_dump($object);
		$stmt = $db->prepare('INSERT INTO object (a, b, c) VALUES (?, ?, ?);');
		$stmt->bind_param('sss', $data->a, $data->b, $data->c);
		$stmt->execute();
		echo $stmt->insert_id;
		$stmt->free_result();
		$stmt->close();
	}
	function update($db, $id, $data)
	{
		$stmt = $db->prepare('UPDATE object SET a = ?, b = ?, c = ? WHERE id = ? LIMIT 1;');
		$stmt->bind_param('sssi', $data->a, $data->b, $data->c, $id);
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
		$password = 'root';
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
				$data = json_decode(file_get_contents("php://input"));
				insert($db, $data);
				break;
			case 'PUT':
				if(isset($_GET['id']))
				{
					$data = json_decode(file_get_contents("php://input"));
					update($db, $_GET['id'], $data);
				}
				break;
			case 'DELETE':
				if(isset($_GET['id']))
				{
					delete($db, $_GET['id']);
				}
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