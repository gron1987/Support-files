<?php
/**
 * File: DI.php.
 * User: gron
 * Date: 3/25/12
 * Time: 8:11 PM
 */
namespace Core;

class DI
{
    /**
     * @var array pair of interface/class name + object/class_name
     */
    private static $_dependencies = array();

    /**
     * Register all dependencies (maybe do this in XML file, but it's too lazy)
     * @static
     */
    public static function init(){
        self::register('\Session\SessionEntity','SessionEntity');
    }

    /**
     * Create object by interface/class name
     * @static
     * @param $interface
     * @return object
     * @throws BadMethodCallException
     */
    public static function create($interface)
    {
        if (!empty(self::$_dependencies[$interface])) {
            $class = self::$_dependencies[$interface];
            return new $class;
        } else {
            throw new \BadMethodCallException("No dependency created for name(interface): " . $interface);
        }
    }

    /**
     * Register dependency
     * @static
     * @param $object
     * @param string $name
     */
    public static function register($object, $name = '')
    {
        if (!empty($name)) {
            $className = (is_object($object)) ?  get_class($object) : $object;
            self::$_dependencies[$name] = $object;
        } else {
            $className = (is_object($object)) ?  get_class($object) : $object;
            $reflection = new \ReflectionClass($className);
            $interfaces = $reflection->getInterfaceNames();
            if (!empty($interfaces)) {
                foreach ($interfaces as $item) {
                    self::$_dependencies[$item] = $object;
                }
            } else {
                self::$_dependencies[$className] = $object;
            }
        }
    }
}
