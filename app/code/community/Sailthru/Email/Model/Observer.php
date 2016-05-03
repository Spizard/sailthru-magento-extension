<?php
/**
 * Primary Observer Model
 *
 * @category  Sailthru
 * @package   Sailthru_Email
 * @author    Kwadwo Juantuah <support@sailthru.com>
 */
class Sailthru_Email_Model_Observer extends Sailthru_Email_Model_Abstract
{
    /**
     * Push new subscriber data to Sailthru
     *
     * @param Varien_Event_Observer $observer
     * @return
     */
    public function customerSubscription(Varien_Event_Observer $observer)
    {

        if($this->_isEnabled && $this->_email) {
            try{
                $subscriber = $observer->getEvent()->getSubscriber();
                $response = Mage::getModel('sailthruemail/client_user')->sendCustomerData($customer);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        return $this;
    }

    public function customerRegistration(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        if($this->_isEnabled && $customer->getEmail()) {
            try{
                $response = Mage::getModel('sailthruemail/client_user')->sendCustomerData($customer);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        return $this;
    }

    public function addItemToCart(Varien_Event_Observer $observer)
  {
        if($this->_isEnabled && $this->_email) {
            try{
                $response = Mage::getModel('sailthruemail/client_purchase')->sendCart($observer->getQuoteItem()->getQuote(),$this->_email,'addItemToCart');
            } catch (Exception $e) {
                Mage::logException($e);
            }
            return $this;
        }
    }

    public function updateItemInCart(Varien_Event_Observer $observer)
    {
        if($this->_isEnabled && $this->_email) {
            try{
                if ($hasChanges = $observer->getCart()->hasDataChanges()) {
                    $response = Mage::getModel('sailthruemail/client_purchase')->sendCart($observer->getCart()->getQuote(),$this->_email,'updateItemInCart');
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
            return $this;
        }
    }

    public function removeItemFromCart(Varien_Event_Observer $observer)
    {
        if($this->_isEnabled && $this->_email) {
            try{
                 $response = Mage::getModel('sailthruemail/client_purchase')->sendCart($observer->getQuoteItem()->getQuote(),$this->_email,'removeItemFromCart');
            } catch (Exception $e) {
                Mage::logException($e);
            }
            return $this;
        }
    }

    /**
     * Notify Sailthru that a purchase has been made. This automatically cancels
     * any scheduled abandoned cart email.
     *
     * @param Varien_Event_Observer $observer
     * @return
     */
    public function saveOrder(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        if($this->_isEnabled && $customer->getEmail()) {
            try{
                $cart = $observer->getCart();
                $customer = $observer->getEvent()->getCustomer();
                $response = Mage::getModel('sailthruemail/client_purchase')->sendOrder($cart, $customer);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        return $this;
    }

    public function sendAbandonedCart(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        if($this->_isEnabled && $customer->getEmail()) {
            try{
                $cart = $observer->getCart();
                $response = Mage::getModel('sailthruemail/client_purchase_abandoned')->sendCart($cart,$customer->getEmail());
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
        return $this;
    }



}