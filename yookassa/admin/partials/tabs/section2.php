<?php

/* @var int $testMode */
/* @var int $payMode */
/* @var int $isHoldEnabled */
/* @var int $isSbBOLEnabled */
/* @var array $isSaveCard */
/* @var string $wcCalcTaxes */
/* @var array $wcTaxes */
/* @var string $yookassaNonce */
/* @var int $isECEnabled */
/* @var bool $isSberBnplAvailable */
/* @var bool $isSberBnplEnabled */
/* @var bool $isSberBnplProductEnabled */
/* @var bool $isSberBnplCartEnabled */
/* @var bool $isSberBnplCheckoutEnabled */
/* @var bool $isSberBnplProductCompactEnabled */
/* @var bool $isSberBnplListCompactEnabled */
/* @var bool $isSberBnplCartCompactEnabled */
/* @var bool $isSberBnplCheckoutCompactEnabled */
/* @var bool $isSberBnplProductHideSumEnabled */
/* @var bool $isSberBnplListHideSumEnabled */
/* @var bool $isSberBnplCartHideSumEnabled */
/* @var bool $isSberBnplCheckoutHideSumEnabled */
/* @var string $sberBnplProductTheme */
/* @var string $sberBnplListTheme */
/* @var string $sberBnplCartTheme */
/* @var string $sberBnplCheckoutTheme */
/* @var string $sberBnplProductTemplate */
/* @var string $sberBnplListTemplate */
/* @var string $sberBnplCartTemplate */
/* @var string $sberBnplCheckoutTemplate */
/* @var string $sberBnplProductSize */
/* @var string $sberBnplListSize */
/* @var string $sberBnplCartSize */
/* @var string $sberBnplCheckoutSize */
/* @var string $sberBnplProductAlign */
/* @var string $sberBnplListAlign */
/* @var string $sberBnplCartAlign */
/* @var string $sberBnplCheckoutAlign */
?>
<style>
    .sber-bnpl-preview {
        border: 1px solid #e2e6ea;
        border-radius: 6px;
        background: #fff;
        overflow: visible;
    }
    .sber-bnpl-preview__header {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-bottom: 1px solid #eef1f4;
        font-size: 13px;
        font-weight: 600;
        color: #3c434a;
    }
    .sber-bnpl-preview__header .dashicons {
        color: #6a7a89;
        font-size: 16px;
        width: 16px;
        height: 16px;
    }
    .sber-bnpl-preview__body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 12px;
        background: #f6f8fa;
    }
    .sber-bnpl-preview__widget {
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .sber-bnpl-preview__placeholder,
    .sber-bnpl-preview__fallback {
        display: none;
        font-size: 12px;
        text-align: center;
        max-width: 220px;
        margin: 0 auto;
        padding: 6px;
    }
    .sber-bnpl-preview__placeholder {
        color: #9aa3ad;
    }
    .sber-bnpl-preview__fallback {
        color: #b05a2e;
    }
    .sber-bnpl-preview__footer {
        display: none;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-top: 1px solid #eef1f4;
        font-size: 12px;
        color: #6b7784;
        font-style: italic;
    }
    .sber-bnpl-preview--informer .sber-bnpl-preview__footer {
        display: flex;
    }
    .sber-bnpl-preview[data-bnpl-place="list"] .sber-bnpl-preview__footer {
        display: flex;
    }
    .sber-bnpl-preview--muted .sber-bnpl-preview__widget {
        display: none;
    }
    .sber-bnpl-preview--muted .sber-bnpl-preview__placeholder {
        display: block;
    }
    .sber-bnpl-preview--failed .sber-bnpl-preview__widget {
        display: none;
    }
    .sber-bnpl-preview--failed .sber-bnpl-preview__fallback {
        display: block;
    }
    .sber-bnpl-tabs {
        margin-bottom: 16px;
    }
    .sber-bnpl-tabs .nav-link {
        padding: 6px 12px;
        font-size: 13px;
    }
    .sber-bnpl-control--disabled {
        color: #6c757d;
        cursor: default;
    }
    @media (max-width: 991px) {
        .sber-bnpl-preview {
            margin-top: 16px;
        }
    }
</style>
<form id="yoomoney-form-2" class="yoomoney-form">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 padding-bottom">
                <div class="form-group qa-payment-scenario">
                    <label for="yookassa_pay_mode"><?= __('Сценарий оплаты', 'yookassa') ?></label>
                    <select id="yookassa_pay_mode" name="yookassa_pay_mode" class="form-control">
                        <option value="1" <?= $payMode == 1 ? 'selected="selected"' : '' ?>><?= __('Умный платёж (рекомендуем)', 'yookassa') ?></option>
                        <option value="0" <?= ($payMode == 0) ? 'selected="selected"' : '' ?>><?= __('Виджет ЮKassa', 'yookassa') ?></option>
                    </select>
                    <p class="help-block help-block-error"></p>
                </div>

            </div>
            <div class="col-md-5 col-md-offset-1 help-side qa-payment-scenarios-info">
                <p class="title qa-title"><b><?= __('Как будет проходить оплата', 'yookassa') ?></b></p>
                <p id="pay-mode-1" class="pay-mode-block qa-text-info" style="<?= ($payMode == 1) ? '' : 'display:none;' ?>">
                    <?= __('Из вашего магазина покупатель перейдёт на страницу ЮKassa и заплатит любым из способов, которые вы подключили.', 'yookassa') ?><br><br>
                    <a class="qa-link" target="_blank" href="https://yookassa.ru/developers/payment-acceptance/integration-scenarios/smart-payment"><?= __('Подробнее про сценарий оплаты', 'yookassa') ?></a>
                </p>
                <p id="pay-mode-0" class="pay-mode-block" style="<?= ($payMode == 0) ? '' : 'display:none;' ?>">
                    <?= __('Покупатель сможет выбрать способ оплаты в платёжной форме, которая встроена в ваш сайт — переходить на нашу страницу для оплаты не нужно.', 'yookassa') ?><br><br>
                    <a target="_blank" href="https://yookassa.ru/developers/payment-acceptance/integration-scenarios/widget/basics"><?= __('Подробнее про сценарий оплаты', 'yookassa') ?></a>
                </p>
            </div>
        </div>

        <div id="save-card" class="qa-save-card">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="custom-control custom-switch qa-checkbox">
                        <input type="hidden" name="yookassa_save_card" value="0">
                        <input <?= ($isSaveCard) ? ' checked' : '' ?> type="checkbox" class="custom-control-input" id="yookassa_save_card" name="yookassa_save_card" value="1">
                        <label class="custom-control-label" for="yookassa_save_card">
                            <?= __('Покупатели могут сохранять данные карты в вашем магазине', 'yookassa') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-6 qa-text-info">
                    <p><small class="text-muted"><?= __('Это поможет им быстрее оплачивать следующие покупки — достаточно будет ввести код из пуша или смс, иногда CVC.', 'yookassa') ?></small></p>
                </div>
            </div>
        </div>

        <div class="qa-enable-hold">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="custom-control custom-switch qa-checkbox">
                        <input type="hidden" name="yookassa_enable_hold" value="0">
                        <input <?=($isHoldEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input" id="yookassa_enable_hold" name="yookassa_enable_hold" value="1">
                        <label class="custom-control-label" for="yookassa_enable_hold">
                            <?= __('Отложенные платежи', 'yookassa') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-6 qa-text-info">
                    <p><small class="text-muted"><?= __('Если включить, платежи будут проходить в 2 этапа: сначала сумма замораживается у покупателя, затем вы вручную подтверждаете её списание — через панель администратора. Не сработает с b2b-платежами, а также при оплате по СБП и электронными сертификатами.', 'yookassa') ?></small></p>
                </div>
            </div>
        </div>

        <?php if (!empty($isSberBnplAvailable)): ?>
        <div class="qa-sber-bnpl">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="custom-control custom-switch qa-checkbox">
                        <input type="hidden" name="yookassa_sber_bnpl_enabled" value="0">
                        <input <?=($isSberBnplEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input"
                               id="yookassa_sber_bnpl_enabled" name="yookassa_sber_bnpl_enabled" value="1"
                               data-toggle="collapse" data-target="#sber-bnpl-collapsible" aria-controls="sber-bnpl-collapsible">
                        <label class="custom-control-label" for="yookassa_sber_bnpl_enabled">
                            <?= __('«Плати частями» от Сбера', 'yookassa') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-8 qa-text-info">
                    <p><small class="text-muted">
                        <?= __('Если включить, на странице оформления заказа появится способ оплаты «Плати частями». Также можно будет настроить показ виджетов, чтобы покупатели видели, что покупку можно оплатить частями без оформления кредитного договора.', 'yookassa') ?>
                    </small></p>
                </div>
            </div>
        </div>

        <div id="sber-bnpl-collapsible" class="in collapse<?=($isSberBnplEnabled)?' show':'' ?>">
            <div class="row">
                <div class="col-md-10 padding-bottom">
                    <!-- тумблер списка товаров -->
                    <div class="custom-control custom-checkbox qa-checkbox qa-sber-bnpl-pay-in-list">
                        <input type="hidden" name="yookassa_add_sber_bnpl_list" value="0">
                        <input <?=($isSberBnplListEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input"
                               id="yookassa_add_sber_bnpl_list" name="yookassa_add_sber_bnpl_list" value="1">
                        <label class="custom-control-label" for="yookassa_add_sber_bnpl_list">
                            <?= __('Виджет в списке товаров', 'yookassa') ?>
                        </label>
                    </div>
                    <!-- тумблер карточки товара -->
                    <div class="custom-control custom-checkbox qa-checkbox qa-sber-bnpl-pay-in-product">
                        <input type="hidden" name="yookassa_add_sber_bnpl_product" value="0">
                        <input <?=($isSberBnplProductEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input"
                               id="yookassa_add_sber_bnpl_product" name="yookassa_add_sber_bnpl_product" value="1">
                        <label class="custom-control-label" for="yookassa_add_sber_bnpl_product">
                            <?= __('Виджет в карточке товара', 'yookassa') ?>
                        </label>
                    </div>
                    <!-- тумблер корзины -->
                    <div class="custom-control custom-checkbox qa-checkbox qa-sber-bnpl-pay-in-cart">
                        <input type="hidden" name="yookassa_add_sber_bnpl_cart" value="0">
                        <input <?=($isSberBnplCartEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input"
                               id="yookassa_add_sber_bnpl_cart" name="yookassa_add_sber_bnpl_cart" value="1">
                        <label class="custom-control-label" for="yookassa_add_sber_bnpl_cart">
                            <?= __('Виджет в корзине', 'yookassa') ?>
                        </label>
                    </div>
                    <!-- тумблер формы оформления -->
                    <div class="custom-control custom-checkbox qa-checkbox qa-sber-bnpl-pay-in-checkout">
                        <input type="hidden" name="yookassa_add_sber_bnpl_checkout" value="0">
                        <input <?=($isSberBnplCheckoutEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input"
                               id="yookassa_add_sber_bnpl_checkout" name="yookassa_add_sber_bnpl_checkout" value="1">
                        <label class="custom-control-label" for="yookassa_add_sber_bnpl_checkout">
                            <?= __('Виджет на странице оформления заказа', 'yookassa') ?>
                        </label>
                    </div>
                </div>
            </div>
            <!-- внешний вид виджета: вкладки по месту показа -->
            <?php
            $sberBnplPlaces = array(
                'list'     => array(
                    'title'       => __('Список товаров', 'yookassa'),
                    'muted'       => __('Для предпросмотра включите показ виджета', 'yookassa'),
                    'enabled'     => $isSberBnplListEnabled,
                    'hasTemplate' => false,
                    'theme'       => $sberBnplListTheme,
                    'template'    => $sberBnplListTemplate,
                    'size'        => $sberBnplListSize,
                    'align'       => $sberBnplListAlign,
                    'compact'     => $isSberBnplListCompactEnabled,
                    'hideSum'     => $isSberBnplListHideSumEnabled,
                ),
                'product'  => array(
                    'title'       => __('Карточка товара', 'yookassa'),
                    'muted'       => __('Для предпросмотра включите показ виджета', 'yookassa'),
                    'enabled'     => $isSberBnplProductEnabled,
                    'hasTemplate' => true,
                    'theme'       => $sberBnplProductTheme,
                    'template'    => $sberBnplProductTemplate,
                    'size'        => $sberBnplProductSize,
                    'align'       => $sberBnplProductAlign,
                    'compact'     => $isSberBnplProductCompactEnabled,
                    'hideSum'     => $isSberBnplProductHideSumEnabled,
                ),
                'cart'     => array(
                    'title'       => __('Корзина', 'yookassa'),
                    'muted'       => __('Для предпросмотра включите показ виджета', 'yookassa'),
                    'enabled'     => $isSberBnplCartEnabled,
                    'hasTemplate' => true,
                    'theme'       => $sberBnplCartTheme,
                    'template'    => $sberBnplCartTemplate,
                    'size'        => $sberBnplCartSize,
                    'align'       => $sberBnplCartAlign,
                    'compact'     => $isSberBnplCartCompactEnabled,
                    'hideSum'     => $isSberBnplCartHideSumEnabled,
                ),
                'checkout' => array(
                    'title'       => __('Заказ', 'yookassa'),
                    'muted'       => __('Для предпросмотра включите показ виджета', 'yookassa'),
                    'enabled'     => $isSberBnplCheckoutEnabled,
                    'hasTemplate' => true,
                    'theme'       => $sberBnplCheckoutTheme,
                    'template'    => $sberBnplCheckoutTemplate,
                    'size'        => $sberBnplCheckoutSize,
                    'align'       => $sberBnplCheckoutAlign,
                    'compact'     => $isSberBnplCheckoutCompactEnabled,
                    'hideSum'     => $isSberBnplCheckoutHideSumEnabled,
                ),
            );

            $sberBnplThemeOptions = array(
                'classic' => __('Классика', 'yookassa'),
                'dark'    => __('Тёмная', 'yookassa'),
                'inverse' => __('Инверсия', 'yookassa'),
                'mint'    => __('Мятная', 'yookassa'),
            );
            $sberBnplTemplateOptions = array(
                'informer' => __('Компактный', 'yookassa'),
                'dialog'   => __('Полный: график сразу на странице', 'yookassa'),
            );
            $sberBnplSizeOptions = array(
                'small'  => __('S', 'yookassa'),
                'medium' => __('M', 'yookassa'),
            );
            $sberBnplAlignOptions = array(
                'left'   => __('Слева', 'yookassa'),
                'center' => __('По центру', 'yookassa'),
                'right'  => __('Справа', 'yookassa'),
            );

            $sberBnplPreviewSum = 15000;
            foreach ($sberBnplPlaces as $sberBnplPlaceKey => $sberBnplPlace) {
                $sberBnplPreviewAttrs = array(
                    'sum'   => $sberBnplPreviewSum,
                    'theme' => $sberBnplPlace['theme'],
                    'size'  => $sberBnplPlace['size'],
                    'align' => $sberBnplPlace['align'],
                );
                if (!empty($sberBnplPlace['hasTemplate'])) {
                    $sberBnplPreviewAttrs['template'] = $sberBnplPlace['template'];
                }
                if ('list' === $sberBnplPlaceKey) {
                    $sberBnplPreviewAttrs['variant'] = 'popup';
                }
                if (!empty($sberBnplPlace['compact'])) {
                    $sberBnplPreviewAttrs['compact'] = 'true';
                }
                $sberBnplPreviewAttrs['label'] = !empty($sberBnplPlace['hideSum']) ? 'false' : 'true';

                $sberBnplPreviewHtml = '<bnpl-payments';
                foreach ($sberBnplPreviewAttrs as $sberBnplAttrKey => $sberBnplAttrValue) {
                    $sberBnplPreviewHtml .= ' ' . esc_attr($sberBnplAttrKey) . '="' . esc_attr($sberBnplAttrValue) . '"';
                }
                $sberBnplPreviewHtml .= '></bnpl-payments>';
                $sberBnplPlaces[$sberBnplPlaceKey]['previewHtml'] = $sberBnplPreviewHtml;
            }
            ?>
            <div class="row padding-bottom">
                <div class="col-md-10">
                    <h4><?= __('Дизайн виджета', 'yookassa') ?></h4>
                    <ul class="nav nav-tabs sber-bnpl-tabs" role="tablist">
                        <?php foreach ($sberBnplPlaces as $sberBnplPlaceKey => $sberBnplPlace) : ?>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link<?= 'list' === $sberBnplPlaceKey ? ' active' : '' ?>"
                                   id="sber-bnpl-tab-link-<?= esc_attr($sberBnplPlaceKey) ?>"
                                   data-bnpl-place="<?= esc_attr($sberBnplPlaceKey) ?>"
                                   data-toggle="tab" href="#sber-bnpl-tab-<?= esc_attr($sberBnplPlaceKey) ?>"
                                   role="tab" aria-controls="sber-bnpl-tab-<?= esc_attr($sberBnplPlaceKey) ?>"
                                   aria-selected="<?= 'product' === $sberBnplPlaceKey ? 'true' : 'false' ?>">
                                    <?= $sberBnplPlace['title'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content sber-bnpl-tabs-content">
                        <?php foreach ($sberBnplPlaces as $sberBnplPlaceKey => $sberBnplPlace) : ?>
                            <div class="tab-pane fade<?= 'list' === $sberBnplPlaceKey ? ' show active' : '' ?>"
                                 id="sber-bnpl-tab-<?= esc_attr($sberBnplPlaceKey) ?>" role="tabpanel"
                                 aria-labelledby="sber-bnpl-tab-link-<?= esc_attr($sberBnplPlaceKey) ?>">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <label for="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_theme"><?= __('Тема', 'yookassa') ?></label>
                                            <select class="form-control" id="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_theme" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_theme">
                                                <?php foreach ($sberBnplThemeOptions as $sberBnplThemeKey => $sberBnplThemeLabel) : ?>
                                                    <option value="<?= esc_attr($sberBnplThemeKey) ?>" <?= selected($sberBnplPlace['theme'], $sberBnplThemeKey, false) ?>><?= $sberBnplThemeLabel ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php if (!empty($sberBnplPlace['hasTemplate'])) : ?>
                                            <div class="form-group">
                                                <label for="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_template"><?= __('Вид', 'yookassa') ?></label>
                                                <select class="form-control" id="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_template" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_template">
                                                    <?php foreach ($sberBnplTemplateOptions as $sberBnplTemplateKey => $sberBnplTemplateLabel) : ?>
                                                        <option value="<?= esc_attr($sberBnplTemplateKey) ?>" <?= selected($sberBnplPlace['template'], $sberBnplTemplateKey, false) ?>><?= $sberBnplTemplateLabel ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label for="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_size"><?= __('Размер', 'yookassa') ?></label>
                                            <select class="form-control" id="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_size" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_size">
                                                <?php foreach ($sberBnplSizeOptions as $sberBnplSizeKey => $sberBnplSizeLabel) : ?>
                                                    <option value="<?= esc_attr($sberBnplSizeKey) ?>" <?= selected($sberBnplPlace['size'], $sberBnplSizeKey, false) ?>><?= $sberBnplSizeLabel ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_align"><?= __('Выравнивание', 'yookassa') ?></label>
                                            <select class="form-control" id="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_align" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_align">
                                                <?php foreach ($sberBnplAlignOptions as $sberBnplAlignKey => $sberBnplAlignLabel) : ?>
                                                    <option value="<?= esc_attr($sberBnplAlignKey) ?>" <?= selected($sberBnplPlace['align'], $sberBnplAlignKey, false) ?>><?= $sberBnplAlignLabel ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <?php if ('list' !== $sberBnplPlaceKey): ?>
                                        <div class="custom-control custom-checkbox qa-checkbox">
                                            <input type="hidden" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_compact" value="0">
                                            <input <?= !empty($sberBnplPlace['compact']) ? ' checked' : '' ?>
                                                   type="checkbox" class="custom-control-input"
                                                   id="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_compact" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_compact" value="1">
                                            <label class="custom-control-label" for="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_compact">
                                                <?= __('Компактный график платежей', 'yookassa') ?>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox qa-checkbox">
                                            <input type="hidden" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_hide_sum" value="0">
                                            <input <?= !empty($sberBnplPlace['hideSum']) ? ' checked' : '' ?>
                                                   type="checkbox" class="custom-control-input"
                                                   id="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_hide_sum" name="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_hide_sum" value="1">
                                            <label class="custom-control-label" for="yookassa_sber_bnpl_<?= esc_attr($sberBnplPlaceKey) ?>_hide_sum">
                                                <?= __('Скрыть сумму', 'yookassa') ?>
                                            </label>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="sber-bnpl-preview<?= (empty($isSberBnplEnabled) || empty($sberBnplPlace['enabled'])) ? ' sber-bnpl-preview--muted' : '' ?>"
                                             id="sber-bnpl-preview-<?= esc_attr($sberBnplPlaceKey) ?>" data-bnpl-place="<?= esc_attr($sberBnplPlaceKey) ?>">
                                            <div class="sber-bnpl-preview__header">
                                                <span class="dashicons dashicons-visibility" aria-hidden="true"></span>
                                                <?= __('Предпросмотр', 'yookassa') ?>
                                            </div>
                                            <div class="sber-bnpl-preview__body">
                                                <div class="sber-bnpl-preview__widget" id="sber-bnpl-widget-<?= esc_attr($sberBnplPlaceKey) ?>">
                                                    <?= $sberBnplPlace['previewHtml'] ?>
                                                </div>
                                                <div class="sber-bnpl-preview__placeholder">
                                                    <?= $sberBnplPlace['muted'] ?>
                                                </div>
                                                <div class="sber-bnpl-preview__fallback">
                                                    <?= __('Не удалось загрузить виджет для предпросмотра', 'yookassa') ?>
                                                </div>
                                            </div>
                                            <div class="sber-bnpl-preview__footer">
                                                <?= __('Нажмите, чтобы увидеть больше', 'yookassa') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-12">
                    <p><small class="text-muted">
                        <?= __('Если у вас не получается настроить показ виджета в настройках модуля ЮKassa, попробуйте сделать это самостоятельно', 'yookassa') ?>
                        <a data-qa-link="https://platichastyami.ru/integration" target="_blank" href="https://platichastyami.ru/integration"><?= __('по гайду «Плати частями»', 'yookassa') ?></a>
                    </small></p>
                </div>
            </div>
        </div>

    <?php endif; ?>
        <div class="qa-electronic-certificate">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="custom-control custom-switch qa-checkbox">
                        <input type="hidden" name="yookassa_electronic_certificate_enabled" value="0">
                        <input <?=($isECEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input" id="yookassa_electronic_certificate_enabled" name="yookassa_electronic_certificate_enabled" value="1">
                        <label class="custom-control-label" for="yookassa_electronic_certificate_enabled">
                            <?= __('Оплата электронным сертификатом', 'yookassa') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-6 qa-text-info">
                    <p><small class="text-muted">
                            <?= __("Включите, если <a data-qa-link='https://yookassa.ru/developers/payment-acceptance/integration-scenarios/manual-integration/other/electronic-certificate/basics#payment-method-overview-activation' target='_blank' href='https://yookassa.ru/developers/payment-acceptance/integration-scenarios/manual-integration/other/electronic-certificate/basics#payment-method-overview-activation'>настроили</a> приём платежей с помощью электронных сертификатов.", 'yookassa') ?>
                            <br><?= __('Чтобы всё заработало, нужно будет указать код ТРУ в настройках товара.', 'yookassa') ?>
                        </small></p>
                </div>
            </div>
        </div>

        <div class="qa-sbbol">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="custom-control custom-switch qa-checkbox">
                        <input type="hidden" name="yookassa_enable_sbbol" value="0">
                        <input <?=($isSbBOLEnabled)?' checked':'' ?> type="checkbox" class="custom-control-input" id="yookassa_enable_sbbol" name="yookassa_enable_sbbol" value="1" data-toggle="collapse" data-target="#sbbol-collapsible" aria-controls="sbbol-collapsible">
                        <label class="custom-control-label" for="yookassa_enable_sbbol">
                            <?= __('СберБанк Бизнес Онлайн', 'yookassa') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row padding-bottom">
                <div class="col-md-6 qa-text-info">
                    <p><small class="text-muted">
                            <?= __('Если опция включена, вы можете принимать онлайн-платежи от юрлиц через СберБанк Бизнес Онлайн.', 'yookassa') ?>
                            <?= __("Подробнее — <a data-qa-link='https://yookassa.ru/docs/support/payments/extra/b2b-payments' target='_blank' href='https://yookassa.ru/docs/support/payments/extra/b2b-payments'>на сайте ЮKassa</a>", 'yookassa') ?>
                        </small></p>
                </div>
            </div>
        </div>

        <div id="sbbol-collapsible" class="in collapse<?=($isSbBOLEnabled)?' show':'' ?>">
            <div class="row">
                <div class="col-md-7">

                    <?php $ymSbbolTaxRatesEnum = get_option('yookassa_sbbol_tax_rates_enum'); ?>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="yookassa_sbbol_purpose"><?= __("Шаблон для назначения платежа", 'yookassa') ?></label>
                        </div>
                        <div class="col-md-7">
                            <textarea type="text" id="yookassa_sbbol_purpose" name="yookassa_sbbol_purpose" class="form-control"
                                      placeholder="<?= __('Заполните поле', 'yookassa') ?>"><?= $sbbolTemplate ?></textarea>

                            <p><small class="text-muted"><?= __("Это назначение платежа будет в платёжном поручении.", 'yookassa') ?></small></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="yookassa_default_tax_rate"><?= __("Ставка по умолчанию", 'yookassa') ?></label>
                        </div>
                        <div class="col-md-7">
                            <?php $selected20 = get_option('yookassa_sbbol_default_tax_rate') == '20'; ?>
                            <select id="yookassa_default_tax_rate" name="yookassa_sbbol_default_tax_rate" class="yookassa_sbbol_tax_rate_select">
                                <?php foreach ($ymSbbolTaxRatesEnum as $taxId => $taxName) : ?>
                                    <option value="<?php echo $taxId ?>" <?php echo $taxId == get_option('yookassa_sbbol_default_tax_rate') ? 'selected=\'selected\'' : ''; ?>><?php echo $taxName ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p><small class="text-muted"><?= __("Эта ставка передаётся в СберБанк Бизнес Онлайн, если в карточке товара не указана другая ставка.", 'yookassa') ?></small></p>
                        </div>
                    </div>
                    <?php if ($wcCalcTaxes === 'yes' && $wcTaxes) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label><?= __("Сопоставьте ставки НДС в вашем магазине со ставками для Сбербанка Бизнес Онлайн", 'yookassa') ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <p><?= __("Ставка НДС в вашем магазине", 'yookassa') ?></p>
                            </div>
                            <div class="col-sm-7">
                                <p><?= __("Ставка НДС для СберБанк Бизнес Онлайн", 'yookassa') ?></p>
                            </div>
                        </div>
                        <?php $ymTaxes = get_option('yookassa_sbbol_tax_rate'); ?>
                        <?php foreach ($wcTaxes as $wcTax) : ?>
                        <div class="row">
                            <div class="col-sm-5"><?= round($wcTax->tax_rate) ?>%</div>
                            <div class="col-sm-7">
                                <?php
                                    $selected = isset($ymTaxes[$wcTax->tax_rate_id]) ? $ymTaxes[$wcTax->tax_rate_id] : null;
                                    if ($selected == '20') { $selected20 = true; }
                                ?>
                                <select id="yookassa_sbbol_tax_rate[<?= $wcTax->tax_rate_id ?>]" name="yookassa_sbbol_tax_rate[<?= $wcTax->tax_rate_id ?>]" class="yookassa_sbbol_tax_rate_select">
                                    <?php foreach ($ymSbbolTaxRatesEnum as $taxId => $taxName) : ?>
                                        <option value="<?php echo $taxId ?>" <?= $selected == $taxId ? 'selected' : '' ?> >
                                            <?= $taxName ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="col-md-5">
                    <div class="info-block">
                        <span class="dashicons dashicons-info" aria-hidden="true"></span>
                        <?= __('При оплате через СберБанк Бизнес Онлайн есть ограничение: в одном чеке могут быть только товары с одинаковой ставкой НДС. Если клиент захочет оплатить за один раз товары с разными ставками — мы покажем ему сообщение, что так сделать не получится.', 'yookassa') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
        jQuery(function($) {
            var mainToggle = $('#yookassa_sber_bnpl_enabled');

            var places = ['product', 'list', 'cart', 'checkout'];

            // Enable toggles per place (block -> карточка товара).
            var placeToggles = {
                product:  $('#yookassa_add_sber_bnpl_product'),
                list:     $('#yookassa_add_sber_bnpl_list'),
                cart:     $('#yookassa_add_sber_bnpl_cart'),
                checkout: $('#yookassa_add_sber_bnpl_checkout')
            };

            var previews = {};
            var widgets  = {};
            places.forEach(function(place) {
                previews[place] = $('#sber-bnpl-preview-' + place);
                var wrap = $('#sber-bnpl-widget-' + place);
                widgets[place] = wrap.length ? wrap.find('bnpl-payments') : $();
            });

            var fields = {};
            places.forEach(function(place) {
                fields[place] = {
                    theme:    $('#yookassa_sber_bnpl_' + place + '_theme'),
                    template: $('#yookassa_sber_bnpl_' + place + '_template'),
                    size:     $('#yookassa_sber_bnpl_' + place + '_size'),
                    align:    $('#yookassa_sber_bnpl_' + place + '_align'),
                    compact:  $('#yookassa_sber_bnpl_' + place + '_compact'),
                    hideSum:  $('#yookassa_sber_bnpl_' + place + '_hide_sum')
                };
            });

            function applyAttrs(place) {
                var el = widgets[place];
                if (!el.length) {
                    return;
                }
                var f = fields[place];
                var isList = place === 'list';

                if (f.theme.length) {
                    el.attr('theme', f.theme.val());
                }
                if (isList) {
                    el.removeAttr('template');
                    el.attr('variant', 'popup');
                } else if (f.template.length) {
                    el.attr('template', f.template.val());
                    if (f.template.val() === 'informer') {
                        previews[place].addClass('sber-bnpl-preview--informer');
                    } else {
                        previews[place].removeClass('sber-bnpl-preview--informer');
                    }
                }
                if (f.size.length) {
                    el.attr('size', f.size.val());
                }
                if (f.align.length) {
                    el.attr('align', f.align.val());
                }
                if (f.compact.length) {
                    if (f.compact.prop('checked')) {
                        el.attr('compact', 'true');
                    } else {
                        el.removeAttr('compact');
                    }
                }
                if (f.hideSum.length) {
                    el.attr('label', f.hideSum.prop('checked') ? 'false' : 'true');
                }
            }

            function setDisabled(c, disabled) {
                if (!c.length) {
                    return;
                }
                c.prop('disabled', disabled);
                // Uncheck checkboxes being disabled.
                if (disabled && c.is(':checkbox')) {
                    c.prop('checked', false);
                }
                // Dim the associated label too.
                var label = c.is('select') ? c.prev('label') : c.next('label');
                if (label.length) {
                    label.toggleClass('sber-bnpl-control--disabled', disabled);
                }
            }

            function updateControls(place, placeOn) {
                var f = fields[place];
                var isList = place === 'list';
                var isDialog = f.template.length && f.template.val() === 'dialog';

                // If the place toggle is off, disable all controls in the tab.
                var controls = [f.theme, f.template, f.size, f.align, f.compact, f.hideSum];
                controls.forEach(function(c) {
                    setDisabled(c, !placeOn);
                });

                if (!placeOn) {
                    return;
                }

                // In dialog (full graph) mode alignment and "hide sum" don't affect the widget.
                if (isDialog) {
                    setDisabled(f.align, true);
                    setDisabled(f.hideSum, true);
                }
            }

            function updateAll() {
                var mainOn = mainToggle.length ? mainToggle.prop('checked') : true;
                places.forEach(function(place) {
                    var panel = previews[place];
                    if (!panel.length) {
                        return;
                    }
                    var placeOn = placeToggles[place].length ? placeToggles[place].prop('checked') : true;
                    var enabled = mainOn && placeOn;
                    panel.toggleClass('sber-bnpl-preview--muted', !enabled);
                    updateControls(place, placeOn);
                    if (enabled) {
                        applyAttrs(place);
                    }
                });
            }

            places.forEach(function(place) {
                var f = fields[place];
                for (var key in f) {
                    if (f.hasOwnProperty(key) && f[key].length) {
                        f[key].on('change', updateAll);
                    }
                }
                if (placeToggles[place].length) {
                    placeToggles[place].on('change', updateAll);
                }
            });
            if (mainToggle.length) {
                mainToggle.on('change', updateAll);
            }

            updateAll();

            // If the widget script hasn't registered the <bnpl-payments> custom
            // element within ~5 seconds, show the graceful fallback messages.
            var attempts = 0;
            var timer = setInterval(function() {
                attempts++;
                var loaded = window.customElements && window.customElements.get('bnpl-payments');
                if (loaded || attempts >= 20) {
                    clearInterval(timer);
                    if (!loaded) {
                        places.forEach(function(place) {
                            if (previews[place].length) {
                                previews[place].addClass('sber-bnpl-preview--failed');
                            }
                        });
                    }
                }
            }, 250);
        });
        </script>

        <div class="row form-footer">
            <div class="col-md-12">
                <button class="btn btn-default btn-back qa-back-button" data-tab="section1"><?= __('Назад', 'yookassa') ?></button>
                <button class="btn btn-primary btn-forward qa-forward-button" data-tab="section3"><?= __('Сохранить и продолжить', 'yookassa') ?></button>
            </div>
        </div>
    </div>
    <input name="form_nonce" type="hidden" value="<?=$yookassaNonce?>" />
</form>
