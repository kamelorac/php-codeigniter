<?php

class MY_Controller extends MX_Controller
{

    public $nonDynPages = array();
    private $dynPages = array();
    protected $template;

    public function __construct()
    {
        parent::__construct();
        $this->getActivePages();
        $this->checkForPostRequests();
        $this->setReferrer();
        //set selected template
        $this->loadTemplate();
    }

    /*
     * Render page from controller
     * it loads header and footer auto
     */

    public function render($view, $head, $data = null, $footer = null)
    {
        $head['cartItems'] = $this->shoppingcart->getCartItems();
        $head['sumOfItems'] = $this->shoppingcart->sumValues;
        $vars = $this->loadVars();
        $this->load->vars($vars);
        $all_categories = $this->Public_model->getShopCategories();

        function buildTree1(array $elements, $parentId = 0)
        {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['sub_for'] == $parentId) {
                    $children = buildTree1($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
            return $branch;
        }

        $head['nav_categories'] = $tree = buildTree1($all_categories);
        $this->load->view($this->template . '_parts/header', $head);
        $this->load->view($this->template . $view, $data);
        $this->load->view($this->template . '_parts/footer', $footer);
    }

    /*
     * Load variables from values-store
     * texts, social media links, logos, etc.
     */

    private function loadVars()
    {
        $vars = array();
        $vars['nonDynPages'] = $this->nonDynPages;
        $vars['dynPages'] = $this->dynPages;
        $vars['footerCategories'] = $this->Public_model->getFooterCategories();

        $this->load->model('admin/Settings_model');
        $values = $this->Settings_model->getValueStores();
        if(is_array($values) && count($values) > 0) {
            foreach($values as $value) {
                if (!array_key_exists($value['thekey'], $vars)) {
                    $vars[$value['thekey']] = htmlentities($value['value']);
                }
            }
        }
        
        $vars['allLanguages'] = $this->getAllLangs();
        $vars['load'] = $this->loop;
        $vars['cookieLaw'] = $this->Public_model->getCookieLaw();
        return $vars;
    }

    /*
     * Get all added languages from administration
     */

    private function getAllLangs()
    {
        $arr = array();
        $this->load->model('admin/Languages_model');
        $langs = $this->Languages_model->getLanguages();
        foreach ($langs as $lang) {
            $arr[$lang->abbr]['name'] = $lang->name;
            $arr[$lang->abbr]['flag'] = $lang->flag;
        }
        return $arr;
    }

    /*
     * Active pages for navigation
     * Managed from administration
     */

    private function getActivePages()
    {
        $this->load->model('admin/Pages_model');
        $activeP = $this->Pages_model->getPages(true);
        $dynPages = $this->config->item('no_dynamic_pages');
        $actDynPages = [];
        foreach ($activeP as $acp) {
            if (($key = array_search($acp, $dynPages)) !== false) {
                $actDynPages[] = $acp;
            }
        }
        $this->nonDynPages = $actDynPages;
        $dynPages = getTextualPages($activeP);
        $this->dynPages = $this->Public_model->getDynPagesLangs($dynPages);
    }

    /*
     * Email subscribe form from footer
     */

    private function checkForPostRequests()
    {
        if (isset($_POST['subscribeEmail'])) {
            $arr = array();
            $arr['browser'] = $_SERVER['HTTP_USER_AGENT'];
            $arr['ip'] = $_SERVER['REMOTE_ADDR'];
            $arr['time'] = time();
            $arr['email'] = $_POST['subscribeEmail'];
            if (filter_var($arr['email'], FILTER_VALIDATE_EMAIL) && !$this->session->userdata('email_added')) {
                $this->session->set_userdata('email_added', 1);
                $this->Public_model->setSubscribe($arr);
                $this->session->set_flashdata('emailAdded', lang('email_added'));
            }
            if (!headers_sent()) {
                redirect();
            } else {
                echo 'window.location = "' . base_url() . '"';
            }
        }
    }

    /*
     * Set referrer to save it in orders
     */

    private function setReferrer()
    {
        if ($this->session->userdata('referrer') == null) {
            if (!isset($_SERVER['HTTP_REFERER'])) {
                $ref = 'Direct';
            } else {
                $ref = $_SERVER['HTTP_REFERER'];
            }
            $this->session->set_userdata('referrer', $ref);
        }
    }

    /*
     * Check for selected template 
     * and set it in config if exists
     */

    private function loadTemplate()
    {
        $template = $this->Home_admin_model->getValueStore('template');
        if ($template == null) {
            $template = $this->config->item('template');
        } else {
            $this->config->set_item('template', $template);
        }
        if (!is_dir(TEMPLATES_DIR . $template)) {
            show_error('The selected template does not exists!');
        }
        $this->template = 'templates' . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR;
    }

}
