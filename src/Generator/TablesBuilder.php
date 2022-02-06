<?php

namespace DatatablesBuilder\Generator;

class TablesBuilder
{
    public $search_form = [];
    public $form_option = [];

    public function loadDatatables(string $source, array $option = [])
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

        $form = "<form class=\"dt-form\" id=\"dt-form-$source\">$this->search_form[$source]</form>";
        $table = "<table class=\"table table-striped\" data-dtcoltitle=\"$ctitle\" data-dtcolda=\"$cdata\" data-esource=\"$source\"></table>";
        $container = "<div class=\"datatable-builder\">$form $table</div>";

        return $container;
    }

    public function startSearchForm(string $source, array $option = [])
    {
        $this->search_option[$source] = $option;
        ob_start();
    }

    public function endSearchForm(string $source)
    {
        $content = ob_get_clean();
        $this->search_form[$source] = $content;
    }
}
