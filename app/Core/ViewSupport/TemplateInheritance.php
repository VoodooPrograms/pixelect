<?php

namespace Quetzal\Core\ViewSupport;

class TemplateInheritance
{
    private $base;
    private $stack;

    private $level;
    private $hash;
    private $end;
    private $after;

    public function startblock($name, $filters=null) {
        $trace = $this->callingTrace();
        $this->init($trace);
        $this->stack[] = $this->newBlock($name, $filters, $trace);
    }

    public function endblock($name=null) {
        $trace = $this->callingTrace();
        $this->init($trace);
        if ($this->stack) {
            $block = array_pop($this->stack);
            if ($name && $name != $block['name']) {
                $this->warning("startblock('{$block['name']}') does not match endblock('$name')", $trace);
            }
            $this->insertBlock($block);
        }else{
            $this->warning(
                $name ? "orphan endblock('$name')" : "orphan endblock()",
                $trace
            );
        }
    }

    public function flushblocks() {
        if ($this->base) {
            while ($block = array_pop($this->stack)) {
                $this->warning(
                    "missing endblock() for startblock('{$block['name']}')",
                    $this->callingTrace(),
                    $block['trace']
                );
            }
            while (ob_get_level() > $this->level) {
                ob_end_flush(); // will eventually trigger bufferCallback
            }
            $this->base = null;
            $this->stack = null;
        }
    }

    public function blockbase() {
        $this->init($this->callingTrace());
    }

    public function init($trace) {
        if ($this->base && !$this->inBaseOrChild($trace)) {
            $this->flushblocks(); // will set $this->base to null
        }
        if (!$this->base) {
            $this->base = array(
                'trace' => $trace,
                'filters' => null, // purely for compile
                'children' => array(),
                'start' => 0, // purely for compile
                'end' => null
            );
            $this->level = ob_get_level();
            $this->stack = array();
            $this->hash = array();
            $this->end = null;
            $this->after = '';
            ob_start(function ($buffer){
                return $this->bufferCallback($buffer);
            });
        }
    }

    public function newBlock($name, $filters, $trace) {
        while ($block = end($this->stack)) {
            if ($this->isSameFile($block['trace'], $trace)) {
                break;
            }else{
                array_pop($this->stack);
                $this->insertBlock($block);
                $this->warning(
                    "missing endblock() for startblock('{$block['name']}')",
                    $this->callingTrace(),
                    $block['trace']
                );
            }
        }
        if ($this->base['end'] === null && !$this->inBase($trace)) {
            $this->base['end'] = ob_get_length();
        }
        return array(
            'name' => $name,
            'trace' => $trace,
            'filters' => $filters,
            'children' => array(),
            'start' => ob_get_length()
        );
    }

    public function insertBlock($block) { // at this point, $block is done being modified
        $block['end'] = $this->end = ob_get_length();
        $name = $block['name'];
        if ($this->stack || $this->inBase($block['trace'])) {
            $block_anchor = array(
                'start' => $block['start'],
                'end' => $this->end,
                'block' => $block
            );
            if ($this->stack) {
                // nested block
                $this->stack[count($this->stack)-1]['children'][] =& $block_anchor;
            }else{
                // top-level block in base
                $this->base['children'][] =& $block_anchor;
            }
            $this->hash[$name] =& $block_anchor; // same reference as children array
        }
        else if (isset($this->hash[$name])) {
            if ($this->isSameFile($this->hash[$name]['block']['trace'], $block['trace'])) {
                $this->warning(
                    "cannot define another block called '$name'",
                    $this->callingTrace(),
                    $block['trace']
                );
            }else{
                // top-level block in a child template; override the base's block
                $this->hash[$name]['block'] = $block;
            }
        }
    }

    public function bufferCallback($buffer) {
        if ($this->base) {
            while ($block = array_pop($this->stack)) {
                dump($this->stack);
                $this->insertBlock($block);
                $this->warning(
                    "missing endblock() for startblock('{$block['name']}')",
                    $this->callingTrace(),
                    $block['trace']
                );
            }
            if ($this->base['end'] === null) {
                $this->base['end'] = strlen($buffer);
                $this->end = null;
                // means there were no blocks other than the base's
            }
            $parts = $this->compile($this->base, $buffer);
            // remove trailing whitespace from end
            $i = count($parts) - 1;
            $parts[$i] = rtrim($parts[$i]);
            // if there are child template blocks, preserve output after last one
            if ($this->end !== null) {
                $parts[] = substr($buffer, $this->end);
            }
            // for error messages
            $parts[] = $this->after;
            return implode($parts);
        }else{
            return '';
        }
    }

    public function compile($block, $buffer) {
        $parts = array();
        $previ = $block['start'];
        foreach ($block['children'] as $child_anchor) {
            $parts[] = substr($buffer, $previ, $child_anchor['start'] - $previ);
            $parts = array_merge(
                $parts,
                $this->compile($child_anchor['block'], $buffer)
            );
            $previ = $child_anchor['end'];
        }
        if ($previ != $block['end']) {
            // could be a big buffer, so only do substr if necessary
            $parts[] = substr($buffer, $previ, $block['end'] - $previ);
        }
        if ($block['filters']) {
            $s = implode($parts);
            foreach ($block['filters'] as $filter) {
                if ($filter) {
                    $s = call_user_func($filter, $s);
                }
            }
            return array($s);
        }
        return $parts;
    }

    public function warning($message, $trace, $warning_trace=null) {
        if (error_reporting() & E_USER_WARNING) {
            if (defined('STDIN')) {
                // from command line
                $format = "\nWarning: %s in %s on line %d\n";
            }else{
                // from browser
                $format = "<br />\n<b>Warning</b>:  %s in <b>%s</b> on line <b>%d</b><br />\n";
            }
            if (!$warning_trace) {
                $warning_trace = $trace;
            }
            $s = sprintf($format, $message, $warning_trace[0]['file'], $warning_trace[0]['line']);
            if (!$this->base || $this->inBase($trace)) {
                echo $s;
            }else{
                $this->after .= $s;
            }
        }
    }






    public function callingTrace() {
        $trace = debug_backtrace();
        foreach ($trace as $i => $location) {
            if ($location['file'] !== __FILE__) {
                return array_slice($trace, $i);
            }
        }
    }

    public function inBase($trace) {
        return $this->isSameFile($trace, $this->base['trace']);
    }

    public function inBaseOrChild($trace) {
        $base_trace = $this->base['trace'];
        return
            $trace && $base_trace &&
            $this->isSubtrace(array_slice($trace, 1), $base_trace) &&
            $trace[0]['file'] === $base_trace[count($base_trace)-count($trace)]['file'];
    }

    public function isSameFile($trace1, $trace2) {
        return
            $trace1 && $trace2 &&
            $trace1[0]['file'] === $trace2[0]['file'] &&
            array_slice($trace1, 1) === array_slice($trace2, 1);
    }

    public function isSubtrace($trace1, $trace2) { // is trace1 a subtrace of trace2
        $len1 = count($trace1);
        $len2 = count($trace2);
        if ($len1 > $len2) {
            return false;
        }
        for ($i=0; $i<$len1; $i++) {
            if ($trace1[$len1-1-$i] !== $trace2[$len2-1-$i]) {
                return false;
            }
        }
        return true;
    }


}