<?php

/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TextualPages extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Textual_pages_model');
    }

    public function pageEdit($page = null)
    {
        $this->login_check();
        if ($page == null) {
            redirect('admin/pages');
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Pages Manage';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['page'] = $this->Textual_pages_model->getOnePageForEdit($page);
        if (empty($data['page'])) {
            redirect('admin/pages');
        }
        if (isset($_POST['updatePage'])) {
            $this->Textual_pages_model->setEditPageTranslations($_POST);
            $this->saveHistory('Page ' . $_POST['pageId'] . ' updated!');
            redirect('admin/pageedit/' . $page);
        }

        $this->load->view('_parts/header', $head);
        $this->load->view('textual_pages/pageEdit', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Edit page - ' . $page);
    }

    public function changePageStatus()
    {
        $this->login_check();
        $result = $this->Textual_pages_model->changePageStatus($_POST['id'], $_POST['status']);
        if ($result == true) {
            echo 1;
        } else {
            echo 0;
        }
        $this->saveHistory('Page status Changed');
    }

}
