<?php 

declare (strict_types = 1);

require 'autoload.php';

class ItemRules 
{
    public const SINGLE_DISCOUNT = 1;
    public const MULTIPLE_DISCOUNT = 2;
    public const DISCOUNTED_ITEM = 3;


    private static array $rules = [
        [
            'sku' => 'A',
            'rule_type' => SINGLE_DISCOUNT,
            'rule' => [
                'quantity' => 3,
                'price' => 130
            ] 
        ],
        [
            'sku' => 'B',
            'rule_type' => SINGLE_DISCOUNT,
            'rule' => [
                'quantity' => 2,
                'price' => 45
            ] 
        ],
        [
            'sku' => 'C',
            'rule_type' => MULTIPLE_DISCOUNT,
            'rule' => [
                ['quantity' => 2, 'price' => 38],
                ['quantity' => 3, 'price' => 50],
            ] 
        ],
        [
            'sku' => 'D',
            'rule_type' => DISCOUNTED_ITEM,
            'rule' => [
                'price' => 5,
                'discounted_item' => 'A' 
            ] 
        ],
    ];

    public static function getItem(string $name): array
    {
        return Helper::filter_list(self::$rules, 'sku', $name);
    }
}