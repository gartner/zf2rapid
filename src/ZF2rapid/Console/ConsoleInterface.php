<?php
/**
 * Norderney.info
 *
 * @package    MODULENAME
 * @link       http://www.norderney.info
 * @copyright  Copyright (c) 2014 Ralf Eggert, Travello GmbH
 * @license    All rights reserved
 */

/**
 * namespace definition and usage
 */
namespace ZF2rapid\Console;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Prompt\Select;

/**
 * Class Console
 *
 * Extends the Zend\Console\Adapter with some convenience methods to write
 * special text lines
 *
 * @package ZF2rapid\Console
 */
interface ConsoleInterface extends AdapterInterface
{
    /**
     * Write a customizable select prompt
     *
     * @param $message
     * @param $options
     *
     * @return string
     */
    public function writeSelectPrompt($message, &$options);

    /**
     * Write a customizable line prompt
     *
     * @param $message
     *
     * @return string
     */
    public function writeLinePrompt($message);

    /**
     * Write a customizable confirm prompt
     *
     * @param string $message
     * @param string $yes
     * @param string $no
     *
     * @return bool
     */
    public function writeConfirmPrompt($message, $yes, $no);

    /**
     * Write a customizable badge
     *
     * @param string $badgeText
     * @param string $badgeColor
     */
    public function writeBadge($badgeText, $badgeColor);

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
    );

    /**
     * Write an indented line
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeIndentedLine($message, array $placeholders = []);

    /**
     * Write a list item line
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeListItemLine($message, array $placeholders = []);

    /**
     * Write a list item line for second level
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeListItemLineLevel2(
        $message, array $placeholders = []
    );

    /**
     * Write a list item line for third level
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeListItemLineLevel3(
        $message, array $placeholders = []
    );

    /**
     * Write a line with a yellow GO badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeGoLine($message, array $placeholders = []);

    /**
     * Write a line with a Blue Done badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeTaskLine($message, array $placeholders = []);

    /**
     * Write a line with a green OK badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeOkLine($message, array $placeholders = []);

    /**
     * Write a line with a red Fail badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeFailLine($message, array $placeholders = []);

    /**
     * Write a line with a red Warn badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeWarnLine($message, array $placeholders = []);

    /**
     * Write a line with a yellow to-do badge
     *
     * @param string $message
     * @param array  $placeholders
     */
    public function writeTodoLine($message, array $placeholders = []);
}