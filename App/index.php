
<?php
session_start ();

require_once "../Slim/Slim.php";
require_once 'DB/DAOs/UsersDAO.php';
require_once 'DB/pdoDbManager.php';
require_once "conf/config.inc.php"; // include configuration file
Slim\Slim::registerAutoloader ();
$app = new \Slim\Slim (); // slim run-time object

//if (empty ( $_SESSION ["localUserList"] ))
//	$_SESSION ["localUserList"] = array (); // initialitation of users container

$app->map ( "/users(/:id)", function ($elementID = null) use($app) {
	$body = $app->request->getBody (); // get the body of the HTTP request (from client)
	$decBody = json_decode ( $body, true ); // this transform the string into an associative array
	$httpMethod = $app->request->getMethod ();

	$DBMngr = new pdoDbManager();
	$DBMngr->openConnection();

	$usersDAO = new UsersDAO($DBMngr);

	// initialisations
	$responseBody = null;
	$responseCode = null;

	switch ($httpMethod) {
		case "GET" :
			// set response body and response code
			//			print_r($usersDAO);
			$usersDAO->getUsers();
			$responseBody = $usersDAO->getUsers();
			$respondeCode = HTTPSTATUS_OK;

			break;
		case "POST" :
			$usersDAO->insertUser($decBody);
			$responseBody = "ok";
			$respondeCode = HTTPSTATUS_CREATED;
			break;
		case "PUT" :
			$usersDAO->updateUser($elementID, $decBody);
			$responseBody = "Updated $elementID";
			$respondeCode = HTTPSTATUS_OK;
			break;
		case "DELETE" :
			$usersDAO->deleteUser($elementID);
			$responseBody = "Deleted $elementID";
			$respondeCode = HTTPSTATUS_OK;
			break;
	}
	$DBMngr->closeConnection();
	// return response to client (as a json string)
	if ($responseBody != null)
	$app->response->write ( json_encode ( $responseBody ) ); // this is the body of the response

	// TODO:we need to write also the response codes in the headers to send back to the client
	$app->response->status ( $respondeCode );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->run ();
?>