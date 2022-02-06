<?php

namespace DatatablesBuilder\Generator;

class DatatablesBuilder implements DatatablesBuilderInterface
{
    protected $dtUtils, $dt;

    protected function reset(): void
    {
        $this->dt = new \stdClass();
        $this->dtUtils = new \stdClass();

        $this->dtUtils->buttons = array();
        $this->dtUtils->withButton = false;
        $this->dtUtils->withSerialNumber = false;
    }

    public function layout(string $header, string $key): DatatablesBuilder
    {
        $this->reset();

        $tableTitle = explode(',', $header);
        $tableDataKey = explode(',', $key);

        $metaTable = [];
        for ($i = 0; $i < count($tableTitle); $i++) {
            $setMeta['title'] = $tableTitle[$i];
            $setMeta['data'] = $tableDataKey[$i];

            array_push($metaTable, $setMeta);
        }


        $this->dt->meta['table'] = $metaTable;

        return $this;
    }

    public function source(array $data): DatatablesBuilder
    {
        $this->dt->data = $data;
        return $this;
    }

    public function useSerialNumber(bool $sn): DatatablesBuilder
    {
        if ($sn) {
            $this->dtUtils->withSerialNumber = true;
        }
        return $this;
    }

    public function actionButton(string $button): DatatablesBuilder
    {
        if (!$this->dtUtils->withButton) {
            $this->dtUtils->withButton = true;
        }

        array_push($this->dtUtils->buttons, $button);

        return $this;
    }

    public function loadHelper(array $helpers): DatatablesBuilder
    {
        if (property_exists($this->dt, 'data')) {
            $data = $this->dt->data;

            // looping data source
            foreach ($data as $data_key => $value) {

                // remapping data with helpers
                foreach ($helpers as $help_key => $helper) {
                    $get_func = $helper[0];
                    $help_args = $helper[1];

                    // replace data param
                    foreach ($value as $data_val_key => $data_val_item) {
                        $help_args =  str_replace("{" . $data_val_key . "}", $data_val_item, $help_args);
                    }

                    $help_args = explode('|', $help_args);

                    $data[$data_key][$help_key] = call_user_func_array($get_func, $help_args);
                }
            }

            $this->dt->data = $data;
        } else {
            throw new \Exception("Source data not found, load helper after set source data !");
        }
        return $this;
    }

    public function build(int $page = null, int $per_page = null): object
    {
        // check serial number
        if ($this->dtUtils->withSerialNumber) {
            $keySN = 'dt-no';

            $metaSN = [
                'title' => 'No',
                'data' => $keySN,
            ];

            //set serial number to meta table
            array_unshift($this->dt->meta['table'], $metaSN);
        }

        // check action button
        if ($this->dtUtils->withButton) {
            $keyAB = 'dt-actionbtn';

            $metaAB = [
                'title' => 'Action',
                'data' => $keyAB,
            ];

            //set action button to meta table
            array_push($this->dt->meta['table'], $metaAB);
        }

        // number start
        $start = (($page - 1) * $per_page) + 1;

        // remaping data with serial number
        foreach ($this->dt->data as $key => $value) {
            if ($this->dtUtils->withSerialNumber) {
                // add numbering 
                $this->dt->data[$key] = array($keySN => $start++) + $this->dt->data[$key];
            }

            if ($this->dtUtils->withButton) {
                // parse string button
                $buttonTpl = implode(' ', $this->dtUtils->buttons);

                //replace placholder button
                $buttonTpl = $this->replacePlaceholder($buttonTpl, $value);

                // add button 
                $this->dt->data[$key] = $this->dt->data[$key] + array($keyAB => $buttonTpl);
            }
        }

        return $this->dt;
    }

    private function replacePlaceholder(string $obj, array $data)
    {
        $objReplace = $obj;
        foreach ($data as $key => $value) {
            $objReplace = str_replace("{" . $key . "}", $value, $objReplace);
        }

        return $objReplace;
    }
}
