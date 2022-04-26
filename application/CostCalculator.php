<?php 

declare (strict_types = 1);

require 'autoload.php';

trait CostCalculator 
{
    /*
    * Function Name: hasSingleDiscountOffer
    * Params: array $itemRule
    * Description: Check if item has single discount.
    * Return: Boolean
    * Date: 25-04-2022
    */
    public function hasSingleDiscountOffer(array $itemRule): bool
    {
        return $itemRule['rule_type'] === SINGLE_DISCOUNT;
    }   


    /*
    * Function Name: hasMultipleDiscountOffer
    * Params: array $itemRule
    * Description: Check if item has multiple discount.
    * Return: Boolean
    * Date: 25-04-2022
    */
    public function hasMultipleDiscountOffer(array $itemRule): bool 
    {
        return $itemRule['rule_type'] === MULTIPLE_DISCOUNT;
    }


    /*
    * Function Name: hasDiscountedItemOffer
    * Params: array $itemRule
    * Description: Check if item has discounted item.
    * Return: Boolean
    * Date: 25-04-2022
    */
    public function hasDiscountedItemOffer(array $itemRule): bool 
    {
        return $itemRule['rule_type'] === DISCOUNTED_ITEM;
    }


    /*
    * Function Name: hasDiscountedItemOffer
    * Params: array $itemRule
    * Description: Check if item has discounted item.
    * Return: Boolean
    * Date: 25-04-2022
    */
    public function calculateGroupQuantity(int $firstQty, int $secondQty): int 
    {
        return (int) ($firstQty / $secondQty);
    }

    public function calculateExtraQuantity(int $firstQty, int $secondQty): int 
    {
        return (int) ($firstQty % $secondQty);
    }


    /*
    * Function Name: 
    * Params: int $itemQty, int $unitPrice
    * Description: calculate price of an item.
    * Return: int
    * Date: 25-04-2022
    */
    public function calculateCost(int $itemQty, int $unitPrice): int
    {
        return Helper::product($itemQty, $unitPrice);
    }



    /*
    * Function Name: calculateSingleRuleCost
    * Params: array $cartItem, array $itemRule, array $item
    * Description: calculate total cost of an item which have discount for single quantity rule.
    * Return: int
    * Date: 25-04-2022
    */
    public function calculateSingleRuleCost(array $cartItem, array $itemRule, array $item): int
    {
        $cartItemQuantity = $cartItem['quantity'];
        $itemRuleQuantity = $itemRule['rule']['quantity'];
        $itemRulePrice    = $itemRule['rule']['price'];
        $perUnitPrice     = $item['unit_price'];

        if ($cartItemQuantity >= $itemRuleQuantity) {
            $groupQty = $this->calculateGroupQuantity($cartItemQuantity, $itemRuleQuantity);
            $extraQty = $this->calculateExtraQuantity($cartItemQuantity, $itemRuleQuantity);
            $groupQtyPrice = Helper::product($groupQty, $itemRulePrice);
            $extraQtyPrice = Helper::product($extraQty, $perUnitPrice);
            return Helper::sum($groupQtyPrice, $extraQtyPrice);
        }

        return $this->calculateCost($cartItemQuantity, $perUnitPrice);
    }



    /*
    * Function Name: getCheapestCost
    * Params: int $cartQty, int $unitPrice, array $itemRule
    * Description: this function works as sub-function for calculating the cheapest combination of costs
    * Return: int
    * Date: 25-04-2022
    */
    public function getCheapestCost(int $cartQty, int $unitPrice, array $itemRule): int
    {
        $firstItemPrice    = $itemRule['rule'][0]['price'];
        $firstItemQty      = $itemRule['rule'][0]['quantity'];
        $secondItemPrice   = $itemRule['rule'][1]['price'];
        $secondItemQty     = $itemRule['rule'][1]['quantity'];
        
        $firstRuleUnitPrice = round(($firstItemPrice/$firstItemQty), 2);
        $secondRuleUnitPrice = round(($secondItemPrice/$secondItemQty), 2);

        if($firstRuleUnitPrice < $secondRuleUnitPrice) {
            $resQty = $firstItemQty;
            $resPrice = $firstItemPrice;
        }
        else {
            $resQty = $secondItemQty;
            $resPrice = $secondItemPrice;
        }

        $groupQty = $this->calculateGroupQuantity($cartQty, $resQty);
        $extraQty = $this->calculateExtraQuantity($cartQty, $resQty);
        $groupQtyPrice = Helper::product($groupQty, $resPrice);
        $extraQtyPrice = 0;
        
        if (Helper::equals($extraQty, $firstItemQty))
            $extraQtyPrice = $firstItemPrice;

        else if (Helper::equals($extraQty, $secondItemQty))
            $extraQtyPrice = $secondItemPrice;

        else 
            $extraQtyPrice = Helper::product($extraQty, $unitPrice);

        return Helper::sum($groupQtyPrice, $extraQtyPrice);
    }


     /*
    * Function Name: calculateMultipleRuleCost
    * Params: array $cartItem, array $itemRule, array $itemPrice
    * Description: calculate total cost of an item which have multiple discount rules.
    * Return: int
    * Date: 25-04-2022
    */
    public function calculateMultipleRuleCost(array $cartItem, array $itemRule, array $itemPrice): int 
    {
        $cartItemQuantity   = $cartItem['quantity'];
        $perUnitPrice       = $itemPrice['unit_price'];  
        
        if (Helper::equals($cartItemQuantity, $itemRule['rule'][0]['quantity'])) {
            return $itemRule['rule'][0]['price'];
        }
        
        else if (Helper::equals($cartItemQuantity, $itemRule['rule'][1]['quantity'])) {
            return $itemRule['rule'][1]['price'];
        }

        else
        {
            return $this->getCheapestCost($cartItemQuantity, $perUnitPrice, $itemRule);
        }

        return $this->calculateCost($cartItemQuantity, $perUnitPrice);
    }


     /*
    * Function Name: calculateDiscountedItemCost
    * Params: array $cartItem, array $filteredItem, array $itemRule, array $itemPrice
    * Description: calculate total cost of an item which have discount over the purchase of another item.
    * Return: int
    * Date: 25-04-2022
    */
    public function calculateDiscountedItemCost(array $cartItem, array $filteredItem, array $itemRule, array $itemPrice): int 
    {
        $filteredItemQty = $filteredItem['quantity'];
        $cartItemQty = $cartItem['quantity'];
        $unitPrice = $itemPrice['unit_price'];
        $itemRulePrice = $itemRule['rule']['price'];

        $extraQty = ($cartItemQty > $filteredItemQty) ? ($cartItemQty - $filteredItemQty) : 0;
        $discountedQty = $cartItemQty - $extraQty;
        $extraQtyPrice = $extraQty * $unitPrice;
        $discountedQtyPrice = $discountedQty * $itemRulePrice;
        
        return Helper::sum($discountedQtyPrice, $extraQtyPrice);
    }
}