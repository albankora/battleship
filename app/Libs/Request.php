<?php

namespace Libs;

class Request
{
    use Singletonable;
    /**
     * Return all parameters base on request method
     *
     * @return array
     */
    public function getAll()
    {
        $params = [];
        $method = $this->server('REQUEST_METHOD');

        switch ($method) {
            case "PUT":
            case "DELETE":
                parse_str(file_get_contents('php://input'), $params);
                break;
            case "GET":
                foreach ($_GET as $key => $values) {
                    $params[$key] = $this->get($key);
                }
                break;
            case "POST":
                foreach ($_POST as $key => $values) {
                    $params[$key] = $this->post($key);
                }
                break;
        }

        return $params;
    }

    /**
     * Return the value from a parameter from $_GET
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        if (filter_has_var(INPUT_GET, $name)) {
            return filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return null;
    }

    /**
     * Return the value from a parameter from $_POST
     *
     * @param string $name
     * @return mixed
     */
    public function post($name)
    {
        if (filter_has_var(INPUT_POST, $name)) {
            return filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return null;
    }

    /**
     * Return the value from a parameter from $_SERVER
     *
     * @param string $name
     * @return mixed
     * REQUEST_URI, HTTP_USER_AGENT, REMOTE_ADDR, REQUEST_METHOD
     */
    public function server($name)
    {
        if (filter_has_var(INPUT_SERVER, $name)) {
            return filter_input(INPUT_SERVER, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return null;
    }

    /**
     * Check if an request is an Ajax one
     *
     * @return boolean
     */
    public function isAjax()
    {
        $ajax = $this->server('HTTP_X_REQUESTED_WITH');
        if (isset($ajax) && !empty($ajax) && strtolower($ajax) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }
}
