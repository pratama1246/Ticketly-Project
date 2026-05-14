<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $updateProfile = [
        'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'email'    => 'required|valid_email|max_length[255]|is_unique[users.email,id,{id}]',
        'foto'     => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]'
    ];

    public array $checkoutInfo = [
        'first_name'      => 'required|string|max_length[100]',
        'email'           => 'required|valid_email|max_length[255]',
        'phone_number'    => 'required|string|max_length[50]',
        'identity_number' => 'required|string|max_length[100]',
        'last_name'       => 'permit_empty|string|max_length[100]',
        'birth_date'      => 'required|string',
    ];
}
