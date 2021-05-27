<?php

namespace HEXONET\WHMCS\ISPAPI\DNS;

use Illuminate\Database\Capsule\Manager as DB;

class Product
{
    /**
     * @return \Illuminate\Support\Collection<mixed>
     */
    public static function getAll(): \Illuminate\Support\Collection
    {
        return DB::table('tblproducts')
            ->where('type', '=', 'hostingaccount')
            ->orderBy('gid')
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'gid', 'name']);
    }

    /**
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function get(int $productId)
    {
        return DB::table('tblproducts')
            ->where('id', '=', $productId)
            ->where('type', '=', 'hostingaccount')
            ->first(['id', 'gid', 'name']);
    }
}
