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
use Zend\Console\Charset\CharsetInterface;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Console as ZendConsole;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Select;
use Zend\I18n\Translator\Translator;

/**
 * Class Console
 *
 * Extends the Zend\Console\Adapter with some convenience methods to write
 * special text lines
 *
 * @package ZF2rapid\Console
 *
 * @todo    Needs to be refactored
 */
class Console implements AdapterInterface, ConsoleInterface
{
    /**
     * Indention spaces for console prompts
     */
    const INDENTION_PROMPT_OPTIONS = '     ';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * Get platform based console adapter
     */
    function __construct()
    {
        $this->adapter = ZendConsole::getInstance();
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Write a chunk of text to console.
     *
     * @param string   $text
     * @param null|int $color
     * @param null|int $bgColor
     *
     * @return void
     */
    public function write($text, $color = null, $bgColor = null)
    {
        $this->adapter->write($text, $color, $bgColor);
    }

    /**
     * Alias for write()
     *
     * @param string   $text
     * @param null|int $color
     * @param null|int $bgColor
     *
     * @return void
     */
    public function writeText($text, $color = null, $bgColor = null)
    {
        $this->adapter->writeText($text, $color, $bgColor);
    }

    /**
     * Write a single line of text to console and advance cursor to the next line.
     * If the text is longer than console width it will be truncated.
     *
     * @param string   $text
     * @param null|int $color
     * @param null|int $bgColor
     *
     * @return void
     */
    public function writeLine($text = "", $color = null, $bgColor = null)
    {
        $this->adapter->writeLine($text, $color, $bgColor);
    }

    /**
     * Write a piece of text at the coordinates of $x and $y
     *
     * @param string   $text Text to write
     * @param int      $x    Console X coordinate (column)
     * @param int      $y    Console Y coordinate (row)
     * @param null|int $color
     * @param null|int $bgColor
     *
     * @return void
     */
    public function writeAt($text, $x, $y, $color = null, $bgColor = null)
    {
        $this->adapter->writeAt($text, $x, $y, $color, $bgColor);
    }

    /**
     * Write a box at the specified coordinates.
     * If X or Y coordinate value is negative, it will be calculated as the distance from far right or bottom edge
     * of the console (respectively).
     *
     * @param int      $x1          Top-left corner X coordinate (column)
     * @param int      $y1          Top-left corner Y coordinate (row)
     * @param int      $x2          Bottom-right corner X coordinate (column)
     * @param int      $y2          Bottom-right corner Y coordinate (row)
     * @param int      $lineStyle   (optional) Box border style.
     * @param int      $fillStyle   (optional) Box fill style or a single character to fill it with.
     * @param int      $color       (optional) Foreground color
     * @param int      $bgColor     (optional) Background color
     * @param null|int $fillColor   (optional) Foreground color of box fill
     * @param null|int $fillBgColor (optional) Background color of box fill
     *
     * @return void
     */
    public function writeBox(
        $x1,
        $y1,
        $x2,
        $y2,
        $lineStyle = self::LINE_SINGLE,
        $fillStyle = self::FILL_NONE,
        $color = null,
        $bgColor = null,
        $fillColor = null,
        $fillBgColor = null
    ) {
        $this->adapter->writeBox(
            $x1, $y1, $x2, $y2, $lineStyle, $fillBgColor, $color, $bgColor,
            $fillColor, $fillBgColor
        );
    }

    /**
     * Write a block of text at the given coordinates, matching the supplied width and height.
     * In case a line of text does not fit desired width, it will be wrapped to the next line.
     * In case the whole text does not fit in desired height, it will be truncated.
     *
     * @param string   $text    Text to write
     * @param int      $width   Maximum block width. Negative value means distance from right edge.
     * @param int|null $height  Maximum block height. Negative value means distance from bottom edge.
     * @param int      $x       Block X coordinate (column)
     * @param int      $y       Block Y coordinate (row)
     * @param null|int $color   (optional) Text color
     * @param null|int $bgColor (optional) Text background color
     *
     * @return void
     */
    public function writeTextBlock(
        $text,
        $width,
        $height = null,
        $x = 0,
        $y = 0,
        $color = null,
        $bgColor = null
    ) {
        $this->adapter->writeTextBlock(
            $text, $width, $height, $x, $y, $color, $bgColor
        );
    }

    /**
     * Determine and return current console width.
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->adapter->getWidth();
    }

    /**
     * Determine and return current console height.
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->adapter->getHeight();
    }

    /**
     * Determine and return current console width and height.
     *
     * @return array  array($width, $height)
     */
    public function getSize()
    {
        return $this->adapter->getSize();
    }

    /**
     * Check if console is UTF-8 compatible
     *
     * @return bool
     */
    public function isUtf8()
    {
        return $this->adapter->isUtf8();
    }

    /**
     * Set cursor position
     *
     * @param int $x
     * @param int $y
     *
     * @return void
     */
    public function setPos($x, $y)
    {
        $this->adapter->setPos($x, $y);
    }

    /**
     * Hide console cursor
     *
     * @return void
     */
    public function hideCursor()
    {
        $this->adapter->hideCursor();
    }

    /**
     * Show console cursor
     *
     * @return void
     */
    public function showCursor()
    {
        $this->adapter->showCursor();
    }

    /**
     * Return current console window title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->adapter->getTitle();
    }

    /**
     * Prepare a string that will be rendered in color.
     *
     * @param string   $string
     * @param null|int $color   Foreground color
     * @param null|int $bgColor Background color
     *
     * @return string
     */
    public function colorize($string, $color = null, $bgColor = null)
    {
        return $this->adapter->colorize($string, $color, $bgColor);
    }

    /**
     * Change current drawing color.
     *
     * @param int $color
     *
     * @return void
     */
    public function setColor($color)
    {
        $this->adapter->setColor($color);
    }

    /**
     * Change current drawing background color
     *
     * @param int $color
     *
     * @return void
     */
    public function setBgColor($color)
    {
        $this->adapter->setBgColor($color);
    }

    /**
     * Reset color to console default.
     *
     * @return void
     */
    public function resetColor()
    {
        $this->adapter->resetColor();
    }

    /**
     * Set Console charset to use.
     *
     * @param CharsetInterface $charset
     *
     * @return void
     */
    public function setCharset(CharsetInterface $charset)
    {
        $this->adapter->setCharset($charset);
    }

    /**
     * Get charset currently in use by this adapter.
     *
     * @return CharsetInterface $charset
     */
    public function getCharset()
    {
        return $this->adapter->getCharset();
    }

    /**
     * @return CharsetInterface
     */
    public function getDefaultCharset()
    {
        return $this->adapter->getDefaultCharset();
    }

    /**
     * Clear console screen
     *
     * @return void
     */
    public function clear()
    {
        $this->adapter->clear();
    }

    /**
     * Clear line at cursor position
     *
     * @return void
     */
    public function clearLine()
    {
        $this->adapter->clearLine();
    }

    /**
     * Clear console screen
     *
     * @return void
     */
    public function clearScreen()
    {
        $this->adapter->clearScreen();
    }

    /**
     * Read a single line from the console input
     *
     * @param int $maxLength Maximum response length
     *
     * @return string
     */
    public function readLine($maxLength = 2048)
    {
        return $this->adapter->readLine($maxLength);
    }

    /**
     * Read a single character from the console input
     *
     * @param string|null $mask A list of allowed chars
     *
     * @return string
     */
    public function readChar($mask = null)
    {
        return $this->adapter->readChar($mask);
    }

    /**
     * Write a customizable prompt
     *
     * @param $message
     * @param $options
     *
     * @return string
     */
    public function writeSelectPrompt($message, &$options)
    {
        $this->writeLine();

        // translate options
        foreach ($options as $optionKey => $optionValue) {
            $options[$optionKey] = $this->translator->translate($optionValue);
        }

        // write prompt badge
        $this->writeBadge('badge_pick', Color::RED);

        // output prompt
        $prompt = new Select(
            $this->translator->translate($message),
            $options,
            false,
            false
        );

        $answer = $prompt->show();

        $this->writeLine();

        return strtolower($answer);
    }

    /**
     * Write a customizable line prompt
     *
     * @param $message
     *
     * @return string
     */
    public function writeLinePrompt($message)
    {
        $this->writeLine();

        // write prompt badge
        $this->writeBadge('badge_pick', Color::RED);

        // output prompt
        $prompt = new Line(
            $this->translator->translate($message),
            false
        );

        $answer = $prompt->show();

        $this->writeLine();

        return $answer;
    }

    /**
     * Write a customizable confirm prompt
     *
     * @param string $message
     * @param string $yes
     * @param string $no
     *
     * @return bool
     */
    public function writeConfirmPrompt($message, $yes, $no)
    {
        $this->writeLine();

        // write prompt badge
        $this->writeBadge('badge_pick', Color::RED);

        // output prompt
        $prompt = new Confirm(
            $this->translator->translate($message),
            $this->translator->translate($yes),
            $this->translator->translate($no)
        );

        $answer = $prompt->show();

        return $answer;
    }

    /**
     * Write a customizable badge
     *
     * @param string $badgeText
     * @param string $badgeColor
     */
    public function writeBadge($badgeText, $badgeColor)
    {
        $this->adapter->write(
            $this->translator->translate($badgeText),
            Color::NORMAL,
            $badgeColor
        );
        $this->adapter->write(' ');
    }

    /**
     * Write a line with customizable badge
     *
     * @param string $message
     * @param array  $placeholders
     * @param string $badgeText
     * @param string $badgeColor
     * @param bool   $preNewLine
     * @param bool   $postNewLine
     */
    public function writeBadgeLine(
        $message, array $placeholders = [], $badgeText, $badgeColor,
        $preNewLine = false, $postNewLine = false
    ) {
        if ($preNewLine) {
            $this->adapter->writeLine();
        }

        $this->adapter->write(
            $this->translator->translate($badgeText),
            Color::NORMAL,
            $badgeColor
        );

        $this->adapter->write(' ');
        $this->adapter->writeLine(
            vsprintf($this->translator->translate($message), $placeholders)
        );

        if ($postNewLine) {
            $this->adapter->writeLine();
        }
    }

    /**
     * Write an indented line
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeIndentedLine($message, array $placeholders = [])
    {
        $this->adapter->writeLine();
        $this->adapter->write('       ');
        $this->adapter->writeLine(
            vsprintf($this->translator->translate($message), $placeholders)
        );
    }

    /**
     * Write a list item line
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeListItemLine($message, array $placeholders = [])
    {
        $this->adapter->write('       * ');
        $this->adapter->writeLine(
            vsprintf($this->translator->translate($message), $placeholders)
        );
    }

    /**
     * Write a list item line for second level
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeListItemLineLevel2($message, array $placeholders = [])
    {
        $this->adapter->write('         * ');
        $this->adapter->writeLine(
            vsprintf($this->translator->translate($message), $placeholders)
        );
    }

    /**
     * Write a list item line for third level
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeListItemLineLevel3($message, array $placeholders = [])
    {
        $this->adapter->write('           * ');
        $this->adapter->writeLine(
            vsprintf($this->translator->translate($message), $placeholders)
        );
    }

    /**
     * Write a line with a yellow GO badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeGoLine($message, array $placeholders = [])
    {
        $this->writeBadgeLine(
            $message, $placeholders, 'badge_go', Color::YELLOW, false, true
        );
    }

    /**
     * Write a line with a Blue Done badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeTaskLine($message, array $placeholders = [])
    {
        $this->writeBadgeLine(
            $message, $placeholders, 'badge_task', Color::BLUE, false, false
        );
    }

    /**
     * Write a line with a green OK badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeOkLine($message, array $placeholders = [])
    {
        $this->writeBadgeLine(
            $message, $placeholders, 'badge_ok', Color::GREEN, true, true
        );
    }

    /**
     * Write a line with a red Fail badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeFailLine($message, array $placeholders = [])
    {
        $this->writeBadgeLine(
            $message, $placeholders, 'badge_fail', Color::RED, true, true
        );
    }

    /**
     * Write a line with a red Warn badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeWarnLine($message, array $placeholders = [])
    {
        $this->writeBadgeLine(
            $message, $placeholders, 'badge_warning', Color::RED, true, true
        );
    }

    /**
     * Write a line with a yellow to-do badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeTodoLine($message, array $placeholders = [])
    {
        $this->writeBadgeLine(
            $message, $placeholders, 'badge_todo', Color::GREEN, false, true
        );
    }


}