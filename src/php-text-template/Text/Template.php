<?php
/**
 * Text_Template
 *
 * Copyright (c) 2009-2012, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Text
 * @package    Template
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://github.com/sebastianbergmann/php-text-template
 * @since      File available since Release 1.0.0
 */

/**
 * A simple template engine.
 *
 * @category   Text
 * @package    Template
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2009-2012 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.1.1
 * @link       http://github.com/sebastianbergmann/php-text-template
 * @since      Class available since Release 1.0.0
 */
class Text_Template
{
    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var array
     */
    protected $values = array();

    /**
     * Constructor.
     *
     * @param  string $file
     * @throws InvalidArgumentException
     */
    public function __construct($file = '')
    {
        $this->setFile($file);
    }

    /**
     * Sets the template file.
     *
     * @param  string $file
     * @throws InvalidArgumentException
     */
    public function setFile($file)
    {
        $distFile = $file . '.dist';

        if (file_exists($file)) {
            $this->template = file_get_contents($file);
        }

        else if (file_exists($distFile)) {
            $this->template = file_get_contents($distFile);
        }

        else {
            throw new InvalidArgumentException(
              'Template file could not be loaded.'
            );
        }
    }

    /**
     * Sets one or more template variables.
     *
     * @param  array   $values
     * @param  boolean $merge
     */
    public function setVar(array $values, $merge = TRUE)
    {
        if (!$merge || empty($this->values)) {
            $this->values = $values;
        } else {
            $this->values = array_merge($this->values, $values);
        }
    }

    /**
     * Renders the template and returns the result.
     *
     * @return string
     */
    public function render()
    {
        $keys = array();

        foreach ($this->values as $key => $value) {
            $keys[] = '{' . $key . '}';
        }

        return str_replace($keys, $this->values, $this->template);
    }

    /**
     * Renders the template and writes the result to a file.
     *
     * @param string $target
     */
    public function renderTo($target)
    {
        $fp = @fopen($target, 'wt');

        if ($fp) {
            fwrite($fp, $this->render());
            fclose($fp);
        } else {
            throw new RuntimeException('Could not write to ' . $target . '.');
        }
    }
}
