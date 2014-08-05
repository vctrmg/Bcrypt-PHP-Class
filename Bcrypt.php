<?php 
/**
 * Bcrypt PHP class 
 * create and check passwords hash using a strong one-way hashing algorithm. 
 * Using PHP >= 5.5 http://es1.php.net/manual/en/function.password-hash.php
 * 
 * For older PHP versions check this:
 * http://stackoverflow.com/questions/4795385/how-do-you-use-bcrypt-for-hashing-passwords-in-php
 * 
 * @author Víctor Moreno Gil <victormorenogil@gmail.com>
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2014 The Authors
 */

class Bcrypt 
{
    /**
     * Algorithms are currently supported 
     * @var array 
     */
    private static $algorithmsSupported = array(PASSWORD_DEFAULT, PASSWORD_BCRYPT);
    /**
     * the algorithmic cost that should be used by default, you can check
     * the performance of your server with Bcrypt::appropriateCost function 
     * and change this value
     */
    const DEFAULT_WORK_FACTOR = 9;

    /**
     * Creates a password hash
     * 
     * @param string $password 
     * @param const [optional] $algorithm A password algorithm constant denoting the algorithm to use when hashing the password
     * @param integer [optional] $workFactor The algorithmic cost that should be used. If omitted, a default value of 10 will be used. This is a good baseline cost, but you may want to consider increasing it depending on your hardware.
     * @return string|false
     * @throws \Exception
     */
    public static function hash($password, $algorithm = PASSWORD_DEFAULT, $workFactor = self::DEFAULT_WORK_FACTOR)
    {
        if (version_compare(PHP_VERSION, '5.5') < 0) {
            throw new \Exception('Bcrypt requires PHP 5.5.0 or above');
        }
        
        if (!in_array($algorithm, self::$algorithmsSupported)) {
            throw new \Exception('Algorithm not supported');
        }
        
        if ($workFactor < 4 || $workFactor > 31) {
            $workFactor = self::DEFAULT_WORK_FACTOR;
        }
        
        $options = array('cost' => $workFactor,);

        return password_hash($password, $algorithm, $options);
    }

    /**
     * Verifies that a password matches a hash
     * 
     * @param string $password
     * @param string $hash
     * @return boolean
     * @throws \Exception
     */
    public static function check($password, $hash)
    {
        if (version_compare(PHP_VERSION, '5.5') < 0) {
            throw new \Exception('Bcrypt requires PHP 5.5.0 or above');
        }

        return password_verify($password, $hash);
    }

    /**
     * This code will benchmark your server to determine how high of a cost you can
     * afford. You want to set the highest cost that you can without slowing down
     * you server too much. 10 is a good baseline, and more is good if your servers
     * are fast enough.
     * 
     * @param float $timeTarget 
     * @param int $startCost
     * @return type
     */
    public static function appropriateCost($timeTarget = 0.2, $startCost = 9)
    {
        if ($startCost < 4 || $startCost > 31) {
            $startCost = self::DEFAULT_WORK_FACTOR;
        }
        $cost = $startCost;
        do {
            $cost++;
            $start = microtime(true);
            self::hash('test', PASSWORD_BCRYPT, $cost);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        return $cost;
    }
}
