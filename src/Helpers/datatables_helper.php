<?php

if (!function_exists('loadDatatables')) {
    /**
     * Checks to see if the user is logged in.
     *
     * @return bool
     */
    function loadDatatables(string $source, array $option = [])
    {
        $config = config("datatables");

        // dd($config);
        $ctitle = $config->tableSetting[$source]['col_title'];
        $cdata = $config->tableSetting[$source]['col_data'];

        if (array_key_exists('numbering', $config->tableSetting[$source])) {
            if ($config->tableSetting[$source]['numbering'] == true) {
                $ctitle = "No," . $ctitle;
                $cdata = "dt-no," . $cdata;
            }
        }

        if (array_key_exists('action_button', $config->tableSetting[$source])) {
            if ($config->tableSetting[$source]['action_button'] != "") {
                $ctitle = $ctitle . ",Action";
                $cdata = $cdata . ",dt-actionbtn";
            }
        }

        $table = "<table class=\"datatable-builder table table-striped\" id=\"dt-table-$source\" data-dtcoltitle=\"$ctitle\" data-dtcoldata=\"$cdata\" data-dtesource=\"$source\"></table>";

        return $table;
    }
}
if (!function_exists('startSearchForm')) {
    /**
     * Checks to see if the user is logged in.
     *
     * @return bool
     */
    function startSearchForm(string $source, array $option = [])
    {
        ob_start();
        $class = null;
        $id = null;
        if (key_exists('class', $option)) {
            $class = $option['class'];
        }
        if (key_exists('id', $option)) {
            $id = $option['id'];
        }
        echo "<form class='form-dt-search' data-dtesource=\"$source\" id='form-dt-search-$source'>";
    }
}

if (!function_exists('endSearchForm')) {
    /**
     * Checks to see if the user is logged in.
     *
     * @return bool
     */
    function endSearchForm()
    {
        echo "</form>";
        $content = ob_get_clean();
        return $content;
    }
}
