<?php use SaltedHerring\Debugger;

class DataListExt extends Extension {
    public function format($map = null) {
        $lst = $this->owner;
        $formated = array();
        foreach ($lst as $item) {
            $formated[] = $item->format($map);
        }
        return $formated;
    }

    public function SumFunction($function_name) {
        $lst = $this->owner;
        $n = 0;
        foreach ($lst as $item) {
            $n += $item->{$function_name}();
        }
        return $n;
    }
}
