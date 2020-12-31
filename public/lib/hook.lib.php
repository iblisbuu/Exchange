<?php
include_once(PUBLIC_ROOT.'/lib/Hook/hook.class.php');
include_once(PUBLIC_ROOT.'/lib/Hook/hook.extends.class.php');

function get_hook_class(){

    if( class_exists('GML_Hook') ){
        return GML_Hook::getInstance();
    }

    return null;
}

function add_event($tag, $func, $priority=8, $args=0){

    if( $hook = get_hook_class() ){
        $hook->addAction($tag, $func, $priority, $args);
    }
}

function run_event($tag, $arg = ''){

    if( $hook = get_hook_class() ){

        $args = array();

        if (
            is_array($arg)
            &&
            isset($arg[0])
            &&
            is_object($arg[0])
            &&
            1 == count($arg)
        ) {
            $args[] =& $arg[0];
        } else {
            $args[] = $arg;
        }

        $numArgs = func_num_args();

        for ($a = 2; $a < $numArgs; $a++) {
            $args[] = func_get_arg($a);
        }

        $hook->doAction($tag, $args, false);
    }
}

function add_replace($tag, $func, $priority=8, $args=0){

    if( $hook = get_hook_class() ){
        return $hook->addFilter($tag, $func, $priority, $args);
    }

    return null;
}

function run_replace($tag, $arg = ''){

    if( $hook = get_hook_class() ){

        $args = array();

        if (
            is_array($arg)
            &&
            isset($arg[0])
            &&
            is_object($arg[0])
            &&
            1 == count($arg)
        ) {
            $args[] =& $arg[0];
        } else {
            $args[] = $arg;
        }

        $numArgs = func_num_args();

        for ($a = 2; $a < $numArgs; $a++) {
            $args[] = func_get_arg($a);
        }

        return $hook->apply_filters($tag, $args, false);
    }

    return null;
}
?>