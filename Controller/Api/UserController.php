<?php

class UserController extends BaseController
{
    /** "/user/list" Endpoint - Get list of users */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 10;

                if (isset($arrQueryStringParams['limit']) && is_numeric($arrQueryStringParams['limit'])) {
                    $intLimit = (int)$arrQueryStringParams['limit'];
                }

                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        $this->sendOutput(
            $strErrorDesc ? json_encode(array('error' => $strErrorDesc)) : $responseData,
            array('Content-Type: application/json', $strErrorDesc ? $strErrorHeader : 'HTTP/1.1 200 OK')
        );
    }

    public function createAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                $body = $this->getBodyParams();

                $intDocumento = $body['documento'];
                $strNombre = $body['nombre'];
                $strApellido = $body['apellido'];
                $strUsuario = $body['usuario'];
                $strContrasenia = $body['contrasenia'];

                // Check for required fields
                if (empty($intDocumento) || empty($strNombre) || empty($strApellido) || empty($strUsuario) || empty($strContrasenia)) {
                    throw new Exception('All fields are required.');
                }

                $userId = $userModel->insertUser($intDocumento, $strNombre, $strApellido, $strUsuario, $strContrasenia);
                if($userId){
                    $responseData = json_encode(array('message' => 'User created successfully.'));
                }

            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        $this->sendOutput(
            $strErrorDesc ? json_encode(array('error' => $strErrorDesc)) : $responseData,
            array('Content-Type: application/json', $strErrorDesc ? $strErrorHeader : 'HTTP/1.1 200 OK')
        );
    }

    public function deleteAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'DELETE') {
            try {
                $userModel = new UserModel();
                $intDocumento = isset($arrQueryStringParams['documento']) ? $arrQueryStringParams['documento'] : 0;

                if (empty($intDocumento)) {
                    throw new Exception('Document number is required.');
                }

                $userModel->deleteUser($intDocumento);
                $responseData = json_encode(array('message' => 'User deleted successfully.'));
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        $this->sendOutput(
            $strErrorDesc ? json_encode(array('error' => $strErrorDesc)) : $responseData,
            array('Content-Type: application/json', $strErrorDesc ? $strErrorHeader : 'HTTP/1.1 200 OK')
        );
    }

    public function getOneAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intDocumento = isset($arrQueryStringParams['documento']) ? $arrQueryStringParams['documento'] : 0;

                if (empty($intDocumento)) {
                    throw new Exception('Document number is required.');
                }

                $arrUsers = $userModel->getOneUser($intDocumento);

                if (empty($arrUsers)) {
                    $strErrorDesc = 'No se encontraron resultados';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                } else {
                    $responseData = json_encode($arrUsers);
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        $this->sendOutput(
            $strErrorDesc ? json_encode(array('error' => $strErrorDesc)) : $responseData,
            array('Content-Type: application/json', $strErrorDesc ? $strErrorHeader : 'HTTP/1.1 200 OK')
        );
    }

    public function updateAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $userModel = new UserModel();
                $body = $this->getBodyParams();

                $intDocumento = $body['documento'];
                $strNombre = $body['nombre'];
                $strApellido = $body['apellido'];
                $strUsuario = $body['usuario'];
                $strContrasenia = $body['contrasenia'];

                if (empty($intDocumento) || empty($strNombre) || empty($strApellido) || empty($strUsuario) || empty($strContrasenia)) {
                    throw new Exception('All fields are required.');
                }

                $data = $userModel->getOneUser($intDocumento);

                if (empty($data)) {
                    $strErrorDesc = 'No se encontró el usuario';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                } else {
                    $userModel->updateUser($intDocumento, $strNombre, $strApellido, $strUsuario, $strContrasenia);
                    $responseData = json_encode(array('message' => 'User updated successfully.'));
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        $this->sendOutput(
            $strErrorDesc ? json_encode(array('error' => $strErrorDesc)) : $responseData,
            array('Content-Type: application/json', $strErrorDesc ? $strErrorHeader : 'HTTP/1.1 200 OK')
        );
    }

    public function loginAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            try {
                $userModel = new UserModel();
                $body = $this->getBodyParams();

                $strUsuario = $body['usuario'];
                $strContrasenia = $body['contrasenia'];

                if (empty($strUsuario) || empty($strContrasenia)) {
                    throw new Exception('Username and password are required.');
                }

                $arrUsers = $userModel->login($strUsuario, $strContrasenia);

                if (empty($arrUsers)) {
                    $strErrorDesc = 'Usuario o contraseña incorrectos';
                    $strErrorHeader = 'HTTP/1.1 404 Not Found';
                } else {
                    $responseData = json_encode($arrUsers);
                }
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage().' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        $this->sendOutput(
            $strErrorDesc ? json_encode(array('error' => $strErrorDesc)) : $responseData,
            array('Content-Type: application/json', $strErrorDesc ? $strErrorHeader : 'HTTP/1.1 200 OK')
        );
    }
}
?>
