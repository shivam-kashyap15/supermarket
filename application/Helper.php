<?php 

declare (strict_types = 1);

class Helper 
{

    /*
    * Function Name: filter_list
    * Params: array $list, string $key, string $filterKey
    * Description: filter the array on the basis of key provided.
    * Return: array
    * Date: 25-04-2022
    */
    public static function filter_list(array $list, string $key, string $filterKey): array
    { 
        $filteredItem = array_filter($list, function ($item) use ($key, $filterKey) {
            return $item[$key] === $filterKey;
        });

        return $filteredItem ? array_values($filteredItem)[0] : [];
    }


    /*
    * Function Name: sum
    * Params: int $first, int $second
    * Description: Provide sum of two integers
    * Return: int
    * Date: 25-04-2022
    */
    public static function sum(int $first, int $second): int 
    {
        return $first + $second;
    }


    /*
    * Function Name: product
    * Params: int $first, int $second
    * Description: Provide product of two integers
    * Return: int
    * Date: 25-04-2022
    */
    public static function product(int $first, int $second): int 
    {
        return $first * $second;
    }


    /*
    * Function Name: equals
    * Params: int $first, int $second
    * Description: result if two integers are equals or not.
    * Return: boolean
    * Date: 25-04-2022
    */
    public static function equals(int $first, int $second): bool
    {
        return $first === $second;
    }

}