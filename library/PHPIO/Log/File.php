<?php

class PHPIO_Log_File {
        var $save_dir = PHPIO_STORE;
        var $logs = array();
        function append($value) {
                $this->logs[] = $value;
        }

        function count() {
                return count($this->logs);
        }
        function save() {
                if ( !file_exists($this->save_dir) ) {
                        mkdir($this->save_dir);
                }

                if ( $this->count() > 0 ) {
                        file_put_contents($this->save_dir.'/prof_'.PHPIO::$run_id, serialize($this->logs));
                }
        }
}
