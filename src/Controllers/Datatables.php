<?php

namespace DatatablesBuilder\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class Datatables extends Controller
{
    use ResponseTrait;

    private $config, $model;

    private $page = null, $perPage = null;

    /**
     * # --------------------------------------------------------------------
     * * Genrate Data
     * # --------------------------------------------------------------------
     *
     * data generator for datatables
     *
     * @param string
     *
     * @return json
     */

    public function genrate($source)
    {
        $this->config = config('datatables');

        // load helpers
        helper($this->config->loadHelpers);

        $respond['data'] = [];

        //check model exist
        if (array_key_exists($source, $this->config->sourceData)) {
            if (!$this->model = model($this->config->sourceData[$source])) {
                $sourceData = $this->config->sourceData[$source];

                $respond['message'] = "$sourceData not found";
                return $this->respond($respond, 500);
            }
        } else {
            $respond['message'] = "misconfiguration source data $source";
            return $this->respond($respond, 500);
        }


        $tableSetting = $this->config->tableSetting;

        $generator = new \DatatablesBuilder\Generator\DatatablesBuilder();

        // get parameters request
        $reqParam = $this->request->getGet();

        $validation =  \Config\Services::validation();

        $validation->setRules([
            'page' => ['rules' => 'required_with[per_page]'],
            'per_page' => ['rules' => 'required_with[page]'],
        ]);

        // set page
        if (isset($reqParam['start']) && isset($reqParam['length'])) {
            $this->page = ($reqParam['start'] / $reqParam['length']) + 1;
            $this->perPage = $reqParam['length'];
        }

        // set limit
        if (isset($reqParam['limit'])) {
        }

        // check configuration
        if (array_key_exists('col_title', $tableSetting[$source]) && array_key_exists('col_data', $tableSetting[$source])) {
            // get header setting
            $header = $tableSetting[$source]['col_title'];

            // get value key data
            $valueKey = $tableSetting[$source]['col_data'];

            // genrate from generator
            $datatables = $generator->layout($header, $valueKey);
        } else {
            $respond['message'] = "misconfiguration on setting table $source";
            return $this->respond($respond, 500);
        }

        // check use numbering
        if (array_key_exists('numbering', $tableSetting[$source])) {
            $setNumbering = $tableSetting[$source]['numbering'];
            if (is_bool($setNumbering)) {
                $datatables = $datatables->useSerialNumber($setNumbering);
            } else {
                $respond['message'] = "table setting numbering on segment $source must be boolean" . gettype($setNumbering) . " given";
                return $this->respond($respond, 500);
            }
        }

        //check setting table action button
        if (array_key_exists('action_button', $tableSetting[$source])) {
            // get setting action button
            $actionButton = explode(',', $tableSetting[$source]['action_button']);

            //get button setting
            $btnSetting = $this->config->buttonAction;

            foreach ($actionButton as $button) {

                // chheck button exists
                if (array_key_exists($button, $btnSetting)) {

                    //check button view
                    if ($btnView = view($btnSetting[$button], [], ['debug' => false])) {

                        // replayce placeholder source
                        $btnView = str_replace("{source}", $source, $btnView);

                        // set action button to builder
                        $datatables = $datatables->actionButton($btnView);
                    }
                }
            }
        }

        // set model arguments
        $customArgs = [];
        if (key_exists('search_payload', $reqParam)) {
            $searchPayload = $reqParam['search_payload'];
            foreach ($searchPayload as $search) {
                $customArgs[$search['name']] = $search['value'];
            }
        }

        //load model method to get data
        $get_data = $this->model;
        if (method_exists($this->model, 'datatablesCustom')) {
            $get_data = $get_data->datatablesCustom($customArgs);
        }
        $data = $this->model->paginate($this->perPage, 'datatables', $this->page);

        // build datatables 
        $datatables = $datatables->source($data);

        // check use helpers
        if (array_key_exists('helpers', $tableSetting[$source])) {
            $helpers = $tableSetting[$source]['helpers'];
            if (is_array($helpers)) {
                $datatables = $datatables->loadHelper($helpers);
            } else {
                $respond['message'] = "table setting fliters on segment $source must be array, " . gettype($helpers) . " given";
                return $this->respond($respond, 500);
            }
        }

        $genrate = $datatables->build($this->page, $this->perPage);

        $respond['message'] = 'data successfully retrieved';
        $respond['data'] = $genrate->data;
        $respond['recordsFiltered'] = $this->model->pager->getTotal('datatables');
        $respond['recordsTotal'] = $this->model->pager->getTotal('datatables');


        // genrate pager
        $pager = [
            'current_page' => $this->model->pager->getCurrentPage('datatables'),
            'per_page' => $this->model->pager->getPerPage('datatables'),
            'total_record' => $this->model->pager->getTotal('datatables'),
            'total_page' => $this->model->pager->getLastPage('datatables'),
        ];

        // meta
        $respond['meta'] = [
            'pager' => $pager,
        ];

        return $this->respond($respond);
    }
}
