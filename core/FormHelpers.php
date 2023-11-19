<?php

    use \Heliumframework\Session;

    function formInit($method)
    {
        formMethod($method);
        csrf(); // Init
    }

    function formMethod( $method )
    {
        echo '<input type="hidden" name="_method" id="_method" value="'.$method.'" >';
    }

    /**
     * Set a CSRF session
     * @return string
     */
    function csrf()
    {
        $code = md5(time());
        Session::put('csrf', $code);
        echo '<input type="hidden" name="csrf" value="'.$code.'" />';
    }
    