<?php
	function selectById($db, $id)
	{
		$stmt = $db->prepare('SELECT a, b, c FROM object WHERE id = ? LIMIT 1;');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($a, $b, $c);
		$stmt->fetch();
		$object->a = $a;
		$object->b = $b;
		$object->c = $c;
		echo json_encode($object);
	}
	function selectAll($db)
	{
		$result = $db->query('SELECT a, b, c, id FROM object');
		while($row = $result->fetch_assoc())
		{
			$object = null;
			$object->a = $row['a'];
			$object->b = $row['b'];
			$object->c = $row['c'];
			$object->id = $row['id'];

			$objects[] = $object;
		}
		$result->free();
		echo json_encode($objects);
	}
	function insert($db, $object)
	{
		var_dump($object);
		$stmt = $db->prepare('INSERT INTO object (a, b, c) VALUES (?, ?, ?);');
		$stmt->bind_param('iis', $object->a, $object->b, $object->c);
		$stmt->execute();
		echo $stmt->insert_id;
	}
	function update($db, $object)
	{
		$stmt = $db->prepare('UPDATE object SET a = ?, b = ?, c = ? WHERE id = ? LIMIT 1;');
		$stmt->bind_param('iisi', $object->a, $object->b, $object->c, $object->id);
		$stmt->execute();
		echo $stmt->affected_rows;
	}
	function delete($db, $id)
	{
		$stmt = $db->prepare('DELETE FROM object WHERE id = ? LIMIT 1;');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		echo $stmt->affected_rows;
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
				selectAll($db);
				// $object = json_decode(file_get_contents("php://input"));
				// if(isset($object->id) && is_numeric($object->id))
				// {
				// 	selectById($db, $id)
				// }
				// else
				// {
				// 	selectAll($db);
				// }
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