<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

class ProductTitleAi extends Module
{
    public function __construct()
    {
        $this->name = 'producttitleai';
        $this->tab = 'administration';
        $this->version = '0.1.0';
        $this->author = 'Dymirt';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Title Ai', [], 'Modules.ProductTitleAi.Admin');
        $this->description = $this->trans('Description of my module.', [], 'Modules.ProductTitleAi.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.ProductTitleAi.Admin');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->trans('No name provided', [], 'Modules.ProductTitleAi.Admin');
        }
    }

    public function install()
    {

        // Call parent install method
        if (!parent::install()) {
            return false;
        }
                // Register hooks
        if (!$this->registerHooks()) {
            return false;
        }

        return true;
    }


    public function getContent()
    {
      $output = null;

      if (Tools::isSubmit("submit" . $this->name)) {
        $apiKey = strval(Tools::getValue("GPT_API_KEY"));
        if (!$apiKey || empty($apiKey)) {
          $output .= $this->displayError($this->l("Invalid API Key"));
        } else {
          Configuration::updateValue("GPT_API_KEY", $apiKey);
          $output .= $this->displayConfirmation($this->l("Settings updated"));
        }
      }
      return $output . $this->displayForm();
    }

    public function displayForm()
  {
    // Init Fields form array
    $fieldsForm[0]["form"] = [
      "legend" => [
        "title" => $this->l("Settings"),
      ],
      "input" => [
        [
          "type" => "text",
          "label" => $this->l("API Key"),
          "name" => "GPT_API_KEY",
          "size" => 50,
          "required" => true,
        ],
      ],
      "submit" => [
        "title" => $this->l("Save"),
        "class" => "btn btn-default pull-right",
      ],
    ];

    $helper = new HelperForm();

    // Module, token, and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite("AdminModules");
    $helper->currentIndex =
      AdminController::$currentIndex . "&configure=" . $this->name;

    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true; // false -> remove toolbar
    $helper->toolbar_scroll = true; // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = "submit" . $this->name;
    $helper->toolbar_btn = [
      "save" => [
        "desc" => $this->l("Save"),
        "href" =>
          AdminController::$currentIndex .
          "&configure=" .
          $this->name .
          "&save" .
          $this->name .
          "&token=" .
          Tools::getAdminTokenLite("AdminModules"),
      ],
      "back" => [
        "href" =>
          AdminController::$currentIndex .
          "&token=" .
          Tools::getAdminTokenLite("AdminModules"),
        "desc" => $this->l("Back to list"),
      ],
    ];

    // Load current value
    $helper->fields_value["GPT_API_KEY"] = Configuration::get("GPT_API_KEY");

    return $helper->generateForm($fieldsForm);
  }

    private function registerHooks()
    {
        // Register the hook
    }

}

