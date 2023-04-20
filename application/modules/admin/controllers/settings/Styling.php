<?php

/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Styling extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Styling';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_POST['newStyle'])) {
            $this->Home_admin_model->setValueStore('newStyle', $_POST['newStyle']);
            $this->saveHistory('Change site styling');
            redirect('admin/styling');
        }

        $data['newStyle'] = $this->Home_admin_model->getValueStore('newStyle');
        $this->load->view('_parts/header', $head);
        $this->load->view('settings/styling', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to Styling page');
    }

}
