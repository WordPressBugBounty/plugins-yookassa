import { decodeEntities } from '@wordpress/html-entities';
import { useState, useEffect, useRef } from '@wordpress/element';

const { registerPaymentMethod } = window.wc.wcBlocksRegistry
const ownerGateways = window.yookassaOwnPaymentMethods;
const paymentGateways = window.wc.wcSettings.allSettings.paymentMethodData;

Object.keys(paymentGateways).forEach(function(gatewayKey) {

    if (ownerGateways.indexOf(gatewayKey) < 0) {
        return;
    }

    const settings = paymentGateways[gatewayKey]
    const label = decodeEntities( settings.title )

    const Description = () => {
        return decodeEntities( settings.description || '' )
    }

    const Icon = () => {
        return settings.icon
            ? <img src={settings.icon} style={{ float: 'right', marginRight: '20px' }}  alt={settings.title}/>
            : ''
    }

    const Label = () => {
        return (
            <span style={{ width: '100%' }}>
                {label}
                <Icon />
            </span>
        )
    }

    let Content = Description;

    if (gatewayKey === 'yookassa_sber_bnpl') {
        const formatPhone = (raw) => {
            let digits = String(raw || '').replace(/\D+/g, '');

            if (digits.charAt(0) === '8') {
                digits = '7' + digits.slice(1);
            } else if (digits && digits.charAt(0) !== '7') {
                digits = '7' + digits;
            }

            digits = digits.slice(0, 11);

            if (digits.length === 0) {
                return '';
            }

            let result = '+7';

            if (digits.length > 1) {
                result += ' (' + digits.slice(1, 4);
            }
            if (digits.length >= 4) {
                result += ') ' + digits.slice(4, 7);
            }
            if (digits.length >= 7) {
                result += '-' + digits.slice(7, 9);
            }
            if (digits.length >= 9) {
                result += '-' + digits.slice(9, 11);
            }

            return result;
        };

        Content = ({ eventRegistration, emitResponse, billing }) => {
            const { onPaymentProcessing } = eventRegistration;
            const [phone, setPhone] = useState('');
            const [isActive, setIsActive] = useState(false);
            const userEdited = useRef(false);

            const billingPhone = billing && billing.billingAddress ? billing.billingAddress.phone : '';

            useEffect(() => {
                if (billingPhone && !userEdited.current) {
                    setPhone(formatPhone(billingPhone));
                }
            }, [billingPhone]);

            useEffect(() => {
                return onPaymentProcessing(() => {
                    if (phone.replace(/\D/g, '').length < 4) {
                        return {
                            type: emitResponse.responseTypes.ERROR,
                            message: 'Введите корректный номер телефона',
                        };
                    }
                    return {
                        type: emitResponse.responseTypes.SUCCESS,
                        meta: {
                            paymentMethodData: {
                                'wc-yookassa_sber_bnpl-phone': phone,
                            },
                        },
                    };
                });
            }, [onPaymentProcessing, phone, emitResponse]);

            const inputClass = 'wc-block-components-text-input' + (isActive || phone ? ' is-active' : '');

            return (
                <div>
                    <Description />
                    <div className={inputClass}>
                        <label htmlFor="wc-yookassa_sber_bnpl-phone">
                            Номер телефона <span className="required">*</span>
                        </label>
                        <input
                            id="wc-yookassa_sber_bnpl-phone"
                            type="tel"
                            className="wc-block-components-text-input__input"
                            value={phone}
                            onChange={(e) => {
                                userEdited.current = true;
                                setPhone(formatPhone(e.target.value));
                            }}
                            onFocus={() => setIsActive(true)}
                            onBlur={() => setIsActive(false)}
                            autoComplete="tel"
                            required
                        />
                    </div>
                </div>
            );
        };
    }

    registerPaymentMethod( {
        name: gatewayKey,
        label: <Label />,
        content: <Content />,
        edit: <Description />,
        canMakePayment: () => true,
        ariaLabel: label,
        supports: {
            features: settings.supports,
        }
    } )
});
