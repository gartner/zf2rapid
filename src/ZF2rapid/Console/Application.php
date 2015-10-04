<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Console;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface as Color;
use Zend\I18n\Translator\Translator;
use ZF\Console\Application as ZFApplication;
use ZF\Console\Dispatcher;
use ZF\Console\RouteCollection;

/**
 * Class Application
 *
 * @package ZF2rapid\Console
 */
class Application extends ZFApplication
{
    /**
     * Name of application
     */
    const NAME = 'ZF2rapid';

    /**
     * Slogan of application
     */
    const SLOGAN = 'Rapid Application Development Tool for the ZF2';

    /**
     * Version of application
     */
    const VERSION = '0.7.0';

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * Overwritten constructor to simplify application instantiation
     *
     * @param string $routes
     * @param ConsoleInterface $console
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        $routes, ConsoleInterface $console, Translator $translator = null,
        Dispatcher $dispatcher = null
    ) {
        if ($translator) {
            $this->translator = $translator;

            $routes = $this->translateRoutes($routes);
        }

        // call parent constructor
        parent::__construct(
            self::NAME . ' - ' . self::SLOGAN,
            self::VERSION,
            $routes,
            $console,
            $dispatcher
        );

        // initialize routes
        $routes = array();

        // get all routes except standard version route
        foreach ($this->routeCollection->getRouteNames() as $routeName) {
            if ($routeName == 'version') {
                continue;
            }

            $routes[$routeName] = $this->routeCollection->getRoute($routeName);
        }

        // create new RouteCollection instance and add routes to it
        $this->routeCollection = new RouteCollection();
        $this->setRoutes($routes);

        // change banner and footer
        $this->setBanner(array($this, 'writeApplicationBanner'));
        $this->setFooter(array($this, 'writeApplicationFooter'));
    }

    /**
     * Run the application
     *
     * Make sure that banner and footer are not shown for autoload command
     *
     * @param array $args
     * @return int
     */
    public function run(array $args = null)
    {
        global $argv;

        if (isset($argv[1]) && $argv[1] == 'autocomplete' && !isset($argv[2])) {
            $this->setBanner(function () {return 0;});
            $this->setFooter(function () {return 0;});
        }

        return parent::run($args);
    }

    /**
     * Translate the route texts
     *
     * @param array $routes
     *
     * @return array
     */
    protected function translateRoutes(array $routes = array())
    {
        foreach ($routes as $routeKey => $routeParams) {
            if (isset($routeParams['description'])) {
                $routes[$routeKey]['description']
                    = $this->translator->translate(
                    $routeParams['description']
                );
            }
            if (isset($routeParams['short_description'])) {
                $routes[$routeKey]['short_description']
                    = $this->translator->translate(
                    $routeParams['short_description']
                );
            }
            if (isset($routeParams['options_descriptions'])) {
                foreach (
                    $routeParams['options_descriptions'] as
                    $optionKey => $optionText
                ) {
                    $routes[$routeKey]['options_descriptions'][$optionKey]
                        = $this->translator->translate(
                        $optionText
                    );
                }
            }
        }

        return $routes;
    }

    /**
     * Write application banner
     *
     * @param AdapterInterface $console
     */
    public function writeApplicationBanner(AdapterInterface $console)
    {
        $console->writeLine();

        $console->writeLine(
            str_pad('', $console->getWidth() - 1, '=', STR_PAD_RIGHT),
            Color::GREEN
        );

        $console->write('=', Color::GREEN);
        $console->write(
            str_pad(
                '' . self::NAME . ' - ' . self::SLOGAN
                . ' (Version ' . self::VERSION . ')',
                $console->getWidth() - 3,
                ' ',
                STR_PAD_BOTH
            )
        );
        $console->writeLine('=', Color::GREEN);

        $console->writeLine(
            str_pad('', $console->getWidth() - 1, '=', STR_PAD_RIGHT),
            Color::GREEN
        );

        $console->writeLine();
    }

    /**
     * Write application footer
     *
     * @param AdapterInterface $console
     */
    public function writeApplicationFooter(AdapterInterface $console)
    {
        $console->writeLine(
            str_pad('', $console->getWidth() - 1, '=', STR_PAD_RIGHT),
            Color::GREEN
        );

        $console->writeLine();
    }


}
