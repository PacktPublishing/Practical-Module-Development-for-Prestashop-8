<?php

use WebHelpers\WHRelayCarrier\Entity\WHRelayCart;

class WHRelayCarrierAjaxModuleFrontController extends ModuleFrontController
{

    public function displayAjax()
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $relayCartRepository = $this->get('webhelpers.whrelaycarrier.repository.whrelaycart_repository');

        $cart = Tools::getValue('cart');
        $relay = Tools::getValue('relay');

        if($relay==0){
            $relayCarts = $relayCartRepository->findBy(['cart'=>$cart]);
            foreach($relayCarts as $relayCart)
            {
                $entityManager->remove($relayCart);
            }
        }else{
            $relayCart = $relayCartRepository->findOneBy(['cart'=>$cart]);
            $edition_mode = false;
            if(is_null($relayCart)){
                $relayCart = new WHRelayCart();
            }else{
                $edition_mode = true;
            }

            $relayCart->setRelay($relay);
            $relayCart->setCart($cart);

            if(!$edition_mode){
                $entityManager->persist($relayCart);
            }
        }
        $entityManager->flush();

        $this->ajaxRender(json_encode(['return_code'=>'OK']));
    }
}
