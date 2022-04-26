<?php

declare (strict_types = 1);

require 'autoload.php';

class Checkout 
{
    use CostCalculator;

    public function calculateTotalPrice(array $cartItems): int
    {
        $total_cost = 0.0;

        foreach ($cartItems as $cartItem)
        {
            $itemName  = $cartItem['item'];
            $itemQty   = $cartItem['quantity'];
            $item      = Items::getItem($itemName);
            $itemRule  = ItemRules::getItem($itemName);

            if ($itemRule) 
            {
                if ($this->hasSingleDiscountOffer($itemRule)) {  
                    $total_cost += $this->calculateSingleRuleCost($cartItem, $itemRule, $item);
                }
                else if ($this->hasMultipleDiscountOffer($itemRule)) {
                    $total_cost += $this->calculateMultipleRuleCost($cartItem, $itemRule, $item);
                }
                else if ($this->hasDiscountedItemOffer($itemRule)) {
                    $cartItemNames = array_column($cartItems, 'item');
                    $discountedItemName = $itemRule['rule']['discounted_item'];
                    $filteredItem = [];
                    
                    if (in_array($discountedItemName, $cartItemNames)) {
                        $filteredItem = Helper::filter_list($cartItems, 'item', $discountedItemName);
                    }

                    if(!empty($filteredItem)) {
                        $total_cost += $this->calculateDiscountedItemCost($cartItem, $filteredItem, $itemRule, $item);
                    }
                    else {
                        $total_cost += Helper::product($itemQty, $item['unit_price']);
                    }
                    
                }
            }
            else
            {
                $total_cost += Helper::product($itemQty, $item['unit_price']);
            }
        }

        return (int) $total_cost;
    }
}