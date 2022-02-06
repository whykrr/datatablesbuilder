<?php

namespace DatatablesBuilder\Config;

/**
 * # --------------------------------------------------------------------
 * * Datatables Configuration
 * # --------------------------------------------------------------------
 *
 * configuration for datatables 
 *
 */
class Datatables
{
    /**
     * # --------------------------------------------------------------------
     * * Table Setting
     * # --------------------------------------------------------------------
     *
     * Example 
     * 'users' => [ 
            'col_title' => 'Nama Depan,Nama Belakang,Alamat,Status',
            'col_data' => 'nama_depan,nama_belakang,alamat,status',
            'helpers' => [
                'status' => ['formatStatus', '{status}'],
            ],
            'numbering' => true,
            'action_button' => 'edit,delete',
        ],
     * 
     * master -> segment / data source
     * header -> Table header (separate with ,) 
     * value -> array key of source data (separate with ,) 
     * number -> true if you want to show consecutive numbers
     * action -> action button
     *
     * @var array
     */
    public $tableSetting = [
        'users' => [
            'col_title' => 'Nama Depan,Nama Belakang,Alamat,Status',
            'col_data' => 'nama_depan,nama_belakang,alamat,status',
            'helpers' => [
                'status' => ['formatStatus', '{status}'],
            ],
            'numbering' => true,
            'action_button' => 'edit,delete',
        ],
    ];

    public $loadHelpers = ['test'];

    /**
     * # --------------------------------------------------------------------
     * * Souce Data Model
     * # --------------------------------------------------------------------
     *
     * Example
     * 'users' => 'UsersModel',
     *
     * @var array
     */
    public $sourceData = [
        'users' => 'App\Models\UsersModel',
    ];

    /**
     * # --------------------------------------------------------------------
     * * Button Action
     * # --------------------------------------------------------------------
     *
     * @var array
     */
    public $buttonAction = [
        'edit' => 'DatatablesBuilder\Views\button_edit',
        'delete' => 'DatatablesBuilder\Views\button_delete',
    ];
}
