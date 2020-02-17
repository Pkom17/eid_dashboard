<?php

namespace App\Utilities;

/**
 * Description of ChartUtilities
 *
 * @author PKom
 */
class Util {

    const MONTHS = ["01" => "Janvier", "02" => "Février", "03" => "Mars", "04" => "Avril", "05" => "Mai", "06" => "Juin", "07" => "Juillet", "08" => "Août",
        "09" => "Septembre", "10" => "Octobre", "11" => "Novembre", "12" => "Décembre"];
    const MONTHS_KEYS = [
        'FR' => ["Janvier" => "Janvier", "Février" => "Février", "Mars" => "Mars", "Avril" => "Avril", "Mai" => "Mai", "Juin" => "Juin", "Juillet" => "Juillet", "Août" => "Août",
            "Septembre" => "Septembre", "Octobre" => "Octobre", "Novembre" => "Novembre", "Décembre" => "Décembre"],
        'EN' => ["January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août",
            "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre"]];

    
    public static function resolve_month($translator, $month) {
        switch ($month) {
            case 1:
                $value = $translator->trans('jan');
                break;
            case 2:
                $value = $translator->trans('feb');
                break;
            case 3:
                $value = $translator->trans('mar');
                break;
            case 4:
                $value = $translator->trans('apr');
                break;
            case 5:
                $value = $translator->trans('may');
                break;
            case 6:
                $value = $translator->trans('jun');
                break;
            case 7:
                $value = $translator->trans('jul');
                break;
            case 8:
                $value = $translator->trans('aug');
                break;
            case 9:
                $value = $translator->trans('sep');
                break;
            case 10:
                $value = $translator->trans('oct');
                break;
            case 11:
                $value = $translator->trans('nov');
                break;
            case 12:
                $value = $translator->trans('dec');
                break;
            default:
                $value = NULL;
                break;
        }

        return $value;
    }

    public static function average($arr) {
        return ($arr) ? array_sum($arr) / count($arr) : 0;
    }

    public static function median($arr) {
        if ($arr) {
            $count = count($arr);
            sort($arr);
            $mid = floor(($count - 1) / 2);
            return ($arr[$mid] + $arr[$mid + 1 - $count % 2]) / 2;
        }
        return 0;
    }

    public static function ct($v) {// avoid have tat that less than 0
        return ($v < 0) ? 0 : $v;
    }

}
