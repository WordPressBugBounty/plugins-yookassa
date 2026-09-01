<?php

namespace Cmssdk\Metrics\Model;

use OpenApi\Attributes as OA;
use YooKassa\Common\AbstractObject;

#[OA\Schema()]
class ModuleInfo extends AbstractObject
{
    /** @var string|null */
    #[OA\Property(property: "os_version", example: "Ubuntu/14.04")]
    private $osVersion;
    /** @var string|null */
    #[OA\Property(property: "php_version", example: "PHP/7.3.28")]
    private $phpVersion;
    /** @var string|null */
    #[OA\Property(property: "cms_version", example: "Wordpress/5.8.10")]
    private $cmsVersion;
    /** @var string|null */
    #[OA\Property(property: "framework_version", example: "Woocommerce/5.9.0")]
    private $frameworkVersion;
    /** @var string|null */
    #[OA\Property(property: "module_version", example: "PaymentGateway/2.10.2")]
    private $moduleVersion;
    /** @var string|null */
    #[OA\Property(property: "sdk_version", example: "YooKassa.PHP/2.11.1")]
    private $sdkVersion;
    /** @var string|null */
    #[OA\Property(property: "host", example: "merchant-site.ru")]
    private $host;

    /**
     * @return string|null
     */
    public function getOsVersion()
    {
        return $this->osVersion;
    }

    /**
     * @return string|null
     */
    public function getPhpVersion()
    {
        return $this->phpVersion;
    }

    /**
     * @return string|null
     */
    public function getCmsVersion()
    {
        return $this->cmsVersion;
    }

    /**
     * @return string|null
     */
    public function getFrameworkVersion()
    {
        return $this->frameworkVersion;
    }

    /**
     * @return string|null
     */
    public function getModuleVersion()
    {
        return $this->moduleVersion;
    }

    /**
     * @return string|null
     */
    public function getSdkVersion()
    {
        return $this->sdkVersion;
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setOsVersion($value = null)
    {
        $this->osVersion = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setPhpVersion($value = null)
    {
        $this->phpVersion = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setCmsVersion($value = null)
    {
        $this->cmsVersion = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setFrameworkVersion($value = null)
    {
        $this->frameworkVersion = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setModuleVersion($value = null)
    {
        $this->moduleVersion = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setSdkVersion($value = null)
    {
        $this->sdkVersion = $value;
        return $this;
    }

    /**
     * @param string|null $value
     * @return self
     */
    public function setHost($value = null)
    {
        $this->host = $value;
        return $this;
    }
}
