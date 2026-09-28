<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/3/2018
 * Time: 12:32 PM
 */

function makeValue($value, $sourceHitung, $sourceRaw, $static = 0)
{
//    arrPrint($sourceHitung);
//    arrPrint($sourceRaw);


    $ci =& get_instance();
    $ci->load->library("FieldCalculator");
    $cal = new FieldCalculator();

    if (substr($value, 0, 1) == ".") {
//        cekKuning("APA ADANYA");

        $realCol = ltrim($value, ".");
        $realValue = $realCol;
    }
    else {
        $tmpEx = $cal->multiExplode($value);
//        arrPrint($tmpEx);
        if (sizeof($tmpEx) > 1) {//===pakai perhitungan
            $newSrc = $value;

            foreach ($tmpEx as $key2 => $val2) {
                if (strlen($val2) > 0) {
                    $newValues = isset($sourceHitung[$val2]) && $sourceHitung[$val2] != null ? $sourceHitung[$val2] : 0;
                    if (isset($sourceHitung[$val2])) {
                        $newSrc = str_replace($val2, $newValues, $newSrc);
                    }
                    else {
                        if (isset($val2) && $val2 > 0) {
                            $newSrc = str_replace($val2, $val2, $newSrc);
                        }
                        else {
                            $newSrc = str_replace($val2, $static, $newSrc);
                        }
                    }
                }

            }

            $newSrc = str_replace("--", "+", $newSrc);
//            cekHijau("setelah replace: $newSrc");
            $realValue = $cal->calculate($newSrc);
//            cekmerah("---> hasilnya:".$realValue);
        }
        else {
//            cekUngu("BUKAN PERHITUNGAN [$value]");
//            arrPrint($sourceRaw);
            $realCol = $value;
            $realValue = isset($sourceRaw[$realCol]) ? ($sourceRaw[$realCol]) : $static;
        }
    }


    return $realValue;
}


function makeFilter($configParams, $srcArray, $object)
{
//    arrPrint($configParams);
    if (is_array($configParams) && sizeof($configParams) > 0) {
        foreach ($configParams as $filter) {
            $exFilter = explode("=", $filter);
//            arrPrint($exFilter);
            if (sizeof($exFilter) > 1) {
                if (substr($exFilter[1], 0, 1) == ".") {
                    $object->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                }
                else {
                    if (isset($srcArray[$exFilter[1]])) {
                        $object->addFilter($exFilter[0] . "='" . $srcArray[$exFilter[1]] . "'");
                    }
                    else {
                        $object->addFilter($exFilter[0] . "='none'");
                    }
                }
            }
            else {
                $exFilter = explode("<>", $filter);
                if (sizeof($exFilter) > 1) {
                    if (substr($exFilter[1], 0, 1) == ".") {
                        $object->addFilter($exFilter[0] . "<>'" . ltrim($exFilter[1], ".") . "'");
                    }
                    else {
                        if (isset($srcArray[$exFilter[1]])) {
                            $object->addFilter($exFilter[0] . "<>'" . $srcArray[$exFilter[1]] . "'");
                        }
                        else {
                            $object->addFilter($exFilter[0] . "<>'none'");
                        }
                    }
                }
                else {
                    $exFilter = explode(">", $filter);
                    if (sizeof($exFilter) > 1) {
                        if (substr($exFilter[1], 0, 1) == ".") {
                            $object->addFilter($exFilter[0] . ">'" . ltrim($exFilter[1], ".") . "'");
                        }
                        else {
                            if (isset($srcArray[$exFilter[1]])) {
                                $object->addFilter($exFilter[0] . ">'" . $srcArray[$exFilter[1]] . "'");
                            }
                            else {
                                $object->addFilter($exFilter[0] . ">'none'");
                            }
                        }
                    }
                    else {
                        $exFilter = explode("<", $filter);
                        if (sizeof($exFilter) > 1) {
                            if (substr($exFilter[1], 0, 1) == ".") {
                                $object->addFilter($exFilter[0] . "<'" . ltrim($exFilter[1], ".") . "'");
                            }
                            else {
                                if (isset($srcArray[$exFilter[1]])) {
                                    $object->addFilter($exFilter[0] . "<'" . $srcArray[$exFilter[1]] . "'");
                                }
                                else {
                                    $object->addFilter($exFilter[0] . "<'none'");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $object;
}