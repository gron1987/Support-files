<?php
/**
 * File: SL.php.
 * User: gron
 * Date: 3/25/12
 * Time: 8:11 PM
 */
namespace Core;

/**
 * Service Locator
 */
class SL
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
        self::register('\Auth\UserMapper','UserMapper');
        self::register('\Auth\User','AuthUser');
        self::register('\Chat\MessagesMapper','MessagesMapper');
        self::register('\Chat\Messages','ChatMessages');
    }

    /**
     * Create object by interface/class name
     * @static
     * @param $name
     * @return object
     * @throws \BadMethodCallException
     */
    public static function create($name)
    {
        if (!empty(self::$_dependencies[$name])) {
            $class = self::$_dependencies[$name];
            return new $class;
        } else {
            var_dump(self::$_dependencies);
            throw new \BadMethodCallException("No dependency created for name(interface): " . $name);
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
