<?php

class WHCashPaymentValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        if (!($this->module instanceof WHCashPayment)) {
            Tools::redirect('index.php?controller=order&step=1');
            return;
        }

        $cart = $this->context->cart;

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
            return;
        }

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'whcashpayment') {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            exit($this->trans('This payment method is not available.', [], 'Modules.Whcashpayment.Shop'));
        }

        $customer = new Customer($cart->id_customer);

        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect('index.php?controller=order&step=1');

            return;
        }

        $currency = $this->context->currency;
        $total = (float) $cart->getOrderTotal(true, Cart::BOTH);

        $mailVars = [];
        $order_status_id = (int) Configuration::getGlobalValue(WHCashPayment::CONFIG_OS_WHCASH);

        $this->module->validateOrder(
            (int) $cart->id,
            $order_status_id,
            $total,
            $this->module->displayName,
            null,
            $mailVars,
            (int) $currency->id,
            false,
            $customer->secure_key
        );
        Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' . (int) $this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key);
    }
}
