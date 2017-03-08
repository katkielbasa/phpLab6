<?php
/**
 * @author Luca
 * definition of the User DAO (database access object)
 */
class UsersDAO {
	private $pdo;
	function UsersDAO($DBMngr) {
		$this->pdo = $DBMngr;
	}
	function getUsers() {
		$sql = "SELECT * ";
		$sql .= "FROM dit.users ";
		$sql .= "ORDER BY users.name; ";
		
		$stmt = $this->pdo->prepareQuery($sql);
		$this->pdo->executeQuery($stmt);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}

		function updateUser($id, $p){
			$sql = "UPDATE dit.users SET name = :name,";
			$sql .= "surname = :surname,";
			$sql .= "email = :email,";
			$sql .="password = :password";
			$sql .=" where id = :userID;";

			$stmt = $this->pdo->prepareQuery($sql);
			$this->pdo->bindValue($stmt, ':name', $p['name'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':surname', $p['surname'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':email', $p['email'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':password', $p['password'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':userID', $id, PDO::PARAM_INT);
			$this->pdo->executeQuery($stmt);
		}
		function insertUser($p) {

			$sql = "INSERT INTO dit.users (name, surname, email, password) ";
			$sql .= "VALUES (:name, :surname, :email, :password);";
			
			$stmt = $this->pdo->prepareQuery($sql);
			$this->pdo->bindValue($stmt, ':name', $p['name'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':surname', $p['surname'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':email', $p['email'], PDO::PARAM_STR);
			$this->pdo->bindValue($stmt, ':password', $p['password'], PDO::PARAM_STR);
			$this->pdo->executeQuery($stmt);
		}
		function deleteUser($id){
			$sql = "DELETE FROM dit.users WHERE id = :id";
			$stmt = $this->pdo->prepareQuery($sql);
			$this->pdo->bindValue($stmt, ':id', $id, PDO::PARAM_INT);
			$this->pdo->executeQuery($stmt);
		}
	}

?>
