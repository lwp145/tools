<?php

function foo(){
    return 'foo';
}

/*
* array unique_rand( int $min, int $max, int $num )
* 生成一定数量的不重复随机数，指定的范围内整数的数量必须
* 比要生成的随机数数量大
* $min 和 $max: 指定随机数的范围
* $num: 指定生成数量
*/
function unique_rand($min, $max, $num) {
    $count = 0;
    $return = array();
    while ($count < $num) {
        $return[] = mt_rand($min, $max);
        $return = array_flip(array_flip($return));
        $count = count($return);
    }
    //打乱数组，重新赋予数组新的下标
    shuffle($return);
    return $return;
}
