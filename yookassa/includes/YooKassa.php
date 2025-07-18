<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * payment-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class YooKassa
{
    public static $pluginUrl;
    public static $pluginPath;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      YooKassaLoader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the payment-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'yookassa';
        $this->version     = '2.12.1';
        self::$pluginUrl   = plugin_dir_url(dirname(__FILE__));
        self::$pluginPath  = plugin_dir_path(dirname(__FILE__));

        $this->loadDependencies();
        $this->setLocale();
        $this->defineAdminHooks();
        $this->definePaymentHooks();
        $this->defineChangeOrderStatuses();

        if (get_option('yookassa_marking_enabled') && get_option('yookassa_enable_second_receipt')) {
            $this->defineMarkingProductHooks();
            $this->defineMarkingOrderHooks();
        }
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - YooKassaLoader. Orchestrates the hooks of the plugin.
     * - YooKassai18n. Defines internationalization functionality.
     * - YooKassaAdmin. Defines all hooks for the admin area.
     * - YooKassaPublic. Defines all hooks for the payment side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function loadDependencies()
    {

        require_once self::$pluginPath . 'includes/lib/autoload.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once self::$pluginPath . 'includes/YooKassaLoader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once self::$pluginPath . 'includes/YooKassaI18N.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once self::$pluginPath . 'admin/YooKassaAdmin.php';
        require_once self::$pluginPath . 'admin/YooKassaTransactionsListTable.php';
        require_once self::$pluginPath . 'admin/YooKassaPaymentChargeDispatcher.php';
        require_once self::$pluginPath . 'admin/YooKassaMarkingProduct.php';
        require_once self::$pluginPath . 'admin/YooKassaMarkingOrder.php';

        /**
         * The class responsible for defining all actions that occur in the payment-facing
         * side of the site.
         */
        require_once self::$pluginPath . 'includes/YooKassaPayment.php';
        require_once self::$pluginPath . 'includes/YooKassaHandler.php';
        require_once self::$pluginPath . 'includes/YooKassaOrderHelper.php';
        require_once self::$pluginPath . 'includes/YooKassaSecondReceipt.php';
        require_once self::$pluginPath . 'includes/YooKassaLogger.php';
        require_once self::$pluginPath . 'includes/WC_Payment_Token_YooKassa.php';
        require_once self::$pluginPath . 'includes/WC_Payment_Token_SBP.php';
        require_once self::$pluginPath . 'includes/YooKassaFileCache.php';
        require_once self::$pluginPath . 'includes/YooKassaCBRAgent.php';
        require_once self::$pluginPath . 'includes/YooKassaClientFactory.php';
        require_once self::$pluginPath . 'includes/YookassaWebhookSubscriber.php';
        require_once self::$pluginPath . 'includes/PaymentsTableModel.php';
        require_once self::$pluginPath . 'includes/CaptureNotificationChecker.php';
        require_once self::$pluginPath . 'includes/SucceededNotificationChecker.php';
        require_once self::$pluginPath . 'includes/YooKassaMarkingCodeHandler.php';

        $this->loader = new YooKassaLoader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the YooKassai18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function setLocale()
    {

        $plugin_i18n = new YooKassaI18N();

        $this->loader->addAction('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function defineAdminHooks()
    {
        $plugin_admin = new YooKassaAdmin($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('admin_menu', $plugin_admin, 'addMenu');
        $this->loader->addAction('admin_init', $plugin_admin, 'registerSettings');
        $this->loader->addAction('wp_ajax_vote_nps', $plugin_admin, 'voteNps');
        $this->loader->addAction('admin_head', $plugin_admin, 'addGatewaysScripts');
    }

    private function defineMarkingProductHooks()
    {
        $plugin_admin = new YooKassaMarkingProduct($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('woocommerce_product_data_tabs', $plugin_admin, 'addMarkingProductTab');
        $this->loader->addAction('woocommerce_product_data_panels', $plugin_admin, 'markingProductTabContent');
        $this->loader->addAction('woocommerce_process_product_meta', $plugin_admin, 'saveMarkingProductFields');
    }

    private function defineMarkingOrderHooks()
    {
        $plugin_admin = new YooKassaMarkingOrder($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('woocommerce_admin_order_item_headers', $plugin_admin, 'addMarkingProductHeadersTab');
        $this->loader->addAction('woocommerce_admin_order_item_values', $plugin_admin, 'addMarkingProductValuesTab', 10, 3);
        $this->loader->addAction('admin_footer', $plugin_admin, 'addMarkingProductPopup');
        $this->loader->addAction('wp_ajax_save_marking_meta', $plugin_admin, 'saveMarkingMetaCallback');
        $this->loader->addAction('wp_ajax_woocommerce_get_oder_item_meta', $plugin_admin, 'getOderItemMetaCallback');
        $this->loader->addAction('admin_notices', $plugin_admin, 'displayOrderWarning');
    }

    /**
     * Register all of the hooks related to the payment-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function definePaymentHooks()
    {
        $paymentKernel = new YooKassaPayment($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('plugins_loaded', $paymentKernel, 'loadGateways');
        $this->loader->addAction('parse_request', $paymentKernel, 'processCallback');

        $this->loader->addFilter('woocommerce_payment_gateways', $paymentKernel, 'addGateways');
        $this->loader->addAction( 'wp_enqueue_scripts', $paymentKernel, 'addGatewaysScripts' );

        $this->loader->addAction('woocommerce_order_status_on-hold_to_processing', $paymentKernel, 'changeOrderStatusToProcessing');
        $this->loader->addAction('woocommerce_order_status_on-hold_to_cancelled', $paymentKernel, 'changeOrderStatusToCancelled');
        $this->loader->addFilter('woocommerce_payment_methods_list_item', $paymentKernel, 'getAccountSavedPaymentMethodsListItem', 10, 2);

        $this->loader->addAction('wp_ajax_nopriv_yookassa_check_payment', $paymentKernel, 'checkPaymentStatus');
    }

    /**
     * Register all of the hooks related to the changes of order statuses
     *
     * @since    1.0.0
     * @access   private
     */
    private function defineChangeOrderStatuses()
    {
        $secondReceipt = new YooKassaSecondReceipt($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('woocommerce_order_status_processing', $secondReceipt, 'changeOrderStatusToProcessing');
        $this->loader->addAction('woocommerce_order_status_completed', $secondReceipt, 'changeOrderStatusToCompleted');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function getPluginName()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    YooKassaLoader  Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function getVersion()
    {
        return $this->version;
    }

}
