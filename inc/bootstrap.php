<?php

const PROJECT_ROOT_PATH = __DIR__;

// include the config file
require_once PROJECT_ROOT_PATH . '/config.php';

// include the base controller file
require_once PROJECT_ROOT_PATH . '/../Controller/Api/BaseController.php';

// include the user controller file

require_once PROJECT_ROOT_PATH . '/../Controller/Api/UserController.php';

// include the user model file
require_once PROJECT_ROOT_PATH . '/../Model/UserModel.php';

?>



