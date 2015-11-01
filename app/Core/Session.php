<?php

namespace Core;

class Session
{
    use Singletonable;

    /**
     * check if session_start function has run before else run the function
     * required for the session variables to work
     */
    public function start()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Set a session variable
     *
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->start();
        $_SESSION[$name] = $value;
    }

    /**
     * get the value of a session variable
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        $this->start();

        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return null;
    }

    /**
     * Create an new cookie
     *
     * @param string $name
     * @param string $value
     * @param string $expireString e.x 30m, 20h, 7d
     */
    public static function setCookie($name, $value, $expireString)
    {
        $expireTime = 0;

        list($num, $timeStr) = preg_split('#(?<=\d)(?=[a-zA-Z])#i', $expireString);

        switch ($timeStr) {
            case 'm':
                $expireTime = time() + ($num * 60);
                break;
            case 'h':
                $expireTime = time() + ($num * 3600);
                break;
            case 'd':
                $expireTime = time() + ($num * 24 * 3600);
                break;
        }

        setcookie($name, $value, $expireTime);
    }

    /**
     * Return the value from a parameter from $_COOKIE
     *
     * @param string $name
     * @return mixed
     */
    public static function getCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            if (filter_has_var(INPUT_COOKIE, $name)) {
                return filter_input(INPUT_COOKIE, $name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            }
        }

        return null;
    }
}
