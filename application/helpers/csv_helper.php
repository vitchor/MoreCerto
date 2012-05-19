<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('csv2array')){
	function csv2array($csv,$delimiter=';',$enclosure='"',$escape='\\')
    {
        $fields=explode($delimiter, $csv);
        foreach($fields as $key=>$val)
            if( substr($val,0,1)==$enclosure )
                $fields[$key] = str_replace($enclosure.$enclosure,$enclosure,substr($val, 1,-1));
        return($fields);
    }
}
