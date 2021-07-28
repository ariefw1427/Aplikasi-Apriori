<?php

function get_com_category($base, $n)
{
    $baselen = count($base);
    if ($baselen == 0) {
        return;
    }
    if ($n == 1) {
        $return = array();
        foreach ($base as $b) {
            $return[] = array($b);
        }
        return $return;
    } else {
        //get one level lower combinations
        $oneLevelLower = get_com_category($base, $n - 1);

        //echo '<pre>'.$n.': '.print_r($oneLevelLower, 1).'</pre>';

        //for every one level lower combinations add one element to them that the last element of a combination is preceeded by the element which follows it in base array if there is none, does not add
        $newCombs = array();
        foreach ((array) $oneLevelLower as $oll) {
            $lastEl = $oll[$n - 2];
            $found = false;
            foreach ($base as  $key => $b) {
                if ($b == $lastEl) {
                    $found = true;
                    continue;
                    //last element found
                }
                if ($found == true) {
                    //add to combinations with last element
                    if ($key < $baselen) {
                        $tmp = $oll;
                        $newCombination = array_slice($tmp, 0);
                        $newCombination[] = $b;
                        $newCombs[] = array_slice($newCombination, 0);
                    }
                }
            }
        }
    }
    return $newCombs;
}
function get_candidate($data, $com_category)
{
    $arr = array();
    foreach ($com_category as $key => $val) {
        $arr[$key] = get_match($val, $data);
    }
    //echo '<pre>'. print_r($data, 1). '</pre>';
    return $arr;
}
function get_match($needed, $data)
{
    $matches = 0;
    foreach ($data as $k => $v) {
        $counter = 0;
        foreach ($v as $a => $b) {
            if (in_array($a, $needed))
                $counter++;
        }
        if ($counter >= count($needed))
            $matches++;
    }
    //echo '<pre>'. print_r($needed, 1). '</pre>';
    return $matches;
}
function get_support($candicate, $total)
{
    $arr = array();
    foreach ($candicate as $key => $val) {
        $arr[$key] = $val / $total;
    }
    return $arr;
}
function filter_candicate($candicate, $support, $min_support)
{
    $arr = array();
    foreach ($candicate as $key => $val) {
        if ($support[$key] >= $min_support)
            $arr[$key] = $val;
    }
    return $arr;
}

function filter_category($l_can, $com_category)
{
    $arr = array();
    foreach ($l_can as $key => $val) {
        foreach ($com_category[$key] as $v) {
            $arr[$v] = $v;
        }
    }
    //echo '<pre>'. print_r($arr, 1). '</pre>';
    return array_values($arr);
}

function ass_key($large_candicate, $com_category)
{

    $category = filter_category($large_candicate, $com_category);

    $arr = array();
    foreach ($large_candicate as $key => $val) {
        $arr[$key] = $com_category[$key];
    }

    $arr2 = array();
    $no = 0;
    foreach ($arr as $key => $val) {
        for ($a = 0; $a < count($val); $a++) {
            $arr2[$no]['left'] = $val;
            $arr2[$no]['right'] = $val[$a];
            unset($arr2[$no]['left'][$a]);
            $arr2[$no]['index'] = $key;
            $no++;
        }
    }

    $arr = $arr2;

    return $arr;
}

function get_ass_qty($ass_key, $data)
{
    $arr = array();
    foreach ($ass_key as $key => $val) {
        $arr[$key] = get_match(array($val['right']), $data);
    }
    return $arr;
}

function get_confidence($ass_key, $ass_qty, $large_candicate)
{
    $arr = array();
    foreach ($ass_key as $key => $val) {
        $arr[$key] = $large_candicate[$val['index']] / $ass_qty[$key];
    }
    return $arr;
}

function convert_data($data, $kategori)
{
    $arr = array();
    $arr_kategori = array_values($kategori);

    foreach ($data as $row) {
        $arr[$row->id_data] = array();
        foreach ($arr_kategori as $key => $val) {
            if (substr($row->nama_data, $key, 1) == 1)
                $arr[$row->id_data][] = $val;
        }
    }
    return $arr;
}
